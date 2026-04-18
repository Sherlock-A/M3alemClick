<?php

namespace App\Livewire\Professionals;

use App\Models\Category;
use App\Models\Professional;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $city = '';

    #[Url]
    public string $category = '';

    #[Url]
    public string $availability = '';

    #[Url]
    public string $sort = 'featured';

    public function updatingSearch(): void     { $this->resetPage(); }
    public function updatingCity(): void        { $this->resetPage(); }
    public function updatingCategory(): void    { $this->resetPage(); }
    public function updatingAvailability(): void{ $this->resetPage(); }
    public function updatingSort(): void        { $this->resetPage(); }

    public function clearFilters(): void
    {
        $this->search       = '';
        $this->city         = '';
        $this->category     = '';
        $this->availability = '';
        $this->sort         = 'featured';
        $this->resetPage();
    }

    public function render()
    {
        $query = Professional::with('category')
            ->when($this->search, fn($q) => $q->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('description', 'like', "%{$this->search}%")
                  ->orWhere('city', 'like', "%{$this->search}%");
            }))
            ->when($this->city, fn($q) => $q->where('city', $this->city))
            ->when($this->category, fn($q) => $q->whereHas('category', fn($q) => $q->where('slug', $this->category)))
            ->when($this->availability, fn($q) => $q->where('availability', $this->availability));

        $query = match ($this->sort) {
            'rating'  => $query->orderByDesc('average_rating'),
            'views'   => $query->orderByDesc('total_views'),
            'newest'  => $query->orderByDesc('created_at'),
            default   => $query->orderByDesc('is_featured')->orderByDesc('average_rating'),
        };

        return view('livewire.professionals.index', [
            'professionals' => $query->paginate(12),
            'categories'    => Category::orderBy('order')->get(),
            'cities'        => Professional::distinct()->orderBy('city')->pluck('city'),
        ])->layout('layouts.app', ['title' => 'Professionnels - M3allemClick']);
    }
}
