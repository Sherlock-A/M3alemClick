<?php

namespace App\Livewire\Dashboard;

use App\Models\Category;
use App\Models\Professional;
use App\Models\Tracking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;

class MyDashboard extends Component
{
    public ?Professional $professional = null;
    public bool $hasProfile = false;

    // Profile form fields
    public string $name         = '';
    public string $phone        = '';
    public string $city         = '';
    public string $description  = '';
    public string $availability = 'available';
    public array  $skills       = [];
    public array  $languages    = [];
    public array  $travel_cities = [];
    public int    $category_id  = 0;

    public string $newSkill      = '';
    public string $newLanguage   = '';
    public string $newTravelCity = '';

    public bool $editMode = false;
    public bool $saved    = false;

    protected array $rules = [
        'name'        => 'required|min:2|max:100',
        'phone'       => 'required|min:9|max:20',
        'city'        => 'required|max:100',
        'category_id' => 'required|exists:categories,id',
        'description' => 'nullable|max:1000',
    ];

    public function mount(): void
    {
        $user = Auth::user();

        if (!$user || !$user->isProfessional()) {
            $this->redirect(route('login'), navigate: true);
            return;
        }

        // Try to find the linked professional
        $this->professional = $user->professional;

        // Fallback: try to find an orphaned professional with matching name
        if (!$this->professional) {
            $orphan = Professional::whereNull('user_id')
                ->where('name', 'like', '%' . explode(' ', $user->name)[0] . '%')
                ->latest()
                ->first();

            if ($orphan) {
                $orphan->update(['user_id' => $user->id]);
                $user->load('professional');
                $this->professional = $user->fresh()->professional;
            }
        }

        $this->hasProfile = $this->professional !== null;

        if ($this->hasProfile) {
            $this->syncFields();
        } else {
            // Pre-fill from user data so profile creation is easy
            $this->name  = $user->name;
            $this->phone = $user->phone ?? '';
            $this->editMode = true;
        }
    }

    private function syncFields(): void
    {
        $this->name         = $this->professional->name;
        $this->phone        = $this->professional->phone;
        $this->city         = $this->professional->city;
        $this->description  = $this->professional->description ?? '';
        $this->availability = $this->professional->availability;
        $this->skills       = $this->professional->skills ?? [];
        $this->languages    = $this->professional->languages ?? [];
        $this->travel_cities = $this->professional->travel_cities ?? [];
        $this->category_id  = $this->professional->category_id ?? 0;
    }

    public function createProfile(): void
    {
        $this->validate();

        $user = Auth::user();

        $this->professional = Professional::create([
            'user_id'      => $user->id,
            'category_id'  => $this->category_id,
            'name'         => $this->name,
            'slug'         => Str::slug($this->name) . '-' . Str::random(6),
            'phone'        => $this->phone,
            'city'         => $this->city,
            'description'  => $this->description,
            'availability' => 'available',
            'skills'       => $this->skills,
            'languages'    => $this->languages,
            'travel_cities'=> $this->travel_cities,
        ]);

        $this->hasProfile = true;
        $this->editMode   = false;
        $this->saved      = true;
    }

    public function setAvailability(string $status): void
    {
        $this->professional->update(['availability' => $status]);
        $this->availability = $status;
        $this->saved = true;
    }

    public function addSkill(): void
    {
        $s = trim($this->newSkill);
        if ($s && !in_array($s, $this->skills)) { $this->skills[] = $s; $this->newSkill = ''; }
    }
    public function removeSkill(int $i): void { array_splice($this->skills, $i, 1); $this->skills = array_values($this->skills); }

    public function addLanguage(): void
    {
        $l = trim($this->newLanguage);
        if ($l && !in_array($l, $this->languages)) { $this->languages[] = $l; $this->newLanguage = ''; }
    }
    public function removeLanguage(int $i): void { array_splice($this->languages, $i, 1); $this->languages = array_values($this->languages); }

    public function addTravelCity(): void
    {
        $c = trim($this->newTravelCity);
        if ($c && !in_array($c, $this->travel_cities)) { $this->travel_cities[] = $c; $this->newTravelCity = ''; }
    }
    public function removeTravelCity(int $i): void { array_splice($this->travel_cities, $i, 1); $this->travel_cities = array_values($this->travel_cities); }

    public function saveProfile(): void
    {
        $this->validate();

        $this->professional->update([
            'name'         => $this->name,
            'phone'        => $this->phone,
            'city'         => $this->city,
            'description'  => $this->description,
            'category_id'  => $this->category_id,
            'skills'       => $this->skills,
            'languages'    => $this->languages,
            'travel_cities'=> $this->travel_cities,
        ]);

        $this->professional->refresh();
        $this->editMode = false;
        $this->saved    = true;
    }

    public function resubmit(): void
    {
        if ($this->editMode) {
            $this->saveProfile();
        }
        Auth::user()->update(['status' => 'en_attente', 'rejection_reason' => null]);
        $this->saved = true;
        $this->dispatch('toast-success', message: 'Votre profil a été soumis à nouveau.');
    }

    public function cancelEdit(): void
    {
        if ($this->hasProfile) $this->syncFields();
        $this->editMode = false;
    }

    public function render()
    {
        $user  = Auth::user();
        $stats = ['views_total' => 0, 'views_today' => 0, 'whatsapp_total' => 0,
                  'whatsapp_today' => 0, 'calls_total' => 0, 'calls_today' => 0,
                  'rating' => 0, 'total_reviews' => 0];

        if ($this->professional) {
            $pid   = $this->professional->id;
            $today = today();
            $stats = [
                'views_total'    => $this->professional->total_views,
                'views_today'    => Tracking::where('professional_id', $pid)->where('action', 'view')->whereDate('created_at', $today)->count(),
                'whatsapp_total' => $this->professional->total_whatsapp_clicks,
                'whatsapp_today' => Tracking::where('professional_id', $pid)->where('action', 'whatsapp')->whereDate('created_at', $today)->count(),
                'calls_total'    => $this->professional->total_calls,
                'calls_today'    => Tracking::where('professional_id', $pid)->where('action', 'call')->whereDate('created_at', $today)->count(),
                'rating'         => $this->professional->average_rating,
                'total_reviews'  => $this->professional->total_reviews,
            ];
        }

        return view('livewire.dashboard.my-dashboard', [
            'stats'      => $stats,
            'user'       => $user,
            'categories' => Category::orderBy('order')->get(),
        ])->layout('layouts.app', ['title' => 'Mon Espace Artisan']);
    }
}
