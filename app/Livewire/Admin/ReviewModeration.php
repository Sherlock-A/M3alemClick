<?php

namespace App\Livewire\Admin;

use App\Models\Review;
use Livewire\Component;
use Livewire\WithPagination;

class ReviewModeration extends Component
{
    use WithPagination;

    public string $filterStatus = 'pending';

    public function approve(int $id): void
    {
        Review::findOrFail($id)->update(['status' => 'approved']);
        session()->flash('success', 'Avis approuvé.');
    }

    public function reject(int $id): void
    {
        Review::findOrFail($id)->update(['status' => 'rejected']);
        session()->flash('success', 'Avis rejeté.');
    }

    public function delete(int $id): void
    {
        Review::findOrFail($id)->delete();
        session()->flash('success', 'Avis supprimé.');
    }

    public function updatingFilterStatus(): void { $this->resetPage(); }

    public function render()
    {
        $reviews = Review::with('professional')
            ->when($this->filterStatus !== 'all', fn($q) => $q->where('status', $this->filterStatus))
            ->latest()
            ->paginate(20);

        $counts = [
            'pending'  => Review::where('status', 'pending')->count(),
            'approved' => Review::where('status', 'approved')->count(),
            'rejected' => Review::where('status', 'rejected')->count(),
        ];

        return view('livewire.admin.review-moderation', compact('reviews', 'counts'))
            ->layout('layouts.admin', ['title' => 'Modération des avis', 'active' => 'reviews']);
    }
}
