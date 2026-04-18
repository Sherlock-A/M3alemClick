<div>
    {{-- ===== HERO SECTION ===== --}}
    <section class="bg-gradient-to-br from-blue-700 via-blue-600 to-indigo-700 text-white py-20 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-10 left-10 w-40 h-40 rounded-full bg-white"></div>
            <div class="absolute bottom-10 right-20 w-64 h-64 rounded-full bg-white"></div>
        </div>
        <div class="max-w-4xl mx-auto px-4 text-center relative z-10">
            <h1 class="text-4xl md:text-5xl font-bold mb-4 leading-tight">
                Trouvez votre <span class="text-orange-400">artisan idéal</span><br>en quelques secondes
            </h1>
            <p class="text-xl text-blue-100 mb-10">
                {{ $totalProfessionals }}+ professionnels vérifiés dans {{ $totalCities }}+ villes du Maroc
            </p>

            {{-- Search Card --}}
            <div class="bg-white rounded-2xl p-5 shadow-2xl">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <input
                        wire:model="search"
                        type="text"
                        placeholder="🔍 Quel service recherchez-vous?"
                        class="border border-gray-200 rounded-xl px-4 py-3 text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 w-full text-sm"
                    >
                    <input
                        wire:model="city"
                        type="text"
                        placeholder="📍 Votre ville (ex: Casablanca)"
                        class="border border-gray-200 rounded-xl px-4 py-3 text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500 w-full text-sm"
                    >
                    <button
                        wire:click="doSearch"
                        class="bg-orange-500 hover:bg-orange-600 active:bg-orange-700 text-white font-semibold py-3 px-6 rounded-xl transition w-full shadow-md"
                    >
                        Rechercher
                    </button>
                </div>
                <div class="flex flex-wrap gap-2 mt-3 justify-center">
                    @foreach(['Plombier', 'Électricien', 'Peintre', 'Femme de ménage'] as $quick)
                    <button
                        wire:click="$set('search', '{{ $quick }}')"
                        class="text-xs text-blue-600 bg-blue-50 hover:bg-blue-100 px-3 py-1 rounded-full transition"
                    >
                        {{ $quick }}
                    </button>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- ===== CATEGORIES ===== --}}
    <section class="max-w-7xl mx-auto px-4 py-16">
        <h2 class="text-3xl font-bold text-gray-800 text-center mb-3">Nos catégories de services</h2>
        <p class="text-gray-500 text-center mb-10">Choisissez le service dont vous avez besoin</p>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4">
            @foreach($categories as $cat)
            <a href="{{ route('professionals.index', ['category' => $cat->slug]) }}"
               class="bg-white rounded-xl p-5 text-center shadow-sm hover:shadow-lg border border-gray-100 hover:border-blue-200 transition group cursor-pointer">
                <div class="text-4xl mb-3">{{ $cat->icon }}</div>
                <div class="text-sm font-semibold text-gray-700 group-hover:text-blue-600 transition">{{ $cat->name }}</div>
                <div class="text-xs text-gray-400 mt-1">{{ $cat->professionals_count }} pro{{ $cat->professionals_count > 1 ? 's' : '' }}</div>
            </a>
            @endforeach
        </div>
    </section>

    {{-- ===== FEATURED PROFESSIONALS ===== --}}
    @if($featuredProfessionals->isNotEmpty())
    <section class="bg-gray-50 py-16">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center mb-10">
                <div>
                    <h2 class="text-3xl font-bold text-gray-800">Professionnels en vedette</h2>
                    <p class="text-gray-500 mt-1">Les mieux notés sur notre plateforme</p>
                </div>
                <a href="{{ route('professionals.index') }}"
                   class="text-blue-600 hover:text-blue-800 font-medium transition flex items-center space-x-1">
                    <span>Voir tous</span>
                    <span>→</span>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($featuredProfessionals as $pro)
                @php $badge = $pro->getStatusBadge(); @endphp
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition">
                    <div class="flex items-start space-x-4">
                        <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center text-2xl font-bold text-blue-600 flex-shrink-0">
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
                                <span>⭐ {{ number_format($pro->average_rating, 1) }} ({{ $pro->total_reviews }})</span>
                            </div>
                        </div>
                        <span class="text-xs px-2 py-1 rounded-full {{ $badge['class'] }} flex-shrink-0">
                            {{ $badge['label'] }}
                        </span>
                    </div>

                    @if($pro->description)
                    <p class="text-sm text-gray-600 mt-3 line-clamp-2">{{ $pro->description }}</p>
                    @endif

                    @if($pro->skills && count($pro->skills) > 0)
                    <div class="flex flex-wrap gap-1.5 mt-3">
                        @foreach(array_slice($pro->skills, 0, 3) as $skill)
                        <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full">{{ $skill }}</span>
                        @endforeach
                        @if(count($pro->skills) > 3)
                        <span class="text-xs bg-gray-100 text-gray-400 px-2 py-0.5 rounded-full">+{{ count($pro->skills) - 3 }}</span>
                        @endif
                    </div>
                    @endif

                    <div class="flex space-x-2 mt-4">
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
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ===== STATS BANNER ===== --}}
    <section class="bg-blue-700 text-white py-16">
        <div class="max-w-5xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                <div>
                    <div class="text-5xl font-extrabold text-orange-400">{{ $totalProfessionals }}+</div>
                    <div class="text-blue-100 mt-2 text-lg">Professionnels inscrits</div>
                </div>
                <div>
                    <div class="text-5xl font-extrabold text-orange-400">{{ $totalCities }}+</div>
                    <div class="text-blue-100 mt-2 text-lg">Villes couvertes</div>
                </div>
                <div>
                    <div class="text-5xl font-extrabold text-orange-400">{{ $categories->count() }}</div>
                    <div class="text-blue-100 mt-2 text-lg">Catégories de services</div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== HOW IT WORKS ===== --}}
    <section class="max-w-5xl mx-auto px-4 py-16 text-center">
        <h2 class="text-3xl font-bold text-gray-800 mb-3">Comment ça marche?</h2>
        <p class="text-gray-500 mb-12">Simple, rapide, efficace</p>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="p-6">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center text-3xl mx-auto mb-4">🔍</div>
                <h3 class="font-semibold text-gray-800 mb-2">1. Recherchez</h3>
                <p class="text-gray-500 text-sm">Entrez votre service et votre ville pour trouver les professionnels disponibles.</p>
            </div>
            <div class="p-6">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center text-3xl mx-auto mb-4">📋</div>
                <h3 class="font-semibold text-gray-800 mb-2">2. Comparez</h3>
                <p class="text-gray-500 text-sm">Consultez les profils, les avis et les compétences de chaque professionnel.</p>
            </div>
            <div class="p-6">
                <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center text-3xl mx-auto mb-4">📱</div>
                <h3 class="font-semibold text-gray-800 mb-2">3. Contactez</h3>
                <p class="text-gray-500 text-sm">Contactez directement via WhatsApp ou téléphone. Rapide et gratuit.</p>
            </div>
        </div>
    </section>
</div>
