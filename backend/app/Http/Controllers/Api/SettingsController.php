<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\OfficeSetting;

class SettingsController extends Controller
{
    /**
     * Display office settings.
     */
    public function index()
    {
        return OfficeSetting::first() ?: response()->json(['message' => 'No settings found'], 404);
    }

    /**
     * Update office settings (admin).
     */
    public function store(Request $request)
    {
        $settings = OfficeSetting::first() ?: new OfficeSetting();

        $validated = $request->validate([
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'address' => 'nullable|string',
            'working_hours' => 'nullable|string',
            'map_url' => 'nullable|string',
            'facebook_url' => 'nullable|string',
            'twitter_url' => 'nullable|string',
            'linkedin_url' => 'nullable|string',
            'youtube_url' => 'nullable|string',
        ]);

        $settings->fill($validated);
        $settings->save();

        return response()->json(['message' => 'Settings updated successfully']);
    }
}
