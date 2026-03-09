<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\AdminMessage;
use Illuminate\Support\Str;

class AdminMessageController extends Controller
{
    /**
     * Display active admin message.
     */
    public function index()
    {
        return AdminMessage::where('is_active', 1)->first() ?: response()->json(['message' => 'No active message'], 404);
    }

    /**
     * Store or update admin message (admin).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'nullable|string',
            'title_position' => 'nullable|string',
            'message_en' => 'required|string',
            'message_am' => 'nullable|string',
            'message_or' => 'nullable|string',
            'is_active' => 'nullable',
        ]);

        $data = $validated;
        $data['is_active'] = filter_var($request->input('is_active', true), FILTER_VALIDATE_BOOLEAN) ? 1 : 0;

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->move(public_path('uploads'), Str::random(40) . '.' . $request->file('photo')->getClientOriginalExtension());
            $data['photo_url'] = '/uploads/' . basename($path);
        }

        $message = AdminMessage::create($data);

        return response()->json(['message' => 'Admin message created', 'id' => $message->id], 201);
    }

    /**
     * Display the specified message.
     */
    public function show(string $id)
    {
        return AdminMessage::findOrFail($id);
    }

    /**
     * Update the specified message.
     */
    public function update(Request $request, string $id)
    {
        $message = AdminMessage::findOrFail($id);
        $data = $request->all();

        if ($request->has('is_active')) {
            $data['is_active'] = filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
        }

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->move(public_path('uploads'), Str::random(40) . '.' . $request->file('photo')->getClientOriginalExtension());
            $data['photo_url'] = '/uploads/' . basename($path);
        }

        $message->update($data);

        return response()->json(['message' => 'Admin message updated']);
    }

    /**
     * Remove the specified message.
     */
    public function destroy(string $id)
    {
        $message = AdminMessage::findOrFail($id);
        $message->delete();

        return response()->json(['message' => 'Admin message deleted']);
    }
}
