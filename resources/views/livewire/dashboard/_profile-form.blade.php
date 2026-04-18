<div class="space-y-5">

    {{-- Nom + Téléphone --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Nom complet <span class="text-red-500">*</span></label>
            @if($creating || $editMode)
                <input wire:model="name" type="text" placeholder="Ex: Hassan Alami"
                       class="input-field w-full @error('name') border-red-400 @enderror">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            @else
                <p class="text-gray-800 font-medium py-2.5">{{ $name ?: '—' }}</p>
            @endif
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Téléphone <span class="text-red-500">*</span></label>
            @if($creating || $editMode)
                <input wire:model="phone" type="tel" placeholder="Ex: 0612345678"
                       class="input-field w-full @error('phone') border-red-400 @enderror">
                @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            @else
                <p class="text-gray-800 font-medium py-2.5">{{ $phone ?: '—' }}</p>
            @endif
        </div>
    </div>

    {{-- Ville + Catégorie --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Ville <span class="text-red-500">*</span></label>
            @if($creating || $editMode)
                <input wire:model="city" type="text" placeholder="Ex: Casablanca"
                       class="input-field w-full @error('city') border-red-400 @enderror">
                @error('city') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            @else
                <p class="text-gray-800 font-medium py-2.5">{{ $city ?: '—' }}</p>
            @endif
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Catégorie <span class="text-red-500">*</span></label>
            @if($creating || $editMode)
                <select wire:model="category_id"
                        class="input-field w-full @error('category_id') border-red-400 @enderror">
                    <option value="0">— Choisir une catégorie —</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
                @error('category_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            @else
                @php $cat = $categories->firstWhere('id', $category_id); @endphp
                <p class="text-gray-800 font-medium py-2.5">{{ $cat ? $cat->name : '—' }}</p>
            @endif
        </div>
    </div>

    {{-- Description --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Description / Présentation</label>
        @if($creating || $editMode)
            <textarea wire:model="description" rows="4"
                      placeholder="Décrivez votre activité, votre expérience, vos spécialités..."
                      class="input-field w-full resize-none @error('description') border-red-400 @enderror"></textarea>
            <div class="flex justify-between mt-1">
                @error('description')
                    <p class="text-red-500 text-xs">{{ $message }}</p>
                @else
                    <span></span>
                @enderror
                <span class="text-xs text-gray-400">{{ strlen($description) }}/1000</span>
            </div>
        @else
            <p class="text-gray-700 text-sm leading-relaxed py-2 {{ $description ? '' : 'italic text-gray-400' }}">
                {{ $description ?: 'Aucune description renseignée.' }}
            </p>
        @endif
    </div>

    {{-- Submit (create mode only) --}}
    @if($creating)
    <div class="pt-2">
        <button wire:click="createProfile" wire:loading.attr="disabled"
                class="btn-primary w-full py-3 text-sm font-semibold disabled:opacity-50 flex items-center justify-center space-x-2">
            <span wire:loading.remove wire:target="createProfile">🚀 Créer mon profil artisan</span>
            <span wire:loading wire:target="createProfile">⏳ Création en cours...</span>
        </button>
    </div>
    @endif

</div>
