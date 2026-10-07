<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        if (auth()->user()->isManager()) {
            $this->redirectIntended(default: route('manager.dashboard', absolute: false), navigate: true);
        } else {
            $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
        }
    }

    public function quickLogin(string $role): void
    {
        if ($role === 'manager') {
            $this->form->email = 'manager@wms.test';
            $this->form->password = 'password';
        } else {
            $this->form->email = 'medewerker@wms.test';
            $this->form->password = 'password';
        }

        $this->login();
    }
}; ?>

<div class="space-y-5">
    <!-- Snelle Testknoppen -->
    <div class="p-4 bg-indigo-50/70 border border-indigo-100 rounded-2xl space-y-2.5">
        <span class="text-[11px] font-bold text-indigo-900 uppercase tracking-wider block text-center">
            ⚡ Snelle Test-Inlog (1-Klik)
        </span>
        <div class="grid grid-cols-2 gap-2">
            <button 
                type="button" 
                wire:click="quickLogin('manager')"
                class="py-2 px-3 bg-amber-500 hover:bg-amber-600 text-amber-950 font-bold text-xs rounded-xl shadow-sm transition flex items-center justify-center gap-1.5">
                <span>👑</span>
                <span>Als Manager</span>
            </button>
            <button 
                type="button" 
                wire:click="quickLogin('employee')"
                class="py-2 px-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-sm transition flex items-center justify-center gap-1.5">
                <span>💼</span>
                <span>Als Medewerker</span>
            </button>
        </div>
        <p class="text-[10px] text-center text-indigo-700/70">Wachtwoord: password</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="login" class="space-y-4">
        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('E-mailadres')" />
            <x-text-input wire:model="form.email" id="email" class="block mt-1 w-full text-xs" type="email" name="email" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Wachtwoord')" />
            <x-text-input wire:model="form.password" id="password" class="block mt-1 w-full text-xs"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label for="remember" class="inline-flex items-center">
                <input wire:model="form.remember" id="remember" type="checkbox" class="rounded border-slate-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-xs text-slate-600">{{ __('Onthoud mij') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-xs text-indigo-600 hover:underline" href="{{ route('password.request') }}" wire:navigate>
                    {{ __('Wachtwoord vergeten?') }}
                </a>
            @endif
        </div>

        <button type="submit" class="w-full py-2.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-md transition">
            Inloggen &rarr;
        </button>
    </form>
</div>
