<?php

namespace App\Livewire\Manager;

use App\Models\Desk;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class DeskManagement extends Component
{
    public string $name = '';

    public string $zone = 'IT-Vleugel';

    public array $features = [];

    public bool $is_active = true;

    public ?int $editingDeskId = null;

    public bool $showModal = false;

    public ?string $successMessage = null;

    public function openCreateModal(): void
    {
        $this->reset(['name', 'zone', 'features', 'is_active', 'editingDeskId']);
        $this->zone = 'IT-Vleugel';
        $this->is_active = true;
        $this->showModal = true;
    }

    public function openEditModal(int $deskId): void
    {
        $desk = Desk::findOrFail($deskId);
        $this->editingDeskId = $desk->id;
        $this->name = $desk->name;
        $this->zone = $desk->zone;
        $this->features = $desk->features ?? [];
        $this->is_active = $desk->is_active;
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->reset(['name', 'zone', 'features', 'is_active', 'editingDeskId']);
    }

    public function saveDesk(): void
    {
        $this->validate([
            'name' => 'required|string|max:50',
            'zone' => 'required|string|max:50',
            'features' => 'array',
            'is_active' => 'boolean',
        ]);

        if ($this->editingDeskId) {
            $desk = Desk::findOrFail($this->editingDeskId);
            $desk->update([
                'name' => $this->name,
                'zone' => $this->zone,
                'features' => $this->features,
                'is_active' => $this->is_active,
            ]);
            $this->successMessage = "Werkplek '{$this->name}' succesvol bijgewerkt!";
        } else {
            Desk::create([
                'name' => $this->name,
                'zone' => $this->zone,
                'features' => $this->features,
                'is_active' => $this->is_active,
            ]);
            $this->successMessage = "Nieuwe werkplek '{$this->name}' succesvol toegevoegd!";
        }

        $this->closeModal();
    }

    public function toggleActive(int $deskId): void
    {
        $desk = Desk::findOrFail($deskId);
        $desk->update(['is_active' => ! $desk->is_active]);
        $statusStr = $desk->is_active ? 'geactiveerd' : 'gedeactiveerd';
        $this->successMessage = "Werkplek '{$desk->name}' is {$statusStr}.";
    }

    public function deleteDesk(int $deskId): void
    {
        $desk = Desk::findOrFail($deskId);
        $name = $desk->name;
        $desk->delete();
        $this->successMessage = "Werkplek '{$name}' is verwijderd.";
    }

    public function render()
    {
        $desks = Desk::orderBy('zone')->orderBy('name')->get();

        return view('livewire.manager.desk-management', [
            'desks' => $desks,
        ]);
    }
}
