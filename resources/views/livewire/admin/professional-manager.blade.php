<div>
    {{-- Filters --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-4 flex flex-wrap gap-3">
        <input wire:model.live.debounce.300ms="search" type="text"
               placeholder="🔍 Nom ou ville..." class="input-field flex-1 min-w-48">
        <select wire:model.live="filterCategory" class="input-field w-48">
            <option value="">Toutes catégories</option>
            @foreach($categories as $cat)
            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
        </select>
        <select wire:model.live="filterAvailability" class="input-field w-40">
            <option value="">Disponibilité</option>
            <option value="available">Disponible</option>
            <option value="busy">Occupé</option>
            <option value="closed">Fermé</option>
        </select>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="text-left px-5 py-3 font-semibold text-gray-600">Professionnel</th>
                    <th class="text-left px-5 py-3 font-semibold text-gray-600">Catégorie</th>
                    <th class="text-left px-5 py-3 font-semibold text-gray-600">Stats</th>
                    <th class="text-left px-5 py-3 font-semibold text-gray-600">Statut</th>
                    <th class="text-right px-5 py-3 font-semibold text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($professionals as $pro)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-5 py-4">
                        <div class="font-medium text-gray-800">{{ $pro->name }}</div>
                        <div class="text-gray-400 text-xs">{{ $pro->city }} · {{ $pro->phone }}</div>
                    </td>
                    <td class="px-5 py-4 text-gray-600">{{ $pro->category?->name ?? '—' }}</td>
                    <td class="px-5 py-4 text-xs text-gray-500">
                        <span title="Vues">👁 {{ $pro->total_views }}</span>
                        <span class="ml-2" title="WhatsApp">💬 {{ $pro->total_whatsapp_clicks }}</span>
                        <span class="ml-2" title="Appels">📞 {{ $pro->total_calls }}</span>
                    </td>
                    <td class="px-5 py-4">
                        <div class="flex items-center space-x-2">
                            <button wire:click="toggleVerified({{ $pro->id }})"
                                    class="text-xs px-2 py-1 rounded-full transition {{ $pro->is_verified ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-500' }}"
                                    title="{{ $pro->is_verified ? 'Vérifié' : 'Non vérifié' }}">
                                {{ $pro->is_verified ? '✓ Vérifié' : 'Non vérifié' }}
                            </button>
                            <button wire:click="toggleFeatured({{ $pro->id }})"
                                    class="text-xs px-2 py-1 rounded-full transition {{ $pro->is_featured ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-500' }}"
                                    title="Vedette">
                                {{ $pro->is_featured ? '⭐ Vedette' : 'Normal' }}
                            </button>
                        </div>
                    </td>
                    <td class="px-5 py-4">
                        <div class="flex items-center justify-end space-x-2">
                            <a href="{{ route('professionals.show', $pro->slug) }}" target="_blank"
                               class="text-blue-600 hover:underline text-xs">Voir</a>
                            <button wire:click="editProfessional({{ $pro->id }})"
                                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs px-3 py-1.5 rounded-lg transition">
                                Modifier
                            </button>
                            <button wire:click="deleteProfessional({{ $pro->id }})"
                                    wire:confirm="Supprimer définitivement ce professionnel ?"
                                    class="bg-red-50 hover:bg-red-100 text-red-600 text-xs px-3 py-1.5 rounded-lg transition">
                                Supprimer
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-5 py-10 text-center text-gray-400">Aucun professionnel trouvé.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @if($professionals->hasPages())
        <div class="px-5 py-4 border-t border-gray-100">{{ $professionals->links() }}</div>
        @endif
    </div>

    {{-- Edit Modal --}}
    @if($showEditModal && $editing)
    <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full p-6 max-h-[90vh] overflow-y-auto">
            <h3 class="text-lg font-bold text-gray-800 mb-5">Modifier le professionnel</h3>
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Nom</label>
                        <input wire:model="editName" class="input-field">
                        @error('editName') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Téléphone</label>
                        <input wire:model="editPhone" class="input-field">
                        @error('editPhone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Ville</label>
                        <input wire:model="editCity" class="input-field">
                        @error('editCity') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Catégorie</label>
                        <select wire:model="editCatId" class="input-field">
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        @error('editCatId') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Disponibilité</label>
                    <select wire:model="editAvail" class="input-field">
                        <option value="available">Disponible</option>
                        <option value="busy">Occupé</option>
                        <option value="closed">Fermé</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Description</label>
                    <textarea wire:model="editDesc" rows="3" class="input-field resize-none"></textarea>
                </div>
                <div class="flex space-x-4 text-sm">
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input wire:model="editVerified" type="checkbox" class="rounded text-blue-600">
                        <span>Vérifié</span>
                    </label>
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input wire:model="editFeatured" type="checkbox" class="rounded text-yellow-500">
                        <span>Vedette</span>
                    </label>
                </div>
            </div>
            <div class="flex space-x-3 mt-6">
                <button wire:click="$set('showEditModal', false)"
                        class="flex-1 border border-gray-200 text-gray-600 py-2.5 rounded-lg text-sm hover:bg-gray-50 transition">
                    Annuler
                </button>
                <button wire:click="saveEdit"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-lg text-sm transition">
                    Enregistrer
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
