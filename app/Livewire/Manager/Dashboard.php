<?php

namespace App\Livewire\Manager;

use App\Models\Booking;
use App\Models\Desk;
use App\Models\Ticket;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Dashboard extends Component
{
    public function render()
    {
        $today = Carbon::today()->toDateString();
        $totalDesks = Desk::where('is_active', true)->count();
        $bookedToday = Booking::whereDate('booking_date', $today)->where('status', 'active')->count();
        $occupancyRate = $totalDesks > 0 ? round(($bookedToday / $totalDesks) * 100) : 0;
        $openTicketsCount = Ticket::whereIn('status', ['open', 'in_progress'])->count();

        // Zone occupancy breakdown
        $zones = Desk::where('is_active', true)->distinct()->pluck('zone');
        $zoneStats = [];
        foreach ($zones as $zone) {
            $zoneDesks = Desk::where('is_active', true)->where('zone', $zone)->pluck('id');
            $zoneBooked = Booking::whereIn('desk_id', $zoneDesks)
                ->whereDate('booking_date', $today)
                ->where('status', 'active')
                ->count();
            $zoneStats[] = [
                'zone' => $zone,
                'total' => count($zoneDesks),
                'booked' => $zoneBooked,
                'rate' => count($zoneDesks) > 0 ? round(($zoneBooked / count($zoneDesks)) * 100) : 0,
            ];
        }

        // Recent open tickets
        $recentTickets = Ticket::with(['user', 'desk'])
            ->whereIn('status', ['open', 'in_progress'])
            ->latest()
            ->take(5)
            ->get();

        return view('livewire.manager.dashboard', [
            'totalDesks' => $totalDesks,
            'bookedToday' => $bookedToday,
            'occupancyRate' => $occupancyRate,
            'openTicketsCount' => $openTicketsCount,
            'zoneStats' => $zoneStats,
            'recentTickets' => $recentTickets,
        ]);
    }
}
