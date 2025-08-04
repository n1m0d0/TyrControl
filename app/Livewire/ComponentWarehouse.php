<?php

namespace App\Livewire;

use App\Livewire\Forms\WarehouseForm;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Warehouse;
use Flux\Flux;
use Livewire\Component;
use Livewire\WithPagination;
use Usernotnull\Toast\Concerns\WireToast;

class ComponentWarehouse extends Component
{
    use WireToast;
    use WithPagination;

    public $activity = 'create';
    public $search = '';

    public WarehouseForm $form;

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
        $warehouses = Warehouse::with('branch.company')
            ->when($this->search, fn($query) => $query->whereAny([
                'name',
                'code'
            ], 'like', '%' . $this->search . '%'))
            ->orderBy('id', 'DESC')
            ->paginate(10);

        return view('livewire.component-warehouse', compact('warehouses'));
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

        Flux::modal('warehouse-form')->close();
    }

    public function showForm($id = null)
    {
        $this->company = null;
        $this->branches = collect();
        $this->selectedBranch = null;
        $this->form->resetForm();

        if ($id) {
            $warehouse = Warehouse::findOrFail($id);
            $this->company = $warehouse->branch->company_id;
            $this->selectedBranch = $warehouse->branch_id;

            $this->branches = Branch::select('id', 'name')->where('company_id', $this->company)->get();

            $this->form->setWarehouse($warehouse);
            $this->activity = "edit";
        } else {
            $this->activity = "create";
        }

        Flux::modal('warehouse-form')->show();
    }

    public function showDelete($id)
    {
        $warehouse = Warehouse::findOrFail($id);
        $this->form->setWarehouse($warehouse);

        Flux::modal('warehouse-delete')->show();
    }

    public function closeDelete()
    {
        Flux::modal('warehouse-delete')->close();
    }

    public function delete()
    {
        $this->form->delete();

        toast()
            ->danger('El registro se eliminó correctamente.')
            ->push();

        Flux::modal('warehouse-delete')->close();
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
