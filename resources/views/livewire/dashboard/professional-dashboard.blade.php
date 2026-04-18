<div>
    {{-- Page Header --}}
    <div class="bg-gradient-to-r from-indigo-700 to-blue-600 text-white py-8">
        <div class="max-w-6xl mx-auto px-4 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">Dashboard Professionnel</h1>
                <p class="text-blue-100 mt-1">{{ $professional->name }} — {{ $professional->category->name }}</p>
            </div>
            <a href="{{ route('professionals.show', $professional->slug) }}"
               class="text-sm bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition font-medium">
                Voir mon profil →
            </a>
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-4 py-8 space-y-8">

        {{-- Save Success --}}
        @if($saved)
        <div
            x-data="{ show: true }"
            x-show="show"
            x-init="setTimeout(() => show = false, 3000)"
            class="bg-green-50 border border-green-200 rounded-xl p-4 flex items-center space-x-3 text-green-700"
        >
            <span class="text-xl">✅</span>
            <span class="font-medium">Modifications enregistrées avec succès!</span>
        </div>
        @endif

        {{-- ===== STATS CARDS ===== --}}
        <div>
            <h2 class="text-lg font-bold text-gray-800 mb-4">📊 Statistiques</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                    <div class="text-3xl font-bold text-gray-800">{{ number_format($stats['views_total']) }}</div>
                    <div class="text-sm text-gray-500 mt-1">Vues totales</div>
                    <div class="text-xs text-blue-600 mt-2">+{{ $stats['views_today'] }} aujourd'hui</div>
                </div>
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                    <div class="text-3xl font-bold text-gray-800">{{ $stats['views_week'] }}</div>
                    <div class="text-sm text-gray-500 mt-1">Vues cette semaine</div>
                    <div class="text-xs text-green-600 mt-2">7 derniers jours</div>
                </div>
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                    <div class="text-3xl font-bold text-green-700">{{ number_format($stats['whatsapp_total']) }}</div>
                    <div class="text-sm text-gray-500 mt-1">Clics WhatsApp</div>
                    <div class="text-xs text-green-600 mt-2">+{{ $stats['whatsapp_today'] }} aujourd'hui</div>
                </div>
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                    <div class="text-3xl font-bold text-blue-700">{{ number_format($stats['calls_total']) }}</div>
                    <div class="text-sm text-gray-500 mt-1">Appels reçus</div>
                    <div class="text-xs text-blue-600 mt-2">+{{ $stats['calls_today'] }} aujourd'hui</div>
                </div>
            </div>
        </div>

        {{-- Rating Summary --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center space-x-6">
            <div class="text-center">
                <div class="text-4xl font-extrabold text-yellow-500">{{ number_format($stats['rating'], 1) }}</div>
                <div class="flex justify-center mt-1">
                    @for($i = 1; $i <= 5; $i++)
                    <svg class="w-5 h-5 {{ $i <= round($stats['rating']) ? 'text-yellow-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    @endfor
                </div>
            </div>
            <div>
                <p class="text-lg font-semibold text-gray-700">Note moyenne</p>
                <p class="text-gray-500 text-sm">Basée sur {{ $stats['total_reviews'] }} avis</p>
            </div>
        </div>

        {{-- ===== AVAILABILITY TOGGLE ===== --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">🟢 Statut de disponibilité</h2>
            <div class="flex flex-wrap gap-3">
                <button
                    wire:click="setAvailability('available')"
                    class="flex items-center space-x-2 px-5 py-3 rounded-xl border-2 font-semibold transition
                        {{ $availability === 'available'
                            ? 'bg-green-500 border-green-500 text-white shadow-md'
                            : 'bg-white border-gray-200 text-gray-600 hover:border-green-300 hover:text-green-600' }}"
                >
                    <span class="w-3 h-3 rounded-full {{ $availability === 'available' ? 'bg-white' : 'bg-green-400' }}"></span>
                    <span>✅ Disponible</span>
                </button>
                <button
                    wire:click="setAvailability('busy')"
                    class="flex items-center space-x-2 px-5 py-3 rounded-xl border-2 font-semibold transition
                        {{ $availability === 'busy'
                            ? 'bg-yellow-500 border-yellow-500 text-white shadow-md'
                            : 'bg-white border-gray-200 text-gray-600 hover:border-yellow-300 hover:text-yellow-600' }}"
                >
                    <span class="w-3 h-3 rounded-full {{ $availability === 'busy' ? 'bg-white' : 'bg-yellow-400' }}"></span>
                    <span>⏳ Occupé</span>
                </button>
                <button
                    wire:click="setAvailability('closed')"
                    class="flex items-center space-x-2 px-5 py-3 rounded-xl border-2 font-semibold transition
                        {{ $availability === 'closed'
                            ? 'bg-red-500 border-red-500 text-white shadow-md'
                            : 'bg-white border-gray-200 text-gray-600 hover:border-red-300 hover:text-red-600' }}"
                >
                    <span class="w-3 h-3 rounded-full {{ $availability === 'closed' ? 'bg-white' : 'bg-red-400' }}"></span>
                    <span>❌ Fermé</span>
                </button>
            </div>
            <p class="text-xs text-gray-400 mt-3">Le changement est immédiat et visible sur votre profil public.</p>
        </div>

        {{-- ===== PROFILE EDITOR ===== --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <div class="flex justify-between items-center mb-5">
                <h2 class="text-lg font-bold text-gray-800">✏️ Mon Profil</h2>
                @if(!$editMode)
                <button wire:click="$set('editMode', true)"
                        class="text-sm bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition font-medium">
                    Modifier
                </button>
                @else
                <div class="flex space-x-2">
                    <button wire:click="cancelEdit"
                            class="text-sm bg-gray-100 text-gray-600 px-4 py-2 rounded-lg hover:bg-gray-200 transition font-medium">
                        Annuler
                    </button>
                    <button wire:click="saveProfile"
                            wire:loading.attr="disabled"
                            class="text-sm bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition font-medium disabled:opacity-50">
                        <span wire:loading.remove wire:target="saveProfile">💾 Enregistrer</span>
                        <span wire:loading wire:target="saveProfile">Sauvegarde...</span>
                    </button>
                </div>
                @endif
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                {{-- Name --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nom complet *</label>
                    @if($editMode)
                    <input wire:model="name" type="text"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    @else
                    <p class="px-3 py-2.5 bg-gray-50 rounded-lg text-sm text-gray-700">{{ $name }}</p>
                    @endif
                </div>

                {{-- Phone --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone *</label>
                    @if($editMode)
                    <input wire:model="phone" type="text"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    @else
                    <p class="px-3 py-2.5 bg-gray-50 rounded-lg text-sm text-gray-700">{{ $phone }}</p>
                    @endif
                </div>

                {{-- City --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ville *</label>
                    @if($editMode)
                    <input wire:model="city" type="text"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('city') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    @else
                    <p class="px-3 py-2.5 bg-gray-50 rounded-lg text-sm text-gray-700">{{ $city }}</p>
                    @endif
                </div>

                {{-- Description --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    @if($editMode)
                    <textarea wire:model="description" rows="4"
                              placeholder="Décrivez vos services, votre expérience..."
                              class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
                    @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    @else
                    <p class="px-3 py-2.5 bg-gray-50 rounded-lg text-sm text-gray-700 min-h-[80px]">{{ $description ?: 'Aucune description' }}</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- ===== SKILLS MANAGER ===== --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">🛠️ Compétences</h2>
            <div class="flex flex-wrap gap-2 mb-4">
                @foreach($skills as $index => $skill)
                <span class="inline-flex items-center space-x-1.5 bg-blue-50 text-blue-700 border border-blue-200 px-3 py-1.5 rounded-full text-sm">
                    <span>{{ $skill }}</span>
                    <button wire:click="removeSkill({{ $index }})"
                            class="hover:text-red-500 transition font-bold">✕</button>
                </span>
                @endforeach
                @if(count($skills) === 0)
                <p class="text-sm text-gray-400 italic">Aucune compétence ajoutée</p>
                @endif
            </div>
            <div class="flex space-x-2">
                <input wire:model="newSkill" wire:keydown.enter="addSkill"
                       type="text" placeholder="Ajouter une compétence..."
                       class="flex-1 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button wire:click="addSkill"
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition text-sm font-medium">
                    Ajouter
                </button>
            </div>
        </div>

        {{-- ===== LANGUAGES MANAGER ===== --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">🗣️ Langues parlées</h2>
            <div class="flex flex-wrap gap-2 mb-4">
                @foreach($languages as $index => $lang)
                <span class="inline-flex items-center space-x-1.5 bg-purple-50 text-purple-700 border border-purple-200 px-3 py-1.5 rounded-full text-sm">
                    <span>{{ $lang }}</span>
                    <button wire:click="removeLanguage({{ $index }})"
                            class="hover:text-red-500 transition font-bold">✕</button>
                </span>
                @endforeach
                @if(count($languages) === 0)
                <p class="text-sm text-gray-400 italic">Aucune langue ajoutée</p>
                @endif
            </div>
            <div class="flex space-x-2">
                <input wire:model="newLanguage" wire:keydown.enter="addLanguage"
                       type="text" placeholder="Ex: Français, Anglais..."
                       class="flex-1 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button wire:click="addLanguage"
                        class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition text-sm font-medium">
                    Ajouter
                </button>
            </div>
        </div>

        {{-- ===== TRAVEL CITIES MANAGER ===== --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">🚗 Zones de déplacement</h2>
            <div class="flex flex-wrap gap-2 mb-4">
                @foreach($travel_cities as $index => $tCity)
                <span class="inline-flex items-center space-x-1.5 bg-green-50 text-green-700 border border-green-200 px-3 py-1.5 rounded-full text-sm">
                    <span>📍 {{ $tCity }}</span>
                    <button wire:click="removeTravelCity({{ $index }})"
                            class="hover:text-red-500 transition font-bold">✕</button>
                </span>
                @endforeach
                @if(count($travel_cities) === 0)
                <p class="text-sm text-gray-400 italic">Aucune ville de déplacement ajoutée</p>
                @endif
            </div>
            <div class="flex space-x-2">
                <input wire:model="newTravelCity" wire:keydown.enter="addTravelCity"
                       type="text" placeholder="Ex: Casablanca, Rabat..."
                       class="flex-1 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button wire:click="addTravelCity"
                        class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition text-sm font-medium">
                    Ajouter
                </button>
            </div>
        </div>

    </div>
</div>
