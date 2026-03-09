<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\HeroSlide;
use Illuminate\Support\Str;

use App\Models\AdminMessage;

class HeroSlideController extends Controller
{
    /**
     * Display active slides.
     */
    public function index()
    {
        return HeroSlide::where('is_active', 1)
            ->orderBy('sort_order', 'asc')
            ->get();
    }

    /**
     * Store a newly created slide (admin).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title_en' => 'nullable|string|max:255',
            'subtitle_en' => 'nullable|string|max:255',
            'title_am' => 'nullable|string|max:255',
            'subtitle_am' => 'nullable|string|max:255',
            'title_or' => 'nullable|string|max:255',
            'subtitle_or' => 'nullable|string|max:255',
            'media_type' => 'required|in:image,video',
            'cta_text' => 'nullable|string',
            'cta_text_am' => 'nullable|string',
            'cta_text_or' => 'nullable|string',
            'cta_url' => 'nullable|string',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable',
        ]);

        $data = $validated;
        $data['is_active'] = filter_var($request->input('is_active', true), FILTER_VALIDATE_BOOLEAN) ? 1 : 0;

        if ($request->hasFile('media')) {
            $path = $request->file('media')->move(public_path('uploads'), Str::random(40) . '.' . $request->file('media')->getClientOriginalExtension());
            $data['media_url'] = '/uploads/' . basename($path);
        }

        $slide = HeroSlide::create($data);

        return response()->json(['message' => 'Slide added successfully', 'id' => $slide->id], 201);
    }

    /**
     * Display the specified slide.
     */
    public function show(string $id)
    {
        return HeroSlide::findOrFail($id);
    }

    /**
     * Update the specified slide (admin).
     */
    public function update(Request $request, string $id)
    {
        $slide = HeroSlide::findOrFail($id);
        $validated = $request->validate([
            'title_en' => 'nullable|string|max:255',
            'subtitle_en' => 'nullable|string|max:255',
            'title_am' => 'nullable|string|max:255',
            'subtitle_am' => 'nullable|string|max:255',
            'title_or' => 'nullable|string|max:255',
            'subtitle_or' => 'nullable|string|max:255',
            'media_type' => 'nullable|in:image,video',
            'cta_text' => 'nullable|string',
            'cta_text_am' => 'nullable|string',
            'cta_text_or' => 'nullable|string',
            'cta_url' => 'nullable|string',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable',
        ]);

        $data = $validated;

        if ($request->has('is_active')) {
            $data['is_active'] = filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
        }

        if ($request->hasFile('media')) {
            $file = $request->file('media');
            $extension = $file->getClientOriginalExtension();
            $path = $file->move(public_path('uploads'), Str::random(40) . '.' . $extension);
            $data['media_url'] = '/uploads/' . basename($path);

            // Auto-detect type if not provided
            if (!$request->has('media_type')) {
                $mime = $file->getClientMimeType();
                $data['media_type'] = str_starts_with($mime, 'video/') ? 'video' : 'image';
            }
        }

        $slide->update($data);

        return response()->json(['message' => 'Slide updated successfully']);
    }

    /**
     * Remove the specified slide (admin).
     */
    public function destroy(string $id)
    {
        $slide = HeroSlide::findOrFail($id);
        $slide->delete();

        return response()->json(['message' => 'Slide removed successfully']);
    }
}
