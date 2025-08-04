<?php

namespace App\Livewire;

use App\Livewire\Forms\BoxForm;
use App\Models\Box;
use App\Models\Branch;
use App\Models\Company;
use Flux\Flux;
use Livewire\Component;
use Livewire\WithPagination;
use Usernotnull\Toast\Concerns\WireToast;

class ComponentBox extends Component
{
    use WireToast;
    use WithPagination;

    public $activity = 'create';
    public $search = '';

    public BoxForm $form;

    public $company;
    public $companies;
    public $branches;

    public $selectedBranch;

    public function mount()
    {
        $this->companies = Company::select('id', 'name')->get();
        $this->branches = collect();
        $this->selectedBranch = null;
    }

    public function render()
    {
        $boxes = Box::with('branch.company')
            ->when($this->search, fn($query) => $query->whereAny([
                'name',
                'code'
            ], 'like', '%' . $this->search . '%'))
            ->orderBy('id', 'DESC')
            ->paginate(10);

        return view('livewire.component-box', compact('boxes'));
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

        Flux::modal('box-form')->close();
    }

    public function showForm($id = null)
    {
        $this->company = null;
        $this->branches = collect();
        $this->selectedBranch = null;
        $this->form->resetForm();

        if ($id) {
            $box = Box::findOrFail($id);
            $this->company = $box->branch->company_id;
            $this->selectedBranch = $box->branch_id;

            $this->branches = Branch::select('id', 'name')->where('company_id', $this->company)->get();

            $this->form->setBox($box);
            $this->activity = "edit";
        } else {
            $this->activity = "create";
        }

        Flux::modal('box-form')->show();
    }

    public function showDelete($id)
    {
        $box = box::findOrFail($id);
        $this->form->setBox($box);

        Flux::modal('box-delete')->show();
    }

    public function closeDelete()
    {
        Flux::modal('box-delete')->close();
    }

    public function delete()
    {
        $this->form->delete();

        toast()
            ->danger('El registro se eliminó correctamente.')
            ->push();

        Flux::modal('box-delete')->close();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedCompany()
    {
        if ($this->company) {
            $this->branches = Branch::select('id', 'name')->where('company_id', $this->company)->get();
        } else {
            $this->branches = collect();
            $this->form->branch = null;
        }
    }
}
