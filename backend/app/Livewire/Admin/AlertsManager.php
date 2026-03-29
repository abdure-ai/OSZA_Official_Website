<?php

namespace App\Livewire\Admin;

use App\Models\EmergencyAlert;
use Livewire\Component;
use Livewire\WithPagination;

class AlertsManager extends Component
{
    use WithPagination;

    public $showModal = false;
    public $editingId = null;
    public $message_en, $message_am, $message_or;
    public $severity = 'info';
    public $is_active = true;
    public $expires_at;

    protected $rules = [
        'message_en' => 'required|string',
        'severity' => 'required|in:info,warning,danger',
        'expires_at' => 'nullable|date',
    ];

    public function openCreate()
    {
        $this->reset(['editingId', 'message_en', 'message_am', 'message_or', 'severity', 'is_active', 'expires_at']);
        $this->severity = 'info';
        $this->is_active = true;
        $this->showModal = true;
    }

    public function openEdit($id)
    {
        $alert = EmergencyAlert::findOrFail($id);
        $this->editingId = $id;
        $this->message_en = $alert->message_en;
        $this->message_am = $alert->message_am;
        $this->message_or = $alert->message_or;
        $this->severity = $alert->severity;
        $this->is_active = $alert->is_active;
        $this->expires_at = $alert->expires_at ? $alert->expires_at->format('Y-m-d\TH:i') : null;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();
        $data = [
            'message_en' => $this->message_en,
            'message_am' => $this->message_am,
            'message_or' => $this->message_or,
            'severity' => $this->severity,
            'is_active' => $this->is_active,
            'expires_at' => $this->expires_at,
        ];
        if ($this->editingId) {
            EmergencyAlert::findOrFail($this->editingId)->update($data);
        } else {
            EmergencyAlert::create($data);
        }
        $this->showModal = false;
        $this->dispatch('notify', message: 'Alert saved successfully.', type: 'success');
    }

    public function delete($id)
    {
        EmergencyAlert::findOrFail($id)->delete();
        $this->dispatch('notify', message: 'Alert deleted.', type: 'info');
    }

    public function render()
    {
        $alerts = EmergencyAlert::orderByDesc('created_at')->paginate(10);
        return view('livewire.admin.alerts-manager', ['alerts' => $alerts]);
    }
}
