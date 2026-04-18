<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{
    public string $email    = '';
    public string $password = '';
    public bool   $remember = false;

    protected array $rules = [
        'email'    => 'required|email',
        'password' => 'required|min:6',
    ];

    public function login(): void
    {
        $this->validate();

        if (!Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            $this->addError('email', 'Email ou mot de passe incorrect.');
            return;
        }

        $user = Auth::user();

        if ($user->isSuspended()) {
            Auth::logout();
            $this->addError('email', 'Votre compte a été suspendu. Contactez l\'administration.');
            return;
        }

        session()->regenerate();

        if ($user->isAdmin()) {
            $this->redirect(route('admin.dashboard'), navigate: true);
        } elseif ($user->isProfessional()) {
            $this->redirect(route('pro.dashboard'), navigate: true);
        } else {
            $this->redirect(route('home'), navigate: true);
        }
    }

    public function render()
    {
        return view('livewire.auth.login')
            ->layout('layouts.auth', ['title' => 'Connexion']);
    }
}
