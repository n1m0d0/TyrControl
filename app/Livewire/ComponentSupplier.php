<?php

namespace App\Livewire;

use App\Livewire\Forms\SupplierForm;
use App\Models\Supplier;
use Flux\Flux;
use Livewire\Component;
use Livewire\WithPagination;
use Usernotnull\Toast\Concerns\WireToast;

class ComponentSupplier extends Component
{
    use WireToast;
    use WithPagination;

    public $activity = 'create';
    public $search = '';

    public SupplierForm $form;

    public function render()
    {
        $suppliers = Supplier::query()
            ->when($this->search, fn($query) => $query->whereAny([
                'name',
                'contact'
            ], 'like', '%' . $this->search . '%'))
            ->orderBy('id', 'DESC')
            ->paginate(10);

        return view('livewire.component-supplier', compact('suppliers'));
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

        Flux::modal('supplier-form')->close();
    }

    public function showForm($id = null)
    {
        $this->form->resetForm();

        if ($id) {
            $supplier = Supplier::findOrFail($id);
            $this->form->setSupplier($supplier);
            $this->activity = "edit";
        } else {
            $this->activity = "create";
        }

        Flux::modal('supplier-form')->show();
    }

    public function showDelete($id)
    {
        $supplier = Supplier::findOrFail($id);
        $this->form->setSupplier($supplier);

        Flux::modal('supplier-delete')->show();
    }

    public function closeDelete()
    {
        Flux::modal('supplier-delete')->close();
    }

    public function delete()
    {
        $this->form->delete();

        toast()
            ->danger('El registro se eliminó correctamente.')
            ->push();

        Flux::modal('supplier-delete')->close();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
}
