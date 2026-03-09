<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;

class UsersManager extends Component
{
    use WithPagination;

    public $showModal = false, $editingId = null;
    public $name, $email, $password, $password_confirmation;

    protected $rules = ['name' => 'required|string|max:255', 'email' => 'required|email', 'password' => 'nullable|min:6|confirmed'];

    public function openCreate()
    {
        $this->reset(['editingId', 'name', 'email', 'password', 'password_confirmation']);
        $this->showModal = true;
    }
    public function openEdit($id)
    {
        $u = User::findOrFail($id);
        $this->name = $u->name;
        $this->email = $u->email;
        $this->editingId = $id;
        $this->showModal = true;
    }

    public function save()
    {
        $rules = $this->rules;
        if (!$this->editingId) {
            $rules['password'] = 'required|min:6|confirmed';
            $rules['email'] .= '|unique:users';
        }
        $this->validate($rules);
        $data = ['name' => $this->name, 'email' => $this->email];
        if ($this->password)
            $data['password'] = Hash::make($this->password);
        if ($this->editingId) {
            User::findOrFail($this->editingId)->update($data);
        } else {
            User::create($data);
        }
        $this->showModal = false;
        session()->flash('success', 'User saved.');
    }
    public function delete($id)
    {
        if ($id !== auth()->id()) {
            User::findOrFail($id)->delete();
        }
    }
    public function render()
    {
        return view('livewire.admin.users-manager', ['users' => User::orderBy('name')->paginate(15)]);
    }
}
