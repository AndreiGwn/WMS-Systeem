<?php

namespace App\Livewire\Manager;

use App\Models\Ticket;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class TicketManager extends Component
{
    public string $filter = 'all';

    public ?string $successMessage = null;

    public function setFilter(string $filter): void
    {
        $this->filter = $filter;
    }

    public function updateStatus(int $ticketId, string $newStatus): void
    {
        $ticket = Ticket::findOrFail($ticketId);
        $ticket->update(['status' => $newStatus]);

        $statusDutch = match ($newStatus) {
            'in_progress' => 'In Behandeling genomen',
            'resolved' => 'gemarkeerd als Opgelost',
            'open' => 'heropend',
            default => $newStatus,
        };

        $this->successMessage = "Ticket '{$ticket->title}' is {$statusDutch}.";
    }

    public function render()
    {
        $query = Ticket::with(['user', 'desk'])->latest();

        if ($this->filter !== 'all') {
            $query->where('status', $this->filter);
        }

        $tickets = $query->get();

        return view('livewire.manager.ticket-manager', [
            'tickets' => $tickets,
        ]);
    }
}
