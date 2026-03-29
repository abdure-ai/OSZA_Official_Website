<?php

namespace App\Livewire\Admin;

use App\Models\Leadership;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class LeadershipManager extends Component
{
    use WithPagination, WithFileUploads;

    public $showModal = false, $editingId = null;
    public $name_en, $name_am, $name_or, $title_en, $title_am, $title_or, $rank_order = 0;
    public $photo;

    protected $rules = ['name_en' => 'required|string|max:255', 'title_en' => 'required|string|max:255'];

    public function openCreate()
    {
        $this->reset(['editingId', 'name_en', 'name_am', 'name_or', 'title_en', 'title_am', 'title_or', 'photo']);
        $this->rank_order = 0;
        $this->showModal = true;
    }
    public function openEdit($id)
    {
        $l = Leadership::findOrFail($id);
        foreach (['name_en', 'name_am', 'name_or', 'title_en', 'title_am', 'title_or', 'rank_order'] as $f) {
            $this->$f = $l->$f;
        }
        $this->editingId = $id;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();
        $data = ['name_en' => $this->name_en, 'name_am' => $this->name_am, 'name_or' => $this->name_or, 'title_en' => $this->title_en, 'title_am' => $this->title_am, 'title_or' => $this->title_or, 'rank_order' => $this->rank_order];
        if ($this->photo) {
            $path = $this->photo->store('uploads', 'public_uploads');
            $data['photo_url'] = '/uploads/' . basename($path);
        }
        if ($this->editingId) {
            Leadership::findOrFail($this->editingId)->update($data);
        } else {
            Leadership::create($data);
        }
        $this->showModal = false;
        $this->dispatch('notify', message: 'Leader saved successfully.', type: 'success');
    }
    public function delete($id)
    {
        Leadership::findOrFail($id)->delete();
        $this->dispatch('notify', message: 'Leader removed.', type: 'info');
    }
    public function render()
    {
        return view('livewire.admin.leadership-manager', ['leaders' => Leadership::orderBy('rank_order')->paginate(12)]);
    }
}
