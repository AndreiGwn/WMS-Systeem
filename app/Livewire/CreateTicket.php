<?php

namespace App\Livewire;

use App\Mail\TicketReportedMail;
use App\Models\Desk;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class CreateTicket extends Component
{
    use WithFileUploads;

    public ?int $desk_id = null;

    public string $title = '';

    public string $description = '';

    public $image = null;

    public ?string $successMessage = null;

    public function submitTicket(): void
    {
        $this->validate([
            'desk_id' => 'nullable|exists:desks,id',
            'title' => 'required|string|max:120',
            'description' => 'required|string|min:5|max:1000',
            'image' => 'nullable|image|max:5120', // max 5MB
        ]);

        $imagePath = null;
        if ($this->image) {
            $imagePath = $this->image->store('tickets', 'public');
        }

        $ticket = Ticket::create([
            'user_id' => Auth::id(),
            'desk_id' => $this->desk_id,
            'title' => $this->title,
            'description' => $this->description,
            'image_path' => $imagePath,
            'status' => 'open',
        ]);

        // Verstuur mailnotificatie naar alle managers via Mailpit (SMTP :1025)
        $managers = User::where('role', 'manager')->pluck('email');
        if ($managers->isNotEmpty()) {
            Mail::to($managers)->send(new TicketReportedMail($ticket));
        }

        // Reset form
        $this->reset(['desk_id', 'title', 'description', 'image']);
        $this->successMessage = 'Schademelding succesvol ingediend! De beheerder is via e-mail genotificeerd.';
    }

    public function render()
    {
        $desks = Desk::where('is_active', true)->orderBy('name')->get();
        $myTickets = Ticket::with('desk')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('livewire.create-ticket', [
            'desks' => $desks,
            'myTickets' => $myTickets,
        ]);
    }
}
