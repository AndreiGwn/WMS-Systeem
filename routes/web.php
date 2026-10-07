<?php

use App\Livewire\CreateTicket;
use App\Livewire\DeskBooking;
use App\Livewire\Manager\Dashboard as ManagerDashboard;
use App\Livewire\Manager\DeskManagement;
use App\Livewire\Manager\TicketManager;
use App\Livewire\TeammateFinder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        return Auth::user()->isManager()
            ? redirect()->route('manager.dashboard')
            : redirect()->route('dashboard');
    }

    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Medewerker Flow
    Route::view('/dashboard', 'dashboard')->name('dashboard');
    Route::get('/desks', DeskBooking::class)->name('desks.index');
    Route::get('/colleagues', TeammateFinder::class)->name('colleagues.index');
    Route::get('/tickets', CreateTicket::class)->name('tickets.index');

    // Manager Flow (Beveiligd met 'manager' middleware)
    Route::middleware(['manager'])->prefix('manager')->name('manager.')->group(function () {
        Route::get('/dashboard', ManagerDashboard::class)->name('dashboard');
        Route::get('/desks', DeskManagement::class)->name('desks');
        Route::get('/tickets', TicketManager::class)->name('tickets');
    });

    Route::view('profile', 'profile')->name('profile');
});

require __DIR__.'/auth.php';
