<?php

namespace Tests\Feature\Livewire;

use App\Livewire\DeskBooking;
use App\Models\Booking;
use App\Models\Desk;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class DeskBookingCollisionTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_book_available_desk(): void
    {
        $user = User::factory()->create(['role' => 'employee']);
        $desk = Desk::create([
            'name' => 'Werkplek A1',
            'zone' => 'IT-Vleugel',
            'features' => ['sit_stand', 'dual_screen'],
            'is_active' => true,
        ]);

        $date = Carbon::tomorrow()->toDateString();

        Livewire::actingAs($user)
            ->test(DeskBooking::class)
            ->set('selectedDate', $date)
            ->call('openBookingModal', $desk->id)
            ->set('workHoursNotes', '09:00 - 17:00')
            ->call('bookDesk')
            ->assertSet('collisionError', null)
            ->assertSee('succesvol gereserveerd');

        $this->assertDatabaseHas('bookings', [
            'user_id' => $user->id,
            'desk_id' => $desk->id,
            'status' => 'active',
        ]);

        $booking = Booking::first();
        $this->assertEquals($date, $booking->booking_date->toDateString());
    }

    public function test_collision_detection_prevents_double_booking_on_same_date(): void
    {
        $userA = User::factory()->create(['name' => 'User A', 'role' => 'employee']);
        $userB = User::factory()->create(['name' => 'User B', 'role' => 'employee']);

        $desk = Desk::create([
            'name' => 'Werkplek A1',
            'zone' => 'IT-Vleugel',
            'features' => ['dual_screen'],
            'is_active' => true,
        ]);

        $date = Carbon::tomorrow()->toDateString();

        // User A boekt het bureau
        Booking::create([
            'user_id' => $userA->id,
            'desk_id' => $desk->id,
            'booking_date' => $date,
            'work_hours_notes' => '08:30 - 16:30',
            'status' => 'active',
        ]);

        // User B probeert hetzelfde bureau op dezelfde datum te boeken
        Livewire::actingAs($userB)
            ->test(DeskBooking::class)
            ->set('selectedDate', $date)
            ->set('selectedDeskId', $desk->id)
            ->call('bookDesk')
            ->assertSet('collisionError', 'Helaas! Dit bureau is zojuist al gereserveerd door een collega voor deze datum.');

        // Er mag slechts 1 boeking bestaan
        $this->assertEquals(1, Booking::where('desk_id', $desk->id)->whereDate('booking_date', $date)->count());
    }

    public function test_user_can_cancel_own_booking(): void
    {
        $user = User::factory()->create(['role' => 'employee']);
        $desk = Desk::create([
            'name' => 'Werkplek B1',
            'zone' => 'Stiltezone',
            'is_active' => true,
        ]);

        $booking = Booking::create([
            'user_id' => $user->id,
            'desk_id' => $desk->id,
            'booking_date' => Carbon::today()->toDateString(),
            'status' => 'active',
        ]);

        Livewire::actingAs($user)
            ->test(DeskBooking::class)
            ->call('cancelBooking', $booking->id);

        $this->assertEquals('cancelled', $booking->fresh()->status);
    }
}
