<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Leadership;
use Illuminate\Support\Str;

class LeadershipController extends Controller
{
    /**
     * Display a listing of leadership.
     */
    public function index()
    {
        return Leadership::orderBy('rank_order', 'asc')->get();
    }

    /**
     * Store a newly created leader (admin).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name_en' => 'required|string|max:255',
            'position_en' => 'required|string|max:255',
            'name_am' => 'nullable|string',
            'name_or' => 'nullable|string',
            'position_am' => 'nullable|string',
            'position_or' => 'nullable|string',
            'bio_en' => 'nullable|string',
            'bio_am' => 'nullable|string',
            'bio_or' => 'nullable|string',
            'rank_order' => 'nullable|integer',
        ]);

        $data = $validated;
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->move(public_path('uploads'), Str::random(40) . '.' . $request->file('photo')->getClientOriginalExtension());
            $data['photo_url'] = '/uploads/' . basename($path);
        }

        $leader = Leadership::create($data);

        return response()->json(['message' => 'Leader added successfully', 'id' => $leader->id], 201);
    }

    /**
     * Display the specified leader.
     */
    public function show(string $id)
    {
        return Leadership::findOrFail($id);
    }

    /**
     * Update the specified leader (admin).
     */
    public function update(Request $request, string $id)
    {
        $leader = Leadership::findOrFail($id);
        $data = $request->all();

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->move(public_path('uploads'), Str::random(40) . '.' . $request->file('photo')->getClientOriginalExtension());
            $data['photo_url'] = '/uploads/' . basename($path);
        }

        $leader->update($data);

        return response()->json(['message' => 'Leader updated successfully']);
    }

    /**
     * Remove the specified leader (admin).
     */
    public function destroy(string $id)
    {
        $leader = Leadership::findOrFail($id);
        $leader->delete();

        return response()->json(['message' => 'Leader removed successfully']);
    }
}
