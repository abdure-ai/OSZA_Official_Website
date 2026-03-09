<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Tender;
use Illuminate\Support\Str;

class TenderController extends Controller
{
    /**
     * Display a listing of tenders.
     */
    public function index(Request $request)
    {
        $query = Tender::query();

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        return $query->orderBy('deadline', 'desc')->get();
    }

    /**
     * Store a newly created tender (admin).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title_en' => 'required|string|max:255',
            'deadline' => 'required|date',
            'title_am' => 'nullable|string',
            'title_or' => 'nullable|string',
            'description_en' => 'nullable|string',
            'description_am' => 'nullable|string',
            'description_or' => 'nullable|string',
            'ref_number' => 'nullable|string|max:100',
            'status' => 'nullable|string|in:active,closed,archived,Open,Closed,Awarded,Cancelled',
        ]);

        $data = $validated;
        // Check for 'document' or 'file' for backward compatibility, preferring 'document'
        $fileField = $request->hasFile('document') ? 'document' : ($request->hasFile('file') ? 'file' : null);

        if ($fileField) {
            $path = $request->file($fileField)->move(public_path('uploads'), Str::random(40) . '.' . $request->file($fileField)->getClientOriginalExtension());
            $data['file_url'] = '/uploads/' . basename($path);
        }

        $tender = Tender::create($data);

        return response()->json(['message' => 'Tender created successfully', 'tenderId' => $tender->id], 201);
    }

    /**
     * Display the specified tender.
     */
    public function show(string $id)
    {
        return Tender::findOrFail($id);
    }

    /**
     * Update the specified tender (admin).
     */
    public function update(Request $request, string $id)
    {
        $tender = Tender::findOrFail($id);

        $validated = $request->validate([
            'title_en' => 'sometimes|required|string|max:255',
            'deadline' => 'sometimes|required|date',
            'title_am' => 'nullable|string',
            'title_or' => 'nullable|string',
            'description_en' => 'nullable|string',
            'description_am' => 'nullable|string',
            'description_or' => 'nullable|string',
            'ref_number' => 'nullable|string|max:100',
            'status' => 'nullable|string|in:active,closed,archived,Open,Closed,Awarded,Cancelled',
        ]);

        $data = $validated;
        $fileField = $request->hasFile('document') ? 'document' : ($request->hasFile('file') ? 'file' : null);

        if ($fileField) {
            $path = $request->file($fileField)->move(public_path('uploads'), Str::random(40) . '.' . $request->file($fileField)->getClientOriginalExtension());
            $data['file_url'] = '/uploads/' . basename($path);
        }

        $tender->update($data);

        return response()->json(['message' => 'Tender updated successfully', 'tender' => $tender]);
    }

    /**
     * Remove the specified tender (admin).
     */
    public function destroy(string $id)
    {
        $tender = Tender::findOrFail($id);
        $tender->delete();

        return response()->json(['message' => 'Tender deleted successfully']);
    }
}
