<div>
    {{-- Status tabs --}}
    <div class="flex space-x-2 mb-5">
        @foreach([
            ['key' => 'pending',  'label' => '⏳ En attente'],
            ['key' => 'approved', 'label' => '✅ Approuvés'],
            ['key' => 'rejected', 'label' => '❌ Rejetés'],
            ['key' => 'all',      'label' => '📋 Tous'],
        ] as $tab)
        <button wire:click="$set('filterStatus','{{ $tab['key'] }}')"
                class="px-4 py-2 rounded-lg text-sm font-medium border transition
                       {{ $filterStatus === $tab['key'] ? 'bg-blue-600 border-blue-600 text-white' : 'bg-white border-gray-200 text-gray-600 hover:border-gray-300' }}">
            {{ $tab['label'] }}
            <span class="ml-1 text-xs opacity-70">{{ $counts[$tab['key']] ?? '' }}</span>
        </button>
        @endforeach
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="text-left px-5 py-3 font-semibold text-gray-600">Avis</th>
                    <th class="text-left px-5 py-3 font-semibold text-gray-600">Professionnel</th>
                    <th class="text-left px-5 py-3 font-semibold text-gray-600">Note</th>
                    <th class="text-left px-5 py-3 font-semibold text-gray-600">Date</th>
                    <th class="text-right px-5 py-3 font-semibold text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($reviews as $review)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-5 py-4 max-w-xs">
                        <div class="font-medium text-gray-800">{{ $review->reviewer_name }}</div>
                        <div class="text-gray-500 text-xs line-clamp-2 mt-1">{{ $review->comment }}</div>
                    </td>
                    <td class="px-5 py-4 text-gray-600">{{ $review->professional?->name ?? '—' }}</td>
                    <td class="px-5 py-4">
                        <div class="flex text-yellow-400 text-sm">
                            @for($i=1; $i<=5; $i++)
                                {{ $i <= $review->rating ? '★' : '☆' }}
                            @endfor
                        </div>
                    </td>
                    <td class="px-5 py-4 text-gray-400 text-xs">{{ $review->created_at->format('d/m/Y') }}</td>
                    <td class="px-5 py-4">
                        <div class="flex items-center justify-end space-x-2">
                            @if($review->status !== 'approved')
                            <button wire:click="approve({{ $review->id }})"
                                    class="bg-green-50 hover:bg-green-100 text-green-700 text-xs px-3 py-1.5 rounded-lg transition">
                                ✓ Approuver
                            </button>
                            @endif
                            @if($review->status !== 'rejected')
                            <button wire:click="reject({{ $review->id }})"
                                    class="bg-yellow-50 hover:bg-yellow-100 text-yellow-700 text-xs px-3 py-1.5 rounded-lg transition">
                                Rejeter
                            </button>
                            @endif
                            <button wire:click="delete({{ $review->id }})"
                                    wire:confirm="Supprimer cet avis ?"
                                    class="bg-red-50 hover:bg-red-100 text-red-600 text-xs px-3 py-1.5 rounded-lg transition">
                                Supprimer
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-5 py-10 text-center text-gray-400">Aucun avis.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @if($reviews->hasPages())
        <div class="px-5 py-4 border-t border-gray-100">{{ $reviews->links() }}</div>
        @endif
    </div>
</div>
