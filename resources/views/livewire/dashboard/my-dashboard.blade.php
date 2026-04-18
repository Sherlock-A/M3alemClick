<div class="min-h-screen bg-gray-50">

    {{-- ===== HEADER ===== --}}
    <div class="bg-gradient-to-r from-blue-700 via-blue-600 to-indigo-600 text-white">
        <div class="max-w-5xl mx-auto px-4 py-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    {{-- Avatar --}}
                    <div class="w-14 h-14 rounded-2xl bg-white/20 flex items-center justify-center text-2xl font-bold shadow-lg">
                        {{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}
                    </div>
                    <div>
                        <h1 class="text-xl font-bold">{{ $user->name }}</h1>
                        <div class="flex items-center space-x-2 mt-1">
                            @php $badge = $user->getStatusBadge(); @endphp
                            <span class="text-xs px-2.5 py-1 rounded-full font-medium
                                {{ $user->isActive()    ? 'bg-green-400/30 text-green-100' :
                                   ($user->isPending()  ? 'bg-yellow-400/30 text-yellow-100' :
                                   ($user->isRefused()  ? 'bg-red-400/30 text-red-100' :
                                                          'bg-gray-400/30 text-gray-100')) }}">
                                {{ $user->isActive() ? '✓ Profil actif' :
                                   ($user->isPending() ? '⏳ En attente de validation' :
                                   ($user->isRefused() ? '✗ Profil refusé' : '🚫 Compte suspendu')) }}
                            </span>
                        </div>
                    </div>
                </div>
                @if($user->isActive() && $professional)
                <a href="{{ route('professionals.show', $professional->slug) }}" target="_blank"
                   class="hidden md:flex items-center space-x-2 bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-xl transition text-sm font-medium">
                    <span>🌐</span><span>Voir mon profil public</span>
                </a>
                @endif
            </div>

            {{-- Progress steps --}}
            @if(!$user->isActive())
            <div class="mt-6 flex items-center space-x-2">
                @php
                    $steps = [
                        ['label' => 'Inscription', 'done' => true],
                        ['label' => 'Profil complété', 'done' => $hasProfile],
                        ['label' => 'Validation admin', 'done' => $user->isActive()],
                        ['label' => 'Profil visible', 'done' => $user->isActive()],
                    ];
                @endphp
                @foreach($steps as $i => $step)
                <div class="flex items-center {{ $i < count($steps)-1 ? 'flex-1' : '' }}">
                    <div class="flex items-center space-x-1.5">
                        <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold
                             {{ $step['done'] ? 'bg-green-400 text-white' : 'bg-white/20 text-white/60' }}">
                            {{ $step['done'] ? '✓' : $i+1 }}
                        </div>
                        <span class="text-xs {{ $step['done'] ? 'text-white' : 'text-white/60' }} hidden sm:inline">{{ $step['label'] }}</span>
                    </div>
                    @if($i < count($steps)-1)
                    <div class="flex-1 h-px {{ $step['done'] ? 'bg-green-400/50' : 'bg-white/20' }} mx-2"></div>
                    @endif
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 py-8 space-y-6">

        {{-- Flash --}}
        @if($saved)
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show=false, 3500)"
             class="bg-green-50 border border-green-200 rounded-xl px-4 py-3 flex items-center space-x-3 text-green-700 text-sm animate-slide-down">
            <span>✅</span><span class="font-medium">Modifications enregistrées avec succès.</span>
        </div>
        @endif

        {{-- ===== BANNERS SELON STATUT ===== --}}

        @if($user->isSuspended())
        {{-- SUSPENDU --}}
        <div class="bg-gray-50 border-2 border-gray-300 rounded-2xl p-6 flex items-start space-x-4">
            <span class="text-3xl">🚫</span>
            <div>
                <h3 class="font-bold text-gray-700 text-lg">Compte suspendu</h3>
                <p class="text-gray-500 text-sm mt-1">Contactez l'administration : <a href="mailto:contact@m3allemclick.ma" class="text-blue-600 hover:underline">contact@m3allemclick.ma</a></p>
            </div>
        </div>

        @elseif($user->isRefused())
        {{-- REFUSÉ --}}
        <div class="bg-red-50 border-2 border-red-300 rounded-2xl p-6">
            <div class="flex items-start space-x-4">
                <span class="text-3xl mt-1">❌</span>
                <div class="flex-1">
                    <h3 class="font-bold text-red-800 text-lg">Votre profil a été refusé</h3>
                    @if($user->rejection_reason)
                    <div class="mt-3 bg-white border border-red-200 rounded-xl p-4">
                        <p class="text-xs text-red-500 font-semibold uppercase tracking-wide mb-1">Motif du refus</p>
                        <p class="text-red-700 text-sm leading-relaxed">{{ $user->rejection_reason }}</p>
                    </div>
                    @endif
                    <p class="text-red-600 text-sm mt-3">Corrigez les informations ci-dessous et soumettez à nouveau votre profil.</p>
                    <button wire:click="resubmit"
                            class="mt-4 bg-blue-600 hover:bg-blue-700 text-white text-sm px-6 py-2.5 rounded-xl transition font-medium inline-flex items-center space-x-2">
                        <span>📤</span><span>Soumettre à nouveau</span>
                    </button>
                </div>
            </div>
        </div>

        @elseif($user->isPending())
        {{-- EN ATTENTE --}}
        <div class="bg-yellow-50 border-2 border-yellow-300 rounded-2xl p-6">
            <div class="flex items-start space-x-4">
                <div class="text-3xl animate-pulse-soft mt-1">⏳</div>
                <div class="flex-1">
                    <h3 class="font-bold text-yellow-800 text-lg">Votre profil est en cours d'examen</h3>
                    <p class="text-yellow-700 text-sm mt-2 leading-relaxed">
                        Notre équipe examine votre demande. Vous recevrez un email de confirmation dès qu'une décision sera prise.
                        <strong>En attendant, complétez votre profil</strong> pour maximiser vos chances d'approbation.
                    </p>
                    <div class="mt-4 grid grid-cols-3 gap-3 text-center">
                        @foreach([['icon'=>'📝','label'=>'Inscription','done'=>true],['icon'=>'⏳','label'=>'Validation','done'=>false],['icon'=>'🌐','label'=>'Visible','done'=>false]] as $s)
                        <div class="bg-white rounded-xl p-3 border {{ $s['done'] ? 'border-green-200' : 'border-yellow-200' }}">
                            <span class="text-xl">{{ $s['icon'] }}</span>
                            <p class="text-xs font-medium mt-1 {{ $s['done'] ? 'text-green-700' : 'text-yellow-700' }}">{{ $s['label'] }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        @else
        {{-- ACTIF : stats --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach([
                ['val' => $stats['views_total'],    'today' => $stats['views_today'],    'label' => 'Vues totales',    'icon' => '👁',  'color' => 'blue'],
                ['val' => $stats['whatsapp_total'], 'today' => $stats['whatsapp_today'], 'label' => 'WhatsApp',        'icon' => '💬', 'color' => 'green'],
                ['val' => $stats['calls_total'],    'today' => $stats['calls_today'],    'label' => 'Appels',          'icon' => '📞', 'color' => 'orange'],
                ['val' => number_format($stats['rating'],1), 'today' => $stats['total_reviews'], 'label' => 'Note moyenne', 'icon' => '⭐', 'color' => 'yellow'],
            ] as $s)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 animate-fade-in">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xl">{{ $s['icon'] }}</span>
                    <span class="text-xs text-gray-400">+{{ $s['today'] }} auj.</span>
                </div>
                <div class="text-2xl font-bold text-gray-800">{{ $s['val'] }}</div>
                <div class="text-xs text-gray-500 mt-1">{{ $s['label'] }}</div>
            </div>
            @endforeach
        </div>
        @endif

        {{-- ===== NO PROFILE STATE ===== --}}
        @if(!$hasProfile)
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-1">Créer votre profil artisan</h2>
            <p class="text-gray-500 text-sm mb-5">Complétez les informations pour que votre profil soit examiné par notre équipe.</p>
            @include('livewire.dashboard._profile-form', ['creating' => true])
        </div>

        @else

        {{-- ===== AVAILABILITY (actif seulement) ===== --}}
        @if($user->isActive())
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h2 class="font-bold text-gray-800 mb-4">🟢 Statut de disponibilité</h2>
            <div class="flex flex-wrap gap-3">
                @foreach([
                    ['val'=>'available','label'=>'✅ Disponible','on'=>'bg-green-500 border-green-500 text-white shadow-md','off'=>'border-gray-200 text-gray-600 hover:border-green-300'],
                    ['val'=>'busy',     'label'=>'⏳ Occupé',    'on'=>'bg-yellow-500 border-yellow-500 text-white shadow-md','off'=>'border-gray-200 text-gray-600 hover:border-yellow-300'],
                    ['val'=>'closed',   'label'=>'❌ Fermé',     'on'=>'bg-red-500 border-red-500 text-white shadow-md',    'off'=>'border-gray-200 text-gray-600 hover:border-red-300'],
                ] as $btn)
                <button wire:click="setAvailability('{{ $btn['val'] }}')"
                        class="px-5 py-2.5 rounded-xl border-2 font-semibold transition text-sm
                               {{ $availability === $btn['val'] ? $btn['on'] : 'bg-white '.$btn['off'] }}">
                    {{ $btn['label'] }}
                </button>
                @endforeach
            </div>
            <p class="text-xs text-gray-400 mt-3">Changement immédiat et visible sur votre profil public.</p>
        </div>
        @endif

        {{-- ===== PROFILE EDITOR ===== --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-center justify-between mb-5">
                <h2 class="font-bold text-gray-800 text-lg">✏️ Mon Profil</h2>
                @if(!$editMode)
                <button wire:click="$set('editMode',true)" class="btn-primary text-xs">Modifier</button>
                @else
                <div class="flex space-x-2">
                    <button wire:click="cancelEdit" class="btn-secondary text-xs">Annuler</button>
                    <button wire:click="saveProfile" wire:loading.attr="disabled"
                            class="btn-primary text-xs disabled:opacity-50">
                        <span wire:loading.remove>💾 Enregistrer</span>
                        <span wire:loading>Sauvegarde...</span>
                    </button>
                </div>
                @endif
            </div>
            @include('livewire.dashboard._profile-form', ['creating' => false])
        </div>

        {{-- ===== SKILLS ===== --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h2 class="font-bold text-gray-800 mb-1">🛠 Compétences</h2>
            <p class="text-gray-400 text-xs mb-4">Ajoutez vos spécialités pour apparaître dans plus de recherches.</p>

            {{-- Suggestions rapides --}}
            @php
                $suggestions = ['Pose carrelage','Peinture intérieure','Plomberie sanitaire','Électricité basse tension',
                                'Menuiserie aluminium','Climatisation','Soudure','Maçonnerie','Plâtrerie','Rénovation'];
            @endphp
            <div class="flex flex-wrap gap-1.5 mb-4">
                @foreach($suggestions as $sug)
                    @if(!in_array($sug, $skills))
                    <button wire:click="$set('newSkill','{{ $sug }}')"
                            class="text-xs bg-blue-50 text-blue-600 hover:bg-blue-100 px-2.5 py-1 rounded-full transition border border-blue-100">
                        + {{ $sug }}
                    </button>
                    @endif
                @endforeach
            </div>

            <div class="flex flex-wrap gap-2 mb-4">
                @forelse($skills as $i => $skill)
                <span class="inline-flex items-center space-x-1.5 bg-blue-50 text-blue-700 border border-blue-200 px-3 py-1.5 rounded-full text-sm font-medium">
                    <span>{{ $skill }}</span>
                    <button wire:click="removeSkill({{ $i }})" class="hover:text-red-500 transition">✕</button>
                </span>
                @empty
                <p class="text-gray-400 text-sm italic">Aucune compétence — ajoutez-en ci-dessous.</p>
                @endforelse
            </div>
            <div class="flex space-x-2">
                <input wire:model="newSkill" wire:keydown.enter="addSkill" type="text"
                       placeholder="Ex: Pose de faïence..." class="input-field flex-1">
                <button wire:click="addSkill" class="btn-primary px-5">Ajouter</button>
            </div>
        </div>

        {{-- ===== LANGUES ===== --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h2 class="font-bold text-gray-800 mb-4">🗣 Langues parlées</h2>
            @php $langSuggestions = ['Darija','Français','Arabe classique','Tamazight','Anglais','Espagnol']; @endphp
            <div class="flex flex-wrap gap-1.5 mb-4">
                @foreach($langSuggestions as $ls)
                    @if(!in_array($ls, $languages))
                    <button wire:click="$set('newLanguage','{{ $ls }}')"
                            class="text-xs bg-purple-50 text-purple-600 hover:bg-purple-100 px-2.5 py-1 rounded-full transition border border-purple-100">
                        + {{ $ls }}
                    </button>
                    @endif
                @endforeach
            </div>
            <div class="flex flex-wrap gap-2 mb-4">
                @forelse($languages as $i => $lang)
                <span class="inline-flex items-center space-x-1.5 bg-purple-50 text-purple-700 border border-purple-200 px-3 py-1.5 rounded-full text-sm font-medium">
                    <span>{{ $lang }}</span>
                    <button wire:click="removeLanguage({{ $i }})" class="hover:text-red-500 transition">✕</button>
                </span>
                @empty
                <p class="text-gray-400 text-sm italic">Aucune langue ajoutée.</p>
                @endforelse
            </div>
            <div class="flex space-x-2">
                <input wire:model="newLanguage" wire:keydown.enter="addLanguage" type="text"
                       placeholder="Ex: Darija, Français..." class="input-field flex-1">
                <button wire:click="addLanguage" class="bg-purple-600 hover:bg-purple-700 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition">Ajouter</button>
            </div>
        </div>

        {{-- ===== VILLES DE DÉPLACEMENT ===== --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h2 class="font-bold text-gray-800 mb-1">📍 Zones de déplacement</h2>
            <p class="text-gray-400 text-xs mb-4">Indiquez les villes où vous intervenez pour être trouvé plus facilement.</p>
            @php $citySuggestions = ['Casablanca','Rabat','Marrakech','Fès','Tanger','Agadir','Oujda','Meknès','Salé','Kenitra']; @endphp
            <div class="flex flex-wrap gap-1.5 mb-4">
                @foreach($citySuggestions as $cs)
                    @if(!in_array($cs, $travel_cities))
                    <button wire:click="$set('newTravelCity','{{ $cs }}')"
                            class="text-xs bg-green-50 text-green-600 hover:bg-green-100 px-2.5 py-1 rounded-full transition border border-green-100">
                        + {{ $cs }}
                    </button>
                    @endif
                @endforeach
            </div>
            <div class="flex flex-wrap gap-2 mb-4">
                @forelse($travel_cities as $i => $tCity)
                <span class="inline-flex items-center space-x-1.5 bg-green-50 text-green-700 border border-green-200 px-3 py-1.5 rounded-full text-sm font-medium">
                    <span>📍 {{ $tCity }}</span>
                    <button wire:click="removeTravelCity({{ $i }})" class="hover:text-red-500 transition">✕</button>
                </span>
                @empty
                <p class="text-gray-400 text-sm italic">Aucune ville de déplacement.</p>
                @endforelse
            </div>
            <div class="flex space-x-2">
                <input wire:model="newTravelCity" wire:keydown.enter="addTravelCity" type="text"
                       placeholder="Ex: Casablanca..." class="input-field flex-1">
                <button wire:click="addTravelCity" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition">Ajouter</button>
            </div>
        </div>

        @endif {{-- end hasProfile --}}

    </div>
</div>
