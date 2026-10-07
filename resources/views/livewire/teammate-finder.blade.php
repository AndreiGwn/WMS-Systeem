<div class="space-y-5 pb-24">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Wie is er op kantoor?</h1>
            <p class="text-xs text-slate-500">Ontdek welke collega's vandaag aanwezig zijn</p>
        </div>
        <a href="{{ route('desks.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 text-indigo-700 text-xs font-semibold rounded-xl hover:bg-indigo-100 transition">
            <span>🪑</span>
            <span>Reserveren</span>
        </a>
    </div>

    <!-- Datum Selector -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm space-y-3">
        <div class="flex items-center justify-between">
            <label class="text-xs font-bold uppercase tracking-wider text-slate-500">Datum</label>
            <input 
                type="date" 
                wire:model.live="selectedDate" 
                class="text-xs font-semibold px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-slate-800 focus:ring-indigo-500 focus:border-indigo-500"
            />
        </div>

        <!-- Quick date pills -->
        <div class="flex items-center gap-2 overflow-x-auto pb-1 text-xs">
            @php
                $today = now()->toDateString();
                $tomorrow = now()->addDay()->toDateString();
            @endphp
            <button 
                wire:click="setDate('{{ $today }}')" 
                class="px-3 py-1.5 rounded-xl font-medium whitespace-nowrap transition {{ $selectedDate === $today ? 'bg-indigo-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Vandaag ({{ now()->format('d M') }})
            </button>
            <button 
                wire:click="setDate('{{ $tomorrow }}')" 
                class="px-3 py-1.5 rounded-xl font-medium whitespace-nowrap transition {{ $selectedDate === $tomorrow ? 'bg-indigo-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Morgen ({{ now()->addDay()->format('d M') }})
            </button>
        </div>

        <!-- Zoekbalk -->
        <div class="relative">
            <input 
                type="text" 
                wire:model.live.debounce.250ms="search" 
                placeholder="Zoek collega, bureau of zone..." 
                class="w-full pl-9 pr-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-slate-800"
            />
            <span class="absolute left-3 top-2.5 text-slate-400 text-sm">🔍</span>
        </div>
    </div>

    <!-- Zones Filter -->
    <div class="flex items-center gap-1.5 overflow-x-auto pb-1 text-xs">
        <button 
            wire:click="setZone('all')" 
            class="px-3 py-1.5 rounded-full font-medium whitespace-nowrap transition {{ $selectedZone === 'all' ? 'bg-slate-900 text-white' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
            Alle Zones
        </button>
        @foreach($zones as $zone)
            <button 
                wire:click="setZone('{{ $zone }}')" 
                class="px-3 py-1.5 rounded-full font-medium whitespace-nowrap transition {{ $selectedZone === $zone ? 'bg-slate-900 text-white' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
                {{ $zone }}
            </button>
        @endforeach
    </div>

    <!-- Team Members List -->
    <div class="space-y-2.5">
        <div class="flex items-center justify-between px-1 text-xs text-slate-500">
            <span>Aanwezig op kantoor ({{ count($bookings) }})</span>
            <span class="font-medium text-slate-400">{{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('l j F') }}</span>
        </div>

        @forelse($bookings as $booking)
            <div class="bg-white p-3.5 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between gap-3 hover:border-slate-300 transition">
                <div class="flex items-center gap-3 min-w-0">
                    <!-- Avatar circle with initials -->
                    @php
                        $words = explode(' ', $booking->user->name);
                        $initials = count($words) > 1 ? strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1)) : strtoupper(substr($booking->user->name, 0, 2));
                        $isMe = $booking->user_id === auth()->id();
                    @endphp
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-xs shrink-0 {{ $isMe ? 'bg-indigo-600 text-white ring-2 ring-indigo-200' : 'bg-slate-100 text-slate-700' }}">
                        {{ $initials }}
                    </div>

                    <div class="min-w-0">
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-sm text-slate-900 truncate">{{ $booking->user->name }}</span>
                            @if($isMe)
                                <span class="px-1.5 py-0.5 bg-indigo-50 text-indigo-700 text-[10px] font-bold rounded">Jij</span>
                            @endif
                            @if($booking->user->isManager())
                                <span class="px-1.5 py-0.5 bg-amber-50 text-amber-700 text-[10px] font-bold rounded">Manager</span>
                            @endif
                        </div>
                        <div class="text-xs text-slate-500 flex items-center gap-1.5 mt-0.5 truncate">
                            <span class="font-semibold text-slate-700">{{ $booking->desk->name }}</span>
                            <span>&bull;</span>
                            <span class="text-slate-400">{{ $booking->desk->zone }}</span>
                        </div>
                        @if($booking->work_hours_notes)
                            <div class="text-[11px] text-slate-500 italic mt-0.5 truncate">
                                🕒 {{ $booking->work_hours_notes }}
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Status pill -->
                <span class="shrink-0 inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    Aanwezig
                </span>
            </div>
        @empty
            <div class="py-12 text-center bg-white rounded-2xl border border-slate-200 text-slate-500">
                <p class="text-2xl mb-1">🏢</p>
                <p class="font-semibold text-sm">Nog geen collega's aangemeld</p>
                <p class="text-xs text-slate-400 mt-0.5">Er zijn geen actieve reserveringen voor deze datum.</p>
                <div class="mt-4">
                    <a href="{{ route('desks.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-600 text-white text-xs font-bold rounded-xl shadow-sm hover:bg-indigo-700 transition">
                        Reserveer als eerste een bureau &rarr;
                    </a>
                </div>
            </div>
        @endforelse
    </div>
</div>
