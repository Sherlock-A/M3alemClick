<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'M3allemClick' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-50 min-h-screen font-sans antialiased">

    {{-- Navigation --}}
    <nav class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                {{-- Logo --}}
                <a href="{{ route('home') }}" class="flex items-center space-x-2">
                    <span class="text-2xl">🔧</span>
                    <span class="text-xl font-bold">
                        <span class="text-blue-600">M3allem</span><span class="text-orange-500">Click</span>
                    </span>
                </a>

                {{-- Desktop Nav --}}
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('home') }}"
                       class="text-gray-600 hover:text-blue-600 transition font-medium {{ request()->routeIs('home') ? 'text-blue-600' : '' }}">
                        Accueil
                    </a>
                    <a href="{{ route('professionals.index') }}"
                       class="text-gray-600 hover:text-blue-600 transition font-medium {{ request()->routeIs('professionals.*') ? 'text-blue-600' : '' }}">
                        Professionnels
                    </a>
                </div>

                {{-- Auth / CTA --}}
                <div class="flex items-center space-x-3">
                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}"
                               class="text-sm text-gray-600 hover:text-blue-600 transition font-medium">
                                🛠 Admin
                            </a>
                        @elseif(auth()->user()->isProfessional())
                            <a href="{{ route('pro.dashboard') }}"
                               class="text-sm text-gray-600 hover:text-blue-600 transition font-medium">
                                📊 Mon dashboard
                            </a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button class="text-sm text-gray-500 hover:text-red-500 transition">
                                Déconnexion
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}"
                           class="text-sm text-gray-600 hover:text-blue-600 transition font-medium">
                            Connexion
                        </a>
                        <a href="{{ route('professionals.index') }}"
                           class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition text-sm font-medium shadow-sm">
                            🔍 Trouver un artisan
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- Main Content --}}
    <main>
        {{ $slot }}
    </main>

    {{-- Footer --}}
    <footer class="bg-gray-800 text-gray-300 mt-16 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        <span class="text-2xl">🔧</span>
                        <span class="text-xl font-bold text-white">
                            M3allem<span class="text-orange-400">Click</span>
                        </span>
                    </div>
                    <p class="text-sm leading-relaxed">
                        La plateforme de référence pour trouver les meilleurs artisans et professionnels au Maroc.
                    </p>
                </div>
                <div>
                    <h3 class="text-white font-semibold mb-4">Liens rapides</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('home') }}" class="hover:text-white transition">Accueil</a></li>
                        <li><a href="{{ route('professionals.index') }}" class="hover:text-white transition">Tous les professionnels</a></li>
                        <li><a href="{{ route('professionals.index', ['category' => 'plomberie']) }}" class="hover:text-white transition">Plombiers</a></li>
                        <li><a href="{{ route('professionals.index', ['category' => 'electricite']) }}" class="hover:text-white transition">Électriciens</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-white font-semibold mb-4">Contact</h3>
                    <div class="space-y-2 text-sm">
                        <p>📧 contact@m3allemclick.ma</p>
                        <p>📱 +212 6XX XXX XXX</p>
                        <p>🌍 Maroc</p>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-sm text-gray-500">
                <p>&copy; {{ date('Y') }} M3allemClick. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
