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
    public $activeTab = 'en';

    // Multilingual Fields
    public $title_en, $title_am, $title_or;
    public $description_en, $description_am, $description_or;
    public $location_en, $location_am, $location_or;
    public $contractor, $contractor_am, $contractor_or;
    public $funding_source, $funding_source_am, $funding_source_or;
    
    // Standard Fields
    public $status = 'ongoing';
    public $budget, $progress, $start_date, $end_date, $is_published = true;
    public $image;
    public $search = '';

    protected $rules = [
        'title_en' => 'required|string|max:255',
        'title_am' => 'nullable|string|max:255',
        'title_or' => 'nullable|string|max:255',
        'description_en' => 'nullable|string',
        'location_en' => 'nullable|string|max:255',
        'contractor' => 'nullable|string|max:255',
        'funding_source' => 'nullable|string|max:255',
        'status' => 'required|in:planned,ongoing,completed',
        'budget' => 'nullable|numeric|min:0',
        'progress' => 'nullable|numeric|min:0|max:100',
        'start_date' => 'nullable|date',
        'end_date' => 'nullable|date|after_or_equal:start_date',
        'image' => 'nullable|image|max:4096',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openCreate()
    {
        $this->reset([
            'editingId', 'activeTab',
            'title_en', 'title_am', 'title_or', 
            'description_en', 'description_am', 'description_or', 
            'location_en', 'location_am', 'location_or', 
            'contractor', 'contractor_am', 'contractor_or',
            'funding_source', 'funding_source_am', 'funding_source_or',
            'status', 'budget', 'progress', 'start_date', 'end_date', 'is_published', 'image'
        ]);
        $this->status = 'ongoing';
        $this->is_published = true;
        $this->showModal = true;
    }

    public function openEdit($id)
    {
        $project = Project::findOrFail($id);
        $this->editingId = $id;
        $this->activeTab = 'en';
        
        $this->title_en = $project->title_en;
        $this->title_am = $project->title_am;
        $this->title_or = $project->title_or;
        $this->description_en = $project->description_en;
        $this->description_am = $project->description_am;
        $this->description_or = $project->description_or;
        $this->location_en = $project->location_en;
        $this->location_am = $project->location_am;
        $this->location_or = $project->location_or;
        $this->contractor = $project->contractor;
        $this->contractor_am = $project->contractor_am;
        $this->contractor_or = $project->contractor_or;
        $this->funding_source = $project->funding_source;
        $this->funding_source_am = $project->funding_source_am;
        $this->funding_source_or = $project->funding_source_or;
        
        $this->status = $project->status;
        $this->budget = $project->budget;
        $this->progress = $project->progress;
        $this->start_date = $project->start_date?->format('Y-m-d');
        $this->end_date = $project->end_date?->format('Y-m-d');
        $this->is_published = $project->is_published;
        
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
            'location_en' => $this->location_en,
            'location_am' => $this->location_am,
            'location_or' => $this->location_or,
            'contractor' => $this->contractor,
            'contractor_am' => $this->contractor_am,
            'contractor_or' => $this->contractor_or,
            'funding_source' => $this->funding_source,
            'funding_source_am' => $this->funding_source_am,
            'funding_source_or' => $this->funding_source_or,
            'status' => $this->status,
            'budget' => $this->budget !== '' && $this->budget !== null ? $this->budget : null,
            'progress' => $this->progress !== '' && $this->progress !== null ? $this->progress : 0,
            'start_date' => $this->start_date ?: null,
            'end_date' => $this->end_date ?: null,
            'is_published' => $this->is_published ? true : false,
        ];
        
        if ($this->image) {
            $path = $this->image->store('uploads', 'public_uploads');
            $data['cover_image_url'] = '/uploads/' . basename($path);
        }
        
        $message = $this->editingId ? 'Project updated successfully!' : 'Project registered successfully!';
        
        if ($this->editingId) {
            Project::findOrFail($this->editingId)->update($data);
        } else {
            Project::create($data);
        }
        
        $this->showModal = false;
        $this->dispatch('notify', message: $message, type: 'success');
    }

    public function delete($id)
    {
        Project::findOrFail($id)->delete();
        $this->dispatch('notify', message: 'Project successfully deleted.', type: 'info');
    }

    public function render()
    {
        $projects = Project::when($this->search, fn($q) => $q->where('title_en', 'like', '%' . $this->search . '%'))
            ->orderByDesc('created_at')->paginate(10);
        return view('livewire.admin.projects-manager', ['projects' => $projects]);
    }
}
