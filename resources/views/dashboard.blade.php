<x-app-layout>
    <div class="space-y-6">
        <!-- Welcome Hero Banner -->
        <div class="p-5 bg-gradient-to-r from-indigo-600 to-indigo-800 text-white rounded-3xl shadow-md relative overflow-hidden">
            <div class="relative z-10 space-y-1">
                <div class="flex items-center gap-2">
                    <span class="text-xs uppercase tracking-wider font-semibold opacity-90">Welkom terug</span>
                    @if(auth()->user()->isManager())
                        <span class="px-2 py-0.5 bg-amber-400 text-amber-950 text-[10px] font-black rounded-full uppercase">Manager</span>
                    @endif
                </div>
                <h1 class="text-xl sm:text-2xl font-black">{{ auth()->user()->name }}</h1>
                <p class="text-xs text-indigo-100 font-medium">
                    {{ \Carbon\Carbon::now()->translatedFormat('l j F Y') }}
                </p>
            </div>
            <div class="absolute -right-4 -bottom-4 text-7xl opacity-15 select-none">
                🏢
            </div>
        </div>

        <!-- Manager Quick Banner (als de gebruiker manager is) -->
        @if(auth()->user()->isManager())
            <div class="p-4 bg-amber-50 border border-amber-200 rounded-2xl flex items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">⚙️</span>
                    <div>
                        <h3 class="font-bold text-xs text-amber-900">Manager Controlepaneel</h3>
                        <p class="text-[11px] text-amber-700">Bekijk de kantoorbezetting en openstaande meldingen</p>
                    </div>
                </div>
                <a href="{{ route('manager.dashboard') }}" class="px-3 py-1.5 bg-amber-600 text-white text-xs font-bold rounded-xl shadow-sm hover:bg-amber-700 transition shrink-0">
                    Naar Beheer &rarr;
                </a>
            </div>
        @endif

        <!-- Mijn Reservering voor Vandaag -->
        @php
            $today = \Carbon\Carbon::today()->toDateString();
            $myBookingToday = \App\Models\Booking::with('desk')
                ->where('user_id', auth()->id())
                ->whereDate('booking_date', $today)
                ->where('status', 'active')
                ->first();
        @endphp

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-3">
            <h2 class="text-xs font-bold uppercase tracking-wider text-slate-500">Jouw Werkplek Vandaag</h2>
            
            @if($myBookingToday)
                <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl flex items-center justify-between gap-3">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                            <h3 class="font-black text-base text-emerald-950">{{ $myBookingToday->desk->name }}</h3>
                        </div>
                        <p class="text-xs text-emerald-800 font-medium">
                            Zone: <strong>{{ $myBookingToday->desk->zone }}</strong>
                        </p>
                        @if($myBookingToday->work_hours_notes)
                            <p class="text-[11px] text-emerald-700 italic">
                                🕒 {{ $myBookingToday->work_hours_notes }}
                            </p>
                        @endif
                    </div>
                    <a href="{{ route('desks.index') }}" class="px-3 py-1.5 bg-white border border-emerald-300 text-emerald-800 text-xs font-bold rounded-xl hover:bg-emerald-100 transition shrink-0">
                        Bekijken
                    </a>
                </div>
            @else
                <div class="p-4 bg-slate-50 border border-dashed border-slate-200 rounded-2xl text-center space-y-2">
                    <p class="text-xs text-slate-600">Je hebt nog geen bureau geboekt voor vandaag.</p>
                    <a href="{{ route('desks.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-sm transition">
                        <span>🪑</span>
                        <span>Reserveer Nu een Bureau &rarr;</span>
                    </a>
                </div>
            @endif
        </div>

        <!-- Quick Access Knoppen -->
        <div class="grid grid-cols-2 gap-3.5">
            <a href="{{ route('desks.index') }}" class="p-4 bg-white rounded-2xl border border-slate-200 shadow-sm hover:border-indigo-300 hover:shadow-md transition group">
                <span class="text-2xl block mb-2 group-hover:scale-110 transition">🪑</span>
                <h3 class="font-bold text-sm text-slate-900">Bureaus Boeken</h3>
                <p class="text-[11px] text-slate-500 mt-0.5">Filters op zit-sta, 2x scherm en stiltezones</p>
            </a>

            <a href="{{ route('colleagues.index') }}" class="p-4 bg-white rounded-2xl border border-slate-200 shadow-sm hover:border-indigo-300 hover:shadow-md transition group">
                <span class="text-2xl block mb-2 group-hover:scale-110 transition">👥</span>
                <h3 class="font-bold text-sm text-slate-900">Wie is er op Kantoor?</h3>
                <p class="text-[11px] text-slate-500 mt-0.5">Vind je collega's en hun werkplekken</p>
            </a>

            <a href="{{ route('tickets.index') }}" class="p-4 bg-white rounded-2xl border border-slate-200 shadow-sm hover:border-indigo-300 hover:shadow-md transition group">
                <span class="text-2xl block mb-2 group-hover:scale-110 transition">🎫</span>
                <h3 class="font-bold text-sm text-slate-900">Schademelding</h3>
                <p class="text-[11px] text-slate-500 mt-0.5">Meld kapotte schermen of ontbrekende kabels</p>
            </a>

            @if(auth()->user()->isManager())
                <a href="{{ route('manager.dashboard') }}" class="p-4 bg-white rounded-2xl border border-amber-200 shadow-sm hover:border-amber-400 hover:shadow-md transition group">
                    <span class="text-2xl block mb-2 group-hover:scale-110 transition">📊</span>
                    <h3 class="font-bold text-sm text-slate-900">Beheer Statistieken</h3>
                    <p class="text-[11px] text-slate-500 mt-0.5">Bezetting & capaciteitsanalyse</p>
                </a>
            @else
                <div class="p-4 bg-slate-50/50 rounded-2xl border border-slate-200 flex flex-col justify-center">
                    <span class="text-xs font-semibold text-slate-400">Flexibel werken</span>
                    <p class="text-[11px] text-slate-500 mt-0.5">Reserveer tijdig om jouw voorkeursplek vast te leggen.</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
