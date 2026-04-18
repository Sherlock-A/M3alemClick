<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\Professional;
use App\Models\Review;
use App\Models\Tracking;
use App\Models\User;
use Livewire\Component;

class Dashboard extends Component
{
    public string $period = 'week';

    public function render()
    {
        $stats = [
            'total_professionals' => Professional::count(),
            'pending'             => User::where('role', 'professional')->where('status', 'en_attente')->count(),
            'active_pros'         => User::where('role', 'professional')->where('status', 'actif')->count(),
            'total_clients'       => User::where('role', 'client')->count(),
            'total_views'         => Tracking::where('action', 'view')->count(),
            'total_whatsapp'      => Tracking::where('action', 'whatsapp')->count(),
            'total_calls'         => Tracking::where('action', 'call')->count(),
            'total_reviews'       => Review::count(),
            'pending_reviews'     => Review::where('status', 'pending')->count(),
        ];

        $stats['conversion_rate'] = $stats['total_views'] > 0
            ? round(($stats['total_whatsapp'] + $stats['total_calls']) / $stats['total_views'] * 100, 1)
            : 0;

        $days = $this->period === 'month' ? 30 : 7;
        $chartData = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $chartData[] = [
                'date'     => now()->subDays($i)->format('d/m'),
                'views'    => Tracking::where('action', 'view')->whereDate('created_at', $date)->count(),
                'whatsapp' => Tracking::where('action', 'whatsapp')->whereDate('created_at', $date)->count(),
                'calls'    => Tracking::where('action', 'call')->whereDate('created_at', $date)->count(),
            ];
        }

        $topPros = Professional::with('category')
            ->orderByDesc('total_whatsapp_clicks')
            ->limit(5)
            ->get();

        $categoryStats = Category::withCount('professionals')->orderByDesc('professionals_count')->get();

        return view('livewire.admin.dashboard', compact('stats', 'chartData', 'topPros', 'categoryStats'))
            ->layout('layouts.admin', ['title' => 'Dashboard', 'active' => 'dashboard']);
    }
}
