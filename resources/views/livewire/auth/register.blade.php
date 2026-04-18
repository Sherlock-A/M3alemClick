<div>
    <h2 class="text-2xl font-bold text-gray-800 mb-1">Créer un compte</h2>
    <p class="text-gray-500 text-sm mb-6">Rejoignez M3allemClick</p>

    <form wire:submit="register" class="space-y-4">

        {{-- Role tabs --}}
        <div class="grid grid-cols-2 gap-3 mb-2">
            <button type="button" wire:click="$set('role','client')"
                    class="py-2.5 rounded-lg text-sm font-medium border-2 transition
                           {{ $role === 'client' ? 'border-blue-600 bg-blue-50 text-blue-700' : 'border-gray-200 text-gray-500 hover:border-gray-300' }}">
                👤 Je cherche un artisan
            </button>
            <button type="button" wire:click="$set('role','professional')"
                    class="py-2.5 rounded-lg text-sm font-medium border-2 transition
                           {{ $role === 'professional' ? 'border-orange-500 bg-orange-50 text-orange-700' : 'border-gray-200 text-gray-500 hover:border-gray-300' }}">
                🔧 Je suis artisan
            </button>
        </div>

        <div class="grid grid-cols-1 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nom complet</label>
                <input wire:model="name" type="text" placeholder="Mohamed Alami"
                       class="input-field @error('name') border-red-400 @enderror">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input wire:model="email" type="email" placeholder="votre@email.com"
                       class="input-field @error('email') border-red-400 @enderror">
                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
                <input wire:model="phone" type="tel" placeholder="06XXXXXXXX"
                       class="input-field @error('phone') border-red-400 @enderror">
                @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Professional fields --}}
            @if($role === 'professional')
            <div x-data x-transition>
                <label class="block text-sm font-medium text-gray-700 mb-1">Ville</label>
                <input wire:model="city" type="text" placeholder="Casablanca"
                       class="input-field @error('city') border-red-400 @enderror">
                @error('city') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Catégorie de métier</label>
                <select wire:model="category_id"
                        class="input-field @error('category_id') border-red-400 @enderror">
                    <option value="0">-- Choisir une catégorie --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->icon }} {{ $cat->name }}</option>
                    @endforeach
                </select>
                @error('category_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
                <input wire:model="password" type="password" placeholder="Min. 8 caractères"
                       class="input-field @error('password') border-red-400 @enderror">
                @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Confirmer le mot de passe</label>
                <input wire:model="password_confirmation" type="password" placeholder="••••••••"
                       class="input-field">
            </div>
        </div>

        @if($role === 'professional')
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg px-4 py-3 text-xs text-yellow-700">
            ⏳ Votre profil sera soumis à validation admin avant d'être visible publiquement.
        </div>
        @endif

        <button type="submit"
                class="w-full {{ $role === 'professional' ? 'bg-orange-500 hover:bg-orange-600' : 'bg-blue-600 hover:bg-blue-700' }} text-white font-semibold py-2.5 rounded-lg transition"
                wire:loading.attr="disabled" wire:loading.class="opacity-75">
            <span wire:loading.remove>{{ $role === 'professional' ? 'Soumettre mon profil' : 'Créer mon compte' }}</span>
            <span wire:loading>Création en cours...</span>
        </button>
    </form>

    <p class="text-center text-sm text-gray-500 mt-6">
        Déjà inscrit ?
        <a href="{{ route('login') }}" class="text-blue-600 font-medium hover:underline">Se connecter</a>
    </p>
</div>
