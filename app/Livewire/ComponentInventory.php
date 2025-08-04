<?php

namespace App\Livewire;

use App\Models\Branch;
use App\Models\Brand;
use App\Models\Company;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\Warehouse;
use Livewire\Component;
use Livewire\WithPagination;
use Usernotnull\Toast\Concerns\WireToast;

class ComponentInventory extends Component
{
    use WireToast;
    use WithPagination;

    public $search = '';

    public $company_id;
    public $companies;

    public $branch_id;
    public $branches;

    public $warehouse_id;
    public $warehouses;

    public $brand_id;
    public $brands;

    public $product_id;
    public $products;

    public function mount()
    {
        $this->companies = Company::select('id', 'name')->get();
        $this->branches = collect();
        $this->warehouses = collect();
        $this->brands = Brand::select('id', 'name')->get();
        $this->products = collect();
    }

    public function render()
    {
        $inventories = Inventory::with('warehouse.branch', 'product.brand')
            ->when($this->warehouse_id, fn($query) => $query->where('warehouse_id', $this->warehouse_id))
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

        return view('livewire.component-inventory', compact('inventories'));
    }

    public function resetSearch()
    {
        $this->reset(['search']);
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedCompanyId()
    {
        if ($this->company_id) {
            $this->branches = Branch::select('id', 'name')->where('company_id', $this->company_id)->get();
        } else {
            $this->branches = collect();
            $this->branch_id = null;
            $this->warehouses = collect();
            $this->warehouse_id = null;
        }

        $this->resetPage();
    }

    public function updatedBranchId()
    {
        if ($this->branch_id) {
            $this->warehouses = Warehouse::select('id', 'name')->where('branch_id', $this->branch_id)->get();
        } else {
            $this->warehouses = collect();
            $this->warehouse_id = null;
        }

        $this->resetPage();
    }

    public function updatedBrandId()
    {
        if ($this->brand_id) {
            $this->products = Product::select('id', 'name')->where('brand_id', $this->brand_id)->get();
        } else {
            $this->products = collect();
            $this->product_id = null;
        }

        $this->resetPage();
    }
}
