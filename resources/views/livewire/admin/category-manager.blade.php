<div>
    <div class="flex justify-end mb-4">
        <button wire:click="create"
                class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded-lg transition">
            + Nouvelle catégorie
        </button>
    </div>

    {{-- Form --}}
    @if($showForm)
    <div class="bg-white rounded-xl shadow-sm border border-blue-100 p-6 mb-5">
        <h3 class="font-semibold text-gray-800 mb-4">{{ $editingId ? 'Modifier' : 'Nouvelle' }} catégorie</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-2">
                <label class="block text-xs font-medium text-gray-600 mb-1">Nom</label>
                <input wire:model="name" type="text" placeholder="Ex: Plomberie"
                       class="input-field @error('name') border-red-400 @enderror">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Icône (emoji)</label>
                <input wire:model="icon" type="text" placeholder="🔧"
                       class="input-field @error('icon') border-red-400 @enderror">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Ordre d'affichage</label>
                <input wire:model="order" type="number" min="0"
                       class="input-field @error('order') border-red-400 @enderror">
            </div>
        </div>
        <div class="flex space-x-3 mt-4">
            <button wire:click="$set('showForm', false)"
                    class="border border-gray-200 text-gray-600 px-4 py-2 rounded-lg text-sm hover:bg-gray-50 transition">
                Annuler
            </button>
            <button wire:click="save"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm transition">
                Enregistrer
            </button>
        </div>
    </div>
    @endif

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="text-left px-5 py-3 font-semibold text-gray-600">Ordre</th>
                    <th class="text-left px-5 py-3 font-semibold text-gray-600">Icône</th>
                    <th class="text-left px-5 py-3 font-semibold text-gray-600">Nom / Slug</th>
                    <th class="text-left px-5 py-3 font-semibold text-gray-600">Professionnels</th>
                    <th class="text-right px-5 py-3 font-semibold text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($categories as $cat)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-5 py-3 text-gray-500">{{ $cat->order }}</td>
                    <td class="px-5 py-3 text-xl">{{ $cat->icon }}</td>
                    <td class="px-5 py-3">
                        <div class="font-medium text-gray-800">{{ $cat->name }}</div>
                        <div class="text-gray-400 text-xs">{{ $cat->slug }}</div>
                    </td>
                    <td class="px-5 py-3">
                        <span class="bg-blue-50 text-blue-700 text-xs px-2 py-1 rounded-full font-medium">
                            {{ $cat->professionals_count }}
                        </span>
                    </td>
                    <td class="px-5 py-3">
                        <div class="flex items-center justify-end space-x-2">
                            <button wire:click="edit({{ $cat->id }})"
                                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs px-3 py-1.5 rounded-lg transition">
                                Modifier
                            </button>
                            <button wire:click="delete({{ $cat->id }})"
                                    wire:confirm="Supprimer cette catégorie ? Les professionnels associés ne seront pas supprimés."
                                    class="bg-red-50 hover:bg-red-100 text-red-600 text-xs px-3 py-1.5 rounded-lg transition">
                                Supprimer
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-5 py-10 text-center text-gray-400">Aucune catégorie.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
