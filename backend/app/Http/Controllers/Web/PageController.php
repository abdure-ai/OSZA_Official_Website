<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\HeroSlide;
use App\Models\Woreda;
use App\Models\GalleryItem;
use App\Models\AdminMessage;
use App\Models\Document;
use App\Models\Leadership;
use App\Models\Tender;
use App\Models\Vacancy;
use App\Models\Investment;
use App\Models\Project;
use App\Models\DirectoryRecord;
use App\Models\ContactMessage;
use App\Models\TouristSite;
use App\Models\Album;
use App\Models\VisitorLog;
use Illuminate\Http\Request;

class PageController extends Controller
{
    // ── Home ──────────────────────────────────────────────────────────────────
    public function home()
    {
        $heroSlides = HeroSlide::where('is_active', 1)
            ->where(function ($q) {
                $q->where('page', 'home')->orWhereNull('page')->orWhere('page', '');
            })
            ->orderBy('sort_order')
            ->get();
        $latestNews = Post::where('status', 'published')->orderByDesc('published_at')->limit(3)->get();
        $woredas = Woreda::where('is_active', 1)->orderBy('name_en')->get();
        $galleryItems = GalleryItem::where('is_active', 1)->orderBy('sort_order')->limit(8)->get();
        $galleryAlbums = Album::where('is_active', 1)
            ->with(['items' => fn($q) => $q->where('is_active', 1)->orderBy('sort_order')])
            ->orderBy('sort_order')
            ->limit(6)
            ->get();
        $adminMessage = AdminMessage::orderByDesc('created_at')->first();
        $visitorStats = [
            'daily' => VisitorLog::daily()->count(),
            'weekly' => VisitorLog::weekly()->count(),
            'monthly' => VisitorLog::monthly()->count(),
            'annual' => VisitorLog::annual()->count(),
        ];

        return view('pages.home', compact('heroSlides', 'latestNews', 'woredas', 'galleryItems', 'galleryAlbums', 'adminMessage', 'visitorStats'));
    }

