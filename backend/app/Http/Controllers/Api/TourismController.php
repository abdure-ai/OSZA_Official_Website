<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TouristSite;
use Illuminate\Http\Request;

class TourismController extends Controller
{
    public function index(Request $request)
    {
        $query = TouristSite::with('woreda')->where('is_active', 1);

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $query->where('name_en', 'like', '%' . $request->search . '%');
        }

        $sites = $query->orderBy('sort_order')->get();
        return response()->json($sites);
    }

    public function show($slug)
    {
        $site = TouristSite::with('woreda')
            ->where('slug', $slug)
            ->where('is_active', 1)
            ->firstOrFail();

        $related = TouristSite::where('is_active', 1)
            ->where('category', $site->category)
            ->where('id', '!=', $site->id)
            ->limit(3)
            ->get();

        return response()->json([
            'site' => $site,
            'related' => $related
        ]);
    }

    public function categories()
    {
        $categories = TouristSite::where('is_active', 1)
            ->distinct()
            ->pluck('category')
            ->filter();

        return response()->json($categories);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name_en' => 'required|string|max:255',
            'name_am' => 'nullable|string|max:255',
            'name_or' => 'nullable|string|max:255',
            'slug' => 'required|string|unique:tourist_sites,slug',
            'description_en' => 'required|string',
            'description_am' => 'nullable|string',
            'description_or' => 'nullable|string',
            'category' => 'nullable|string',
            'woreda_id' => 'nullable|exists:woredas,id',
            'location_name_en' => 'nullable|string',
            'cover_image' => 'nullable|image|max:10240',
            'gallery_images.*' => 'nullable|image|max:10240',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('tourism/covers', 'public');
            $validated['cover_image_url'] = '/storage/' . $path;
        }

        if ($request->hasFile('gallery_images')) {
            $galleryPaths = [];
            foreach ($request->file('gallery_images') as $image) {
                $path = $image->store('tourism/gallery', 'public');
                $galleryPaths[] = '/storage/' . $path;
            }
            $validated['gallery_urls'] = $galleryPaths;
        }

        $site = TouristSite::create($validated);
        return response()->json($site, 201);
    }

    public function update(Request $request, $id)
    {
        $site = TouristSite::findOrFail($id);

        $validated = $request->validate([
            'name_en' => 'sometimes|string|max:255',
            'name_am' => 'nullable|string|max:255',
            'name_or' => 'nullable|string|max:255',
            'slug' => 'sometimes|string|unique:tourist_sites,slug,' . $id,
            'description_en' => 'sometimes|string',
            'description_am' => 'nullable|string',
            'description_or' => 'nullable|string',
            'category' => 'nullable|string',
            'woreda_id' => 'nullable|exists:woredas,id',
            'location_name_en' => 'nullable|string',
            'cover_image' => 'nullable|image|max:10240',
            'gallery_images.*' => 'nullable|image|max:10240',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('tourism/covers', 'public');
            $validated['cover_image_url'] = '/storage/' . $path;
        }

        if ($request->hasFile('gallery_images')) {
            $galleryPaths = $site->gallery_urls ?? [];
            foreach ($request->file('gallery_images') as $image) {
                $path = $image->store('tourism/gallery', 'public');
                $galleryPaths[] = '/storage/' . $path;
            }
            $validated['gallery_urls'] = $galleryPaths;
        }

        $site->update($validated);
        return response()->json($site);
    }

    public function destroy($id)
    {
        $site = TouristSite::findOrFail($id);
        $site->delete();
        return response()->json(null, 204);
    }
}
