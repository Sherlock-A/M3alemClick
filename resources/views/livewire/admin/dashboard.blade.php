<div>
    {{-- KPI Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <p class="text-xs text-gray-500 font-medium">Professionnels actifs</p>
            <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['active_pros'] }}</p>
            <p class="text-xs text-gray-400 mt-1">/ {{ $stats['total_professionals'] }} total</p>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-yellow-100">
            <p class="text-xs text-yellow-600 font-medium">En attente validation</p>
            <p class="text-3xl font-bold text-yellow-600 mt-1">{{ $stats['pending'] }}</p>
            <a href="{{ route('admin.approvals') }}" class="text-xs text-yellow-500 hover:underline mt-1 block">Voir →</a>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-blue-100">
            <p class="text-xs text-blue-600 font-medium">Vues totales</p>
            <p class="text-3xl font-bold text-blue-600 mt-1">{{ number_format($stats['total_views']) }}</p>
            <p class="text-xs text-gray-400 mt-1">Taux conv. {{ $stats['conversion_rate'] }}%</p>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-green-100">
            <p class="text-xs text-green-600 font-medium">Contacts WhatsApp</p>
            <p class="text-3xl font-bold text-green-600 mt-1">{{ number_format($stats['total_whatsapp']) }}</p>
            <p class="text-xs text-gray-400 mt-1">+ {{ $stats['total_calls'] }} appels</p>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <p class="text-xs text-gray-500 font-medium">Clients inscrits</p>
            <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['total_clients'] }}</p>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-orange-100">
            <p class="text-xs text-orange-600 font-medium">Avis en attente</p>
            <p class="text-3xl font-bold text-orange-600 mt-1">{{ $stats['pending_reviews'] }}</p>
            <a href="{{ route('admin.reviews') }}" class="text-xs text-orange-500 hover:underline mt-1 block">Modérer →</a>
        </div>
    </div>

    {{-- Chart + Period selector --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-gray-800">Activité — Vues & Contacts</h3>
            <div class="flex rounded-lg border border-gray-200 overflow-hidden text-sm">
                <button wire:click="$set('period','week')"
                        class="px-3 py-1.5 transition {{ $period === 'week' ? 'bg-blue-600 text-white' : 'bg-white text-gray-500 hover:bg-gray-50' }}">
                    7 jours
                </button>
                <button wire:click="$set('period','month')"
                        class="px-3 py-1.5 transition {{ $period === 'month' ? 'bg-blue-600 text-white' : 'bg-white text-gray-500 hover:bg-gray-50' }}">
                    30 jours
                </button>
            </div>
        </div>
        <div wire:ignore>
            <canvas id="activityChart" height="80"></canvas>
        </div>
    </div>

    {{-- Top pros + Category stats --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Top 5 professionnels --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-semibold text-gray-800 mb-4">Top 5 Professionnels (WhatsApp)</h3>
            <div class="space-y-3">
                @forelse($topPros as $i => $pro)
                <div class="flex items-center space-x-3">
                    <span class="w-6 text-sm font-bold text-gray-400">{{ $i + 1 }}</span>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-800 truncate">{{ $pro->name }}</p>
                        <p class="text-xs text-gray-400">{{ $pro->category->name ?? '—' }} · {{ $pro->city }}</p>
                    </div>
                    <span class="text-sm font-bold text-green-600">{{ $pro->total_whatsapp_clicks }}</span>
                </div>
                @empty
                <p class="text-gray-400 text-sm">Aucune donnée.</p>
                @endforelse
            </div>
        </div>

        {{-- Catégories --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-semibold text-gray-800 mb-4">Professionnels par catégorie</h3>
            <div class="space-y-2">
                @foreach($categoryStats as $cat)
                @php $max = $categoryStats->max('professionals_count') ?: 1; @endphp
                <div class="flex items-center space-x-3">
                    <span class="text-base">{{ $cat->icon }}</span>
                    <div class="flex-1">
                        <div class="flex justify-between text-xs text-gray-600 mb-1">
                            <span>{{ $cat->name }}</span>
                            <span class="font-semibold">{{ $cat->professionals_count }}</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-1.5">
                            <div class="bg-blue-500 h-1.5 rounded-full" style="width: {{ $max > 0 ? ($cat->professionals_count / $max * 100) : 0 }}%"></div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('livewire:navigated', initChart);
document.addEventListener('DOMContentLoaded', initChart);

function initChart() {
    const ctx = document.getElementById('activityChart');
    if (!ctx) return;

    if (window._activityChart) window._activityChart.destroy();

    const data = @json($chartData);

    window._activityChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.map(d => d.date),
            datasets: [
                { label: 'Vues',     data: data.map(d => d.views),    borderColor: '#3b82f6', backgroundColor: 'rgba(59,130,246,.1)', tension: 0.4, fill: true },
                { label: 'WhatsApp', data: data.map(d => d.whatsapp), borderColor: '#22c55e', backgroundColor: 'transparent', tension: 0.4 },
                { label: 'Appels',   data: data.map(d => d.calls),    borderColor: '#f97316', backgroundColor: 'transparent', tension: 0.4 },
            ]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: true, position: 'top' } },
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
        }
    });
}

document.addEventListener('livewire:updated', () => {
    setTimeout(initChart, 50);
});
</script>
@endpush