    // ── News ──────────────────────────────────────────────────────────────────
    public function news(Request $request)
    {
        $query = Post::where('status', 'published');
        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(fn($q2) => $q2->where('title_en', 'like', "%$q%")->orWhere('title_am', 'like', "%$q%")->orWhere('title_or', 'like', "%$q%"));
        }
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        $news = $query->orderByDesc('published_at')->paginate(9)->withQueryString();
        $categories = Post::where('status', 'published')->distinct()->pluck('category')->filter();
        return view('pages.news.index', compact('news', 'categories'));
    }

    public function newsShow(int $id)
    {
        $post = Post::where('status', 'published')->findOrFail($id);
        $related = Post::where('status', 'published')->where('category', $post->category)->where('id', '!=', $id)->limit(3)->get();
        return view('pages.news.show', compact('post', 'related'));
    }

    // ── Documents ─────────────────────────────────────────────────────────────
    public function documents(Request $request)
    {
        $query = Document::query();
        if ($request->filled('search')) {
            $query->where('title_en', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        $documents = $query->orderByDesc('created_at')->paginate(12)->withQueryString();
        $categories = Document::distinct()->pluck('category')->filter();
        return view('pages.documents', compact('documents', 'categories'));
    }

    public function documentRead(int $id)
    {
        $document = Document::findOrFail($id);
        return view('pages.documents.reader', compact('document'));
    }

    // ── Gallery ───────────────────────────────────────────────────────────────
    public function gallery(Request $request)
    {
        $categories = Album::where('is_active', 1)->distinct()->pluck('category')->filter();
        $activeCategory = $request->get('category');

        $albums = Album::where('is_active', 1)
            ->when($activeCategory, fn($q) => $q->where('category', $activeCategory))
            ->with(['items' => fn($q) => $q->where('is_active', 1)->orderBy('sort_order')])
            ->orderBy('sort_order')
            ->get();

        return view('pages.gallery', compact('categories', 'activeCategory', 'albums'));
    }

    // ── About ─────────────────────────────────────────────────────────────────
    public function about()
    {
        $leadership = Leadership::orderBy('rank_order')->get();
        return view('pages.about', compact('leadership'));
    }

    // ── Leadership ────────────────────────────────────────────────────────────
    public function leadership()
    {
        $leaders = Leadership::orderBy('rank_order')->get();
        return view('pages.leadership', compact('leaders'));
    }

    // ── Tenders ───────────────────────────────────────────────────────────────
    public function tenders(Request $request)
    {
        $query = Tender::query();
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        $tenders = $query->orderByDesc('created_at')->paginate(10)->withQueryString();
        return view('pages.tenders', compact('tenders'));
    }

    public function tenderShow(int $id)
    {
        $tender = Tender::findOrFail($id);
        return view('pages.tenders.show', compact('tender'));
    }

    // ── Vacancies ─────────────────────────────────────────────────────────────
    public function vacancies(Request $request)
    {
        $vacancies = Vacancy::where('is_active', 1)
            ->where(fn($q) => $q->whereNull('deadline')->orWhere('deadline', '>=', now()))
            ->orderByDesc('created_at')->paginate(10)->withQueryString();
        return view('pages.vacancies', compact('vacancies'));
    }

    public function vacancyShow(int $id)
    {
        $vacancy = Vacancy::where('is_active', 1)->findOrFail($id);
        return view('pages.vacancies.show', compact('vacancy'));
    }

    public function projects(\Illuminate\Http\Request $request)
    {
        $status = $request->get('status');
        $query = Project::where('is_published', 1);

        if ($status && in_array($status, ['Ongoing', 'Completed'])) {
            $query->where('status', $status);
        }

        $projects = $query->orderByDesc('created_at')->get();
        return view('pages.projects', compact('projects'));
    }

    public function projectsShow(int $id)
    {
        $project = Project::where('is_published', 1)->findOrFail($id);
        $related = Project::where('is_published', 1)
            ->where('id', '!=', $id)
            ->limit(3)
            ->get();
        return view('pages.projects.show', compact('project', 'related'));
    }

    public function investment()
    {
        $investments = Investment::orderByDesc('created_at')->paginate(10);
        return view('pages.investment', compact('investments'));
    }

    public function investmentShow(int $id)
    {
        $investment = Investment::findOrFail($id);
        $related = Investment::where('id', '!=', $id)
            ->where('category', $investment->category)
            ->limit(3)
            ->get();
        return view('pages.investment.show', compact('investment', 'related'));
    }

    public function directory(Request $request)
    {
        $query = DirectoryRecord::query();
        if ($request->filled('search')) {
            $query->where(fn($q) => $q->where('name_en', 'like', '%' . $request->search . '%')->orWhere('department_en', 'like', '%' . $request->search . '%'));
        }
        $records = $query->orderBy('department_en')->orderBy('sort_order')->get();
        return view('pages.directory', compact('records'));
    }

    // ── Contact ───────────────────────────────────────────────────────────────
    public function contact()
    {
        return view('pages.contact');
    }

    public function contactStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|max:5000',
        ]);
        ContactMessage::create($validated);
        return back()->with('success', __('Your message has been sent successfully.'));
    }

    // ── Tourism ───────────────────────────────────────────────────────────────
    public function tourismIndex(Request $request)
    {
        $query = TouristSite::where('is_active', 1);

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $query->where('name_en', 'like', '%' . $request->search . '%');
        }

        $sites = $query->orderBy('sort_order')->paginate(12)->withQueryString();
        $categories = TouristSite::where('is_active', 1)->distinct()->pluck('category')->filter();

        $heroSlides = HeroSlide::where('is_active', 1)
            ->where('page', 'tourism')
            ->orderBy('sort_order')
            ->get();

        return view('pages.tourism.index', compact('sites', 'categories', 'heroSlides'));
    }

    public function tourismShow($slug)
    {
        $site = TouristSite::where('slug', $slug)->where('is_active', 1)->firstOrFail();
        $related = TouristSite::where('is_active', 1)
            ->where('category', $site->category)
            ->where('id', '!=', $site->id)
            ->limit(3)
            ->get();

        return view('pages.tourism.show', compact('site', 'related'));
    }
}
