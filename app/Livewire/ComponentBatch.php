<?php

namespace App\Livewire;

use App\Livewire\Forms\BatchForm;
use App\Models\Batch;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Supplier;
use Flux\Flux;
use Livewire\Component;
use Livewire\WithPagination;
use Usernotnull\Toast\Concerns\WireToast;

class ComponentBatch extends Component
{
    use WireToast;
    use WithPagination;

    public $activity = 'create';
    public $search = '';

    public BatchForm $form;

    public $supplier_id;
    public $suppliers;

    public $brand_id;
    public $brand;
    public $brands;

    public $product_id;
    public $products;
    public $searchProducts;

    public $selectedProduct;

    public function mount()
    {
        $this->suppliers = Supplier::select('id', 'name')->get();
        $this->brands = Brand::select('id', 'name')->get();
        $this->products = collect();
        $this->searchProducts = collect();
        $this->selectedProduct = null;
    }

    public function render()
    {
        $batches = Batch::with('supplier', 'product.brand')
            ->when($this->supplier_id, fn($query) => $query->where('supplier_id', $this->supplier_id))
            ->when($this->product_id, fn($query) => $query->where('product_id', $this->product_id))
            ->when(
                $this->search,
                fn($query) =>
                $query->whereHas(
                    'product',
                    fn($q) =>
                    $q->where(function ($subQuery) {
                        $subQuery->whereAny([
                            'name',
                            'description',
                            'sku',
                            'code'
                        ], 'like', '%' . $this->search . '%');
                    })
                )
            )
            ->orderBy('id', 'DESC')
            ->paginate(10);

        return view('livewire.component-batch', compact('batches'));
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

        Flux::modal('batch-form')->close();
    }

    public function showForm($id = null)
    {
        $this->brand = null;
        $this->products = collect();
        $this->selectedProduct = null;
        $this->form->resetForm();

        if ($id) {
            $batch = Batch::findOrFail($id);
            $this->brand = $batch->product->brand_id;
            $this->selectedProduct = $batch->product_id;

            $this->products = Product::select('id', 'name')
                ->where('brand_id', $this->brand)
                ->get();

            $this->form->setBatch($batch);
            $this->activity = "edit";
        } else {
            $this->activity = "create";
        }

        Flux::modal('batch-form')->show();
    }

    public function showDelete($id)
    {
        $batch = Batch::findOrFail($id);
        $this->form->setBatch($batch);

        Flux::modal('batch-delete')->show();
    }

    public function closeDelete()
    {
        Flux::modal('batch-delete')->close();
    }

    public function delete()
    {
        $this->form->delete();

        toast()
            ->danger('El registro se eliminó correctamente.')
            ->push();

        Flux::modal('batch-delete')->close();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedBrand()
    {
        if ($this->brand) {
            $this->products = Product::select('id', 'name')->where('brand_id', $this->brand)->get();
        } else {
            $this->products = collect();
            $this->form->product = null;
            $this->selectedProduct = null;
        }
    }

    public function updatedBrandId()
    {
        if ($this->brand_id) {
            $this->searchProducts = Product::select('id', 'name')->where('brand_id', $this->brand_id)->get();
        } else {
            $this->searchProducts = collect();
            $this->product_id = null;
        }

        $this->resetPage();
    }
}
