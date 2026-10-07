<div class="space-y-6 pb-24">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2">
                <h1 class="text-xl font-bold text-slate-900">Manager Beheerpaneel</h1>
                <span class="px-2 py-0.5 bg-amber-50 text-amber-700 text-[10px] font-bold rounded-full border border-amber-200">Manager</span>
            </div>
            <p class="text-xs text-slate-500">Overzicht van kantoorbezetting en faciliteiten</p>
        </div>
        <div class="flex items-center gap-1.5">
            <a href="{{ route('manager.desks') }}" class="px-3 py-1.5 bg-white border border-slate-200 text-slate-700 text-xs font-semibold rounded-xl hover:bg-slate-50 transition">
                🪑 Bureaus
            </a>
            <a href="{{ route('manager.tickets') }}" class="px-3 py-1.5 bg-indigo-600 text-white text-xs font-semibold rounded-xl hover:bg-indigo-700 transition">
                🎫 Tickets
            </a>
        </div>
    </div>

    <!-- 4 KPI Cards -->
    <div class="grid grid-cols-2 gap-3.5">
        <!-- Bezetting -->
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm space-y-1">
            <span class="text-slate-400 text-xs font-bold uppercase tracking-wider block">Bezetting Vandaag</span>
            <div class="flex items-baseline gap-2">
                <span class="text-2xl font-black text-indigo-600">{{ $occupancyRate }}%</span>
                <span class="text-[11px] text-slate-500 font-medium">van capaciteit</span>
            </div>
            <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden mt-1">
                <div class="bg-indigo-600 h-1.5 rounded-full transition-all duration-500" style="width: {{ $occupancyRate }}%"></div>
            </div>
        </div>

        <!-- Aanwezig personeel -->
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm space-y-1">
            <span class="text-slate-400 text-xs font-bold uppercase tracking-wider block">Aanwezig</span>
            <div class="flex items-baseline gap-2">
                <span class="text-2xl font-black text-slate-900">{{ $bookedToday }}</span>
                <span class="text-[11px] text-slate-500 font-medium">/ {{ $totalDesks }} plekken</span>
            </div>
            <div class="text-[10px] text-slate-400 mt-1">Actieve reserveringen</div>
        </div>

        <!-- Openstaande Tickets -->
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm space-y-1">
            <span class="text-slate-400 text-xs font-bold uppercase tracking-wider block">Open Tickets</span>
            <div class="flex items-baseline gap-2">
                <span class="text-2xl font-black {{ $openTicketsCount > 0 ? 'text-amber-600' : 'text-emerald-600' }}">{{ $openTicketsCount }}</span>
                <span class="text-[11px] text-slate-500 font-medium">meldingen</span>
            </div>
            <div class="text-[10px] {{ $openTicketsCount > 0 ? 'text-amber-500 font-semibold' : 'text-emerald-500 font-semibold' }} mt-1">
                {{ $openTicketsCount > 0 ? 'Aandacht vereist' : 'Alles in orde' }}
            </div>
        </div>

        <!-- Actieve Werkplekken -->
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm space-y-1">
            <span class="text-slate-400 text-xs font-bold uppercase tracking-wider block">Werkplekken</span>
            <div class="flex items-baseline gap-2">
                <span class="text-2xl font-black text-slate-900">{{ $totalDesks }}</span>
                <span class="text-[11px] text-slate-500 font-medium">beschikbaar</span>
            </div>
            <div class="text-[10px] text-slate-400 mt-1">In 4 kantoorzones</div>
        </div>
    </div>

    <!-- Zone Bezetting Breakdown -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-3">
        <h2 class="text-sm font-bold text-slate-800">Bezetting per Kantoorzone</h2>
        <div class="space-y-3">
            @foreach($zoneStats as $stat)
                <div class="space-y-1">
                    <div class="flex items-center justify-between text-xs">
                        <span class="font-semibold text-slate-700">{{ $stat['zone'] }}</span>
                        <span class="text-slate-500 font-medium">{{ $stat['booked'] }} van {{ $stat['total'] }} bezet ({{ $stat['rate'] }}%)</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                        <div class="bg-indigo-600 h-2 rounded-full transition-all duration-500" style="width: {{ $stat['rate'] }}%"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Recente Schademeldingen & Defecten -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-3">
        <div class="flex items-center justify-between">
            <h2 class="text-sm font-bold text-slate-800 flex items-center gap-1.5">
                <span>⚠️</span>
                <span>Openstaande Schademeldingen</span>
            </h2>
            <a href="{{ route('manager.tickets') }}" class="text-xs text-indigo-600 font-semibold hover:underline">
                Bekijk alles &rarr;
            </a>
        </div>

        @forelse($recentTickets as $ticket)
            <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 flex items-start justify-between gap-3">
                <div class="space-y-0.5">
                    <span class="font-bold text-xs text-slate-900 block">{{ $ticket->title }}</span>
                    <p class="text-[11px] text-slate-500">
                        {{ $ticket->desk ? $ticket->desk->name : 'Algemeen' }} &bull; door {{ $ticket->user->name }}
                    </p>
                    <p class="text-xs text-slate-600 pt-1">{{ Str::limit($ticket->description, 80) }}</p>
                </div>
                <span class="shrink-0 px-2 py-0.5 text-[10px] font-bold rounded-full {{ $ticket->status === 'open' ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800' }}">
                    {{ ucfirst($ticket->status) }}
                </span>
            </div>
        @empty
            <div class="text-center py-6 text-slate-400 text-xs">
                🎉 Geen openstaande schademeldingen!
            </div>
        @endforelse
    </div>
</div>
