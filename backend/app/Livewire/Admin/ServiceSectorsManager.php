<?php

namespace App\Livewire\Admin;

use App\Models\ServiceSector;
use Livewire\Component;
use Livewire\WithPagination;

class ServiceSectorsManager extends Component
{
    use WithPagination;

    public $showModal = false;
    public $editingId = null;
    public $name_en, $name_am, $name_or;
    public $description_en, $description_am, $description_or;
    public $icon_svg, $sort_order = 0, $is_active = true;
    public $search = '';

    protected $rules = [
        'name_en' => 'required|string|max:255',
        'icon_svg' => 'nullable|string',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openCreate()
    {
        $this->reset(['editingId', 'name_en', 'name_am', 'name_or', 'description_en', 'description_am', 'description_or', 'icon_svg']);
        $this->sort_order = 0;
        $this->is_active = true;
        $this->showModal = true;
    }

    public function openEdit($id)
    {
        $record = ServiceSector::findOrFail($id);
        $this->editingId = $id;
        $this->name_en = $record->name_en;
        $this->name_am = $record->name_am;
        $this->name_or = $record->name_or;
        $this->description_en = $record->description_en;
        $this->description_am = $record->description_am;
        $this->description_or = $record->description_or;
        $this->icon_svg = $record->icon_svg;
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
            'description_en' => $this->description_en,
            'description_am' => $this->description_am,
            'description_or' => $this->description_or,
            'icon_svg' => $this->icon_svg,
            'sort_order' => $this->sort_order,
            'is_active' => $this->is_active,
        ];

        if ($this->editingId) {
            ServiceSector::findOrFail($this->editingId)->update($data);
        } else {
            ServiceSector::create($data);
        }
        $this->showModal = false;
        $this->dispatch('notify', message: 'Service Sector saved successfully.', type: 'success');
    }

    public function delete($id)
    {
        ServiceSector::findOrFail($id)->delete();
        $this->dispatch('notify', message: 'Service Sector deleted.', type: 'info');
    }

    public function render()
    {
        $sectors = ServiceSector::when($this->search, fn($q) => $q->where('name_en', 'like', '%' . $this->search . '%'))
            ->orderBy('sort_order')->paginate(10);
        return view('livewire.admin.service-sectors-manager', ['sectors' => $sectors]);
    }
}
