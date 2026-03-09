<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Woreda;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class WoredaController extends Controller
{
    /**
     * Display active woredas (public).
     */
    public function index(Request $request)
    {
        if ($request->query('admin') === 'true') {
            return Woreda::orderBy('name_en', 'asc')->get();
        }
        return Woreda::where('is_active', 1)->orderBy('name_en', 'asc')->get();
    }

    /**
     * Store a newly created woreda (admin).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name_en' => 'required|string|max:255',
            'slug' => 'required|string|max:100|unique:woredas,slug',
            'name_am' => 'nullable|string',
            'name_or' => 'nullable|string',
            'description_en' => 'nullable|string',
            'description_am' => 'nullable|string',
            'description_or' => 'nullable|string',
            'population' => 'nullable|string',
            'area_km2' => 'nullable|string',
            'established_year' => 'nullable|string',
            'capital_en' => 'nullable|string',
            'capital_am' => 'nullable|string',
            'capital_or' => 'nullable|string',
            'administrator_name' => 'nullable|string',
            'administrator_title' => 'nullable|string',
            'contact_phone' => 'nullable|string',
            'contact_email' => 'nullable|email',
            'address_en' => 'nullable|string',
            'address_am' => 'nullable|string',
            'address_or' => 'nullable|string',
            'is_active' => 'nullable',
        ]);

        $data = $validated;
        $data['is_active'] = filter_var($request->input('is_active', true), FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
        $data['slug'] = Str::slug($request->slug);

        if ($request->hasFile('banner')) {
            $path = $request->file('banner')->move(public_path('uploads'), Str::random(40) . '.' . $request->file('banner')->getClientOriginalExtension());
            $data['banner_url'] = '/uploads/' . basename($path);
        }
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->move(public_path('uploads'), Str::random(40) . '.' . $request->file('logo')->getClientOriginalExtension());
            $data['logo_url'] = '/uploads/' . basename($path);
        }
        if ($request->hasFile('admin_photo')) {
            $path = $request->file('admin_photo')->move(public_path('uploads'), Str::random(40) . '.' . $request->file('admin_photo')->getClientOriginalExtension());
            $data['administrator_photo_url'] = '/uploads/' . basename($path);
        }

        $woreda = Woreda::create($data);

        return response()->json(['message' => 'Woreda created', 'id' => $woreda->id], 201);
    }

    /**
     * Display the specified woreda (public/admin).
     */
    public function show(string $idOrSlug)
    {
        $woreda = Woreda::where('id', $idOrSlug)
            ->orWhere('slug', $idOrSlug)
            ->firstOrFail();

        return response()->json($woreda);
    }

    /**
     * Update the specified woreda (admin).
     */
    public function update(Request $request, string $id)
    {
        $woreda = Woreda::findOrFail($id);

        $data = $request->all();

        if ($request->has('is_active')) {
            $data['is_active'] = filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
        }

        if ($request->hasFile('banner')) {
            $path = $request->file('banner')->move(public_path('uploads'), Str::random(40) . '.' . $request->file('banner')->getClientOriginalExtension());
            $data['banner_url'] = '/uploads/' . basename($path);
        }
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->move(public_path('uploads'), Str::random(40) . '.' . $request->file('logo')->getClientOriginalExtension());
            $data['logo_url'] = '/uploads/' . basename($path);
        }
        if ($request->hasFile('admin_photo')) {
            $path = $request->file('admin_photo')->move(public_path('uploads'), Str::random(40) . '.' . $request->file('admin_photo')->getClientOriginalExtension());
            $data['administrator_photo_url'] = '/uploads/' . basename($path);
        }

        $woreda->update($data);

        return response()->json(['message' => 'Woreda updated']);
    }

    /**
     * Remove the specified woreda (admin).
     */
    public function destroy(string $id)
    {
        $woreda = Woreda::findOrFail($id);
        $woreda->delete();

        return response()->json(['message' => 'Woreda deleted']);
    }
}
