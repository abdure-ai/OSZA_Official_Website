<?php

namespace App\Livewire\Admin;

use App\Models\TouristSite;
use App\Models\Woreda;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class TourismManager extends Component
{
    use WithPagination, WithFileUploads;

    public $showModal = false;
    public $editingId = null;
    public $name_en, $name_am, $name_or, $slug;
    public $description_en, $description_am, $description_or;
    public $category, $woreda_id, $location_name_en;
    public $cover_image, $temp_gallery = [];
    public $cover_image_url, $gallery_urls = [];
    public $latitude, $longitude;
    public $sort_order = 0;
    public $is_active = true;
    public $search = '';

    protected $rules = [
        'name_en' => 'required|string|max:255',
        'description_en' => 'required|string',
        'slug' => 'nullable|string|alpha_dash',
        'cover_image' => 'nullable|image|max:10240',
        'temp_gallery.*' => 'nullable|image|max:10240',
        'latitude' => 'nullable|numeric',
        'longitude' => 'nullable|numeric',
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
            'category',
            'woreda_id',
            'location_name_en',
            'cover_image',
            'temp_gallery',
            'cover_image_url',
            'gallery_urls',
            'latitude',
            'longitude',
            'sort_order'
        ]);
        $this->is_active = true;
        $this->showModal = true;
    }

    public function openEdit($id)
    {
        $site = TouristSite::findOrFail($id);
        $this->editingId = $id;
        $this->name_en = $site->name_en;
        $this->name_am = $site->name_am;
        $this->name_or = $site->name_or;
        $this->slug = $site->slug;
        $this->description_en = $site->description_en;
        $this->description_am = $site->description_am;
        $this->description_or = $site->description_or;
        $this->category = $site->category;
        $this->woreda_id = $site->woreda_id;
        $this->location_name_en = $site->location_name_en;
        $this->cover_image_url = $site->cover_image_url;
        $this->gallery_urls = $site->gallery_urls ?? [];
        $this->latitude = $site->latitude;
        $this->longitude = $site->longitude;
        $this->sort_order = $site->sort_order;
        $this->is_active = (bool) $site->is_active;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        if (!$this->slug) {
            $this->slug = Str::slug($this->name_en);
        }

        $data = [
            'name_en' => $this->name_en,
            'name_am' => $this->name_am,
            'name_or' => $this->name_or,
            'slug' => $this->slug,
            'description_en' => $this->description_en,
            'description_am' => $this->description_am,
            'description_or' => $this->description_or,
            'category' => $this->category,
            'woreda_id' => $this->woreda_id ?: null,
            'location_name_en' => $this->location_name_en,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'sort_order' => $this->sort_order,
            'is_active' => $this->is_active ? 1 : 0,
        ];

        if ($this->cover_image) {
            $path = $this->cover_image->store('uploads', 'public_uploads');
            $data['cover_image_url'] = '/uploads/' . basename($path);
        }

        if ($this->temp_gallery) {
            $newGallery = $this->gallery_urls;
            foreach ($this->temp_gallery as $photo) {
                $path = $photo->store('uploads', 'public_uploads');
                $newGallery[] = '/uploads/' . basename($path);
            }
            $data['gallery_urls'] = $newGallery;
        }

        if ($this->editingId) {
            TouristSite::findOrFail($this->editingId)->update($data);
        } else {
            TouristSite::create($data);
        }

        $this->showModal = false;
        session()->flash('success', 'Tourism site saved successfully.');
    }

    public function delete($id)
    {
        TouristSite::findOrFail($id)->delete();
        session()->flash('success', 'Tourism site deleted.');
    }

    public function removeGalleryImage($index)
    {
        unset($this->gallery_urls[$index]);
        $this->gallery_urls = array_values($this->gallery_urls);

        if ($this->editingId) {
            TouristSite::findOrFail($this->editingId)->update(['gallery_urls' => $this->gallery_urls]);
        }
    }

    public function render()
    {
        $sites = TouristSite::when($this->search, fn($q) => $q->where('name_en', 'like', '%' . $this->search . '%'))
            ->orderBy('sort_order')
            ->paginate(10);

        $woredas = Woreda::orderBy('name_en')->get();

        return view('livewire.admin.tourism-manager', compact('sites', 'woredas'));
    }
}
