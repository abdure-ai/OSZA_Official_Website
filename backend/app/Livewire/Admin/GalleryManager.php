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
    public $title, $category, $woreda_id, $sort_order = 0, $is_active = true;
    public $image;
    public $filterWoreda = '';

    protected $rules = [
        'title' => 'nullable|string|max:255',
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
        $this->reset(['editingId', 'title', 'category', 'woreda_id', 'image']);
        $this->sort_order = 0;
        $this->is_active = true;
        $this->showModal = true;
    }

    public function openEdit($id)
    {
        $item = GalleryItem::findOrFail($id);
        $this->editingId = $id;
        $this->title = $item->title;
        $this->category = $item->category;
        $this->woreda_id = $item->woreda_id;
        $this->sort_order = $item->sort_order;
        $this->is_active = (bool) $item->is_active;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();
        $data = ['title' => $this->title, 'category' => $this->category, 'woreda_id' => $this->woreda_id ?: null, 'sort_order' => $this->sort_order, 'is_active' => $this->is_active ? 1 : 0];
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
        session()->flash('success', 'Gallery item saved.');
    }

    public function delete($id)
    {
        GalleryItem::findOrFail($id)->delete();
        session()->flash('success', 'Item deleted.');
    }

    public function render()
    {
        $items = GalleryItem::when($this->filterWoreda, fn($q) => $q->where('woreda_id', $this->filterWoreda))->orderBy('sort_order')->paginate(12);
        $woredas = Woreda::orderBy('name_en')->get();
        return view('livewire.admin.gallery-manager', compact('items', 'woredas'));
    }
}
