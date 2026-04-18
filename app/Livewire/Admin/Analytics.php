<?php

namespace App\Livewire\Admin;

use App\Models\Professional;
use App\Models\Tracking;
use App\Models\User;
use Livewire\Component;

class Analytics extends Component
{
    public string $period    = '30';
    public string $action    = 'all';
    public string $city      = '';
    public int    $categoryId = 0;

    public function render()
    {
        $days  = (int) $this->period;
        $start = now()->subDays($days - 1)->startOfDay();

        $query = Tracking::whereBetween('created_at', [$start, now()]);

        if ($this->action !== 'all') {
            $query->where('action', $this->action);
        }

        // Chart data: activity per day
        $chartData = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dayQ = Tracking::whereDate('created_at', $date);
            if ($this->action !== 'all') $dayQ->where('action', $this->action);

            $chartData[] = [
                'date'     => now()->subDays($i)->format('d/m'),
                'views'    => Tracking::whereDate('created_at', $date)->where('action', 'view')->count(),
                'whatsapp' => Tracking::whereDate('created_at', $date)->where('action', 'whatsapp')->count(),
                'calls'    => Tracking::whereDate('created_at', $date)->where('action', 'call')->count(),
            ];
        }

        // Top professionals by period
        $topPros = Professional::with('category')
            ->withCount(['trackings as period_clicks' => function ($q) use ($start) {
                $q->whereBetween('created_at', [$start, now()])
                  ->whereIn('action', ['whatsapp', 'call']);
            }])
            ->orderByDesc('period_clicks')
            ->limit(10)
            ->get();

        // Top cities
        $topCities = Tracking::whereBetween('trackings.created_at', [$start, now()])
            ->join('professionals', 'trackings.professional_id', '=', 'professionals.id')
            ->selectRaw('professionals.city, COUNT(*) as total')
            ->groupBy('professionals.city')
            ->orderByDesc('total')
            ->limit(8)
            ->get();

        // Summary stats
        $summary = [
            'views'        => Tracking::whereBetween('created_at', [$start, now()])->where('action', 'view')->count(),
            'whatsapp'     => Tracking::whereBetween('created_at', [$start, now()])->where('action', 'whatsapp')->count(),
            'calls'        => Tracking::whereBetween('created_at', [$start, now()])->where('action', 'call')->count(),
            'new_users'    => User::whereBetween('created_at', [$start, now()])->count(),
            'new_pros'     => User::whereBetween('created_at', [$start, now()])->where('role', 'professional')->count(),
        ];
        $summary['total_contacts'] = $summary['whatsapp'] + $summary['calls'];
        $summary['conversion']     = $summary['views'] > 0
            ? round($summary['total_contacts'] / $summary['views'] * 100, 1) : 0;

        // Period comparison (previous period)
        $prevStart = now()->subDays($days * 2 - 1)->startOfDay();
        $prevEnd   = now()->subDays($days)->endOfDay();
        $prevViews = Tracking::whereBetween('created_at', [$prevStart, $prevEnd])->where('action', 'view')->count();
        $prevContacts = Tracking::whereBetween('created_at', [$prevStart, $prevEnd])
            ->whereIn('action', ['whatsapp', 'call'])->count();

        $comparison = [
            'views_diff'    => $prevViews > 0    ? round(($summary['views'] - $prevViews) / $prevViews * 100, 1)           : null,
            'contacts_diff' => $prevContacts > 0 ? round(($summary['total_contacts'] - $prevContacts) / $prevContacts * 100, 1) : null,
        ];

        return view('livewire.admin.analytics', compact(
            'chartData', 'topPros', 'topCities', 'summary', 'comparison'
        ))->layout('layouts.admin', ['title' => 'Analytics', 'active' => 'analytics']);
    }
}
