<?php

namespace App\Livewire\Admin;

use App\Models\DirectoryRecord;
use App\Models\Woreda;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class DirectoryManager extends Component
{
    use WithPagination, WithFileUploads;

    public $showModal = false;
    public $editingId = null;
    public $name_en, $name_am, $name_or;
    public $position_en, $position_am, $position_or;
    public $department_en, $phone, $email, $office_location, $woreda_id, $sort_order = 0;
    public $is_active = true;
    public $photo;
    public $search = '';

    protected $rules = [
        'name_en' => 'required|string|max:255',
        'position_en' => 'required|string|max:255',
        'woreda_id' => 'nullable|exists:woredas,id',
        'photo' => 'nullable|image|max:2048',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openCreate()
    {
        $this->reset(['editingId', 'name_en', 'name_am', 'name_or', 'position_en', 'position_am', 'position_or', 'department_en', 'phone', 'email', 'office_location', 'woreda_id', 'photo']);
        $this->sort_order = 0;
        $this->is_active = true;
        $this->showModal = true;
    }

    public function openEdit($id)
    {
        $record = DirectoryRecord::findOrFail($id);
        $this->editingId = $id;
        $this->name_en = $record->name_en;
        $this->name_am = $record->name_am;
        $this->name_or = $record->name_or;
        $this->position_en = $record->position_en;
        $this->position_am = $record->position_am;
        $this->position_or = $record->position_or;
        $this->department_en = $record->department_en;
        $this->phone = $record->phone;
        $this->email = $record->email;
        $this->office_location = $record->office_location;
        $this->woreda_id = $record->woreda_id;
        $this->sort_order = $record->sort_order;
        $this->is_active = (bool) $record->is_active;
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
            'department_en' => $this->department_en,
            'phone' => $this->phone,
            'email' => $this->email,
            'office_location' => $this->office_location,
            'woreda_id' => $this->woreda_id ?: null,
            'sort_order' => $this->sort_order,
            'is_active' => $this->is_active,
        ];
        if ($this->photo) {
            $path = $this->photo->store('uploads', 'public_uploads');
            $data['photo_url'] = '/uploads/' . basename($path);
        }
        if ($this->editingId) {
            DirectoryRecord::findOrFail($this->editingId)->update($data);
        } else {
            DirectoryRecord::create($data);
        }
        $this->showModal = false;
        session()->flash('success', 'Directory record saved.');
    }

    public function delete($id)
    {
        DirectoryRecord::findOrFail($id)->delete();
        session()->flash('success', 'Record deleted.');
    }

    public function render()
    {
        $records = DirectoryRecord::when($this->search, fn($q) => $q->where('name_en', 'like', '%' . $this->search . '%'))
            ->orderBy('department_en')->orderBy('sort_order')->paginate(10);
        $woredas = Woreda::orderBy('name_en')->get();
        return view('livewire.admin.directory-manager', ['records' => $records, 'woredas' => $woredas]);
    }
}
