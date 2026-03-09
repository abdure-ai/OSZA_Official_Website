<?php

namespace App\Livewire\Admin;

use App\Models\Document;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class DocumentsManager extends Component
{
    use WithPagination, WithFileUploads;

    public $showModal = false, $editingId = null;
    public $title_en, $title_am, $title_or, $category, $search = '';
    public $file, $cover_image;

    protected $rules = [
        'title_en' => 'required|string|max:255',
        'file' => 'nullable|file|max:20480',
        'cover_image' => 'nullable|image|max:5120'
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function openCreate()
    {
        $this->reset(['editingId', 'title_en', 'title_am', 'title_or', 'category', 'file', 'cover_image']);
        $this->showModal = true;
    }
    public function openEdit($id)
    {
        $d = Document::findOrFail($id);
        $this->title_en = $d->title_en;
        $this->title_am = $d->title_am;
        $this->title_or = $d->title_or;
        $this->category = $d->category;
        $this->editingId = $id;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();
        $data = ['title_en' => $this->title_en, 'title_am' => $this->title_am, 'title_or' => $this->title_or, 'category' => $this->category];
        if ($this->file) {
            $path = $this->file->store('uploads/documents', 'public_uploads');
            $data['file_url'] = '/uploads/documents/' . basename($path);
        }
        if ($this->cover_image) {
            $path = $this->cover_image->store('uploads/covers', 'public_uploads');
            $data['cover_image_url'] = '/uploads/covers/' . basename($path);
        }
        if ($this->editingId) {
            Document::findOrFail($this->editingId)->update($data);
        } else {
            Document::create($data);
        }
        $this->showModal = false;
        session()->flash('success', 'Document saved.');
    }
    public function delete($id)
    {
        Document::findOrFail($id)->delete();
    }
    public function render()
    {
        $docs = Document::when($this->search, fn($q) => $q->where('title_en', 'like', '%' . $this->search . '%'))->orderByDesc('created_at')->paginate(10);
        return view('livewire.admin.documents-manager', ['documents' => $docs]);
    }
}
