<div class="space-y-5 pb-24">
    <!-- Top Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Werkplek Reserveren</h1>
            <p class="text-xs text-slate-500">Kies een datum en vind jouw ideale bureau</p>
        </div>
        <a href="{{ route('colleagues.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 text-indigo-700 text-xs font-semibold rounded-xl hover:bg-indigo-100 transition">
            <span>👥</span>
            <span>Wie is er?</span>
        </a>
    </div>

    <!-- Feedback Alerts -->
    @if($successMessage)
        <div x-data="{ show: true }" x-show="show" class="p-3.5 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-xs flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="text-base">✅</span>
                <span>{{ $successMessage }}</span>
            </div>
            <button @click="show = false" class="text-emerald-600 hover:text-emerald-900">&times;</button>
        </div>
    @endif

    @if($collisionError)
        <div class="p-3.5 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl text-xs flex items-center gap-2">
            <span class="text-base">⚠️</span>
            <span>{{ $collisionError }}</span>
        </div>
    @endif

    <!-- Datum Selector Card -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm space-y-3">
        <div class="flex items-center justify-between">
            <label class="text-xs font-bold uppercase tracking-wider text-slate-500">Datum Selecteren</label>
            <input 
                type="date" 
                wire:model.live="selectedDate" 
                min="{{ now()->toDateString() }}" 
                class="text-xs font-semibold px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-slate-800 focus:ring-indigo-500 focus:border-indigo-500"
            />
        </div>

        <!-- Quick date pills -->
        <div class="flex items-center gap-2 overflow-x-auto pb-1 text-xs">
            @php
                $today = now()->toDateString();
                $tomorrow = now()->addDay()->toDateString();
                $dayAfter = now()->addDays(2)->toDateString();
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
            <button 
                wire:click="setDate('{{ $dayAfter }}')" 
                class="px-3 py-1.5 rounded-xl font-medium whitespace-nowrap transition {{ $selectedDate === $dayAfter ? 'bg-indigo-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                {{ now()->addDays(2)->translatedFormat('l d M') }}
            </button>
        </div>
    </div>

    <!-- Filters: Zones & Features -->
    <div class="space-y-2">
        <!-- Zone Pills -->
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

        <!-- Feature Toggle Pills -->
        <div class="flex items-center gap-2 overflow-x-auto text-xs">
            <button 
                wire:click="toggleFeature('sit_stand')" 
                class="px-2.5 py-1 rounded-lg border font-medium flex items-center gap-1 transition {{ in_array('sit_stand', $featureFilters) ? 'bg-indigo-50 border-indigo-300 text-indigo-700' : 'bg-white border-slate-200 text-slate-500 hover:bg-slate-50' }}">
                <span>↕️</span>
                <span>Zit-sta</span>
            </button>
            <button 
                wire:click="toggleFeature('dual_screen')" 
                class="px-2.5 py-1 rounded-lg border font-medium flex items-center gap-1 transition {{ in_array('dual_screen', $featureFilters) ? 'bg-indigo-50 border-indigo-300 text-indigo-700' : 'bg-white border-slate-200 text-slate-500 hover:bg-slate-50' }}">
                <span>🖥️</span>
                <span>Dual Screen</span>
            </button>
            <button 
                wire:click="toggleFeature('usb_c_dock')" 
                class="px-2.5 py-1 rounded-lg border font-medium flex items-center gap-1 transition {{ in_array('usb_c_dock', $featureFilters) ? 'bg-indigo-50 border-indigo-300 text-indigo-700' : 'bg-white border-slate-200 text-slate-500 hover:bg-slate-50' }}">
                <span>🔌</span>
                <span>USB-C Dock</span>
            </button>
        </div>
    </div>

    <!-- Status Legend -->
    <div class="flex items-center justify-between px-2 text-[11px] text-slate-500">
        <div class="flex items-center gap-1.5">
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
            <span>Vrij</span>
        </div>
        <div class="flex items-center gap-1.5">
            <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
            <span>Bezet</span>
        </div>
        <div class="flex items-center gap-1.5">
            <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
            <span>Defect</span>
        </div>
        <span class="text-slate-400 font-medium">{{ count($desks) }} bureaus</span>
    </div>

    <!-- Desk Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
        @forelse($desks as $desk)
            @php
                $status = $desk->statusOn($selectedDate);
                $booking = $desk->activeBookingOn($selectedDate);
                $isMyBooking = $booking && $booking->user_id === auth()->id();
            @endphp
            
            <div class="bg-white rounded-2xl border p-4 transition-all duration-150 flex flex-col justify-between {{ $status === 'free' ? 'border-emerald-200 hover:border-emerald-400 hover:shadow-md cursor-pointer' : ($status === 'booked' ? 'border-rose-200 bg-rose-50/20' : 'border-amber-200 bg-amber-50/20') }}"
                 @if($status === 'free') wire:click="openBookingModal({{ $desk->id }})" @endif>
                
                <div>
                    <!-- Card Top -->
                    <div class="flex items-start justify-between mb-2">
                        <div>
                            <h3 class="font-bold text-slate-900 text-sm flex items-center gap-1.5">
                                {{ $desk->name }}
                            </h3>
                            <span class="text-[11px] font-medium text-slate-500">{{ $desk->zone }}</span>
                        </div>

                        <!-- Status Badge -->
                        @if($status === 'free')
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                Vrij
                            </span>
                        @elseif($status === 'booked')
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                Bezet
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                Defect
                            </span>
                        @endif
                    </div>

                    <!-- Features -->
                    <div class="flex flex-wrap gap-1 my-2">
                        @if(in_array('sit_stand', $desk->features ?? []))
                            <span class="px-2 py-0.5 bg-slate-100 text-slate-600 rounded-md text-[10px] font-medium">↕️ Zit-sta</span>
                        @endif
                        @if(in_array('dual_screen', $desk->features ?? []))
                            <span class="px-2 py-0.5 bg-slate-100 text-slate-600 rounded-md text-[10px] font-medium">🖥️ 2x Scherm</span>
                        @endif
                        @if(in_array('usb_c_dock', $desk->features ?? []))
                            <span class="px-2 py-0.5 bg-slate-100 text-slate-600 rounded-md text-[10px] font-medium">🔌 USB-C Dock</span>
                        @endif
                    </div>
                </div>

                <!-- Card Bottom Details -->
                <div class="mt-3 pt-2.5 border-t border-slate-100">
                    @if($status === 'free')
                        <div class="flex items-center justify-between text-xs text-indigo-600 font-semibold">
                            <span>Nu reserveren</span>
                            <span>&rarr;</span>
                        </div>
                    @elseif($status === 'booked')
                        <div class="space-y-1">
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-slate-500">Geboekt door:</span>
                                <span class="font-semibold text-slate-800 {{ $isMyBooking ? 'text-indigo-600' : '' }}">
                                    {{ $isMyBooking ? 'Jijzelf' : $booking->user->name }}
                                </span>
                            </div>
                            @if($booking->work_hours_notes)
                                <div class="text-[11px] text-slate-500 italic truncate">
                                    "{{ $booking->work_hours_notes }}"
                                </div>
                            @endif
                            @if($isMyBooking)
                                <button 
                                    wire:click.stop="cancelBooking({{ $booking->id }})" 
                                    class="w-full mt-2 py-1 px-2 bg-rose-50 hover:bg-rose-100 text-rose-700 text-[11px] font-bold rounded-lg border border-rose-200 transition">
                                    Mijn Boeking Annuleren
                                </button>
                            @endif
                        </div>
                    @else
                        <div class="flex items-center justify-between text-xs text-amber-700">
                            <span>⚠️ Schademelding actief</span>
                            <a href="{{ route('tickets.index') }}" class="underline font-semibold text-[11px]">Details</a>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center bg-white rounded-2xl border border-slate-200 text-slate-500">
                <p class="text-2xl mb-1">🔍</p>
                <p class="font-semibold text-sm">Geen bureaus gevonden</p>
                <p class="text-xs text-slate-400 mt-0.5">Pas je filters of zonekeuze aan.</p>
            </div>
        @endforelse
    </div>

    <!-- Booking Slide-Over Modal -->
    @if($showModal && $selectedDesk)
        <div class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-0 sm:p-4 bg-slate-900/50 backdrop-blur-sm transition-opacity">
            <div class="w-full sm:max-w-md bg-white rounded-t-3xl sm:rounded-3xl shadow-2xl border border-slate-200 overflow-hidden transform transition-all animate-in slide-in-from-bottom sm:zoom-in-95">
                
                <!-- Modal Header -->
                <div class="p-5 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white flex items-center justify-between">
                    <div>
                        <div class="text-xs uppercase tracking-wider font-semibold opacity-80">Reservering Bevestigen</div>
                        <h2 class="text-lg font-bold">{{ $selectedDesk->name }}</h2>
                    </div>
                    <button wire:click="closeModal" class="p-1 rounded-full text-white/80 hover:text-white hover:bg-white/10 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-5 space-y-4">
                    <!-- Locatie & Datum samenvatting -->
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 flex items-center justify-between text-xs">
                        <div>
                            <span class="text-slate-400 block text-[10px] uppercase font-bold">Zone</span>
                            <span class="font-bold text-slate-700">{{ $selectedDesk->zone }}</span>
                        </div>
                        <div class="text-right">
                            <span class="text-slate-400 block text-[10px] uppercase font-bold">Geselecteerde Datum</span>
                            <span class="font-bold text-indigo-700">{{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('l j F Y') }}</span>
                        </div>
                    </div>

                    <!-- Bureau Faciliteiten -->
                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1.5">Faciliteiten</label>
                        <div class="flex flex-wrap gap-1.5">
                            @if(in_array('sit_stand', $selectedDesk->features ?? []))
                                <span class="px-2.5 py-1 bg-indigo-50 text-indigo-700 border border-indigo-100 rounded-lg text-xs font-medium">↕️ Zit-sta bureau</span>
                            @endif
                            @if(in_array('dual_screen', $selectedDesk->features ?? []))
                                <span class="px-2.5 py-1 bg-indigo-50 text-indigo-700 border border-indigo-100 rounded-lg text-xs font-medium">🖥️ 2x Monitoren</span>
                            @endif
                            @if(in_array('usb_c_dock', $selectedDesk->features ?? []))
                                <span class="px-2.5 py-1 bg-indigo-50 text-indigo-700 border border-indigo-100 rounded-lg text-xs font-medium">🔌 USB-C Docking Station</span>
                            @endif
                        </div>
                    </div>

                    <!-- Werktijden / Notitie Veld -->
                    <div>
                        <label for="workHours" class="block text-xs font-bold text-slate-700 mb-1">
                            Werktijden / Notitie <span class="text-slate-400 font-normal">(zichtbaar voor collega's)</span>
                        </label>
                        <input 
                            type="text" 
                            id="workHours" 
                            wire:model="workHoursNotes" 
                            placeholder="bijv. 08:30 - 16:30 (aanwezig voor overleg)" 
                            class="w-full px-3.5 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-slate-800"
                        />
                    </div>

                    @if($collisionError)
                        <div class="p-3 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs flex items-center gap-2">
                            <span>⚠️</span>
                            <span>{{ $collisionError }}</span>
                        </div>
                    @endif

                    <!-- Actieknoppen -->
                    <div class="pt-2 flex items-center gap-2">
                        <button 
                            type="button" 
                            wire:click="closeModal" 
                            class="w-1/3 py-2.5 px-4 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition">
                            Annuleren
                        </button>
                        <button 
                            type="button" 
                            wire:click="bookDesk" 
                            wire:loading.attr="disabled"
                            class="w-2/3 py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-md hover:shadow-lg transition flex items-center justify-center gap-2">
                            <span wire:loading.remove>Bevestig Reservering</span>
                            <span wire:loading>Bezig met boeken...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
