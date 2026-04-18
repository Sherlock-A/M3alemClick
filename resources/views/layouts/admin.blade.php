<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Admin' }} — M3allemClick</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
</head>
<body class="bg-gray-100 font-sans antialiased">

<div class="flex h-screen overflow-hidden">

    {{-- ===== SIDEBAR ===== --}}
    <aside class="w-64 bg-gray-900 text-white flex flex-col flex-shrink-0">
        {{-- Logo --}}
        <div class="px-6 py-5 border-b border-gray-700">
            <a href="{{ route('home') }}" class="flex items-center space-x-2">
                <span class="text-xl">🔧</span>
                <span class="font-bold text-lg">
                    <span class="text-blue-400">M3allem</span><span class="text-orange-400">Click</span>
                </span>
            </a>
            <p class="text-gray-500 text-xs mt-1">Panneau Admin</p>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">

            @php
                $navItems = [
                    ['route' => 'admin.dashboard',     'icon' => '📊', 'label' => 'Dashboard',         'key' => 'dashboard'],
                    ['route' => 'admin.approvals',     'icon' => '⏳', 'label' => 'Inscriptions',       'key' => 'approvals'],
                    ['route' => 'admin.professionals', 'icon' => '👷', 'label' => 'Professionnels',     'key' => 'professionals'],
                    ['route' => 'admin.categories',    'icon' => '🏷️', 'label' => 'Catégories',         'key' => 'categories'],
                    ['route' => 'admin.reviews',       'icon' => '⭐', 'label' => 'Avis',               'key' => 'reviews'],
                    ['route' => 'admin.analytics',     'icon' => '📈', 'label' => 'Analytics',          'key' => 'analytics'],
                    ['route' => 'admin.settings',      'icon' => '⚙️', 'label' => 'Paramètres',         'key' => 'settings'],
                ];
            @endphp

            @foreach($navItems as $item)
                <a href="{{ route($item['route']) }}"
                   class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-sm transition
                          {{ ($active ?? '') === $item['key']
                              ? 'bg-blue-600 text-white font-semibold'
                              : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                    <span class="text-base">{{ $item['icon'] }}</span>
                    <span>{{ $item['label'] }}</span>
                    @if($item['key'] === 'approvals')
                        @php $pending = \App\Models\User::where('role','professional')->where('status','en_attente')->count(); @endphp
                        @if($pending > 0)
                            <span class="ml-auto bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $pending }}</span>
                        @endif
                    @endif
                </a>
            @endforeach
        </nav>

        {{-- User info --}}
        <div class="px-4 py-4 border-t border-gray-700">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-sm font-bold">
                    {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-white truncate">{{ auth()->user()->name ?? 'Admin' }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email ?? '' }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="mt-3">
                @csrf
                <button class="w-full text-left text-xs text-gray-400 hover:text-red-400 transition px-2 py-1">
                    🚪 Déconnexion
                </button>
            </form>
        </div>
    </aside>

    {{-- ===== MAIN ===== --}}
    <div class="flex-1 flex flex-col overflow-hidden">

        {{-- Top bar --}}
        <header class="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between flex-shrink-0">
            <h1 class="text-lg font-semibold text-gray-800">{{ $title ?? 'Dashboard' }}</h1>
            <div class="flex items-center space-x-3">
                <a href="{{ route('home') }}" target="_blank"
                   class="text-sm text-blue-600 hover:underline">
                    🌐 Voir le site
                </a>
            </div>
        </header>

        {{-- Flash messages --}}
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                 class="mx-6 mt-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm flex items-center justify-between">
                <span>✅ {{ session('success') }}</span>
                <button @click="show = false" class="text-green-500 hover:text-green-700">✕</button>
            </div>
        @endif

        {{-- Content --}}
        <main class="flex-1 overflow-y-auto p-6">
            {{ $slot }}
        </main>
    </div>
</div>

@livewireScripts
@stack('scripts')
</body>
</html>
