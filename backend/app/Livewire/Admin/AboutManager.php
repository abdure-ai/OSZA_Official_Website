<?php

namespace App\Livewire\Admin;

use App\Models\AboutSection;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class AboutManager extends Component
{
    use WithPagination, WithFileUploads;

    public $activeTab = 'history'; // history, mission_vision, objectives
    public $showModal = false;
    public $editingId = null;

    // Form fields
    public $type = 'history';
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

    public function setTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetErrorBag();
        // If history, mission, or vision, we can preload them if they exist
        if (in_array($tab, ['history', 'mission', 'vision'])) {
            $section = AboutSection::where('type', $tab)->first();
            if ($section) {
                $this->loadSection($section->id);
            } else {
                $this->resetForm($tab);
            }
        }
    }

    public function loadSection($id)
    {
        $this->resetForm();
        $s = AboutSection::findOrFail($id);
        $this->editingId = $s->id;
        $this->type = $s->type;
        $this->title_en = $s->title_en;
        $this->title_am = $s->title_am;
        $this->title_or = $s->title_or;
        $this->content_en = $s->content_en;
        $this->content_am = $s->content_am;
        $this->content_or = $s->content_or;
        $this->image_url = $s->image_url;
        $this->icon = $s->icon;
        $this->sort_order = $s->sort_order;
        $this->is_active = (bool) $s->is_active;
    }

    public function resetForm($type = 'objective')
    {
        $this->reset(['editingId', 'title_en', 'title_am', 'title_or', 'content_en', 'content_am', 'content_or', 'image', 'image_url', 'icon', 'sort_order', 'is_active']);
        $this->type = $type;
        $this->is_active = true;
    }

    public function openCreateObjective()
    {
        $this->resetForm('objective');
        $this->showModal = true;
    }

    public function editObjective($id)
    {
        $this->loadSection($id);
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
        }

        if ($this->editingId) {
            AboutSection::findOrFail($this->editingId)->update($data);
        } else {
            AboutSection::create($data);
        }

        $this->showModal = false;
        if (in_array($this->type, ['objective', 'general'])) {
            $this->resetForm('objective');
        } else {
            // Keep the loaded data for static types
            $section = AboutSection::where('type', $this->type)->first();
            if ($section)
                $this->loadSection($section->id);
        }

        $this->dispatch('notify', message: 'About content updated successfully.', type: 'success');
        session()->flash('message', 'Content saved successfully!');
    }

    public function delete($id)
    {
        AboutSection::findOrFail($id)->delete();
        $this->dispatch('notify', message: 'Entry removed.', type: 'info');
    }

    public function mount()
    {
        $this->setTab('history');
    }

    public function render()
    {
        return view('livewire.admin.about-manager', [
            'objectives' => AboutSection::whereIn('type', ['objective', 'general'])->orderBy('sort_order')->paginate(10)
        ]);
    }
}
