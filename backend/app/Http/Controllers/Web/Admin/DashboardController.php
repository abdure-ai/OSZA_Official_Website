<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Woreda;
use App\Models\ContactMessage;
use App\Models\GalleryItem;
use App\Models\Tender;
use App\Models\Vacancy;
use App\Models\Document;
use App\Models\User;

use App\Models\VisitorLog;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'news' => Post::count(),
            'woredas' => Woreda::count(),
            'gallery' => GalleryItem::count(),
            'messages' => ContactMessage::count(),
            'tenders' => Tender::count(),
            'vacancies' => Vacancy::count(),
            'documents' => Document::count(),
            'users' => User::count(),
        ];

        // ── Visitor Analytics (Last 7 Days) ───────────────────────────────────
        $from = now()->subDays(7)->startOfDay();
        $to = now()->endOfDay();

        $dailyRaw = VisitorLog::inRange($from, $to)
            ->select(DB::raw('DATE(visited_at) as day'), DB::raw('COUNT(*) as total'))
            ->groupBy('day')
            ->orderBy('day')
            ->pluck('total', 'day');

        $dailyLabels = [];
        $dailyData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dailyLabels[] = now()->subDays($i)->format('M d');
            $dailyData[] = $dailyRaw[$date] ?? 0;
        }

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

        $recentNews = Post::orderByDesc('created_at')->limit(5)->get();
        $recentMessages = ContactMessage::orderByDesc('created_at')->limit(5)->get();

        return view('admin.dashboard', compact(
            'stats',
            'recentNews',
            'recentMessages',
            'dailyLabels',
            'dailyData',
            'deviceLabels',
            'deviceData'
        ));
    }
}
