<div>
    {{-- Filters --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6 flex flex-wrap gap-3 items-center">

        {{-- Period --}}
        <div class="flex rounded-lg border border-gray-200 overflow-hidden text-sm">
            @foreach(['7' => '7 j', '30' => '30 j', '90' => '90 j'] as $val => $label)
            <button wire:click="$set('period','{{ $val }}')"
                    class="px-3 py-2 transition {{ $period === $val ? 'bg-blue-600 text-white' : 'bg-white text-gray-500 hover:bg-gray-50' }}">
                {{ $label }}
            </button>
            @endforeach
        </div>

        {{-- Action type --}}
        <select wire:model.live="action" class="input-field w-44">
            <option value="all">Tous les types</option>
            <option value="view">Vues uniquement</option>
            <option value="whatsapp">WhatsApp</option>
            <option value="call">Appels</option>
        </select>

        <p class="text-xs text-gray-400 ml-auto">
            Période : {{ now()->subDays((int)$period - 1)->format('d/m/Y') }} → {{ now()->format('d/m/Y') }}
        </p>
    </div>

    {{-- Summary KPIs --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        @php
            $kpis = [
                ['label' => 'Vues',          'val' => $summary['views'],          'color' => 'blue',   'icon' => '👁'],
                ['label' => 'Contacts total', 'val' => $summary['total_contacts'], 'color' => 'green',  'icon' => '📲'],
                ['label' => 'WhatsApp',       'val' => $summary['whatsapp'],       'color' => 'green',  'icon' => '💬'],
                ['label' => 'Appels',         'val' => $summary['calls'],          'color' => 'orange', 'icon' => '📞'],
                ['label' => 'Conversion',     'val' => $summary['conversion'].'%', 'color' => 'purple', 'icon' => '📈'],
                ['label' => 'Nouveaux users', 'val' => $summary['new_users'],      'color' => 'gray',   'icon' => '👥'],
            ];
        @endphp
        @foreach($kpis as $kpi)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 animate-fade-in">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xl">{{ $kpi['icon'] }}</span>
                @if($kpi['label'] === 'Vues' && $comparison['views_diff'] !== null)
                    <span class="text-xs font-medium {{ $comparison['views_diff'] >= 0 ? 'text-green-600' : 'text-red-500' }}">
                        {{ $comparison['views_diff'] >= 0 ? '▲' : '▼' }} {{ abs($comparison['views_diff']) }}%
                    </span>
                @elseif($kpi['label'] === 'Contacts total' && $comparison['contacts_diff'] !== null)
                    <span class="text-xs font-medium {{ $comparison['contacts_diff'] >= 0 ? 'text-green-600' : 'text-red-500' }}">
                        {{ $comparison['contacts_diff'] >= 0 ? '▲' : '▼' }} {{ abs($comparison['contacts_diff']) }}%
                    </span>
                @endif
            </div>
            <p class="text-2xl font-bold text-gray-800">{{ number_format(is_numeric($kpi['val']) ? $kpi['val'] : rtrim($kpi['val'],'%')) }}{{ str_ends_with($kpi['val'],'%') ? '%' : '' }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ $kpi['label'] }}</p>
        </div>
        @endforeach
    </div>

    {{-- Activity chart --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <h3 class="font-semibold text-gray-800 mb-4">Activité quotidienne</h3>
        <div wire:ignore>
            <canvas id="analyticsChart" height="70"></canvas>
        </div>
    </div>

    {{-- Top pros + Top cities --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Top 10 pros --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-semibold text-gray-800 mb-4">Top 10 Professionnels (contacts)</h3>
            <div class="space-y-2">
                @php $maxClicks = $topPros->max('period_clicks') ?: 1; @endphp
                @forelse($topPros as $i => $pro)
                <div class="flex items-center space-x-3 py-1">
                    <span class="w-5 text-xs font-bold text-gray-400 text-right">{{ $i + 1 }}</span>
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between text-xs mb-1">
                            <span class="font-medium text-gray-700 truncate">{{ $pro->name }}</span>
                            <span class="font-bold text-blue-600 ml-2">{{ $pro->period_clicks }}</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-1.5">
                            <div class="bg-blue-500 h-1.5 rounded-full transition-all"
                                 style="width: {{ $maxClicks > 0 ? ($pro->period_clicks / $maxClicks * 100) : 0 }}%"></div>
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-gray-400 text-sm">Aucune donnée sur la période.</p>
                @endforelse
            </div>
        </div>

        {{-- Top cities --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-semibold text-gray-800 mb-4">Top villes actives</h3>
            <div class="space-y-2">
                @php $maxCity = $topCities->max('total') ?: 1; @endphp
                @forelse($topCities as $city)
                <div class="flex items-center space-x-3 py-1">
                    <span class="text-sm">📍</span>
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between text-xs mb-1">
                            <span class="font-medium text-gray-700">{{ $city->city ?: 'Non renseigné' }}</span>
                            <span class="font-bold text-gray-600">{{ $city->total }}</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-1.5">
                            <div class="bg-orange-400 h-1.5 rounded-full"
                                 style="width: {{ ($city->total / $maxCity) * 100 }}%"></div>
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-gray-400 text-sm">Aucune donnée.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', initAnalyticsChart);
document.addEventListener('livewire:updated', () => setTimeout(initAnalyticsChart, 50));

function initAnalyticsChart() {
    const ctx = document.getElementById('analyticsChart');
    if (!ctx) return;
    if (window._analyticsChart) window._analyticsChart.destroy();

    const data = @json($chartData);

    window._analyticsChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: data.map(d => d.date),
            datasets: [
                { label: 'Vues',     data: data.map(d => d.views),    backgroundColor: 'rgba(59,130,246,.7)',  borderRadius: 4 },
                { label: 'WhatsApp', data: data.map(d => d.whatsapp), backgroundColor: 'rgba(34,197,94,.7)',   borderRadius: 4 },
                { label: 'Appels',   data: data.map(d => d.calls),    backgroundColor: 'rgba(249,115,22,.7)',  borderRadius: 4 },
            ]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: true, position: 'top' } },
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } }, x: { grid: { display: false } } }
        }
    });
}
</script>
@endpush
