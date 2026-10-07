<?php

namespace Tests\Feature\Livewire;

use App\Livewire\CreateTicket;
use App\Mail\TicketReportedMail;
use App\Models\Desk;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class CreateTicketTest extends TestCase
{
    use RefreshDatabase;

    public function test_ticket_creation_saves_ticket_and_sends_email_notification(): void
    {
        Mail::fake();
        Storage::fake('public');

        $manager = User::factory()->create([
            'email' => 'manager@wms.test',
            'role' => 'manager',
        ]);

        $employee = User::factory()->create([
            'role' => 'employee',
        ]);

        $desk = Desk::create([
            'name' => 'Werkplek A1',
            'zone' => 'IT-Vleugel',
            'is_active' => true,
        ]);

        $image = UploadedFile::fake()->image('defect.jpg');

        Livewire::actingAs($employee)
            ->test(CreateTicket::class)
            ->set('desk_id', $desk->id)
            ->set('title', 'Kapot scherm')
            ->set('description', 'Het rechter scherm knippert en geeft geen helder beeld.')
            ->set('image', $image)
            ->call('submitTicket')
            ->assertHasNoErrors()
            ->assertSee('Schademelding succesvol ingediend');

        // Controleer of ticket in database is opgeslagen
        $this->assertDatabaseHas('tickets', [
            'user_id' => $employee->id,
            'desk_id' => $desk->id,
            'title' => 'Kapot scherm',
            'status' => 'open',
        ]);

        // Controleer of Mailpit / Mailable is getriggerd
        Mail::assertSent(TicketReportedMail::class, function ($mail) use ($manager) {
            return $mail->hasTo($manager->email) && $mail->ticket->title === 'Kapot scherm';
        });

        // Controleer of bureau nu als Defect wordt aangemerkt
        $this->assertEquals('defect', $desk->fresh()->statusOn(now()));
    }
}
