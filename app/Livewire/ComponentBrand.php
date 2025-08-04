<?php

namespace App\Livewire;

use App\Livewire\Forms\BrandForm;
use App\Models\Brand;
use Flux\Flux;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Usernotnull\Toast\Concerns\WireToast;

class ComponentBrand extends Component
{
    use WireToast;
    use WithPagination;
    use WithFileUploads;

    public $activity = 'create';
    public $search = '';

    public BrandForm $form;
    
    public function render()
    {
        $brands = Brand::query()
            ->when($this->search, fn($query) => $query->where('name', 'like', '%' . $this->search . '%'))
            ->orderBy('id', 'DESC')
            ->paginate(10);

        return view('livewire.component-brand', compact('brands'));
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

        Flux::modal('brand-form')->close();
    }

    public function showForm($id = null)
    {
        $this->form->resetForm();

        if ($id) {
            $brand = Brand::findOrFail($id);
            $this->form->setBrand($brand);
            $this->activity = "edit";
        } else {
            $this->activity = "create";
        }

        Flux::modal('brand-form')->show();
    }

    public function showDelete($id)
    {
        $brand = Brand::findOrFail($id);
        $this->form->setBrand($brand);

        Flux::modal('brand-delete')->show();
    }

    public function closeDelete()
    {
        Flux::modal('brand-delete')->close();
    }

    public function delete()
    {
        $this->form->delete();

        toast()
            ->danger('El registro se eliminó correctamente.')
            ->push();

        Flux::modal('brand-delete')->close();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
}
