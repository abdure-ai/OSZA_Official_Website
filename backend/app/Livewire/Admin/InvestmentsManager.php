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
    public $activeTab = 'en';
    
    // Multilingual Fields
    public $title_en, $title_am, $title_or;
    public $description_en, $description_am, $description_or;
    public $location, $location_am, $location_or;
    public $incentives_en, $incentives_am, $incentives_or;
    
    // Standard Fields
    public $category, $sector, $budget;
    public $contact_name, $contact_phone, $contact_email;
    public $status = 'Open';
    public $image;
    public $search = '';

    protected $rules = [
        'title_en' => 'required|string|max:255',
        'title_am' => 'nullable|string|max:255',
        'title_or' => 'nullable|string|max:255',
        'description_en' => 'nullable|string',
        'description_am' => 'nullable|string',
        'description_or' => 'nullable|string',
        'location' => 'nullable|string|max:255',
        'location_am' => 'nullable|string|max:255',
        'location_or' => 'nullable|string|max:255',
        'incentives_en' => 'nullable|string',
        'incentives_am' => 'nullable|string',
        'incentives_or' => 'nullable|string',
        'category' => 'nullable|string|max:255',
        'sector' => 'nullable|string|max:255',
        'budget' => 'nullable|numeric|min:0',
        'status' => 'required|string|max:255',
        'contact_name' => 'nullable|string|max:255',
        'contact_phone' => 'nullable|string|max:255',
        'contact_email' => 'nullable|email|max:255',
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
            'location', 'location_am', 'location_or',
            'incentives_en', 'incentives_am', 'incentives_or',
            'category', 'sector', 'budget', 'status',
            'contact_name', 'contact_phone', 'contact_email', 'image'
        ]);
        $this->showModal = true;
    }

    public function openEdit($id)
    {
        $investment = Investment::findOrFail($id);
        $this->editingId = $id;
        $this->activeTab = 'en';
        
        $this->title_en = $investment->title_en;
        $this->title_am = $investment->title_am;
        $this->title_or = $investment->title_or;
        $this->description_en = $investment->description_en;
        $this->description_am = $investment->description_am;
        $this->description_or = $investment->description_or;
        $this->location = $investment->location;
        $this->location_am = $investment->location_am;
        $this->location_or = $investment->location_or;
        $this->incentives_en = $investment->incentives_en;
        $this->incentives_am = $investment->incentives_am;
        $this->incentives_or = $investment->incentives_or;
        
        $this->category = $investment->category;
        $this->sector = $investment->sector;
        $this->budget = $investment->budget;
        $this->status = $investment->status ?? 'Open';
        $this->contact_name = $investment->contact_name;
        $this->contact_phone = $investment->contact_phone;
        $this->contact_email = $investment->contact_email;
        
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
            'location' => $this->location,
            'location_am' => $this->location_am,
            'location_or' => $this->location_or,
            'incentives_en' => $this->incentives_en,
            'incentives_am' => $this->incentives_am,
            'incentives_or' => $this->incentives_or,
            'category' => $this->category,
            'sector' => $this->sector,
            'budget' => $this->budget !== '' && $this->budget !== null ? $this->budget : null,
            'status' => $this->status,
            'contact_name' => $this->contact_name,
            'contact_phone' => $this->contact_phone,
            'contact_email' => $this->contact_email,
        ];
        
        if ($this->image) {
            $path = $this->image->store('uploads', 'public_uploads');
            $data['thumbnail_url'] = '/uploads/' . basename($path);
        }
        
        if ($this->editingId) {
            Investment::findOrFail($this->editingId)->update($data);
        } else {
            Investment::create($data);
        }
        
        $this->showModal = false;
        $this->dispatch('notify', message: 'Investment successfully recorded.', type: 'success');
    }

    public function delete($id)
    {
        Investment::findOrFail($id)->delete();
        $this->dispatch('notify', message: 'Investment deleted.', type: 'info');
    }

    public function render()
    {
        $investments = Investment::when($this->search, fn($q) => $q->where('title_en', 'like', '%' . $this->search . '%'))
            ->orderByDesc('created_at')->paginate(10);
        return view('livewire.admin.investments-manager', ['investments' => $investments]);
    }
}
