<?php

namespace App\Livewire\Admin;

use App\Models\Vacancy;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class VacanciesManager extends Component
{
    use WithPagination, WithFileUploads;

    public $showModal = false, $editingId = null;
    public $title_en, $title_am, $title_or;
    public $description_en, $description_am, $description_or;
    public $requirements_en, $requirements_am, $requirements_or;
    public $department, $deadline, $vacancy_type;
    public $location_en, $location_am, $location_or;
    public $is_active = true, $document, $document_url;

    protected $rules = [
        'title_en' => 'required|string|max:255',
        'title_am' => 'nullable|string|max:255',
        'title_or' => 'nullable|string|max:255',
        'description_en' => 'nullable|string',
        'description_am' => 'nullable|string',
        'description_or' => 'nullable|string',
        'requirements_en' => 'nullable|string',
        'requirements_am' => 'nullable|string',
        'requirements_or' => 'nullable|string',
        'department' => 'nullable|string|max:255',
        'vacancy_type' => 'nullable|string|max:255',
        'location_en' => 'nullable|string|max:255',
        'location_am' => 'nullable|string|max:255',
        'location_or' => 'nullable|string|max:255',
        'deadline' => 'nullable|date',
        'is_active' => 'boolean',
        'document' => 'nullable|file|max:10240'
    ];

    public function openCreate()
    {
        $this->editingId = null;
        $this->title_en = $this->title_am = $this->title_or = null;
        $this->description_en = $this->description_am = $this->description_or = null;
        $this->requirements_en = $this->requirements_am = $this->requirements_or = null;
        $this->location_en = $this->location_am = $this->location_or = null;
        $this->department = $this->deadline = $this->vacancy_type = $this->document = $this->document_url = null;
        $this->is_active = true;
        $this->showModal = true;
    }
    public function openEdit($id)
    {
        $v = Vacancy::findOrFail($id);
        $fields = [
            'title_en', 'title_am', 'title_or',
            'description_en', 'description_am', 'description_or',
            'requirements_en', 'requirements_am', 'requirements_or',
            'department', 'deadline', 'vacancy_type',
            'location_en', 'location_am', 'location_or'
        ];
        foreach ($fields as $f) {
            $this->$f = $v->$f ?? null;
        }
        $this->document_url = $v->document_url;
        $this->is_active = (bool) $v->is_active;
        $this->editingId = $id;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();
        $data = [
            'title_en' => $this->title_en, 'title_am' => $this->title_am, 'title_or' => $this->title_or,
            'description_en' => $this->description_en, 'description_am' => $this->description_am, 'description_or' => $this->description_or,
            'requirements_en' => $this->requirements_en, 'requirements_am' => $this->requirements_am, 'requirements_or' => $this->requirements_or,
            'department' => $this->department, 'deadline' => $this->deadline, 'vacancy_type' => $this->vacancy_type,
            'location_en' => $this->location_en, 'location_am' => $this->location_am, 'location_or' => $this->location_or,
            'is_active' => $this->is_active ? 1 : 0
        ];
        if ($this->document) {
            $path = $this->document->store('uploads', 'public_uploads');
            $data['document_url'] = '/uploads/' . basename($path);
        }
        if ($this->editingId) {
            Vacancy::findOrFail($this->editingId)->update($data);
        } else {
            Vacancy::create($data);
        }
        $this->showModal = false;
        $this->dispatch('notify', message: 'Vacancy saved successfully.', type: 'success');
    }
    public function delete($id)
    {
        Vacancy::findOrFail($id)->delete();
        $this->dispatch('notify', message: 'Vacancy deleted.', type: 'info');
    }
    public function render()
    {
        return view('livewire.admin.vacancies-manager', ['vacancies' => Vacancy::orderByDesc('created_at')->paginate(10)]);
    }
}
