<?php

namespace App\Livewire\Admin;

use App\Models\AboutSection;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class AboutManager extends Component
{
    use WithPagination, WithFileUploads;

    public $showModal = false;
    public $editingId = null;
    public $type = 'general';
    public $title_en, $title_am, $title_or;
    public $content_en, $content_am, $content_or;
    public $image, $image_url, $icon, $sort_order = 0, $is_active = true;

    protected $rules = [
        'type' => 'required|string|max:255',
        'title_en' => 'required|string|max:255',
        'content_en' => 'required|string',
        'image' => 'nullable|image|max:10240',
        'sort_order' => 'integer|min:0',
    ];

    public function openCreate()
    {
        $this->reset(['editingId', 'type', 'title_en', 'title_am', 'title_or', 'content_en', 'content_am', 'content_or', 'image', 'image_url', 'icon', 'sort_order', 'is_active']);
        $this->type = 'general';
        $this->is_active = true;
        $this->showModal = true;
    }

    public function openEdit($id)
    {
        $this->reset(['editingId', 'type', 'title_en', 'title_am', 'title_or', 'content_en', 'content_am', 'content_or', 'image', 'image_url', 'icon', 'sort_order', 'is_active']);
        $s = AboutSection::findOrFail($id);
        foreach (['type', 'title_en', 'title_am', 'title_or', 'content_en', 'content_am', 'content_or', 'image_url', 'icon', 'sort_order'] as $f) {
            $this->$f = $s->$f;
        }
        $this->editingId = $id;
        $this->is_active = (bool) $s->is_active;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();
        $data = [
            'type' => $this->type,
            'title_en' => $this->title_en,
            'title_am' => $this->title_am,
            'title_or' => $this->title_or,
            'content_en' => $this->content_en,
            'content_am' => $this->content_am,
            'content_or' => $this->content_or,
            'icon' => $this->icon,
            'sort_order' => $this->sort_order,
            'is_active' => $this->is_active ? 1 : 0,
        ];

        if ($this->image) {
            $path = $this->image->store('uploads', 'public_uploads');
            $data['image_url'] = '/uploads/' . basename($path);
        } else {
            $data['image_url'] = $this->image_url;
        }

        if ($this->editingId) {
            AboutSection::findOrFail($this->editingId)->update($data);
        } else {
            AboutSection::create($data);
        }

        $this->showModal = false;
        $this->dispatch('notify', message: 'About section saved successfully.', type: 'success');
    }

    public function delete($id)
    {
        AboutSection::findOrFail($id)->delete();
        $this->dispatch('notify', message: 'Section removed.', type: 'info');
    }

    public function render()
    {
        return view('livewire.admin.about-manager', [
            'sections' => AboutSection::orderBy('sort_order')->paginate(10)
        ]);
    }
}
