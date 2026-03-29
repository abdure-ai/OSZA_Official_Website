<?php

namespace App\Livewire\Admin;

use App\Models\Album;
use App\Models\Woreda;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class AlbumsManager extends Component
{
    use WithPagination, WithFileUploads;

    public $showModal = false;
    public $editingId = null;
    public $title_en, $title_am, $title_or;
    public $description_en, $description_am, $description_or;
    public $category, $woreda_id, $sort_order = 0, $is_active = true;
    public $cover_image;

    protected $rules = [
        'title_en' => 'required|string|max:255',
        'title_am' => 'nullable|string|max:255',
        'title_or' => 'nullable|string|max:255',
        'description_en' => 'nullable|string',
        'description_am' => 'nullable|string',
        'description_or' => 'nullable|string',
        'category' => 'nullable|string',
        'woreda_id' => 'nullable|exists:woredas,id',
        'sort_order' => 'integer|min:0',
        'is_active' => 'boolean',
        'cover_image' => 'nullable|image|max:8192',
    ];

    public function openCreate()
    {
        $this->reset(['editingId', 'title_en', 'title_am', 'title_or', 'description_en', 'description_am', 'description_or', 'category', 'woreda_id', 'cover_image']);
        $this->sort_order = 0;
        $this->is_active = true;
        $this->showModal = true;
    }

    public function openEdit($id)
    {
        $album = Album::findOrFail($id);
        $this->editingId = $id;
        $this->title_en = $album->title_en;
        $this->title_am = $album->title_am;
        $this->title_or = $album->title_or;
        $this->description_en = $album->description_en;
        $this->description_am = $album->description_am;
        $this->description_or = $album->description_or;
        $this->category = $album->category;
        $this->woreda_id = $album->woreda_id;
        $this->sort_order = $album->sort_order;
        $this->is_active = (bool) $album->is_active;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();
        $data = [
            'title_en' => $this->title_en,
            'title_am' => $this->title_am,
            'title_or' => $this->title_or,
            'description_en' => $this->description_en,
            'description_am' => $this->description_am,
            'description_or' => $this->description_or,
            'category' => $this->category,
            'woreda_id' => $this->woreda_id ?: null,
            'sort_order' => $this->sort_order,
            'is_active' => $this->is_active ? 1 : 0
        ];

        if ($this->cover_image) {
            $path = $this->cover_image->store('uploads', 'public_uploads');
            $data['cover_image_url'] = '/uploads/' . basename($path);
        }

        if ($this->editingId) {
            Album::findOrFail($this->editingId)->update($data);
        } else {
            if (!isset($data['cover_image_url'])) {
                $this->addError('cover_image', 'Cover image is required for new albums.');
                return;
            }
            Album::create($data);
        }

        $this->showModal = false;
        $this->dispatch('notify', message: 'Album saved successfully.', type: 'success');
    }

    public function delete($id)
    {
        Album::findOrFail($id)->delete();
        $this->dispatch('notify', message: 'Album removed.', type: 'info');
    }

    public function render()
    {
        $items = Album::orderBy('sort_order')->paginate(10);
        $woredas = Woreda::orderBy('name_en')->get();
        return view('livewire.admin.albums-manager', compact('items', 'woredas'));
    }
}
