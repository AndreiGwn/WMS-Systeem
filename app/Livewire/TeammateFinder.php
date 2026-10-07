<?php

namespace App\Livewire;

use App\Models\Booking;
use App\Models\Desk;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class TeammateFinder extends Component
{
    public string $selectedDate;

    public string $search = '';

    public string $selectedZone = 'all';

    public function mount(): void
    {
        $this->selectedDate = Carbon::today()->toDateString();
    }

    public function setDate(string $date): void
    {
        $this->selectedDate = $date;
    }

    public function setZone(string $zone): void
    {
        $this->selectedZone = $zone;
    }

    public function render()
    {
        $query = Booking::with(['user', 'desk'])
            ->whereDate('booking_date', $this->selectedDate)
            ->where('status', 'active');

        if ($this->selectedZone !== 'all') {
            $query->whereHas('desk', function ($q) {
                $q->where('zone', $this->selectedZone);
            });
        }

        if (trim($this->search) !== '') {
            $searchTerm = '%'.trim($this->search).'%';
            $query->where(function ($q) use ($searchTerm) {
                $q->whereHas('user', function ($uq) use ($searchTerm) {
                    $uq->where('name', 'like', $searchTerm)
                        ->orWhere('email', 'like', $searchTerm);
                })->orWhereHas('desk', function ($dq) use ($searchTerm) {
                    $dq->where('name', 'like', $searchTerm)
                        ->orWhere('zone', 'like', $searchTerm);
                });
            });
        }

        $bookings = $query->get();
        $zones = Desk::where('is_active', true)->distinct()->pluck('zone');

        return view('livewire.teammate-finder', [
            'bookings' => $bookings,
            'zones' => $zones,
        ]);
    }
}
