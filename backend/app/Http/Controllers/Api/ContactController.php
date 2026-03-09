<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\ContactMessage;

class ContactController extends Controller
{
    /**
     * Display a listing of messages (admin).
     */
    public function index()
    {
        return ContactMessage::orderBy('created_at', 'desc')->get();
    }

    /**
     * Store a newly created message (public).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
        ]);

        ContactMessage::create($validated);

        return response()->json(['message' => 'Message sent successfully. Thank you!']);
    }

    /**
     * Display the specified message (admin).
     */
    public function show(string $id)
    {
        return ContactMessage::findOrFail($id);
    }

    /**
     * Remove the specified message (admin).
     */
    public function destroy(string $id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->delete();

        return response()->json(['message' => 'Message deleted']);
    }
}
