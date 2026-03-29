<?php

namespace App\Livewire\Admin;

use App\Models\Tender;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class TendersManager extends Component
{
    use WithPagination, WithFileUploads;

    public $showModal = false, $editingId = null, $activeTab = 'en';
    public $title_en, $title_am, $title_or, $description_en, $description_am, $description_or;
    public $ref_number, $status = 'active', $deadline, $document;

    protected $rules = [
        'title_en' => 'required|string|max:255',
        'title_am' => 'nullable|string|max:255',
        'title_or' => 'nullable|string|max:255',
        'description_en' => 'nullable|string',
        'description_am' => 'nullable|string',
        'description_or' => 'nullable|string',
        'ref_number' => 'nullable|string|max:255',
        'status' => 'required|in:active,closed,archived',
        'deadline' => 'nullable|date',
        'document' => 'nullable|file|max:10240'
    ];

    public function openCreate()
    {
        $this->reset(['editingId', 'title_en', 'title_am', 'title_or', 'description_en', 'description_am', 'description_or', 'ref_number', 'deadline', 'document']);
        $this->status = 'active';
        $this->showModal = true;
    }
    public function openEdit($id)
    {
        $t = Tender::findOrFail($id);
        foreach (['title_en', 'title_am', 'title_or', 'description_en', 'description_am', 'description_or', 'ref_number', 'status', 'deadline'] as $f) {
            $this->$f = $t->$f;
        }
        $this->editingId = $id;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();
        $data = [
            'title_en' => $this->title_en,
            'title_am' => $this->title_am,
            'title_or' => $this->title_or,
            'description_en' => $this->description_en,
            'description_am' => $this->description_am,
            'description_or' => $this->description_or,
            'ref_number' => $this->ref_number,
            'status' => $this->status,
            'deadline' => $this->deadline ?: null
        ];

        if ($this->document) {
            $path = $this->document->store('uploads', 'public_uploads');
            $data['file_url'] = '/uploads/' . basename($path);
        }

        if ($this->editingId) {
            Tender::findOrFail($this->editingId)->update($data);
        } else {
            Tender::create($data);
        }

        $this->showModal = false;
        $this->dispatch('notify', message: 'Tender saved successfully.', type: 'success');
    }
    public function delete($id)
    {
        Tender::findOrFail($id)->delete();
        $this->dispatch('notify', message: 'Tender deleted.', type: 'info');
    }
    public function render()
    {
        return view('livewire.admin.tenders-manager', ['tenders' => Tender::orderByDesc('created_at')->paginate(10)]);
    }
}
