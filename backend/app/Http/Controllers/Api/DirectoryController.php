<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\DirectoryRecord;
use Illuminate\Support\Str;

class DirectoryController extends Controller
{
    /**
     * Display a listing of directory records.
     */
    public function index(Request $request)
    {
        $query = DirectoryRecord::query();

        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        return $query->orderBy('sort_order', 'asc')->get();
    }

    /**
     * Store a newly created directory record (admin).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'woreda_id' => 'nullable|integer',
            'name_en' => 'required|string|max:255',
            'name_am' => 'nullable|string|max:255',
            'name_or' => 'nullable|string|max:255',
            'position_en' => 'nullable|string|max:255',
            'position_am' => 'nullable|string|max:255',
            'position_or' => 'nullable|string|max:255',
            'department_en' => 'nullable|string|max:255',
            'department_am' => 'nullable|string|max:255',
            'department_or' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'office_location' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer',
        ]);

        $data = $validated;
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->move(public_path('uploads'), Str::random(40) . '.' . $request->file('photo')->getClientOriginalExtension());
            $data['photo_url'] = '/uploads/' . basename($path);
        }

        $record = DirectoryRecord::create($data);

        return response()->json(['message' => 'Directory record added', 'id' => $record->id], 201);
    }

    /**
     * Display the specified record.
     */
    public function show(string $id)
    {
        return DirectoryRecord::findOrFail($id);
    }

    /**
     * Update the specified record (admin).
     */
    public function update(Request $request, string $id)
    {
        $record = DirectoryRecord::findOrFail($id);

        $validated = $request->validate([
            'woreda_id' => 'nullable|integer',
            'name_en' => 'sometimes|required|string|max:255',
            'name_am' => 'nullable|string|max:255',
            'name_or' => 'nullable|string|max:255',
            'position_en' => 'nullable|string|max:255',
            'position_am' => 'nullable|string|max:255',
            'position_or' => 'nullable|string|max:255',
            'department_en' => 'nullable|string|max:255',
            'department_am' => 'nullable|string|max:255',
            'department_or' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'office_location' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $data = $validated;

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->move(public_path('uploads'), Str::random(40) . '.' . $request->file('photo')->getClientOriginalExtension());
            $data['photo_url'] = '/uploads/' . basename($path);
        }

        $record->update($data);

        return response()->json(['message' => 'Directory record updated', 'record' => $record]);
    }

    /**
     * Remove the specified record (admin).
     */
    public function destroy(string $id)
    {
        $record = DirectoryRecord::findOrFail($id);
        $record->delete();

        return response()->json(['message' => 'Directory record deleted']);
    }
}
