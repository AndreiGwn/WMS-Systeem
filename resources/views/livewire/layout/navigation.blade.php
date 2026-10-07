<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<div>
    <!-- Top App Header -->
    <header class="sticky top-0 z-30 bg-white/95 backdrop-blur-sm border-b border-slate-200 px-4 py-3 flex items-center justify-between">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2 group">
            <div class="w-8 h-8 rounded-xl bg-indigo-600 text-white font-black flex items-center justify-center text-sm shadow-sm group-hover:scale-105 transition">
                W
            </div>
            <div>
                <span class="font-extrabold text-sm text-slate-900 tracking-tight block leading-tight">WMS Kantoor</span>
                <span class="text-[10px] text-slate-400 font-medium block leading-none">Workplace Management</span>
            </div>
        </a>

        <!-- User Profile & Quick Actions -->
        <div class="flex items-center gap-2">
            @auth
                <div class="flex items-center gap-1.5 px-2.5 py-1 bg-slate-100 rounded-full border border-slate-200">
                    <span class="text-xs font-bold text-slate-700 truncate max-w-[120px]">{{ auth()->user()->name }}</span>
                    @if(auth()->user()->isManager())
                        <span class="px-1.5 py-0.2 bg-amber-100 text-amber-800 text-[9px] font-extrabold rounded-full uppercase">Manager</span>
                    @else
                        <span class="px-1.5 py-0.2 bg-indigo-100 text-indigo-800 text-[9px] font-extrabold rounded-full uppercase">Medewerker</span>
                    @endif
                </div>

                <button wire:click="logout" class="p-1.5 text-slate-400 hover:text-rose-600 rounded-lg hover:bg-slate-100 transition" title="Uitloggen">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                </button>
            @endauth
        </div>
    </header>

    <!-- Fixed Bottom Navigation Bar (Thumb-Friendly Mobile & Centered Desktop) -->
    <nav class="fixed bottom-0 left-0 right-0 z-40 bg-white/95 backdrop-blur-md border-t border-slate-200 shadow-[0_-4px_12px_rgba(0,0,0,0.05)]">
        <div class="max-w-2xl mx-auto px-2 py-1.5 flex items-center justify-around">
            <!-- 🏠 Home -->
            <a href="{{ route('dashboard') }}" 
               class="flex flex-col items-center justify-center py-1 px-3 rounded-2xl transition {{ request()->routeIs('dashboard') ? 'text-indigo-600 font-bold bg-indigo-50/80' : 'text-slate-500 hover:text-slate-800' }}">
                <span class="text-lg">🏠</span>
                <span class="text-[10px] tracking-tight mt-0.5">Home</span>
            </a>

            <!-- 🪑 Reserveren -->
            <a href="{{ route('desks.index') }}" 
               class="flex flex-col items-center justify-center py-1 px-3 rounded-2xl transition {{ request()->routeIs('desks.index') ? 'text-indigo-600 font-bold bg-indigo-50/80' : 'text-slate-500 hover:text-slate-800' }}">
                <span class="text-lg">🪑</span>
                <span class="text-[10px] tracking-tight mt-0.5">Reserveren</span>
            </a>

            <!-- 👥 Collega's -->
            <a href="{{ route('colleagues.index') }}" 
               class="flex flex-col items-center justify-center py-1 px-3 rounded-2xl transition {{ request()->routeIs('colleagues.index') ? 'text-indigo-600 font-bold bg-indigo-50/80' : 'text-slate-500 hover:text-slate-800' }}">
                <span class="text-lg">👥</span>
                <span class="text-[10px] tracking-tight mt-0.5">Collega's</span>
            </a>

            <!-- 🎫 Tickets -->
            <a href="{{ route('tickets.index') }}" 
               class="flex flex-col items-center justify-center py-1 px-3 rounded-2xl transition {{ request()->routeIs('tickets.index') ? 'text-indigo-600 font-bold bg-indigo-50/80' : 'text-slate-500 hover:text-slate-800' }}">
                <span class="text-lg">🎫</span>
                <span class="text-[10px] tracking-tight mt-0.5">Tickets</span>
            </a>

            <!-- ⚙️ Beheer (Uitsluitend zichtbaar voor managers) -->
            @if(auth()->check() && auth()->user()->isManager())
                <a href="{{ route('manager.dashboard') }}" 
                   class="flex flex-col items-center justify-center py-1 px-3 rounded-2xl transition {{ request()->is('manager*') ? 'text-indigo-600 font-bold bg-indigo-50/80' : 'text-slate-500 hover:text-slate-800' }}">
                    <span class="text-lg">⚙️</span>
                    <span class="text-[10px] tracking-tight mt-0.5">Beheer</span>
                </a>
            @endif
        </div>
    </nav>
</div>
