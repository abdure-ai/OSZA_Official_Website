<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Document;
use App\Models\EmergencyAlert;
use App\Models\User;
use App\Models\Vacancy;
use App\Models\Tender;

class StatsController extends Controller
{
    /**
     * Display a listing of dashboard statistics (admin).
     */
    public function index()
    {
        return response()->json([
            'news' => Post::count(),
            'documents' => Document::count(),
            'alerts' => EmergencyAlert::count(),
            'users' => User::count(),
            'vacancies' => Vacancy::count(),
            'tenders' => Tender::count(),
        ]);
    }
}
