<div>
    {{-- Status tabs --}}
    <div class="flex flex-wrap gap-2 mb-5">
        @foreach([
            ['key' => 'en_attente', 'label' => '⏳ En attente', 'color' => 'yellow'],
            ['key' => 'actif',      'label' => '✅ Actifs',      'color' => 'green'],
            ['key' => 'refuse',     'label' => '❌ Refusés',     'color' => 'red'],
            ['key' => 'suspendu',   'label' => '🚫 Suspendus',   'color' => 'gray'],
            ['key' => 'all',        'label' => '📋 Tous',        'color' => 'blue'],
        ] as $tab)
        <button wire:click="$set('status','{{ $tab['key'] }}')"
                class="flex items-center space-x-2 px-4 py-2 rounded-lg text-sm font-medium border transition
                       {{ $status === $tab['key'] ? 'bg-blue-600 border-blue-600 text-white' : 'bg-white border-gray-200 text-gray-600 hover:border-gray-300' }}">
            <span>{{ $tab['label'] }}</span>
            <span class="bg-white/20 px-1.5 py-0.5 rounded-full text-xs">{{ $counts[$tab['key']] ?? '' }}</span>
        </button>
        @endforeach
    </div>

    {{-- Search --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-4">
        <input wire:model.live.debounce.300ms="search" type="text"
               placeholder="🔍 Rechercher par nom ou email..."
               class="input-field">
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="text-left px-5 py-3 font-semibold text-gray-600">Professionnel</th>
                    <th class="text-left px-5 py-3 font-semibold text-gray-600">Catégorie / Ville</th>
                    <th class="text-left px-5 py-3 font-semibold text-gray-600">Statut</th>
                    <th class="text-left px-5 py-3 font-semibold text-gray-600">Inscrit le</th>
                    <th class="text-right px-5 py-3 font-semibold text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-5 py-4">
                        <div class="font-medium text-gray-800">{{ $user->name }}</div>
                        <div class="text-gray-400 text-xs">{{ $user->email }} · {{ $user->phone }}</div>
                    </td>
                    <td class="px-5 py-4">
                        <div class="text-gray-600">{{ $user->professional?->category?->name ?? '—' }}</div>
                        <div class="text-gray-400 text-xs">{{ $user->professional?->city ?? '—' }}</div>
                    </td>
                    <td class="px-5 py-4">
                        @php $badge = $user->getStatusBadge(); @endphp
                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $badge['class'] }}">
                            {{ $badge['label'] }}
                        </span>
                    </td>
                    <td class="px-5 py-4 text-gray-500 text-xs">{{ $user->created_at->format('d/m/Y') }}</td>
                    <td class="px-5 py-4">
                        <div class="flex items-center justify-end space-x-2">
                            <button wire:click="viewDetail({{ $user->id }})"
                                    class="text-blue-600 hover:underline text-xs">Détail</button>

                            @if($user->isPending() || $user->isRefused())
                            <button wire:click="approveProfessional({{ $user->id }})"
                                    class="bg-green-600 hover:bg-green-700 text-white text-xs px-3 py-1.5 rounded-lg transition">
                                ✓ Valider
                            </button>
                            <button wire:click="openRejectModal({{ $user->id }})"
                                    class="bg-red-500 hover:bg-red-600 text-white text-xs px-3 py-1.5 rounded-lg transition">
                                ✗ Rejeter
                            </button>
                            @endif

                            @if($user->isActive())
                            <button wire:click="suspendUser({{ $user->id }})"
                                    class="bg-gray-500 hover:bg-gray-600 text-white text-xs px-3 py-1.5 rounded-lg transition">
                                Suspendre
                            </button>
                            @endif

                            @if($user->isSuspended())
                            <button wire:click="reactivateUser({{ $user->id }})"
                                    class="bg-blue-600 hover:bg-blue-700 text-white text-xs px-3 py-1.5 rounded-lg transition">
                                Réactiver
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-5 py-10 text-center text-gray-400">
                        Aucun professionnel trouvé.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($users->hasPages())
        <div class="px-5 py-4 border-t border-gray-100">
            {{ $users->links() }}
        </div>
        @endif
    </div>

    {{-- Reject Modal --}}
    @if($showRejectModal)
    <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Motif de refus</h3>
            <textarea wire:model="rejectionReason" rows="4"
                      placeholder="Expliquez pourquoi ce profil est refusé (min. 10 caractères)..."
                      class="input-field resize-none mb-1"></textarea>
            @error('rejectionReason') <p class="text-red-500 text-xs mb-3">{{ $message }}</p> @enderror
            <div class="flex space-x-3 mt-4">
                <button wire:click="$set('showRejectModal', false)"
                        class="flex-1 border border-gray-200 text-gray-600 py-2.5 rounded-lg text-sm hover:bg-gray-50 transition">
                    Annuler
                </button>
                <button wire:click="confirmReject"
                        class="flex-1 bg-red-600 hover:bg-red-700 text-white py-2.5 rounded-lg text-sm transition">
                    Confirmer le refus
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- Detail Modal --}}
    @if($showDetail && $selectedUser)
    <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full p-6 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-start mb-4">
                <h3 class="text-lg font-bold text-gray-800">Profil de {{ $selectedUser->name }}</h3>
                <button wire:click="$set('showDetail', false)" class="text-gray-400 hover:text-gray-600 text-xl">✕</button>
            </div>
            <dl class="space-y-3 text-sm">
                <div><dt class="text-gray-400">Email</dt><dd class="font-medium">{{ $selectedUser->email }}</dd></div>
                <div><dt class="text-gray-400">Téléphone</dt><dd class="font-medium">{{ $selectedUser->phone }}</dd></div>
                <div><dt class="text-gray-400">Catégorie</dt><dd class="font-medium">{{ $selectedUser->professional?->category?->name ?? '—' }}</dd></div>
                <div><dt class="text-gray-400">Ville</dt><dd class="font-medium">{{ $selectedUser->professional?->city ?? '—' }}</dd></div>
                @if($selectedUser->professional?->description)
                <div><dt class="text-gray-400">Description</dt><dd class="font-medium">{{ $selectedUser->professional->description }}</dd></div>
                @endif
                @if($selectedUser->rejection_reason)
                <div class="bg-red-50 rounded-lg p-3">
                    <dt class="text-red-500 text-xs font-semibold mb-1">Motif de refus</dt>
                    <dd class="text-red-700 text-sm">{{ $selectedUser->rejection_reason }}</dd>
                </div>
                @endif
            </dl>
            <button wire:click="$set('showDetail', false)"
                    class="mt-6 w-full border border-gray-200 text-gray-600 py-2 rounded-lg text-sm hover:bg-gray-50 transition">
                Fermer
            </button>
        </div>
    </div>
    @endif
</div>
