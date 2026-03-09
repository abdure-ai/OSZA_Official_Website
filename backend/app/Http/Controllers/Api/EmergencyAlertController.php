<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\EmergencyAlert;

class EmergencyAlertController extends Controller
{
    /**
     * Display active alerts.
     */
    public function index()
    {
        return EmergencyAlert::where('is_active', 1)
            ->where(function ($query) {
                $query->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->get();
    }

    /**
     * Store a newly created alert (admin).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'message_en' => 'required|string',
            'message_am' => 'nullable|string',
            'message_or' => 'nullable|string',
            'severity' => 'nullable|in:info,warning,critical',
            'is_active' => 'nullable',
            'expires_at' => 'nullable|date',
        ]);

        $data = $validated;
        $data['is_active'] = filter_var($request->input('is_active', true), FILTER_VALIDATE_BOOLEAN) ? 1 : 0;

        $alert = EmergencyAlert::create($data);

        return response()->json(['message' => 'Alert created successfully', 'id' => $alert->id], 201);
    }

    /**
     * Display the specified alert.
     */
    public function show(string $id)
    {
        return EmergencyAlert::findOrFail($id);
    }

    /**
     * Update the specified alert (admin).
     */
    public function update(Request $request, string $id)
    {
        $alert = EmergencyAlert::findOrFail($id);
        $data = $request->all();

        if ($request->has('is_active')) {
            $data['is_active'] = filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
        }

        $alert->update($data);

        return response()->json(['message' => 'Alert updated successfully']);
    }

    /**
     * Remove the specified alert (admin).
     */
    public function destroy(string $id)
    {
        $alert = EmergencyAlert::findOrFail($id);
        $alert->delete();

        return response()->json(['message' => 'Alert deleted successfully']);
    }
}
