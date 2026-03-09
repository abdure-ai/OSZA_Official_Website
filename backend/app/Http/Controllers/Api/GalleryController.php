<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GalleryItem;
use Illuminate\Support\Str;

class GalleryController extends Controller
{
    /**
     * Display a listing of gallery items.
     */
    public function index(Request $request)
    {
        $query = GalleryItem::query();

        if ($request->query('admin') !== 'true') {
            $query->where('is_active', 1);
        }

        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        if ($request->has('woreda_id')) {
            $query->where('woreda_id', $request->woreda_id);
        }

        return $query->orderBy('sort_order', 'asc')->get();
    }

    /**
     * Display gallery categories with cover images.
     * Uses a single query with a correlated subquery to avoid N+1 issues.
     */
    public function categories(Request $request)
    {
        $woredaId = $request->woreda_id;

        $categories = GalleryItem::select(
            'category',
            \Illuminate\Support\Facades\DB::raw('COUNT(*) as count'),
            \Illuminate\Support\Facades\DB::raw(
                '(SELECT gi2.image_url FROM gallery_items gi2
                      WHERE gi2.category = gallery_items.category
                      ' . ($woredaId ? 'AND gi2.woreda_id = ' . (int) $woredaId : '') . '
                      ORDER BY gi2.sort_order ASC
                      LIMIT 1) as cover_url'
            )
        )
            ->when($woredaId, fn($q) => $q->where('woreda_id', $woredaId))
            ->groupBy('category')
            ->get();

        return $categories;
    }

    /**
     * Store a newly created gallery item (admin).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'title_am' => 'nullable|string|max:255',
            'title_or' => 'nullable|string|max:255',
            'category' => 'nullable|string',
            'woreda_id' => 'nullable|exists:woredas,id',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable',
        ]);

        $data = $validated;
        $data['is_active'] = filter_var($request->input('is_active', true), FILTER_VALIDATE_BOOLEAN) ? 1 : 0;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->move(public_path('uploads'), Str::random(40) . '.' . $request->file('image')->getClientOriginalExtension());
            $data['image_url'] = '/uploads/' . basename($path);
        } else {
            return response()->json(['message' => 'Image is required'], 422);
        }

        $item = GalleryItem::create($data);

        return response()->json(['message' => 'Gallery item added', 'id' => $item->id], 201);
    }

    /**
     * Display the specified gallery item.
     */
    public function show(string $id)
    {
        return GalleryItem::findOrFail($id);
    }

    /**
     * Update the specified gallery item (admin).
     */
    public function update(Request $request, string $id)
    {
        $item = GalleryItem::findOrFail($id);

        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'title_am' => 'nullable|string|max:255',
            'title_or' => 'nullable|string|max:255',
            'category' => 'nullable|string',
            'woreda_id' => 'nullable|exists:woredas,id',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable',
        ]);

        $data = $validated;

        if ($request->has('is_active')) {
            $data['is_active'] = filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
        }

        if ($request->hasFile('image')) {
            $path = $request->file('image')->move(public_path('uploads'), Str::random(40) . '.' . $request->file('image')->getClientOriginalExtension());
            $data['image_url'] = '/uploads/' . basename($path);
        }

        $item->update($data);

        return response()->json(['message' => 'Gallery item updated', 'item' => $item]);
    }

    /**
     * Remove the specified gallery item (admin).
     */
    public function destroy(string $id)
    {
        $item = GalleryItem::findOrFail($id);
        $item->delete();

        return response()->json(['message' => 'Gallery item deleted']);
    }
}
