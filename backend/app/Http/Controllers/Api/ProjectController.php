<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Project;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    /**
     * Display a listing of projects.
     */
    public function index(Request $request)
    {
        $query = Project::query();

        if ($request->query('admin') !== 'true') {
            $query->where('is_published', 1);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        return $query->orderBy('start_date', 'desc')->get();
    }

    /**
     * Store a newly created project (admin).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title_en' => 'required|string|max:255',
            'description_en' => 'required|string',
            'title_am' => 'nullable|string',
            'title_or' => 'nullable|string',
            'description_am' => 'nullable|string',
            'description_or' => 'nullable|string',
            'location_en' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'status' => 'nullable|in:Planning,Ongoing,Completed,On Hold',
            'budget' => 'nullable|numeric',
            'progress' => 'nullable|integer|min:0|max:100',
            'contractor' => 'nullable|string',
            'funding_source' => 'nullable|string',
            'is_published' => 'nullable',
        ]);

        $data = $validated;
        $data['is_published'] = filter_var($request->input('is_published', true), FILTER_VALIDATE_BOOLEAN) ? 1 : 0;

        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->move(public_path('uploads'), Str::random(40) . '.' . $request->file('cover_image')->getClientOriginalExtension());
            $data['cover_image_url'] = '/uploads/' . basename($path);
        }

        $project = Project::create($data);

        return response()->json(['message' => 'Project created successfully', 'id' => $project->id], 201);
    }

    /**
     * Display the specified project.
     */
    public function show(string $id)
    {
        return Project::findOrFail($id);
    }

    /**
     * Update the specified project (admin).
     */
    public function update(Request $request, string $id)
    {
        $project = Project::findOrFail($id);
        $data = $request->all();

        if ($request->has('is_published')) {
            $data['is_published'] = filter_var($request->is_published, FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
        }

        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->move(public_path('uploads'), Str::random(40) . '.' . $request->file('cover_image')->getClientOriginalExtension());
            $data['cover_image_url'] = '/uploads/' . basename($path);
        }

        $project->update($data);

        return response()->json(['message' => 'Project updated successfully']);
    }

    /**
     * Remove the specified project (admin).
     */
    public function destroy(string $id)
    {
        $project = Project::findOrFail($id);
        $project->delete();

        return response()->json(['message' => 'Project deleted successfully']);
    }
}
