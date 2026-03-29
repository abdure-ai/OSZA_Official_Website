<?php

namespace App\Livewire\Admin;

use App\Models\HeroSlide;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class HeroManager extends Component
{
    use WithPagination, WithFileUploads;

    public $showModal = false;
    public $editingId = null;
    public $title_en, $title_am, $title_or;
    public $subtitle_en, $subtitle_am, $subtitle_or;
    public $cta_text, $cta_text_am, $cta_text_or, $cta_url, $sort_order = 0, $is_active = true;
    public $media, $media_type = 'image', $page = 'home';

    protected $rules = [
        'page' => 'required|in:home,tourism',
        'title_en' => 'required|string|max:255',
        'media' => 'nullable|mimes:jpg,jpeg,png,webp,mp4,mov|max:204800',
        'sort_order' => 'integer|min:0',
        'media_type' => 'required|in:image,video',
    ];

    public function openCreate()
    {
        $this->reset(['editingId', 'page', 'title_en', 'title_am', 'title_or', 'subtitle_en', 'subtitle_am', 'subtitle_or', 'cta_text', 'cta_text_am', 'cta_text_or', 'cta_url', 'media']);
        $this->page = 'home';
        $this->media_type = 'image';
        $this->sort_order = 0;
        $this->is_active = true;
        $this->showModal = true;
    }

    public function openEdit($id)
    {
        $this->reset(['editingId', 'page', 'title_en', 'title_am', 'title_or', 'subtitle_en', 'subtitle_am', 'subtitle_or', 'cta_text', 'cta_text_am', 'cta_text_or', 'cta_url', 'media']);
        $s = HeroSlide::findOrFail($id);
        foreach (['page', 'title_en', 'title_am', 'title_or', 'subtitle_en', 'subtitle_am', 'subtitle_or', 'cta_text', 'cta_text_am', 'cta_text_or', 'cta_url', 'sort_order', 'media_type'] as $f) {
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
            'page' => $this->page,
            'title_en' => $this->title_en,
            'title_am' => $this->title_am,
            'title_or' => $this->title_or,
            'subtitle_en' => $this->subtitle_en,
            'subtitle_am' => $this->subtitle_am,
            'subtitle_or' => $this->subtitle_or,
            'cta_text' => $this->cta_text,
            'cta_text_am' => $this->cta_text_am,
            'cta_text_or' => $this->cta_text_or,
            'cta_url' => $this->cta_url,
            'sort_order' => $this->sort_order,
            'is_active' => $this->is_active ? 1 : 0,
            'media_type' => $this->media_type
        ];
        if ($this->media) {
            $ext = $this->media->getClientOriginalExtension();
            $path = $this->media->store('uploads', 'public_uploads');
            $data['media_url'] = '/uploads/' . basename($path);
            $data['media_type'] = in_array(strtolower($ext), ['mp4', 'mov']) ? 'video' : 'image';
        }
        if ($this->editingId) {
            HeroSlide::findOrFail($this->editingId)->update($data);
        } else {
            HeroSlide::create($data);
        }
        $this->showModal = false;
        $this->dispatch('notify', message: 'Slide saved successfully.', type: 'success');
    }

    public function delete($id)
    {
        HeroSlide::findOrFail($id)->delete();
        $this->dispatch('notify', message: 'Slide removed.', type: 'info');
    }

    public function render()
    {
        return view('livewire.admin.hero-manager', ['slides' => HeroSlide::orderBy('sort_order')->paginate(10)]);
    }
}
