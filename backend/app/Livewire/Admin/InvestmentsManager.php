<?php

namespace App\Livewire\Admin;

use App\Models\Investment;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class InvestmentsManager extends Component
{
    use WithPagination, WithFileUploads;

    public $showModal = false;
    public $editingId = null;
    public $title_en, $title_am, $title_or;
    public $description_en, $description_am, $description_or;
    public $category, $sector, $image;
    public $search = '';

    protected $rules = [
        'title_en' => 'required|string|max:255',
        'image' => 'nullable|image|max:4096',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openCreate()
    {
        $this->reset(['editingId', 'title_en', 'title_am', 'title_or', 'description_en', 'description_am', 'description_or', 'category', 'sector', 'image']);
        $this->showModal = true;
    }

    public function openEdit($id)
    {
        $investment = Investment::findOrFail($id);
        $this->editingId = $id;
        $this->title_en = $investment->title_en;
        $this->title_am = $investment->title_am;
        $this->title_or = $investment->title_or;
        $this->description_en = $investment->description_en;
        $this->description_am = $investment->description_am;
        $this->description_or = $investment->description_or;
        $this->category = $investment->category;
        $this->sector = $investment->sector;
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
            'category' => $this->category,
            'sector' => $this->sector,
        ];
        if ($this->image) {
            $path = $this->image->store('uploads', 'public_uploads');
            $data['image_url'] = '/uploads/' . basename($path);
        }
        if ($this->editingId) {
            Investment::findOrFail($this->editingId)->update($data);
        } else {
            Investment::create($data);
        }
        $this->showModal = false;
        session()->flash('success', 'Investment saved.');
    }

    public function delete($id)
    {
        Investment::findOrFail($id)->delete();
        session()->flash('success', 'Investment deleted.');
    }

    public function render()
    {
        $investments = Investment::when($this->search, fn($q) => $q->where('title_en', 'like', '%' . $this->search . '%'))
            ->orderByDesc('created_at')->paginate(10);
        return view('livewire.admin.investments-manager', ['investments' => $investments]);
    }
}
