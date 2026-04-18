<div>
    {{-- Page Header --}}
    <div class="bg-gradient-to-r from-blue-700 to-blue-600 text-white py-10">
        <div class="max-w-7xl mx-auto px-4">
            <h1 class="text-3xl font-bold mb-1">Professionnels</h1>
            <p class="text-blue-100">Trouvez l'artisan idéal pour votre projet</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-8">

        {{-- Filters Card --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 mb-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                {{-- Search --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide">Recherche</label>
                    <input
                        wire:model.live.debounce.300ms="search"
                        type="text"
                        placeholder="Nom, service, ville..."
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                    >
                </div>
                {{-- City --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide">Ville</label>
                    <select wire:model.live="city"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="">📍 Toutes les villes</option>
                        @foreach($cities as $c)
                        <option value="{{ $c }}">{{ $c }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- Category --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide">Catégorie</label>
                    <select wire:model.live="category"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="">🗂️ Toutes les catégories</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->slug }}">{{ $cat->icon }} {{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- Availability --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide">Disponibilité</label>
                    <select wire:model.live="availability"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="">Tous</option>
                        <option value="available">✅ Disponible</option>
                        <option value="busy">⏳ Occupé</option>
                        <option value="closed">❌ Fermé</option>
                    </select>
                </div>
            </div>

            @if($search || $city || $category || $availability)
            <div class="mt-3 pt-3 border-t border-gray-100 flex items-center justify-between">
                <div class="flex flex-wrap gap-2">
                    @if($search)
                    <span class="inline-flex items-center space-x-1 text-xs bg-blue-50 text-blue-700 px-3 py-1 rounded-full">
                        <span>Recherche: {{ $search }}</span>
                        <button wire:click="$set('search', '')" class="hover:text-blue-900">✕</button>
                    </span>
                    @endif
                    @if($city)
                    <span class="inline-flex items-center space-x-1 text-xs bg-blue-50 text-blue-700 px-3 py-1 rounded-full">
                        <span>Ville: {{ $city }}</span>
                        <button wire:click="$set('city', '')" class="hover:text-blue-900">✕</button>
                    </span>
                    @endif
                    @if($category)
                    <span class="inline-flex items-center space-x-1 text-xs bg-blue-50 text-blue-700 px-3 py-1 rounded-full">
                        <span>Catégorie: {{ $category }}</span>
                        <button wire:click="$set('category', '')" class="hover:text-blue-900">✕</button>
                    </span>
                    @endif
                    @if($availability)
                    <span class="inline-flex items-center space-x-1 text-xs bg-blue-50 text-blue-700 px-3 py-1 rounded-full">
                        <span>Dispo: {{ $availability }}</span>
                        <button wire:click="$set('availability', '')" class="hover:text-blue-900">✕</button>
                    </span>
                    @endif
                </div>
                <button wire:click="clearFilters"
                        class="text-sm text-red-500 hover:text-red-700 font-medium transition">
                    Effacer tout
                </button>
            </div>
            @endif
        </div>

        {{-- Sort + Count Bar --}}
        <div class="flex justify-between items-center mb-5">
            <p class="text-sm text-gray-500">
                <span class="font-semibold text-gray-700">{{ $professionals->total() }}</span> professionnel{{ $professionals->total() > 1 ? 's' : '' }} trouvé{{ $professionals->total() > 1 ? 's' : '' }}
            </p>
            <select wire:model.live="sort"
                    class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                <option value="featured">⭐ En vedette</option>
                <option value="rating">🏆 Mieux notés</option>
                <option value="views">👁️ Plus vus</option>
                <option value="newest">🆕 Plus récents</option>
            </select>
        </div>

        {{-- Loading Indicator --}}
        <div wire:loading.flex class="items-center justify-center py-4 mb-4">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
            <span class="ml-3 text-blue-600 text-sm">Chargement...</span>
        </div>

        {{-- Grid --}}
        <div wire:loading.class="opacity-40" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 transition-opacity duration-200">
            @forelse($professionals as $pro)
            @php $badge = $pro->getStatusBadge(); @endphp
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition flex flex-col">
                {{-- Header --}}
                <div class="flex items-start space-x-4">
                    <div class="w-14 h-14 rounded-full bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center text-xl font-bold text-blue-700 flex-shrink-0">
                        {{ strtoupper(substr($pro->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center space-x-1">
                            <h3 class="font-semibold text-gray-800 truncate">{{ $pro->name }}</h3>
                            @if($pro->is_verified)
                            <svg class="w-4 h-4 text-blue-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            @endif
                        </div>
                        <p class="text-sm text-blue-600 font-medium">{{ $pro->category->name }}</p>
                        <div class="flex items-center mt-1 space-x-3 text-xs text-gray-500">
                            <span>📍 {{ $pro->city }}</span>
                            <span>⭐ {{ number_format($pro->average_rating, 1) }}
                                <span class="text-gray-400">({{ $pro->total_reviews }})</span>
                            </span>
                        </div>
                    </div>
                    <span class="text-xs px-2 py-1 rounded-full {{ $badge['class'] }} flex-shrink-0 whitespace-nowrap">
                        {{ $badge['label'] }}
                    </span>
                </div>

                {{-- Description --}}
                @if($pro->description)
                <p class="text-sm text-gray-600 mt-3 line-clamp-2 flex-1">{{ $pro->description }}</p>
                @endif

                {{-- Skills --}}
                @if($pro->skills && count($pro->skills) > 0)
                <div class="flex flex-wrap gap-1.5 mt-3">
                    @foreach(array_slice($pro->skills, 0, 3) as $skill)
                    <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full">{{ $skill }}</span>
                    @endforeach
                    @if(count($pro->skills) > 3)
                    <span class="text-xs text-gray-400 px-2 py-0.5">+{{ count($pro->skills) - 3 }}</span>
                    @endif
                </div>
                @endif

                {{-- Stats Row --}}
                <div class="flex items-center space-x-4 mt-3 text-xs text-gray-400 border-t border-gray-50 pt-3">
                    <span>👁️ {{ number_format($pro->total_views) }}</span>
                    <span>💬 {{ number_format($pro->total_whatsapp_clicks) }}</span>
                    <span>📞 {{ number_format($pro->total_calls) }}</span>
                </div>

                {{-- Actions --}}
                <div class="flex space-x-2 mt-3">
                    <a href="{{ route('professionals.show', $pro->slug) }}"
                       class="flex-1 text-center bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition text-sm font-medium">
                        Voir profil
                    </a>
                    @if($pro->availability !== 'closed')
                    <a href="{{ $pro->getWhatsAppUrl() }}" target="_blank" rel="noopener"
                       class="flex-1 text-center bg-green-500 text-white py-2 rounded-lg hover:bg-green-600 transition text-sm font-medium">
                        💬 WhatsApp
                    </a>
                    @endif
                </div>
            </div>
            @empty
            <div class="col-span-3 text-center py-20 text-gray-400">
                <div class="text-7xl mb-4">🔍</div>
                <p class="text-xl font-semibold text-gray-600">Aucun professionnel trouvé</p>
                <p class="mt-2 text-sm">Essayez de modifier vos critères de recherche</p>
                <button wire:click="clearFilters"
                        class="mt-5 bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition text-sm font-medium">
                    Réinitialiser les filtres
                </button>
            </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($professionals->hasPages())
        <div class="mt-8">
            {{ $professionals->links() }}
        </div>
        @endif
    </div>
</div>
