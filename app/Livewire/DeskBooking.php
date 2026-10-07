<?php

namespace App\Livewire;

use App\Models\Booking;
use App\Models\Desk;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class DeskBooking extends Component
{
    public string $selectedDate;

    public string $selectedZone = 'all';

    public array $featureFilters = [];

    public ?int $selectedDeskId = null;

    public string $workHoursNotes = '08:30 - 17:00';

    public bool $showModal = false;

    public ?string $collisionError = null;

    public ?string $successMessage = null;

    public function mount(): void
    {
        $this->selectedDate = Carbon::today()->toDateString();
    }

    public function setDate(string $date): void
    {
        $this->selectedDate = $date;
        $this->resetModal();
    }

    public function setZone(string $zone): void
    {
        $this->selectedZone = $zone;
    }

    public function toggleFeature(string $feature): void
    {
        if (in_array($feature, $this->featureFilters)) {
            $this->featureFilters = array_values(array_diff($this->featureFilters, [$feature]));
        } else {
            $this->featureFilters[] = $feature;
        }
    }

    public function openBookingModal(int $deskId): void
    {
        $desk = Desk::findOrFail($deskId);

        // Prevent opening booking modal for defect desks or already booked
        if ($desk->hasOpenTicket()) {
            $this->collisionError = 'Dit bureau heeft een openstaand defect en kan niet geboekt worden.';

            return;
        }

        if ($desk->isBookedOn($this->selectedDate)) {
            $this->collisionError = 'Dit bureau is al bezet op deze datum.';

            return;
        }

        $this->selectedDeskId = $deskId;
        $this->collisionError = null;
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->resetModal();
    }

    protected function resetModal(): void
    {
        $this->showModal = false;
        $this->selectedDeskId = null;
        $this->collisionError = null;
    }

    public function bookDesk(): void
    {
        $this->validate([
            'selectedDeskId' => 'required|exists:desks,id',
            'selectedDate' => 'required|date',
            'workHoursNotes' => 'nullable|string|max:100',
        ]);

        $desk = Desk::findOrFail($this->selectedDeskId);

        // Defect check
        if ($desk->hasOpenTicket()) {
            $this->collisionError = 'Dit bureau heeft zojuist een schademelding gekregen en kan niet worden gereserveerd.';

            return;
        }

        // Collision detectie: controleer of dit bureau al geboekt is op deze datum
        $collision = Booking::where('desk_id', $this->selectedDeskId)
            ->whereDate('booking_date', $this->selectedDate)
            ->where('status', 'active')
            ->exists();

        if ($collision) {
            $this->collisionError = 'Helaas! Dit bureau is zojuist al gereserveerd door een collega voor deze datum.';

            return;
        }

        // Maak de boeking aan
        Booking::create([
            'user_id' => Auth::id(),
            'desk_id' => $this->selectedDeskId,
            'booking_date' => $this->selectedDate,
            'work_hours_notes' => $this->workHoursNotes ?: '08:30 - 17:00',
            'status' => 'active',
        ]);

        $deskName = $desk->name;
        $this->resetModal();
        $this->successMessage = "Je hebt {$deskName} succesvol gereserveerd voor ".Carbon::parse($this->selectedDate)->translatedFormat('l j F').'!';
    }

    public function cancelBooking(int $bookingId): void
    {
        $booking = Booking::findOrFail($bookingId);

        if ($booking->user_id !== Auth::id() && ! Auth::user()->isManager()) {
            abort(403, 'Je mag alleen je eigen boekingen annuleren.');
        }

        $booking->update(['status' => 'cancelled']);
        $this->successMessage = 'Reservering succesvol geannuleerd.';
    }

    public function render()
    {
        $query = Desk::query()->where('is_active', true);

        if ($this->selectedZone !== 'all') {
            $query->where('zone', $this->selectedZone);
        }

        $desks = $query->get()->filter(function ($desk) {
            if (empty($this->featureFilters)) {
                return true;
            }
            $deskFeatures = $desk->features ?? [];
            foreach ($this->featureFilters as $f) {
                if (! in_array($f, $deskFeatures)) {
                    return false;
                }
            }

            return true;
        });

        $zones = Desk::where('is_active', true)->distinct()->pluck('zone');

        $selectedDesk = $this->selectedDeskId ? Desk::find($this->selectedDeskId) : null;

        return view('livewire.desk-booking', [
            'desks' => $desks,
            'zones' => $zones,
            'selectedDesk' => $selectedDesk,
        ]);
    }
}
