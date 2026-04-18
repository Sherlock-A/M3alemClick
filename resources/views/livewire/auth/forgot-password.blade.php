<div>
    <h2 class="text-2xl font-bold text-gray-800 mb-1">Mot de passe oublié</h2>
    <p class="text-gray-500 text-sm mb-6">Entrez votre email pour recevoir un lien de réinitialisation.</p>

    @if($sent)
        <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg px-4 py-4 text-sm text-center">
            ✅ Lien envoyé ! Vérifiez votre boîte email.
        </div>
    @else
        <form wire:submit="sendLink" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input wire:model="email" type="email" placeholder="votre@email.com"
                       class="input-field @error('email') border-red-400 @enderror">
                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-lg transition"
                    wire:loading.attr="disabled">
                <span wire:loading.remove>Envoyer le lien</span>
                <span wire:loading>Envoi...</span>
            </button>
        </form>
    @endif

    <p class="text-center text-sm text-gray-500 mt-6">
        <a href="{{ route('login') }}" class="text-blue-600 hover:underline">← Retour à la connexion</a>
    </p>
</div>
