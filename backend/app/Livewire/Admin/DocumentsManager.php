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
    public $title_en, $title_am, $title_or, $category, $search = '', $activeTab = 'en';
    public $file, $cover_image;

    protected $rules = [
        'title_en' => 'required|string|max:255',
        'title_am' => 'nullable|string|max:255',
        'title_or' => 'nullable|string|max:255',
        'file' => 'nullable|file|max:20480',
        'cover_image' => 'nullable|image|max:5120'
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function openCreate()
    {
        $this->reset(['editingId', 'title_en', 'title_am', 'title_or', 'category', 'file', 'cover_image', 'activeTab']);
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
        $this->activeTab = 'en';
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();
        
        try {
            $data = [
                'title_en' => $this->title_en, 
                'title_am' => $this->title_am, 
                'title_or' => $this->title_or, 
                'category' => $this->category
            ];

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
                $this->dispatch('notify', message: 'Digital asset updated successfully.', type: 'success');
            } else {
                Document::create($data);
                $this->dispatch('notify', message: 'New asset committed to archive.', type: 'success');
            }
            
            $this->showModal = false;
        } catch (\Exception $e) {
            $this->dispatch('notify', message: 'Failed to save asset: ' . $e->getMessage(), type: 'error');
        }
    }

    public function delete($id)
    {
        try {
            Document::findOrFail($id)->delete();
            $this->dispatch('notify', message: 'Asset removed from archive.', type: 'success');
        } catch (\Exception $e) {
            $this->dispatch('notify', message: 'Failed to delete: ' . $e->getMessage(), type: 'error');
        }
    }
    public function render()
    {
        $docs = Document::when($this->search, fn($q) => $q->where('title_en', 'like', '%' . $this->search . '%'))->orderByDesc('created_at')->paginate(10);
        return view('livewire.admin.documents-manager', ['documents' => $docs]);
    }
}
