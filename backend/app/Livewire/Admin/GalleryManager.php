<?php

namespace App\Livewire\Admin;

use App\Models\GalleryItem;
use App\Models\Woreda;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class GalleryManager extends Component
{
    use WithPagination, WithFileUploads;

    public $showModal = false;
    public $editingId = null;
    public $title_en, $title_am, $title_or, $category, $woreda_id, $sort_order = 0, $is_active = true;
    public $image;
    public $filterWoreda = '';

    protected $rules = [
        'title_en' => 'required|string|max:255',
        'title_am' => 'nullable|string|max:255',
        'title_or' => 'nullable|string|max:255',
        'category' => 'nullable|string',
        'woreda_id' => 'nullable|exists:woredas,id',
        'sort_order' => 'integer|min:0',
        'is_active' => 'boolean',
        'image' => 'nullable|image|max:8192',
    ];

    public function updatingFilterWoreda()
    {
        $this->resetPage();
    }

    public function openCreate()
    {
        $this->reset(['editingId', 'title_en', 'title_am', 'title_or', 'category', 'woreda_id', 'image']);
        $this->sort_order = 0;
        $this->is_active = true;
        $this->showModal = true;
    }

    public function openEdit($id)
    {
        $item = GalleryItem::findOrFail($id);
        $this->editingId = $id;
        $this->title_en = $item->title; // Legacy title
        $this->title_am = $item->title_am;
        $this->title_or = $item->title_or;
        $this->category = $item->category;
        $this->woreda_id = $item->woreda_id;
        $this->sort_order = $item->sort_order;
        $this->is_active = (bool) $item->is_active;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();
        $data = [
            'title' => $this->title_en,
            'title_am' => $this->title_am,
            'title_or' => $this->title_or,
            'category' => $this->category,
            'woreda_id' => $this->woreda_id ?: null,
            'sort_order' => $this->sort_order,
            'is_active' => $this->is_active ? 1 : 0
        ];
        if ($this->image) {
            $path = $this->image->store('uploads', 'public_uploads');
            $data['image_url'] = '/uploads/' . basename($path);
        }
        if ($this->editingId) {
            GalleryItem::findOrFail($this->editingId)->update($data);
        } else {
            if (!isset($data['image_url'])) {
                $this->addError('image', 'Image is required.');
                return;
            }
            GalleryItem::create($data);
        }
        $this->showModal = false;
        $this->dispatch('notify', message: 'Gallery item saved successfully.', type: 'success');
    }

    public function delete($id)
    {
        GalleryItem::findOrFail($id)->delete();
        $this->dispatch('notify', message: 'Gallery item removed.', type: 'info');
    }

    public function render()
    {
        $items = GalleryItem::when($this->filterWoreda, fn($q) => $q->where('woreda_id', $this->filterWoreda))->orderBy('sort_order')->paginate(12);
        $woredas = Woreda::orderBy('name_en')->get();
        return view('livewire.admin.gallery-manager', compact('items', 'woredas'));
    }
}
