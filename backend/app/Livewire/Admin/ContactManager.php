<?php

namespace App\Livewire\Admin;

use App\Models\ContactMessage;
use Livewire\Component;
use Livewire\WithPagination;

class ContactManager extends Component
{
    use WithPagination;

    public $viewingMessage = null;

    public function viewMessage($id)
    {
        $this->viewingMessage = ContactMessage::findOrFail($id);
    }

    public function closeView()
    {
        $this->viewingMessage = null;
    }

    public function delete($id)
    {
        ContactMessage::findOrFail($id)->delete();
        $this->closeView();
        $this->dispatch('notify', message: 'Message deleted.', type: 'info');
    }

    public function render()
    {
        return view('livewire.admin.contact-manager', ['messages' => ContactMessage::orderByDesc('created_at')->paginate(15)]);
    }
}
