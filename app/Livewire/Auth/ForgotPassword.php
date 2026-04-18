<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Password;
use Livewire\Component;

class ForgotPassword extends Component
{
    public string $email = '';
    public bool   $sent  = false;

    protected array $rules = [
        'email' => 'required|email|exists:users,email',
    ];

    protected array $messages = [
        'email.exists' => 'Aucun compte trouvé avec cet email.',
    ];

    public function sendLink(): void
    {
        $this->validate();

        $status = Password::sendResetLink(['email' => $this->email]);

        if ($status === Password::RESET_LINK_SENT) {
            $this->sent = true;
        } else {
            $this->addError('email', __($status));
        }
    }

    public function render()
    {
        return view('livewire.auth.forgot-password')
            ->layout('layouts.auth', ['title' => 'Mot de passe oublié']);
    }
}
