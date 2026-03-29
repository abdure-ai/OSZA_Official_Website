<?php

namespace App\Livewire\Admin;

use App\Models\AdminMessage;
use Livewire\Component;
use Livewire\WithFileUploads;

class MessageManager extends Component
{
    use WithFileUploads;

    public $name, $name_am, $name_or;
    public $title_position, $title_position_am, $title_position_or;
    public $message_en, $message_am, $message_or;
    public $photo;
    public $photo_url;
    public $is_active = true;
    public $saved = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'name_am' => 'nullable|string|max:255',
        'name_or' => 'nullable|string|max:255',
        'title_position' => 'required|string|max:255',
        'title_position_am' => 'nullable|string|max:255',
        'title_position_or' => 'nullable|string|max:255',
        'message_en' => 'required|string',
        'photo' => 'nullable|image|max:2048',
    ];

    public function mount()
    {
        $msg = AdminMessage::first();
        if ($msg) {
            $this->name = $msg->name;
            $this->name_am = $msg->name_am;
            $this->name_or = $msg->name_or;
            $this->title_position = $msg->title_position;
            $this->title_position_am = $msg->title_position_am;
            $this->title_position_or = $msg->title_position_or;
            $this->message_en = $msg->message_en;
            $this->message_am = $msg->message_am;
            $this->message_or = $msg->message_or;
            $this->photo_url = $msg->photo_url;
            $this->is_active = $msg->is_active;
        }
    }

    public function save()
    {
        $this->validate();
        $data = [
            'name' => $this->name,
            'name_am' => $this->name_am,
            'name_or' => $this->name_or,
            'title_position' => $this->title_position,
            'title_position_am' => $this->title_position_am,
            'title_position_or' => $this->title_position_or,
            'message_en' => $this->message_en,
            'message_am' => $this->message_am,
            'message_or' => $this->message_or,
            'is_active' => $this->is_active,
        ];
        if ($this->photo) {
            $path = $this->photo->store('uploads', 'public_uploads');
            $data['photo_url'] = '/uploads/' . basename($path);
        }
        AdminMessage::updateOrCreate(['id' => 1], $data);
        $this->dispatch('notify', message: 'Message updated successfully!', type: 'success');
        $this->photo_url = $data['photo_url'] ?? $this->photo_url;
    }

    public function render()
    {
        return view('livewire.admin.message-manager');
    }
}
