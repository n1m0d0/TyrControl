<?php

namespace App\Livewire;

use App\Livewire\Forms\ProductForm;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Flux\Flux;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Usernotnull\Toast\Concerns\WireToast;

class ComponentProduct extends Component
{
    use WireToast;
    use WithPagination;
    use WithFileUploads;

    public $activity = 'create';
    public $search = '';

    public ProductForm $form;

    public $categories;
    public $brands;

    public function mount()
    {
        $this->categories = Category::select('id', 'name')->get();
        $this->brands = Brand::select('id', 'name')->get();
    }

    public function render()
    {
        $products = Product::with('brand')
            ->when($this->search, fn($query) => $query->whereAny([
                'name',
                'description',
                'sku',
                'code'
            ], 'like', '%' . $this->search . '%'))
            ->orderBy('id', 'DESC')
            ->paginate(10);

        return view('livewire.component-product', compact('products'));
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

        Flux::modal('product-form')->close();
    }

    public function showForm($id = null)
    {
        $this->form->resetForm();

        if ($id) {
            $product = Product::findOrFail($id);
            $this->form->setProduct($product);
            $this->activity = "edit";
        } else {
            $this->activity = "create";
        }

        Flux::modal('product-form')->show();
    }

    public function showDelete($id)
    {
        $product = Product::findOrFail($id);
        $this->form->setProduct($product);

        Flux::modal('product-delete')->show();
    }

    public function closeDelete()
    {
        Flux::modal('product-delete')->close();
    }

    public function delete()
    {
        $this->form->delete();

        toast()
            ->danger('El registro se eliminó correctamente.')
            ->push();

        Flux::modal('product-delete')->close();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
}
