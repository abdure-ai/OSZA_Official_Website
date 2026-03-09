<?php

namespace App\Livewire\Admin;

use App\Models\Woreda;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class WoredasManager extends Component
{
    use WithPagination, WithFileUploads;

    public $showModal = false;
    public $editingId = null;
    public $name_en, $name_am, $name_or, $slug;
    public $description_en, $description_am, $description_or;
    public $mission_en, $mission_am, $mission_or;
    public $vision_en, $vision_am, $vision_or;
    public $population, $area_km2, $established_year;
    public $capital_en, $capital_am, $capital_or;
    public $administrator_name, $administrator_title;
    public $contact_phone, $contact_email, $address_en;
    public $is_active = true;
    public $banner, $logo, $admin_photo;
    public $search = '';

    // Service Sectors mapping
    public array $selected_services = [];

    protected $rules = [
        'name_en' => 'required|string|max:255',
        'name_am' => 'nullable|string',
        'name_or' => 'nullable|string',
        'slug' => 'nullable|string|alpha_dash',
        'is_active' => 'boolean',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openCreate()
    {
        $this->reset([
            'editingId',
            'name_en',
            'name_am',
            'name_or',
            'slug',
            'description_en',
            'description_am',
            'description_or',
            'mission_en',
            'mission_am',
            'mission_or',
            'vision_en',
            'vision_am',
            'vision_or',
            'population',
            'area_km2',
            'established_year',
            'capital_en',
            'capital_am',
            'capital_or',
            'administrator_name',
            'administrator_title',
            'contact_phone',
            'contact_email',
            'address_en',
            'banner',
            'logo',
            'admin_photo'
        ]);
        $this->selected_services = [];
        $this->is_active = true;
        $this->showModal = true;
    }

    public function openEdit($id)
    {
        $this->reset(['editingId', 'banner', 'logo', 'admin_photo']);
        $w = Woreda::findOrFail($id);
        foreach ([
            'name_en',
            'name_am',
            'name_or',
            'slug',
            'description_en',
            'description_am',
            'description_or',
            'mission_en',
            'mission_am',
            'mission_or',
            'vision_en',
            'vision_am',
            'vision_or',
            'population',
            'area_km2',
            'established_year',
            'capital_en',
            'capital_am',
            'capital_or',
            'administrator_name',
            'administrator_title',
            'contact_phone',
            'contact_email',
            'address_en'
        ] as $f) {
            $this->$f = $w->$f;
        }
        $this->editingId = $id;
        $this->is_active = (bool) $w->is_active;

        $this->selected_services = [];
        foreach ($w->serviceSectors as $sector) {
            $this->selected_services[$sector->id] = [
                'is_selected' => true,
                'official_name_en' => $sector->pivot->official_name_en,
                'official_name_am' => $sector->pivot->official_name_am,
                'official_name_or' => $sector->pivot->official_name_or,
                'official_title_en' => $sector->pivot->official_title_en,
                'official_title_am' => $sector->pivot->official_title_am,
                'official_title_or' => $sector->pivot->official_title_or,
                'official_phone' => $sector->pivot->official_phone,
                'official_email' => $sector->pivot->official_email,
                'official_photo_url' => $sector->pivot->official_photo_url,
                'new_photo' => null, // For tracking file uploads
            ];
        }

        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();
        if (!$this->slug)
            $this->slug = Str::slug($this->name_en);
        $data = [
            'name_en' => $this->name_en,
            'name_am' => $this->name_am,
            'name_or' => $this->name_or,
            'slug' => $this->slug,
            'description_en' => $this->description_en,
            'description_am' => $this->description_am,
            'description_or' => $this->description_or,
            'mission_en' => $this->mission_en,
            'mission_am' => $this->mission_am,
            'mission_or' => $this->mission_or,
            'vision_en' => $this->vision_en,
            'vision_am' => $this->vision_am,
            'vision_or' => $this->vision_or,
            'population' => $this->population,
            'area_km2' => $this->area_km2,
            'established_year' => $this->established_year,
            'capital_en' => $this->capital_en,
            'capital_am' => $this->capital_am,
            'capital_or' => $this->capital_or,
            'administrator_name' => $this->administrator_name,
            'administrator_title' => $this->administrator_title,
            'contact_phone' => $this->contact_phone,
            'contact_email' => $this->contact_email,
            'address_en' => $this->address_en,
            'is_active' => $this->is_active ? 1 : 0
        ];
        foreach (['banner' => 'banner_url', 'logo' => 'logo_url', 'admin_photo' => 'administrator_photo_url'] as $field => $col) {
            if ($this->$field) {
                $path = $this->$field->store('uploads', 'public_uploads');
                $data[$col] = '/uploads/' . basename($path);
            }
        }
        if ($this->editingId) {
            $woreda = Woreda::findOrFail($this->editingId);
            $woreda->update($data);
        } else {
            $woreda = Woreda::create($data);
        }

        $syncData = [];
        foreach ($this->selected_services as $sectorId => $sectorData) {
            if (!empty($sectorData['is_selected'])) {
                $photoUrl = $sectorData['official_photo_url'] ?? null;

                // Handle new photo upload
                if (!empty($sectorData['new_photo'])) {
                    $path = $sectorData['new_photo']->store('uploads/officials', 'public_uploads');
                    $photoUrl = '/uploads/officials/' . basename($path);
                }

                $syncData[$sectorId] = [
                    'official_name_en' => $sectorData['official_name_en'] ?? null,
                    'official_name_am' => $sectorData['official_name_am'] ?? null,
                    'official_name_or' => $sectorData['official_name_or'] ?? null,
                    'official_title_en' => $sectorData['official_title_en'] ?? null,
                    'official_title_am' => $sectorData['official_title_am'] ?? null,
                    'official_title_or' => $sectorData['official_title_or'] ?? null,
                    'official_phone' => $sectorData['official_phone'] ?? null,
                    'official_email' => $sectorData['official_email'] ?? null,
                    'official_photo_url' => $photoUrl,
                ];
            }
        }
        $woreda->serviceSectors()->sync($syncData);

        $this->showModal = false;
        session()->flash('success', 'Woreda information updated successfully.');
    }

    public function delete($id)
    {
        Woreda::findOrFail($id)->delete();
        session()->flash('success', 'Woreda deleted permanently.');
    }

    public function render()
    {
        $woredas = Woreda::when($this->search, fn($q) => $q->where('name_en', 'like', '%' . $this->search . '%'))->orderBy('name_en')->paginate(10);
        $serviceSectors = \App\Models\ServiceSector::where('is_active', true)->orderBy('sort_order')->get();
        return view('livewire.admin.woredas-manager', compact('woredas', 'serviceSectors'));
    }
}
