<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Professional;
use Livewire\Component;

class Homepage extends Component
{
    public string $search = '';
    public string $city   = '';

    public function doSearch(): void
    {
        $params = array_filter([
            'search' => $this->search,
            'city'   => $this->city,
        ]);

        $this->redirect(route('professionals.index', $params));
    }

    public function render()
    {
        return view('livewire.homepage', [
            'categories'           => Category::orderBy('order')->withCount('professionals')->get(),
            'featuredProfessionals'=> Professional::where('is_featured', true)
                ->where('availability', 'available')
                ->with('category')
                ->orderByDesc('average_rating')
                ->take(6)
                ->get(),
            'totalProfessionals'   => Professional::count(),
            'totalCities'          => Professional::distinct('city')->count('city'),
        ])->layout('layouts.app', ['title' => 'M3allemClick - Trouvez votre artisan']);
    }
}
