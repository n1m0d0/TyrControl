<?php

namespace App\Livewire;

use App\Enums\RoleEnum;
use App\Livewire\Forms\UserForm;
use App\Models\User;
use Flux\Flux;
use Livewire\Component;
use Livewire\WithPagination;
use Usernotnull\Toast\Concerns\WireToast;

class ComponentUser extends Component
{
    use WireToast;
    use WithPagination;

    public $activity = 'create';
    public $search = '';

    public $roles;

    public UserForm $form;

    public function mount()
    {
        $this->roles = RoleEnum::cases();
    }

    public function render()
    {
        $users = User::with('roles')
            ->when($this->search, fn($query) => $query->where('name', 'like', '%' . $this->search . '%'))
            ->orderBy('id', 'DESC')
            ->paginate(10);

        return view('livewire.component-user', compact('users'));
    }

    public function save()
    {
        if ($this->activity == "create") {
            $this->form->store();

            toast()
                ->success('El registro se guardó correctamente.')
                ->push();
        }

        if ($this->activity == "edit") {
            $this->form->update();

            toast()
                ->info('Los cambios se guardaron con éxito.')
                ->push();
        }

        $this->activity = "create";

        Flux::modal('user-form')->close();
    }

    public function showForm($id = null)
    {
        $this->form->resetForm();

        if ($id) {
            $user = User::findOrFail($id);
            $this->form->setUser($user);
            $this->activity = "edit";
        } else {
            $this->activity = "create";
        }

        Flux::modal('user-form')->show();
    }

    public function showDelete($id)
    {
        $user = User::findOrFail($id);
        $this->form->setUser($user);

        Flux::modal('user-delete')->show();
    }

    public function closeDelete()
    {
        Flux::modal('user-delete')->close();
    }

    public function delete()
    {
        $this->form->delete();

        toast()
            ->danger('El registro se eliminó correctamente.')
            ->push();

        Flux::modal('user-delete')->close();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
}
