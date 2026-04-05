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
    public $cover_image = null;
    public $temp_gallery = [];
    public $cover_image_url = '';
    public $gallery_urls = [];
    public $video_file = null;
    public $video_url = '';
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
        'video_file' => 'nullable|mimetypes:video/mp4,video/webm,video/quicktime,video/x-msvideo|max:204800',
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
            'video_file',
            'video_url',
            'latitude',
            'longitude',
            'sort_order',
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
        $this->video_url = $site->video_url ?? '';
        $this->latitude = $site->latitude;
        $this->longitude = $site->longitude;
        $this->sort_order = $site->sort_order;
        $this->is_active = (bool) $site->is_active;

        // Always start fresh — no pending uploads when opening edit
        $this->cover_image = null;
        $this->temp_gallery = [];
        $this->video_file = null;

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

        // Cover image — only update if a new file was chosen
        if ($this->cover_image) {
            $path = $this->cover_image->store('uploads', 'public_uploads');
            $data['cover_image_url'] = '/uploads/' . basename($path);
        } else {
            $data['cover_image_url'] = $this->cover_image_url;
        }

        // Video — only update if a new file was chosen
        if ($this->video_file) {
            $path = $this->video_file->store('uploads/videos', 'public_uploads');
            $data['video_url'] = '/uploads/videos/' . basename($path);
        } else {
            $data['video_url'] = $this->video_url;
        }

        // Gallery — only append freshly uploaded photos; never re-append existing
        if ($this->temp_gallery) {
            $newGallery = $this->gallery_urls; // existing paths (already stored)
            foreach ($this->temp_gallery as $photo) {
                $path = $photo->store('uploads', 'public_uploads');
                $newGallery[] = '/uploads/' . basename($path);
            }
            $data['gallery_urls'] = $newGallery;
        } else {
            $data['gallery_urls'] = $this->gallery_urls;
        }

        if ($this->editingId) {
            TouristSite::findOrFail($this->editingId)->update($data);
        } else {
            TouristSite::create($data);
        }

        // ── Reset file inputs after save so re-opening doesn't re-upload ──
        $this->cover_image = null;
        $this->temp_gallery = [];
        $this->video_file = null;

        $this->showModal = false;
        $this->dispatch('notify', message: 'Tourism site saved successfully.', type: 'success');
    }

    public function removeVideo()
    {
        $this->video_url = '';
        $this->video_file = null;
        if ($this->editingId) {
            TouristSite::findOrFail($this->editingId)->update(['video_url' => null]);
            $this->dispatch('notify', message: 'Video removed.', type: 'info');
        }
    }

    public function delete($id)
    {
        TouristSite::findOrFail($id)->delete();
        $this->dispatch('notify', message: 'Tourism site deleted.', type: 'info');
    }

    public function removeGalleryImage($index)
    {
        unset($this->gallery_urls[$index]);
        $this->gallery_urls = array_values($this->gallery_urls);

        if ($this->editingId) {
            TouristSite::findOrFail($this->editingId)->update(['gallery_urls' => $this->gallery_urls]);
            $this->dispatch('notify', message: 'Image removed from gallery.', type: 'info');
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
