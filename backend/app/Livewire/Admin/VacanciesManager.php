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
    public $title_en, $title_am, $title_or, $description_en, $department, $deadline;
    public $is_active = true, $document;

    protected $rules = [
        'title_en' => 'required|string|max:255',
        'title_am' => 'nullable|string|max:255',
        'title_or' => 'nullable|string|max:255',
        'description_en' => 'nullable|string',
        'department' => 'nullable|string|max:255',
        'deadline' => 'nullable|date',
        'is_active' => 'boolean',
        'document' => 'nullable|file|max:10240'
    ];

    public function openCreate()
    {
        $this->reset(['editingId', 'title_en', 'title_am', 'title_or', 'description_en', 'department', 'deadline', 'document']);
        $this->is_active = true;
        $this->showModal = true;
    }
    public function openEdit($id)
    {
        $v = Vacancy::findOrFail($id);
        foreach (['title_en', 'title_am', 'title_or', 'description_en', 'department', 'deadline'] as $f) {
            $this->$f = $v->$f;
        }
        $this->is_active = (bool) $v->is_active;
        $this->editingId = $id;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();
        $data = ['title_en' => $this->title_en, 'title_am' => $this->title_am, 'title_or' => $this->title_or, 'description_en' => $this->description_en, 'department' => $this->department, 'deadline' => $this->deadline, 'is_active' => $this->is_active ? 1 : 0];
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
