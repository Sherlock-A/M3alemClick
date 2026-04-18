<?php

namespace App\Livewire\Auth;

use App\Models\Category;
use App\Models\Professional;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Component;

class Register extends Component
{
    public string $name                  = '';
    public string $email                 = '';
    public string $phone                 = '';
    public string $password              = '';
    public string $password_confirmation = '';
    public string $role                  = 'client';
    public string $city                  = '';
    public int    $category_id           = 0;

    protected array $rules = [
        'name'                  => 'required|min:2|max:100',
        'email'                 => 'required|email|unique:users',
        'phone'                 => 'required|min:9|max:20',
        'password'              => 'required|min:8|confirmed',
        'role'                  => 'required|in:professional,client',
        'city'                  => 'required_if:role,professional|nullable|max:100',
        'category_id'           => 'required_if:role,professional|nullable|exists:categories,id',
    ];

    protected array $messages = [
        'category_id.required_if' => 'Veuillez choisir une catégorie.',
        'city.required_if'        => 'Veuillez indiquer votre ville.',
    ];

    public function register(): void
    {
        $this->validate();

        $status = $this->role === 'professional' ? 'en_attente' : 'actif';

        $user = User::create([
            'name'     => $this->name,
            'email'    => $this->email,
            'phone'    => $this->phone,
            'password' => Hash::make($this->password),
            'role'     => $this->role,
            'status'   => $status,
        ]);

        if ($this->role === 'professional') {
            Professional::create([
                'user_id'      => $user->id,
                'category_id'  => $this->category_id,
                'name'         => $this->name,
                'slug'         => Str::slug($this->name) . '-' . Str::random(6),
                'phone'        => $this->phone,
                'city'         => $this->city,
                'availability' => 'available',
            ]);
        }

        Auth::login($user, false);

        if ($user->isProfessional()) {
            $this->redirect(route('pro.dashboard'), navigate: true);
        } else {
            $this->redirect(route('home'), navigate: true);
        }
    }

    public function render()
    {
        return view('livewire.auth.register', [
            'categories' => Category::orderBy('order')->get(),
        ])->layout('layouts.auth', ['title' => 'Inscription']);
    }
}
