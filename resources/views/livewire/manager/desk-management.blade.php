<div class="space-y-6 pb-24">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Werkplek Beheer</h1>
            <p class="text-xs text-slate-500">Beheer alle bureaus, zones en faciliteiten</p>
        </div>
        <button 
            wire:click="openCreateModal" 
            class="px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-sm transition flex items-center gap-1.5">
            <span>+</span>
            <span>Nieuw Bureau</span>
        </button>
    </div>

    <!-- Feedback Message -->
    @if($successMessage)
        <div x-data="{ show: true }" x-show="show" class="p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-xs flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span>✅</span>
                <span>{{ $successMessage }}</span>
            </div>
            <button @click="show = false" class="text-emerald-600 hover:text-emerald-900">&times;</button>
        </div>
    @endif

    <!-- Desks List -->
    <div class="space-y-3">
        @foreach($desks as $desk)
            <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between gap-3">
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <h3 class="font-bold text-sm text-slate-900">{{ $desk->name }}</h3>
                        <span class="text-xs text-slate-500 font-medium">&bull; {{ $desk->zone }}</span>
                        @if(!$desk->is_active)
                            <span class="px-2 py-0.5 bg-slate-100 text-slate-500 text-[10px] font-bold rounded">Gedeactiveerd</span>
                        @endif
                    </div>
                    <div class="flex flex-wrap gap-1 text-[10px]">
                        @if(in_array('sit_stand', $desk->features ?? []))
                            <span class="px-2 py-0.5 bg-indigo-50 text-indigo-700 rounded font-medium">↕️ Zit-sta</span>
                        @endif
                        @if(in_array('dual_screen', $desk->features ?? []))
                            <span class="px-2 py-0.5 bg-indigo-50 text-indigo-700 rounded font-medium">🖥️ 2x Scherm</span>
                        @endif
                        @if(in_array('usb_c_dock', $desk->features ?? []))
                            <span class="px-2 py-0.5 bg-indigo-50 text-indigo-700 rounded font-medium">🔌 USB-C Dock</span>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-1.5 shrink-0">
                    <button 
                        wire:click="openEditModal({{ $desk->id }})" 
                        class="p-2 text-slate-600 hover:text-indigo-600 hover:bg-slate-50 rounded-xl transition text-xs font-semibold"
                        title="Bewerken">
                        ✏️
                    </button>
                    <button 
                        wire:click="toggleActive({{ $desk->id }})" 
                        class="p-2 {{ $desk->is_active ? 'text-amber-600 hover:bg-amber-50' : 'text-emerald-600 hover:bg-emerald-50' }} rounded-xl transition text-xs font-semibold"
                        title="{{ $desk->is_active ? 'Deactiveren' : 'Activeren' }}">
                        {{ $desk->is_active ? '⏸️' : '▶️' }}
                    </button>
                    <button 
                        wire:click="deleteDesk({{ $desk->id }})" 
                        wire:confirm="Weet je zeker dat je dit bureau wilt verwijderen?" 
                        class="p-2 text-rose-600 hover:bg-rose-50 rounded-xl transition text-xs font-semibold"
                        title="Verwijderen">
                        🗑️
                    </button>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Modal Create / Edit Desk -->
    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm">
            <div class="w-full max-w-md bg-white rounded-3xl shadow-2xl border border-slate-200 overflow-hidden">
                <div class="p-5 bg-indigo-600 text-white flex items-center justify-between">
                    <h2 class="text-base font-bold">{{ $editingDeskId ? 'Bureau Bewerken' : 'Nieuw Bureau Aanmaken' }}</h2>
                    <button wire:click="closeModal" class="text-white/80 hover:text-white">&times;</button>
                </div>

                <form wire:submit="saveDesk" class="p-5 space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Naam Werkplek</label>
                        <input type="text" wire:model="name" placeholder="bijv. Werkplek E1" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white text-slate-800">
                        @error('name') <span class="text-[11px] text-rose-600">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Zone / Afdeling</label>
                        <input type="text" wire:model="zone" placeholder="bijv. Stiltezone of IT-Vleugel" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white text-slate-800">
                        @error('zone') <span class="text-[11px] text-rose-600">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Faciliteiten</label>
                        <div class="space-y-1.5 text-xs text-slate-700">
                            <label class="flex items-center gap-2">
                                <input type="checkbox" wire:model="features" value="sit_stand" class="rounded text-indigo-600">
                                <span>↕️ Zit-sta bureau</span>
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="checkbox" wire:model="features" value="dual_screen" class="rounded text-indigo-600">
                                <span>🖥️ Twee schermen (Dual Screen)</span>
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="checkbox" wire:model="features" value="usb_c_dock" class="rounded text-indigo-600">
                                <span>🔌 USB-C Docking station</span>
                            </label>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 pt-1">
                        <input type="checkbox" id="is_active" wire:model="is_active" class="rounded text-indigo-600">
                        <label for="is_active" class="text-xs font-semibold text-slate-700">Werkplek is direct actief voor boekingen</label>
                    </div>

                    <div class="flex items-center gap-2 pt-2">
                        <button type="button" wire:click="closeModal" class="w-1/3 py-2 bg-slate-100 text-slate-700 text-xs font-bold rounded-xl">Annuleren</button>
                        <button type="submit" class="w-2/3 py-2 bg-indigo-600 text-white text-xs font-bold rounded-xl shadow-md">Opslaan</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
