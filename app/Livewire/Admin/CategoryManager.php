<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use Illuminate\Support\Str;
use Livewire\Component;

class CategoryManager extends Component
{
    public bool   $showForm   = false;
    public ?int   $editingId  = null;
    public string $name       = '';
    public string $icon       = '';
    public int    $order      = 0;

    protected array $rules = [
        'name'  => 'required|min:2|max:100',
        'icon'  => 'nullable|max:10',
        'order' => 'required|integer|min:0',
    ];

    public function create(): void
    {
        $this->reset(['name', 'icon', 'editingId']);
        $this->order    = (Category::max('order') ?? 0) + 1;
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $cat            = Category::findOrFail($id);
        $this->editingId = $id;
        $this->name     = $cat->name;
        $this->icon     = $cat->icon ?? '';
        $this->order    = $cat->order;
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate();

        $data = ['name' => $this->name, 'icon' => $this->icon, 'order' => $this->order];

        if ($this->editingId) {
            Category::findOrFail($this->editingId)->update($data);
            session()->flash('success', 'Catégorie mise à jour.');
        } else {
            $data['slug'] = Str::slug($this->name);
            Category::create($data);
            session()->flash('success', 'Catégorie créée.');
        }

        $this->showForm = false;
        $this->reset(['name', 'icon', 'editingId']);
    }

    public function delete(int $id): void
    {
        Category::findOrFail($id)->delete();
        session()->flash('success', 'Catégorie supprimée.');
    }

    public function render()
    {
        return view('livewire.admin.category-manager', [
            'categories' => Category::withCount('professionals')->orderBy('order')->get(),
        ])->layout('layouts.admin', ['title' => 'Catégories', 'active' => 'categories']);
    }
}
