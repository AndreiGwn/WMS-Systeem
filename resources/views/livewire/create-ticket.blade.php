<div class="space-y-6 pb-24">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Schademelding & Defecten</h1>
            <p class="text-xs text-slate-500">Meld een probleem met een bureau of scherm</p>
        </div>
        <a href="{{ route('desks.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 text-slate-700 text-xs font-semibold rounded-xl hover:bg-slate-200 transition">
            <span>&larr;</span>
            <span>Terug naar bureaus</span>
        </a>
    </div>

    <!-- Feedback Message -->
    @if($successMessage)
        <div x-data="{ show: true }" x-show="show" class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-xs flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="text-base">📬</span>
                <span>{{ $successMessage }}</span>
            </div>
            <button @click="show = false" class="text-emerald-600 hover:text-emerald-900">&times;</button>
        </div>
    @endif

    <!-- Formulier Card -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-4">
        <h2 class="text-sm font-bold text-slate-800 flex items-center gap-1.5">
            <span>📝</span>
            <span>Nieuw probleem doorgeven</span>
        </h2>

        <form wire:submit="submitTicket" class="space-y-4">
            <!-- Werkplek Selectie -->
            <div>
                <label for="desk" class="block text-xs font-bold text-slate-700 mb-1">Betrokken Werkplek</label>
                <select 
                    id="desk" 
                    wire:model="desk_id" 
                    class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-indigo-500 focus:border-indigo-500 text-slate-800">
                    <option value="">Algemene Kantoorruimte (Geen specifiek bureau)</option>
                    @foreach($desks as $desk)
                        <option value="{{ $desk->id }}">{{ $desk->name }} ({{ $desk->zone }})</option>
                    @endforeach
                </select>
                @error('desk_id') <span class="text-[11px] text-rose-600">{{ $message }}</span> @enderror
            </div>

            <!-- Titel van de melding -->
            <div>
                <label for="title" class="block text-xs font-bold text-slate-700 mb-1">Onderwerp / Titel</label>
                <input 
                    type="text" 
                    id="title" 
                    wire:model="title" 
                    placeholder="bijv. Scherm kapot / kabel ontbreekt / zit-sta paneel foutcode" 
                    class="w-full px-3 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-indigo-500 focus:border-indigo-500 text-slate-800"
                />
                @error('title') <span class="text-[11px] text-rose-600">{{ $message }}</span> @enderror
            </div>

            <!-- Beschrijving -->
            <div>
                <label for="description" class="block text-xs font-bold text-slate-700 mb-1">Beschrijving van het probleem</label>
                <textarea 
                    id="description" 
                    rows="3" 
                    wire:model="description" 
                    placeholder="Geef een korte toelichting op het defect..." 
                    class="w-full px-3 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-indigo-500 focus:border-indigo-500 text-slate-800"></textarea>
                @error('description') <span class="text-[11px] text-rose-600">{{ $message }}</span> @enderror
            </div>

            <!-- Foto Upload met Live Preview -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Foto toevoegen (Optioneel)</label>
                <input 
                    type="file" 
                    wire:model="image" 
                    accept="image/*" 
                    class="block w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer"
                />
                <div wire:loading wire:target="image" class="text-[11px] text-indigo-600 mt-1">
                    Foto wordt geüpload...
                </div>
                @error('image') <span class="text-[11px] text-rose-600 block mt-1">{{ $message }}</span> @enderror

                @if($image)
                    <div class="mt-2 p-2 bg-slate-50 rounded-xl border border-slate-200 inline-block">
                        <span class="text-[10px] text-slate-500 font-bold block mb-1">Voorbeeld van foto:</span>
                        <img src="{{ $image->temporaryUrl() }}" class="w-24 h-24 object-cover rounded-lg border border-slate-200">
                    </div>
                @endif
            </div>

            <!-- Verstuurknop -->
            <button 
                type="submit" 
                wire:loading.attr="disabled"
                class="w-full py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-md transition flex items-center justify-center gap-2">
                <span wire:loading.remove>Schademelding Indienen &rarr;</span>
                <span wire:loading>Bezig met verzenden...</span>
            </button>
        </form>
    </div>

    <!-- Mijn Eerdere Meldingen -->
    <div class="space-y-3">
        <h2 class="text-sm font-bold text-slate-800 flex items-center justify-between">
            <span>Mijn recente schademeldingen</span>
            <span class="text-xs text-slate-400 font-normal">({{ count($myTickets) }})</span>
        </h2>

        @forelse($myTickets as $ticket)
            <div class="bg-white p-3.5 rounded-2xl border border-slate-200 shadow-sm space-y-2">
                <div class="flex items-start justify-between gap-2">
                    <div>
                        <h3 class="font-bold text-xs text-slate-900">{{ $ticket->title }}</h3>
                        <p class="text-[11px] text-slate-500">
                            {{ $ticket->desk ? $ticket->desk->name . ' (' . $ticket->desk->zone . ')' : 'Algemene ruimte' }}
                            &bull; {{ $ticket->created_at->diffForHumans() }}
                        </p>
                    </div>

                    <!-- Status Pill -->
                    @if($ticket->status === 'open')
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                            Open
                        </span>
                    @elseif($ticket->status === 'in_progress')
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                            In Behandeling
                        </span>
                    @else
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            Opgelost
                        </span>
                    @endif
                </div>

                <p class="text-xs text-slate-600">{{ $ticket->description }}</p>

                @if($ticket->image_path)
                    <div class="pt-1">
                        <a href="{{ asset('storage/' . $ticket->image_path) }}" target="_blank" class="inline-flex items-center gap-1 text-[11px] text-indigo-600 hover:underline font-semibold">
                            <span>📷</span>
                            <span>Bekijk bijgevoegde foto</span>
                        </a>
                    </div>
                @endif
            </div>
        @empty
            <div class="p-6 text-center bg-white rounded-2xl border border-slate-200 text-slate-400 text-xs">
                Je hebt nog geen schademeldingen gedaan.
            </div>
        @endforelse
    </div>
</div>
