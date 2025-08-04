<?php

namespace App\Livewire;

use App\Enums\MovementTypeEnum;
use App\Livewire\Forms\MovementForm;
use App\Models\Branch;
use App\Models\Brand;
use App\Models\Company;
use App\Models\Movement;
use App\Models\Product;
use App\Models\Warehouse;
use Flux\Flux;
use Livewire\Component;
use Livewire\WithPagination;
use Usernotnull\Toast\Concerns\WireToast;

class ComponentMovement extends Component
{
    use WireToast;
    use WithPagination;

    public $search = '';

    public MovementForm $form;

    public $company_id;
    public $company;
    public $companies;

    public $branch_id;
    public $branch;
    public $branches;
    public $searchBranches;

    public $warehouse_id;
    public $warehouses;
    public $searchWarehouses;

    public $brand_id;
    public $brand;
    public $brands;

    public $product_id;
    public $products;
    public $searchProducts;

    public $types;

    public function mount()
    {
        $this->companies = Company::select('id', 'name')->get();
        $this->branches = collect();
        $this->warehouses = collect();
        $this->searchBranches = collect();
        $this->searchWarehouses = collect();
        $this->brands = Brand::select('id', 'name')->get();
        $this->products = collect();
        $this->searchProducts = collect();
        $this->types = MovementTypeEnum::cases();
    }

    public function render()
    {
        $movements = Movement::with('warehouse.branch', 'product.brand')
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

        return view('livewire.component-movement', compact('movements'));
    }

    public function save()
    {
        $this->form->store();

        Flux::modal('movement-form')->close();
    }

    public function showForm()
    {
        $this->company = null;
        $this->branch = null;
        $this->brand = null;
        $this->form->resetForm();

        Flux::modal('movement-form')->show();
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
            $this->branch = null;
            $this->warehouses = collect();
            $this->form->warehouse = null;
        }
    }

    public function updatedBranch()
    {
        if ($this->branch) {
            $this->warehouses = Warehouse::select('id', 'name')->where('branch_id', $this->branch)->get();
        } else {
            $this->warehouses = collect();
            $this->form->warehouse = null;
        }
    }

    public function updatedBrand()
    {
        if ($this->brand) {
            $this->products = Product::select('id', 'name')->where('brand_id', $this->brand)->get();
        } else {
            $this->products = collect();
            $this->form->product = null;
        }
    }

    public function updatedCompanyId()
    {
        if ($this->company_id) {
            $this->searchBranches = Branch::select('id', 'name')->where('company_id', $this->company_id)->get();
        } else {
            $this->searchBranches = collect();
            $this->branch_id = null;
            $this->searchWarehouses = collect();
            $this->warehouse_id = null;
        }

        $this->resetPage();
    }

    public function updatedBranchId()
    {
        if ($this->branch_id) {
            $this->searchWarehouses = Warehouse::select('id', 'name')->where('branch_id', $this->branch_id)->get();
        } else {
            $this->searchWarehouses = collect();
            $this->warehouse_id = null;
        }

        $this->resetPage();
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
