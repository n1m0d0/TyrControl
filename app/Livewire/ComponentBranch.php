<?php

namespace App\Livewire;

use App\Livewire\Forms\BranchForm;
use App\Models\Branch;
use App\Models\Company;
use Flux\Flux;
use Livewire\Component;
use Livewire\WithPagination;
use Usernotnull\Toast\Concerns\WireToast;

class ComponentBranch extends Component
{
    use WireToast;
    use WithPagination;

    public $activity = 'create';
    public $search = '';

    public BranchForm $form;

    public $companies;

    public function mount()
    {
        $this->companies = Company::select('id', 'name')->get();
    }

    public function render()
    {
        $branches = Branch::with('company')
            ->when($this->search, fn($query) => $query->where('name', 'like', '%' . $this->search . '%'))
            ->orderBy('id', 'DESC')
            ->paginate(10);

        return view('livewire.component-branch', compact('branches'));
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

        Flux::modal('branch-form')->close();
    }

    public function showForm($id = null)
    {
        $this->form->resetForm();

        if ($id) {
            $branch = Branch::findOrFail($id);
            $this->form->setBranch($branch);
            $this->activity = "edit";
        } else {
            $this->activity = "create";
        }

        Flux::modal('branch-form')->show();
    }

    public function showDelete($id)
    {
        $branch = Branch::findOrFail($id);
        $this->form->setBranch($branch);

        Flux::modal('branch-delete')->show();
    }

    public function closeDelete()
    {
        Flux::modal('branch-delete')->close();
    }

    public function delete()
    {
        $this->form->delete();

        toast()
            ->danger('El registro se eliminó correctamente.')
            ->push();

        Flux::modal('branch-delete')->close();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
}
