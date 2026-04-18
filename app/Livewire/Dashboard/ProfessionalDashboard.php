<?php

namespace App\Livewire\Dashboard;

use App\Models\Professional;
use App\Models\Tracking;
use Livewire\Component;

class ProfessionalDashboard extends Component
{
    public Professional $professional;

    public string $name        = '';
    public string $phone       = '';
    public string $city        = '';
    public string $description = '';
    public string $availability= 'available';
    public array  $skills      = [];
    public array  $languages   = [];
    public array  $travel_cities = [];

    public string $newSkill       = '';
    public string $newLanguage    = '';
    public string $newTravelCity  = '';

    public bool $editMode = false;
    public bool $saved    = false;

    protected array $rules = [
        'name'        => 'required|min:2|max:100',
        'phone'       => 'required|min:9|max:20',
        'city'        => 'required|max:100',
        'description' => 'nullable|max:1000',
    ];

    public function mount(int $id): void
    {
        $this->professional = Professional::findOrFail($id);
        $this->syncFields();
    }

    private function syncFields(): void
    {
        $this->name          = $this->professional->name;
        $this->phone         = $this->professional->phone;
        $this->city          = $this->professional->city;
        $this->description   = $this->professional->description ?? '';
        $this->availability  = $this->professional->availability;
        $this->skills        = $this->professional->skills        ?? [];
        $this->languages     = $this->professional->languages     ?? [];
        $this->travel_cities = $this->professional->travel_cities ?? [];
    }

    public function setAvailability(string $status): void
    {
        $this->professional->update(['availability' => $status]);
        $this->availability = $status;
        $this->professional->refresh();
        $this->saved = true;
    }

    public function addSkill(): void
    {
        $skill = trim($this->newSkill);
        if ($skill && !in_array($skill, $this->skills)) {
            $this->skills[]   = $skill;
            $this->newSkill   = '';
        }
    }

    public function removeSkill(int $index): void
    {
        array_splice($this->skills, $index, 1);
        $this->skills = array_values($this->skills);
    }

    public function addLanguage(): void
    {
        $lang = trim($this->newLanguage);
        if ($lang && !in_array($lang, $this->languages)) {
            $this->languages[]  = $lang;
            $this->newLanguage  = '';
        }
    }

    public function removeLanguage(int $index): void
    {
        array_splice($this->languages, $index, 1);
        $this->languages = array_values($this->languages);
    }

    public function addTravelCity(): void
    {
        $c = trim($this->newTravelCity);
        if ($c && !in_array($c, $this->travel_cities)) {
            $this->travel_cities[]  = $c;
            $this->newTravelCity    = '';
        }
    }

    public function removeTravelCity(int $index): void
    {
        array_splice($this->travel_cities, $index, 1);
        $this->travel_cities = array_values($this->travel_cities);
    }

    public function saveProfile(): void
    {
        $this->validate();

        $this->professional->update([
            'name'         => $this->name,
            'phone'        => $this->phone,
            'city'         => $this->city,
            'description'  => $this->description,
            'skills'       => $this->skills,
            'languages'    => $this->languages,
            'travel_cities'=> $this->travel_cities,
        ]);

        $this->professional->refresh();
        $this->editMode = false;
        $this->saved    = true;
    }

    public function cancelEdit(): void
    {
        $this->syncFields();
        $this->editMode = false;
    }

    public function render()
    {
        $pid   = $this->professional->id;
        $today = today();

        $stats = [
            'views_total'     => $this->professional->total_views,
            'views_today'     => Tracking::where('professional_id', $pid)->where('action', 'view')->whereDate('created_at', $today)->count(),
            'views_week'      => Tracking::where('professional_id', $pid)->where('action', 'view')->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'whatsapp_total'  => $this->professional->total_whatsapp_clicks,
            'whatsapp_today'  => Tracking::where('professional_id', $pid)->where('action', 'whatsapp')->whereDate('created_at', $today)->count(),
            'calls_total'     => $this->professional->total_calls,
            'calls_today'     => Tracking::where('professional_id', $pid)->where('action', 'call')->whereDate('created_at', $today)->count(),
            'rating'          => $this->professional->average_rating,
            'total_reviews'   => $this->professional->total_reviews,
        ];

        return view('livewire.dashboard.professional-dashboard', [
            'stats' => $stats,
        ])->layout('layouts.app', ['title' => 'Dashboard - ' . $this->professional->name]);
    }
}
