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
<body class="bg-gradient-to-br from-blue-700 via-blue-600 to-indigo-700 min-h-screen flex items-center justify-center font-sans antialiased">

    <div class="w-full max-w-md mx-auto px-4 py-10">
        {{-- Logo --}}
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-flex items-center space-x-2">
                <span class="text-3xl">🔧</span>
                <span class="text-2xl font-bold text-white">
                    M3allem<span class="text-orange-400">Click</span>
                </span>
            </a>
            <p class="text-blue-200 text-sm mt-2">La plateforme des artisans au Maroc</p>
        </div>

        {{-- Card --}}
        <div class="bg-white rounded-2xl shadow-2xl p-8">
            {{ $slot }}
        </div>

        {{-- Footer link --}}
        <p class="text-center text-blue-200 text-sm mt-6">
            &copy; {{ date('Y') }} M3allemClick — Maroc
        </p>
    </div>

    @livewireScripts
</body>
</html>
