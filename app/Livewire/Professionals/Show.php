<?php

namespace App\Livewire\Professionals;

use App\Models\Professional;
use App\Models\Review;
use App\Models\Tracking;
use Livewire\Component;

class Show extends Component
{
    public Professional $professional;

    public string $reviewer_name = '';
    public int    $rating        = 5;
    public string $comment       = '';

    public bool $showReviewForm  = false;
    public bool $reviewSubmitted = false;

    protected array $rules = [
        'reviewer_name' => 'required|min:2|max:50',
        'rating'        => 'required|integer|min:1|max:5',
        'comment'       => 'nullable|max:500',
    ];

    protected array $messages = [
        'reviewer_name.required' => 'Votre nom est obligatoire.',
        'reviewer_name.min'      => 'Le nom doit comporter au moins 2 caractères.',
        'rating.required'        => 'La note est obligatoire.',
    ];

    public function mount(string $slug): void
    {
        $this->professional = Professional::with(['category', 'reviews'])
            ->where('slug', $slug)
            ->firstOrFail();

        Tracking::create([
            'professional_id' => $this->professional->id,
            'action'          => 'view',
            'ip_address'      => request()->ip(),
            'user_agent'      => request()->userAgent(),
        ]);

        $this->professional->increment('total_views');
    }

    public function trackWhatsApp(): void
    {
        Tracking::create([
            'professional_id' => $this->professional->id,
            'action'          => 'whatsapp',
            'ip_address'      => request()->ip(),
            'user_agent'      => request()->userAgent(),
        ]);
        $this->professional->increment('total_whatsapp_clicks');
        $this->dispatch('open-url', url: $this->professional->getWhatsAppUrl());
    }

    public function trackCall(): void
    {
        Tracking::create([
            'professional_id' => $this->professional->id,
            'action'          => 'call',
            'ip_address'      => request()->ip(),
            'user_agent'      => request()->userAgent(),
        ]);
        $this->professional->increment('total_calls');
    }

    public function submitReview(): void
    {
        $this->validate();

        Review::create([
            'professional_id' => $this->professional->id,
            'reviewer_name'   => $this->reviewer_name,
            'rating'          => $this->rating,
            'comment'         => $this->comment,
        ]);

        $this->professional->updateAverageRating();
        $this->professional->refresh();

        $this->reviewer_name  = '';
        $this->rating         = 5;
        $this->comment        = '';
        $this->showReviewForm = false;
        $this->reviewSubmitted= true;
    }

    public function render()
    {
        return view('livewire.professionals.show', [
            'reviews' => $this->professional->reviews()->where('status', 'approved')->latest()->get(),
        ])->layout('layouts.app', ['title' => $this->professional->name . ' - M3allemClick']);
    }
}
