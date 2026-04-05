<?php

namespace App\Livewire\Admin;

use App\Models\VisitorLog;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsManager extends Component
{
    use WithPagination;

    public string $range = '30'; // days
    public string $filterDevice = '';
    public string $filterBrowser = '';

    public function render()
    {
        $days = (int) $this->range;
        $from = now()->subDays($days)->startOfDay();
        $to = now()->endOfDay();

        // ── Summary KPIs ────────────────────────────────────────────────────────
        $kpis = [
            'today' => VisitorLog::daily()->count(),
            'week' => VisitorLog::weekly()->count(),
            'month' => VisitorLog::monthly()->count(),
            'year' => VisitorLog::annual()->count(),
        ];

        // ── Daily traffic for line chart (last N days) ──────────────────────────
        $dailyRaw = VisitorLog::inRange($from, $to)
            ->select(DB::raw('DATE(visited_at) as day'), DB::raw('COUNT(*) as total'))
            ->groupBy('day')
            ->orderBy('day')
            ->pluck('total', 'day');

        // Fill missing dates with 0
        $dailyLabels = [];
        $dailyData = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dailyLabels[] = now()->subDays($i)->format('M d');
            $dailyData[] = $dailyRaw[$date] ?? 0;
        }

        // ── Hourly for today ────────────────────────────────────────────────────
        $hourlyRaw = VisitorLog::daily()
            ->select(DB::raw('HOUR(visited_at) as hour'), DB::raw('COUNT(*) as total'))
            ->groupBy('hour')
            ->pluck('total', 'hour');

        $hourlyLabels = [];
        $hourlyData = [];
        for ($h = 0; $h < 24; $h++) {
            $hourlyLabels[] = str_pad($h, 2, '0', STR_PAD_LEFT) . ':00';
            $hourlyData[] = $hourlyRaw[$h] ?? 0;
        }

        // ── Device breakdown (donut) ────────────────────────────────────────────
        $deviceRaw = VisitorLog::inRange($from, $to)
            ->select('device', DB::raw('COUNT(*) as total'))
            ->groupBy('device')
            ->pluck('total', 'device');

        $deviceLabels = ['Desktop', 'Mobile', 'Tablet'];
        $deviceData = [
            $deviceRaw['desktop'] ?? 0,
            $deviceRaw['mobile'] ?? 0,
            $deviceRaw['tablet'] ?? 0,
        ];

        // ── Browser breakdown ───────────────────────────────────────────────────
        $browserRaw = VisitorLog::inRange($from, $to)
            ->select('browser', DB::raw('COUNT(*) as total'))
            ->groupBy('browser')
            ->orderByDesc('total')
            ->pluck('total', 'browser');

        // ── Top pages ───────────────────────────────────────────────────────────
        $topPages = VisitorLog::inRange($from, $to)
            ->select('page', DB::raw('COUNT(*) as total'))
            ->groupBy('page')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // ── Recent visitors table ────────────────────────────────────────────────
        $query = VisitorLog::inRange($from, $to)->orderByDesc('visited_at');
        if ($this->filterDevice)
            $query->where('device', $this->filterDevice);
        if ($this->filterBrowser)
            $query->where('browser', $this->filterBrowser);
        $recentVisitors = $query->paginate(20);

        return view('livewire.admin.analytics-manager', compact(
            'kpis',
            'dailyLabels',
            'dailyData',
            'hourlyLabels',
            'hourlyData',
            'deviceLabels',
            'deviceData',
            'browserRaw',
            'topPages',
            'recentVisitors'
        ))->layout('layouts.admin');
    }
}
