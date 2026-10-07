<div class="space-y-6 pb-24">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Schademeldingen Beheer</h1>
            <p class="text-xs text-slate-500">Overzicht van alle ingediende tickets</p>
        </div>
        <a href="{{ route('manager.dashboard') }}" class="px-3 py-1.5 bg-slate-100 text-slate-700 text-xs font-semibold rounded-xl hover:bg-slate-200 transition">
            &larr; Terug
        </a>
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

    <!-- Filter Tabs -->
    <div class="flex items-center gap-1.5 overflow-x-auto text-xs pb-1">
        <button wire:click="setFilter('all')" class="px-3 py-1.5 rounded-full font-medium transition {{ $filter === 'all' ? 'bg-slate-900 text-white' : 'bg-white border border-slate-200 text-slate-600' }}">
            Alle Meldingen
        </button>
        <button wire:click="setFilter('open')" class="px-3 py-1.5 rounded-full font-medium transition {{ $filter === 'open' ? 'bg-amber-600 text-white' : 'bg-white border border-slate-200 text-slate-600' }}">
            🟡 Open
        </button>
        <button wire:click="setFilter('in_progress')" class="px-3 py-1.5 rounded-full font-medium transition {{ $filter === 'in_progress' ? 'bg-blue-600 text-white' : 'bg-white border border-slate-200 text-slate-600' }}">
            🔵 In Behandeling
        </button>
        <button wire:click="setFilter('resolved')" class="px-3 py-1.5 rounded-full font-medium transition {{ $filter === 'resolved' ? 'bg-emerald-600 text-white' : 'bg-white border border-slate-200 text-slate-600' }}">
            🟢 Opgelost
        </button>
    </div>

    <!-- Tickets List -->
    <div class="space-y-4">
        @forelse($tickets as $ticket)
            <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm space-y-3">
                <div class="flex items-start justify-between gap-2">
                    <div>
                        <h3 class="font-bold text-sm text-slate-900">{{ $ticket->title }}</h3>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Locatie: <strong class="text-slate-700">{{ $ticket->desk ? $ticket->desk->name . ' (' . $ticket->desk->zone . ')' : 'Algemene kantoorruimte' }}</strong>
                        </p>
                        <p class="text-[11px] text-slate-400">
                            Gemeld door {{ $ticket->user->name }} &bull; {{ $ticket->created_at->diffForHumans() }}
                        </p>
                    </div>

                    <!-- Status Pill -->
                    @if($ticket->status === 'open')
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200 shrink-0">
                            Open
                        </span>
                    @elseif($ticket->status === 'in_progress')
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200 shrink-0">
                            In Behandeling
                        </span>
                    @else
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 shrink-0">
                            Opgelost
                        </span>
                    @endif
                </div>

                <div class="p-3 bg-slate-50 rounded-xl text-xs text-slate-700">
                    {{ $ticket->description }}
                </div>

                @if($ticket->image_path)
                    <div>
                        <a href="{{ asset('storage/' . $ticket->image_path) }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs text-indigo-600 hover:underline font-semibold">
                            <span>📷</span>
                            <span>Bekijk geüploade foto</span>
                        </a>
                    </div>
                @endif

                <!-- Manager Action Buttons -->
                <div class="pt-2 border-t border-slate-100 flex items-center gap-2 text-xs">
                    @if($ticket->status === 'open')
                        <button 
                            wire:click="updateStatus({{ $ticket->id }}, 'in_progress')" 
                            class="px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 font-bold rounded-xl border border-blue-200 transition">
                            In Behandeling Nemen
                        </button>
                        <button 
                            wire:click="updateStatus({{ $ticket->id }}, 'resolved')" 
                            class="px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-bold rounded-xl border border-emerald-200 transition">
                            Markeer als Opgelost
                        </button>
                    @elseif($ticket->status === 'in_progress')
                        <button 
                            wire:click="updateStatus({{ $ticket->id }}, 'resolved')" 
                            class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-sm transition">
                            Markeer als Opgelost
                        </button>
                        <button 
                            wire:click="updateStatus({{ $ticket->id }}, 'open')" 
                            class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-600 font-medium rounded-xl transition">
                            Terug naar Open
                        </button>
                    @else
                        <button 
                            wire:click="updateStatus({{ $ticket->id }}, 'open')" 
                            class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-600 font-medium rounded-xl transition">
                            Heropenen
                        </button>
                    @endif
                </div>
            </div>
        @empty
            <div class="py-12 text-center bg-white rounded-2xl border border-slate-200 text-slate-400 text-xs">
                Geen tickets gevonden voor dit filter.
            </div>
        @endforelse
    </div>
</div>
