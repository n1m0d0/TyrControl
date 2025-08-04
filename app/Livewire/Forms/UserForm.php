<?php

namespace App\Livewire\Forms;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class UserForm extends Form
{
    public ?User $user = null;

    public $role;
    public $name;
    public $email;

    public $password = 123456789;

    public function rules()
    {
        return [
            'role' => 'required|exists:roles,name',
            'name' => 'required|string|max:150',
            'email' => [
                'required',
                'email',
                'min:8',
                Rule::unique('users')->ignore($this->user?->id)
            ],
        ];
    }

    public function setUser(User $user)
    {
        $this->user = $user;
        $this->role = $user->roles()->first()->name;
        $this->name = $user->name;
        $this->email = $user->email;
    }

    public function store()
    {
        $this->validate();

        try {
            DB::transaction(function () {
                $data = $this->collectData();
                $user = User::create($data);
                $user->assignRole($this->role);
            });
        } catch (Exception $e) {
            throw $e;
        }

        $this->resetForm();
    }

    public function update()
    {
        $this->validate();

        try {
            DB::transaction(function () {
                $this->user->syncRoles([]);

                $data = $this->collectData();
                $this->user->update($data);
                $this->user->assignRole($this->role);
            });
        } catch (Exception $e) {
            throw $e;
        }

        $this->resetForm();
    }

    public function delete()
    {
        $this->user->delete();
        
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset(['role', 'name', 'email', 'user']);
    }

    private function collectData()
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'password' => bcrypt($this->password),
        ];
    }
}
