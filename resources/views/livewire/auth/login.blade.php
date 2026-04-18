<div>
    <h2 class="text-2xl font-bold text-gray-800 mb-1">Connexion</h2>
    <p class="text-gray-500 text-sm mb-6">Bienvenue sur M3allemClick</p>

    <form wire:submit="login" class="space-y-4">

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input wire:model="email" type="email" placeholder="votre@email.com"
                   class="input-field @error('email') border-red-400 @enderror">
            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
            <input wire:model="password" type="password" placeholder="••••••••"
                   class="input-field @error('password') border-red-400 @enderror">
            @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center justify-between text-sm">
            <label class="flex items-center space-x-2 cursor-pointer">
                <input wire:model="remember" type="checkbox" class="rounded text-blue-600">
                <span class="text-gray-600">Se souvenir de moi</span>
            </label>
            <a href="{{ route('password.request') }}" class="text-blue-600 hover:underline">Mot de passe oublié ?</a>
        </div>

        <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-lg transition"
                wire:loading.attr="disabled" wire:loading.class="opacity-75">
            <span wire:loading.remove>Se connecter</span>
            <span wire:loading>Connexion en cours...</span>
        </button>
    </form>

    <p class="text-center text-sm text-gray-500 mt-6">
        Pas de compte ?
        <a href="{{ route('register') }}" class="text-blue-600 font-medium hover:underline">S'inscrire</a>
    </p>
</div>
