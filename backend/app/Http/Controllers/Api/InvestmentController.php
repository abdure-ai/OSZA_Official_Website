<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Investment;
use Illuminate\Support\Str;

class InvestmentController extends Controller
{
    /**
     * Display a listing of investments.
     */
    public function index(Request $request)
    {
        $query = Investment::query();

        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * Store a newly created investment (admin).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title_en' => 'required|string|max:255',
            'title_am' => 'nullable|string|max:255',
            'title_or' => 'nullable|string|max:255',
            'description_en' => 'required|string',
            'description_am' => 'nullable|string',
            'description_or' => 'nullable|string',
            'category' => 'nullable|string',
            'location' => 'nullable|string',
            'location_am' => 'nullable|string',
            'location_or' => 'nullable|string',
            'budget' => 'nullable|string',
            'incentives_en' => 'nullable|string',
            'incentives_am' => 'nullable|string',
            'incentives_or' => 'nullable|string',
            'contact_name' => 'nullable|string',
            'contact_phone' => 'nullable|string',
            'contact_email' => 'nullable|email',
            'status' => 'nullable|string',
        ]);

        $data = $validated;
        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->move(public_path('uploads'), Str::random(40) . '.' . $request->file('thumbnail')->getClientOriginalExtension());
            $data['thumbnail_url'] = '/uploads/' . basename($path);
        }

        $investment = Investment::create($data);

        return response()->json(['message' => 'Investment opportunity created', 'id' => $investment->id], 201);
    }

    /**
     * Display the specified investment.
     */
    public function show(string $id)
    {
        return Investment::findOrFail($id);
    }

    /**
     * Update the specified investment (admin).
     */
    public function update(Request $request, string $id)
    {
        $investment = Investment::findOrFail($id);

        $validated = $request->validate([
            'title_en' => 'sometimes|required|string|max:255',
            'title_am' => 'nullable|string|max:255',
            'title_or' => 'nullable|string|max:255',
            'description_en' => 'sometimes|required|string',
            'description_am' => 'nullable|string',
            'description_or' => 'nullable|string',
            'category' => 'nullable|string',
            'location' => 'nullable|string',
            'location_am' => 'nullable|string',
            'location_or' => 'nullable|string',
            'budget' => 'nullable|string',
            'incentives_en' => 'nullable|string',
            'incentives_am' => 'nullable|string',
            'incentives_or' => 'nullable|string',
            'contact_name' => 'nullable|string',
            'contact_phone' => 'nullable|string',
            'contact_email' => 'nullable|email',
            'status' => 'nullable|string',
        ]);

        $data = $validated;

        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->move(public_path('uploads'), Str::random(40) . '.' . $request->file('thumbnail')->getClientOriginalExtension());
            $data['thumbnail_url'] = '/uploads/' . basename($path);
        }

        $investment->update($data);

        return response()->json(['message' => 'Investment opportunity updated', 'investment' => $investment]);
    }

    /**
     * Remove the specified investment (admin).
     */
    public function destroy(string $id)
    {
        $investment = Investment::findOrFail($id);
        $investment->delete();

        return response()->json(['message' => 'Investment opportunity deleted']);
    }
}
