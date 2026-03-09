<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Woreda;
use App\Models\GalleryItem;
use App\Models\DirectoryRecord;
use Illuminate\Http\Request;

class WoredaController extends Controller
{
    protected function getWoreda(string $slug): Woreda
    {
        return Woreda::where('slug', $slug)->where('is_active', 1)->firstOrFail();
    }

    public function show(string $slug)
    {
        $woreda = $this->getWoreda($slug);
        $recentPhotos = GalleryItem::where('woreda_id', $woreda->id)->where('is_active', 1)->orderBy('sort_order')->limit(6)->get();
        return view('pages.woreda.show', compact('woreda', 'recentPhotos'));
    }

    public function about(string $slug)
    {
        $woreda = $this->getWoreda($slug);
        return view('pages.woreda.about', compact('woreda'));
    }

    public function gallery(string $slug, Request $request)
    {
        $woreda = $this->getWoreda($slug);
        $categories = GalleryItem::where('woreda_id', $woreda->id)->where('is_active', 1)->select('category')->groupBy('category')->pluck('category');
        $activeCategory = $request->get('category', $categories->first());
        $items = GalleryItem::where('woreda_id', $woreda->id)->where('is_active', 1)
            ->when($activeCategory, fn($q) => $q->where('category', $activeCategory))
            ->orderBy('sort_order')->get();
        return view('pages.woreda.gallery', compact('woreda', 'categories', 'activeCategory', 'items'));
    }

    public function services(string $slug)
    {
        $woreda = $this->getWoreda($slug);
        $services = $woreda->serviceSectors()->where('service_sectors.is_active', true)->orderBy('service_sectors.sort_order')->get();
        return view('pages.woreda.services', compact('woreda', 'services'));
    }

    public function contact(string $slug)
    {
        $woreda = $this->getWoreda($slug);
        $records = DirectoryRecord::where('woreda_id', $woreda->id)->where('is_active', 1)->orderBy('department_en')->get();
        return view('pages.woreda.contact', compact('woreda', 'records'));
    }
}
