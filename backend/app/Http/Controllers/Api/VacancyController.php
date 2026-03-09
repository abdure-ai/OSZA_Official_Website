<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Vacancy;

class VacancyController extends Controller
{
    /**
     * Display a listing of vacancies.
     */
    public function index(Request $request)
    {
        $query = Vacancy::query();

        if ($request->has('department')) {
            $query->where('department', $request->department);
        }

        if ($request->has('type')) {
            $query->where('vacancy_type', $request->type);
        }

        if ($request->query('active') === 'false') {
            // Include inactive
        } elseif ($request->query('active') === 'all') {
            // All
        } else {
            $query->where('is_active', 1);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * Store a newly created vacancy (admin).
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
            'requirements_en' => 'nullable|string',
            'requirements_am' => 'nullable|string',
            'requirements_or' => 'nullable|string',
            'department' => 'nullable|string',
            'vacancy_type' => 'nullable|string',
            'location_en' => 'nullable|string',
            'location_am' => 'nullable|string',
            'location_or' => 'nullable|string',
            'deadline' => 'nullable|date',
        ]);

        $vacancy = Vacancy::create($validated);

        return response()->json(['message' => 'Vacancy created successfully', 'vacancyId' => $vacancy->id], 201);
    }

    /**
     * Display the specified vacancy.
     */
    public function show(string $id)
    {
        return Vacancy::findOrFail($id);
    }

    /**
     * Update the specified vacancy (admin).
     */
    public function update(Request $request, string $id)
    {
        $vacancy = Vacancy::findOrFail($id);

        $validated = $request->validate([
            'title_en' => 'sometimes|required|string|max:255',
            'description_en' => 'sometimes|required|string',
            'title_am' => 'nullable|string',
            'title_or' => 'nullable|string',
            'description_am' => 'nullable|string',
            'description_or' => 'nullable|string',
            'requirements_en' => 'nullable|string',
            'requirements_am' => 'nullable|string',
            'requirements_or' => 'nullable|string',
            'department' => 'nullable|string',
            'vacancy_type' => 'nullable|string',
            'location_en' => 'nullable|string',
            'location_am' => 'nullable|string',
            'location_or' => 'nullable|string',
            'deadline' => 'nullable|date',
            'is_active' => 'sometimes',
        ]);

        $data = $validated;

        if ($request->has('is_active')) {
            $data['is_active'] = filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
        }

        $vacancy->update($data);

        return response()->json(['message' => 'Vacancy updated successfully', 'vacancy' => $vacancy]);
    }

    /**
     * Remove the specified vacancy (admin).
     */
    public function destroy(string $id)
    {
        $vacancy = Vacancy::findOrFail($id);
        $vacancy->delete();

        return response()->json(['message' => 'Vacancy deleted successfully']);
    }
}
