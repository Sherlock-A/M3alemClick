<div>
    {{-- Tabs --}}
    <div class="flex space-x-1 bg-white rounded-xl shadow-sm border border-gray-100 p-1.5 mb-6 w-fit">
        @foreach([
            ['key' => 'general',  'label' => '⚙️ Général'],
            ['key' => 'whatsapp', 'label' => '💬 WhatsApp'],
            ['key' => 'limits',   'label' => '🔒 Limites'],
            ['key' => 'system',   'label' => '🖥 Système'],
        ] as $tab)
        <button wire:click="$set('activeTab','{{ $tab['key'] }}')"
                class="px-4 py-2 rounded-lg text-sm font-medium transition
                       {{ $activeTab === $tab['key'] ? 'bg-blue-600 text-white shadow-sm' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}">
            {{ $tab['label'] }}
        </button>
        @endforeach
    </div>

    {{-- General --}}
    @if($activeTab === 'general')
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 max-w-2xl animate-fade-in">
        <h3 class="font-semibold text-gray-800 text-lg mb-5">Informations générales</h3>
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nom de la plateforme</label>
                <input wire:model="appName" class="input-field">
                @error('appName') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email de contact</label>
                <input wire:model="appEmail" type="email" class="input-field">
                @error('appEmail') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
                <input wire:model="appPhone" class="input-field">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Adresse</label>
                <input wire:model="appAddress" class="input-field">
            </div>
        </div>
        <button wire:click="saveGeneral"
                class="mt-6 btn-primary" wire:loading.attr="disabled">
            <span wire:loading.remove wire:target="saveGeneral">💾 Sauvegarder</span>
            <span wire:loading wire:target="saveGeneral">Sauvegarde...</span>
        </button>
    </div>
    @endif

    {{-- WhatsApp --}}
    @if($activeTab === 'whatsapp')
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 max-w-2xl animate-fade-in">
        <h3 class="font-semibold text-gray-800 text-lg mb-2">Message WhatsApp par défaut</h3>
        <p class="text-gray-500 text-sm mb-5">Ce message est pré-rempli quand un client clique sur "Contacter via WhatsApp".</p>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Message</label>
            <textarea wire:model="whatsappMessage" rows="4"
                      class="input-field resize-none"
                      placeholder="Bonjour, j'ai trouvé votre profil sur M3allemClick..."></textarea>
            <p class="text-xs text-gray-400 mt-1">{{ strlen($whatsappMessage) }}/300 caractères</p>
            @error('whatsappMessage') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mt-4 bg-green-50 border border-green-200 rounded-lg p-4">
            <p class="text-xs text-green-700 font-semibold mb-1">Aperçu :</p>
            <p class="text-sm text-green-800">{{ $whatsappMessage }}</p>
        </div>

        <button wire:click="saveWhatsapp" class="mt-5 btn-whatsapp">
            💬 Sauvegarder le message
        </button>
    </div>
    @endif

    {{-- Limits --}}
    @if($activeTab === 'limits')
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 max-w-2xl animate-fade-in">
        <h3 class="font-semibold text-gray-800 text-lg mb-5">Limites de la plateforme</h3>
        <div class="space-y-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Max professionnels par ville</label>
                <input wire:model="maxProsPerCity" type="number" min="1" max="500" class="input-field w-40">
                @error('maxProsPerCity') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Max avis par jour (par IP)</label>
                <input wire:model="maxReviewsPerDay" type="number" min="1" max="100" class="input-field w-40">
                @error('maxReviewsPerDay') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>
        <button wire:click="saveLimits" class="mt-6 btn-primary">💾 Sauvegarder</button>
    </div>
    @endif

    {{-- System --}}
    @if($activeTab === 'system')
    <div class="max-w-2xl space-y-4 animate-fade-in">
        {{-- Cache --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-semibold text-gray-800 mb-2">Cache & Performance</h3>
            <p class="text-gray-500 text-sm mb-4">Vide le cache de vues, config et données. À utiliser après des modifications.</p>
            <button wire:click="clearCache"
                    wire:loading.attr="disabled"
                    class="btn-secondary"
                    wire:confirm="Vider le cache de l'application ?">
                <span wire:loading.remove wire:target="clearCache">🗑 Vider le cache</span>
                <span wire:loading wire:target="clearCache">Nettoyage...</span>
            </button>
        </div>

        {{-- PHP / Laravel info --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-semibold text-gray-800 mb-4">Informations système</h3>
            <dl class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <dt class="text-gray-500">Laravel</dt>
                    <dd class="font-medium">{{ app()->version() }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">PHP</dt>
                    <dd class="font-medium">{{ PHP_VERSION }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Environnement</dt>
                    <dd class="font-medium">{{ app()->environment() }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Debug mode</dt>
                    <dd class="font-medium {{ config('app.debug') ? 'text-yellow-600' : 'text-green-600' }}">
                        {{ config('app.debug') ? 'Activé' : 'Désactivé' }}
                    </dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Cache driver</dt>
                    <dd class="font-medium">{{ config('cache.default') }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Session driver</dt>
                    <dd class="font-medium">{{ config('session.driver') }}</dd>
                </div>
            </dl>
        </div>
    </div>
    @endif
</div>
