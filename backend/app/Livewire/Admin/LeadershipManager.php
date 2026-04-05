<?php

namespace App\Livewire\Admin;

use App\Models\Leadership;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class LeadershipManager extends Component
{
    use WithPagination, WithFileUploads;

    public $showModal = false;
    public $editingId = null;

    // Personnel fields
    public $name_en, $name_am, $name_or;
    public $position_en, $position_am, $position_or;
    public $bio_en, $bio_am, $bio_or;
    public $email, $phone;
    public $office_location_en, $office_location_am, $office_location_or;
    public $rank_order = 0;
    public $parent_id, $hierarchy_level = 1;
    public $photo, $photo_url;

    protected $rules = [
        'name_en' => 'required|string|max:255',
        'position_en' => 'required|string|max:255',
        'email' => 'nullable|email',
        'hierarchy_level' => 'required|integer|min:1|max:5',
    ];

    public function openCreate()
    {
        $this->reset(['editingId', 'name_en', 'name_am', 'name_or', 'position_en', 'position_am', 'position_or', 'bio_en', 'bio_am', 'bio_or', 'email', 'phone', 'office_location_en', 'office_location_am', 'office_location_or', 'rank_order', 'parent_id', 'hierarchy_level', 'photo', 'photo_url']);
        $this->hierarchy_level = 1;
        $this->rank_order = 0;
        $this->showModal = true;
    }

    public function openEdit($id)
    {
        $this->reset(['editingId', 'name_en', 'name_am', 'name_or', 'position_en', 'position_am', 'position_or', 'bio_en', 'bio_am', 'bio_or', 'email', 'phone', 'office_location_en', 'office_location_am', 'office_location_or', 'rank_order', 'parent_id', 'hierarchy_level', 'photo', 'photo_url']);
        $l = Leadership::findOrFail($id);
        $this->editingId = $l->id;
        foreach (['name_en', 'name_am', 'name_or', 'position_en', 'position_am', 'position_or', 'bio_en', 'bio_am', 'bio_or', 'email', 'phone', 'office_location_en', 'office_location_am', 'office_location_or', 'rank_order', 'parent_id', 'hierarchy_level', 'photo_url'] as $f) {
            $this->$f = $l->$f;
        }
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();
        $data = [
            'name_en' => $this->name_en,
            'name_am' => $this->name_am,
            'name_or' => $this->name_or,
            'position_en' => $this->position_en,
            'position_am' => $this->position_am,
            'position_or' => $this->position_or,
            'bio_en' => $this->bio_en,
            'bio_am' => $this->bio_am,
            'bio_or' => $this->bio_or,
            'email' => $this->email,
            'phone' => $this->phone,
            'office_location_en' => $this->office_location_en,
            'office_location_am' => $this->office_location_am,
            'office_location_or' => $this->office_location_or,
            'rank_order' => $this->rank_order,
            'parent_id' => $this->parent_id ?: null,
            'hierarchy_level' => $this->hierarchy_level,
        ];

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
        $this->dispatch('notify', message: 'Official record saved.', type: 'success');
    }

    public function delete($id)
    {
        Leadership::findOrFail($id)->delete();
        $this->dispatch('notify', message: 'Official record removed.', type: 'info');
    }

    public function render()
    {
        return view('livewire.admin.leadership-manager', [
            'leaders' => Leadership::with('parent')->orderBy('hierarchy_level')->orderBy('rank_order')->paginate(12),
            'potentialParents' => Leadership::where('id', '!=', $this->editingId)->orderBy('hierarchy_level')->get()
        ]);
    }
}
