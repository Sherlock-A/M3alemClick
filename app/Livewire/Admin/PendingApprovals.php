<?php

namespace App\Livewire\Admin;

use App\Mail\ProfessionalApproved;
use App\Mail\ProfessionalRejected;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\WithPagination;

class PendingApprovals extends Component
{
    use WithPagination;

    public string  $status          = 'en_attente';
    public string  $search          = '';
    public ?int    $selectedUserId  = null;
    public string  $rejectionReason = '';
    public bool    $showRejectModal = false;
    public ?User   $selectedUser    = null;
    public bool    $showDetail      = false;

    public function approveProfessional(int $userId): void
    {
        $user = User::with('professional')->findOrFail($userId);
        $user->update(['status' => 'actif', 'rejection_reason' => null]);

        if ($user->professional) {
            $user->professional->update(['is_verified' => true]);
        }

        try { Mail::to($user->email)->send(new ProfessionalApproved($user)); } catch (\Exception) {}

        session()->flash('success', "Profil de {$user->name} approuvé.");
    }

    public function openRejectModal(int $userId): void
    {
        $this->selectedUserId  = $userId;
        $this->rejectionReason = '';
        $this->showRejectModal = true;
    }

    public function confirmReject(): void
    {
        $this->validate(['rejectionReason' => 'required|min:10|max:500']);

        $user = User::findOrFail($this->selectedUserId);
        $user->update(['status' => 'refuse', 'rejection_reason' => $this->rejectionReason]);

        try { Mail::to($user->email)->send(new ProfessionalRejected($user)); } catch (\Exception) {}

        $this->showRejectModal = false;
        session()->flash('success', "Profil de {$user->name} refusé.");
    }

    public function suspendUser(int $userId): void
    {
        User::findOrFail($userId)->update(['status' => 'suspendu']);
        session()->flash('success', 'Compte suspendu.');
    }

    public function reactivateUser(int $userId): void
    {
        User::findOrFail($userId)->update(['status' => 'actif']);
        session()->flash('success', 'Compte réactivé.');
    }

    public function viewDetail(int $userId): void
    {
        $this->selectedUser = User::with('professional.category')->findOrFail($userId);
        $this->showDetail   = true;
    }

    public function updatingSearch(): void  { $this->resetPage(); }
    public function updatingStatus(): void  { $this->resetPage(); }

    public function render()
    {
        $users = User::with('professional.category')
            ->where('role', 'professional')
            ->when($this->status !== 'all', fn($q) => $q->where('status', $this->status))
            ->when($this->search, fn($q) => $q->where(fn($q) =>
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('email', 'like', "%{$this->search}%")))
            ->latest()
            ->paginate(15);

        $counts = [
            'en_attente' => User::where('role', 'professional')->where('status', 'en_attente')->count(),
            'actif'      => User::where('role', 'professional')->where('status', 'actif')->count(),
            'refuse'     => User::where('role', 'professional')->where('status', 'refuse')->count(),
            'suspendu'   => User::where('role', 'professional')->where('status', 'suspendu')->count(),
        ];

        return view('livewire.admin.pending-approvals', compact('users', 'counts'))
            ->layout('layouts.admin', ['title' => 'Gestion des inscriptions', 'active' => 'approvals']);
    }
}
