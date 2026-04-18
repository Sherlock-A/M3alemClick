<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\Professional;
use Livewire\Component;
use Livewire\WithPagination;

class ProfessionalManager extends Component
{
    use WithPagination;

    public string $search             = '';
    public string $filterCategory     = '';
    public string $filterAvailability = '';

    public bool         $showEditModal  = false;
    public ?Professional $editing       = null;
    public string       $editName       = '';
    public string       $editPhone      = '';
    public string       $editCity       = '';
    public string       $editDesc       = '';
    public string       $editAvail      = 'available';
    public int          $editCatId      = 0;
    public bool         $editVerified   = false;
    public bool         $editFeatured   = false;

    public function editProfessional(int $id): void
    {
        $this->editing      = Professional::findOrFail($id);
        $this->editName     = $this->editing->name;
        $this->editPhone    = $this->editing->phone;
        $this->editCity     = $this->editing->city;
        $this->editDesc     = $this->editing->description ?? '';
        $this->editAvail    = $this->editing->availability;
        $this->editCatId    = $this->editing->category_id;
        $this->editVerified = $this->editing->is_verified;
        $this->editFeatured = $this->editing->is_featured;
        $this->showEditModal = true;
    }

    public function saveEdit(): void
    {
        $this->validate([
            'editName'  => 'required|min:2|max:100',
            'editPhone' => 'required|min:9|max:20',
            'editCity'  => 'required|max:100',
            'editCatId' => 'required|exists:categories,id',
        ]);

        $this->editing->update([
            'name'         => $this->editName,
            'phone'        => $this->editPhone,
            'city'         => $this->editCity,
            'description'  => $this->editDesc,
            'availability' => $this->editAvail,
            'category_id'  => $this->editCatId,
            'is_verified'  => $this->editVerified,
            'is_featured'  => $this->editFeatured,
        ]);

        $this->showEditModal = false;
        session()->flash('success', 'Professionnel mis à jour.');
    }

    public function deleteProfessional(int $id): void
    {
        Professional::findOrFail($id)->delete();
        session()->flash('success', 'Professionnel supprimé.');
    }

    public function toggleFeatured(int $id): void
    {
        $pro = Professional::findOrFail($id);
        $pro->update(['is_featured' => !$pro->is_featured]);
    }

    public function toggleVerified(int $id): void
    {
        $pro = Professional::findOrFail($id);
        $pro->update(['is_verified' => !$pro->is_verified]);
    }

    public function updatingSearch(): void { $this->resetPage(); }

    public function render()
    {
        $professionals = Professional::with('category')
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%")
                ->orWhere('city', 'like', "%{$this->search}%"))
            ->when($this->filterCategory, fn($q) => $q->where('category_id', $this->filterCategory))
            ->when($this->filterAvailability, fn($q) => $q->where('availability', $this->filterAvailability))
            ->latest()
            ->paginate(15);

        return view('livewire.admin.professional-manager', [
            'professionals' => $professionals,
            'categories'    => Category::orderBy('order')->get(),
        ])->layout('layouts.admin', ['title' => 'Professionnels', 'active' => 'professionals']);
    }
}
