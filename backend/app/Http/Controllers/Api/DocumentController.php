<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Document;
use Illuminate\Support\Str;

use App\Models\Leadership;

class DocumentController extends Controller
{
    /**
     * Display a listing of documents.
     */
    public function index(Request $request)
    {
        $query = Document::query();

        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * Store a newly created document (admin).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title_en' => 'required|string|max:255',
            'category' => 'required|string',
            'title_am' => 'nullable|string',
            'title_or' => 'nullable|string',
            'file_type' => 'nullable|string',
            'author' => 'nullable|string',
            'description_en' => 'nullable|string',
            'pages' => 'nullable|integer',
            'language' => 'nullable|string',
        ]);

        $data = $validated;
        if ($request->hasFile('file')) {
            $path = $request->file('file')->move(public_path('uploads'), Str::random(40) . '.' . $request->file('file')->getClientOriginalExtension());
            $data['file_url'] = '/uploads/' . basename($path);
        }
        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->move(public_path('uploads'), Str::random(40) . '.' . $request->file('cover_image')->getClientOriginalExtension());
            $data['cover_image_url'] = '/uploads/' . basename($path);
        }

        $document = Document::create($data);

        return response()->json(['message' => 'Document uploaded successfully', 'id' => $document->id], 201);
    }

    /**
     * Display the specified document.
     */
    public function show(string $id)
    {
        return Document::findOrFail($id);
    }

    /**
     * Update the specified document (admin).
     */
    public function update(Request $request, string $id)
    {
        $document = Document::findOrFail($id);
        $data = $request->all();

        if ($request->hasFile('file')) {
            $path = $request->file('file')->move(public_path('uploads'), Str::random(40) . '.' . $request->file('file')->getClientOriginalExtension());
            $data['file_url'] = '/uploads/' . basename($path);
        }
        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->move(public_path('uploads'), Str::random(40) . '.' . $request->file('cover_image')->getClientOriginalExtension());
            $data['cover_image_url'] = '/uploads/' . basename($path);
        }

        $document->update($data);

        return response()->json(['message' => 'Document updated successfully']);
    }

    /**
     * Remove the specified document (admin).
     */
    public function destroy(string $id)
    {
        $document = Document::findOrFail($id);
        $document->delete();

        return response()->json(['message' => 'Document deleted successfully']);
    }
}
