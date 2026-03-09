<?php

namespace App\Livewire\Admin;

use App\Models\Project;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class ProjectsManager extends Component
{
    use WithPagination, WithFileUploads;

    public $showModal = false;
    public $editingId = null;
    public $title_en, $title_am, $title_or;
    public $description_en, $description_am, $description_or;
    public $status = 'ongoing';
    public $budget, $location_en, $start_date, $end_date;
    public $image;
    public $search = '';

    protected $rules = [
        'title_en' => 'required|string|max:255',
        'status' => 'required|in:planned,ongoing,completed',
        'image' => 'nullable|image|max:4096',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openCreate()
    {
        $this->reset(['editingId', 'title_en', 'title_am', 'title_or', 'description_en', 'description_am', 'description_or', 'status', 'budget', 'location_en', 'start_date', 'end_date', 'image']);
        $this->status = 'ongoing';
        $this->showModal = true;
    }

    public function openEdit($id)
    {
        $project = Project::findOrFail($id);
        $this->editingId = $id;
        $this->title_en = $project->title_en;
        $this->title_am = $project->title_am;
        $this->title_or = $project->title_or;
        $this->description_en = $project->description_en;
        $this->description_am = $project->description_am;
        $this->description_or = $project->description_or;
        $this->status = $project->status;
        $this->budget = $project->budget;
        $this->location_en = $project->location_en;
        $this->start_date = $project->start_date?->format('Y-m-d');
        $this->end_date = $project->end_date?->format('Y-m-d');
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
            'status' => $this->status,
            'budget' => $this->budget,
            'location_en' => $this->location_en,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ];
        if ($this->image) {
            $path = $this->image->store('uploads', 'public_uploads');
            $data['cover_image_url'] = '/uploads/' . basename($path);
        }
        if ($this->editingId) {
            Project::findOrFail($this->editingId)->update($data);
        } else {
            Project::create($data);
        }
        $this->showModal = false;
        session()->flash('success', 'Project saved.');
    }

    public function delete($id)
    {
        Project::findOrFail($id)->delete();
        session()->flash('success', 'Project deleted.');
    }

    public function render()
    {
        $projects = Project::when($this->search, fn($q) => $q->where('title_en', 'like', '%' . $this->search . '%'))
            ->orderByDesc('created_at')->paginate(10);
        return view('livewire.admin.projects-manager', ['projects' => $projects]);
    }
}
