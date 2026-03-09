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

        $recentNews = Post::orderByDesc('created_at')->limit(5)->get();
        $recentMessages = ContactMessage::orderByDesc('created_at')->limit(5)->get();

        return view('admin.dashboard', compact('stats', 'recentNews', 'recentMessages'));
    }
}
