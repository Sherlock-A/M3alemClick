<div
    x-data="{}"
    @open-url.window="window.open($event.detail.url, '_blank')"
>
    @php $badge = $professional->getStatusBadge(); @endphp

    {{-- Breadcrumb --}}
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-5xl mx-auto px-4 py-3 text-sm text-gray-500">
            <a href="{{ route('home') }}" class="hover:text-blue-600 transition">Accueil</a>
            <span class="mx-2">›</span>
            <a href="{{ route('professionals.index') }}" class="hover:text-blue-600 transition">Professionnels</a>
            <span class="mx-2">›</span>
            <span class="text-gray-800 font-medium">{{ $professional->name }}</span>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- ===== LEFT COLUMN: Profile Card ===== --}}
            <div class="lg:col-span-1 space-y-4">

                {{-- Main Card --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 text-center">
                    {{-- Avatar --}}
                    <div class="w-24 h-24 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-4xl font-bold text-white mx-auto mb-4 shadow-lg">
                        {{ strtoupper(substr($professional->name, 0, 1)) }}
                    </div>

                    {{-- Name & Badge --}}
                    <div class="flex items-center justify-center space-x-2 mb-1">
                        <h1 class="text-xl font-bold text-gray-800">{{ $professional->name }}</h1>
                        @if($professional->is_verified)
                        <svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        @endif
                    </div>

                    <p class="text-blue-600 font-medium">{{ $professional->category->name }}</p>

                    <div class="mt-2">
                        <span class="inline-flex items-center space-x-1.5 text-sm px-3 py-1 rounded-full {{ $badge['class'] }}">
                            <span class="w-2 h-2 rounded-full {{ $badge['dot'] }}"></span>
                            <span>{{ $badge['label'] }}</span>
                        </span>
                    </div>

                    {{-- Rating --}}
                    <div class="mt-4 flex items-center justify-center space-x-2">
                        <div class="flex">
                            @for($i = 1; $i <= 5; $i++)
                            <svg class="w-5 h-5 {{ $i <= round($professional->average_rating) ? 'text-yellow-400' : 'text-gray-200' }}"
                                 fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            @endfor
                        </div>
                        <span class="text-gray-700 font-semibold">{{ number_format($professional->average_rating, 1) }}</span>
                        <span class="text-gray-400 text-sm">({{ $professional->total_reviews }} avis)</span>
                    </div>

                    {{-- Info --}}
                    <div class="mt-4 space-y-2 text-sm text-gray-600 text-left">
                        <div class="flex items-center space-x-2">
                            <span class="text-lg">📍</span>
                            <span>{{ $professional->city }}</span>
                        </div>
                        @if($professional->languages && count($professional->languages) > 0)
                        <div class="flex items-center space-x-2">
                            <span class="text-lg">🗣️</span>
                            <span>{{ implode(', ', $professional->languages) }}</span>
                        </div>
                        @endif
                        <div class="flex items-center space-x-2">
                            <span class="text-lg">👁️</span>
                            <span>{{ number_format($professional->total_views) }} vues</span>
                        </div>
                    </div>
                </div>

                {{-- Contact Buttons --}}
                @if($professional->availability !== 'closed')
                <div class="space-y-3">
                    <button
                        wire:click="trackWhatsApp"
                        class="w-full flex items-center justify-center space-x-3 bg-green-500 hover:bg-green-600 text-white py-3.5 rounded-xl font-semibold transition shadow-md hover:shadow-lg"
                    >
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                        <span>Contacter sur WhatsApp</span>
                    </button>

                    <a href="tel:{{ $professional->phone }}"
                       wire:click="trackCall"
                       class="w-full flex items-center justify-center space-x-3 bg-blue-600 hover:bg-blue-700 text-white py-3.5 rounded-xl font-semibold transition shadow-md hover:shadow-lg">
                        <span class="text-xl">📞</span>
                        <span>{{ $professional->phone }}</span>
                    </a>
                </div>
                @else
                <div class="bg-red-50 border border-red-200 rounded-xl p-4 text-center text-red-600">
                    <p class="font-medium">Ce professionnel est actuellement fermé</p>
                    <p class="text-sm mt-1">Revenez plus tard ou cherchez une autre personne</p>
                </div>
                @endif

                {{-- Travel Cities --}}
                @if($professional->travel_cities && count($professional->travel_cities) > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                    <h3 class="font-semibold text-gray-700 mb-3">🚗 Zones de déplacement</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($professional->travel_cities as $city)
                        <span class="text-xs bg-blue-50 text-blue-700 px-3 py-1 rounded-full">{{ $city }}</span>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Dashboard Link --}}
                <div class="text-center">
                    <a href="{{ route('dashboard', $professional->id) }}"
                       class="text-xs text-gray-400 hover:text-gray-600 transition underline">
                        Accéder au dashboard professionnel
                    </a>
                </div>
            </div>

            {{-- ===== RIGHT COLUMN: Details ===== --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Description --}}
                @if($professional->description)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-bold text-gray-800 mb-3">À propos</h2>
                    <p class="text-gray-600 leading-relaxed">{{ $professional->description }}</p>
                </div>
                @endif

                {{-- Skills --}}
                @if($professional->skills && count($professional->skills) > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-bold text-gray-800 mb-4">🛠️ Compétences & Services</h2>
                    <div class="flex flex-wrap gap-2">
                        @foreach($professional->skills as $skill)
                        <span class="bg-blue-50 text-blue-700 border border-blue-100 px-4 py-1.5 rounded-full text-sm font-medium">
                            {{ $skill }}
                        </span>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Stats --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-bold text-gray-800 mb-4">📊 Statistiques</h2>
                    <div class="grid grid-cols-3 gap-4">
                        <div class="text-center p-4 bg-gray-50 rounded-xl">
                            <div class="text-2xl font-bold text-gray-800">{{ number_format($professional->total_views) }}</div>
                            <div class="text-xs text-gray-500 mt-1">Vues du profil</div>
                        </div>
                        <div class="text-center p-4 bg-green-50 rounded-xl">
                            <div class="text-2xl font-bold text-green-700">{{ number_format($professional->total_whatsapp_clicks) }}</div>
                            <div class="text-xs text-gray-500 mt-1">Clics WhatsApp</div>
                        </div>
                        <div class="text-center p-4 bg-blue-50 rounded-xl">
                            <div class="text-2xl font-bold text-blue-700">{{ number_format($professional->total_calls) }}</div>
                            <div class="text-xs text-gray-500 mt-1">Appels</div>
                        </div>
                    </div>
                </div>

                {{-- Reviews --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex justify-between items-center mb-5">
                        <h2 class="text-lg font-bold text-gray-800">⭐ Avis clients ({{ $reviews->count() }})</h2>
                        <button
                            wire:click="$toggle('showReviewForm')"
                            class="text-sm bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition font-medium"
                        >
                            {{ $showReviewForm ? '✕ Annuler' : '+ Laisser un avis' }}
                        </button>
                    </div>

                    {{-- Success Message --}}
                    @if($reviewSubmitted)
                    <div class="mb-4 bg-green-50 border border-green-200 rounded-lg p-4 flex items-center space-x-2 text-green-700">
                        <span>✅</span>
                        <span class="text-sm font-medium">Merci! Votre avis a été publié avec succès.</span>
                    </div>
                    @endif

                    {{-- Review Form --}}
                    @if($showReviewForm)
                    <div class="mb-6 p-5 bg-gray-50 rounded-xl border border-gray-200">
                        <h3 class="font-semibold text-gray-700 mb-4">Écrire un avis</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Votre nom *</label>
                                <input wire:model="reviewer_name" type="text" placeholder="Ex: Mohammed A."
                                       class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('reviewer_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Note *</label>
                                <div class="flex space-x-2">
                                    @for($i = 1; $i <= 5; $i++)
                                    <button
                                        wire:click="$set('rating', {{ $i }})"
                                        type="button"
                                        class="text-3xl transition hover:scale-110 {{ $rating >= $i ? 'text-yellow-400' : 'text-gray-200' }}"
                                    >★</button>
                                    @endfor
                                    <span class="ml-2 text-sm text-gray-500 self-center">{{ $rating }}/5</span>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Commentaire</label>
                                <textarea wire:model="comment" rows="3"
                                          placeholder="Partagez votre expérience..."
                                          class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
                                @error('comment') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <button
                                wire:click="submitReview"
                                wire:loading.attr="disabled"
                                class="bg-blue-600 text-white px-6 py-2.5 rounded-lg hover:bg-blue-700 transition font-medium text-sm disabled:opacity-50"
                            >
                                <span wire:loading.remove wire:target="submitReview">Publier l'avis</span>
                                <span wire:loading wire:target="submitReview">Publication...</span>
                            </button>
                        </div>
                    </div>
                    @endif

                    {{-- Reviews List --}}
                    @forelse($reviews as $review)
                    <div class="py-4 border-b border-gray-100 last:border-0">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-semibold text-gray-800 text-sm">{{ $review->reviewer_name }}</p>
                                <div class="flex mt-1">
                                    @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-200' }}"
                                         fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    @endfor
                                </div>
                            </div>
                            <span class="text-xs text-gray-400">{{ $review->created_at->diffForHumans() }}</span>
                        </div>
                        @if($review->comment)
                        <p class="text-sm text-gray-600 mt-2">{{ $review->comment }}</p>
                        @endif
                    </div>
                    @empty
                    <div class="text-center py-10 text-gray-400">
                        <div class="text-5xl mb-3">⭐</div>
                        <p class="text-sm">Aucun avis pour le moment.</p>
                        <p class="text-xs mt-1">Soyez le premier à laisser un avis!</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
