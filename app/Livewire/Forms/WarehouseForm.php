<?php

namespace App\Livewire\Forms;

use App\Models\Warehouse;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class WarehouseForm extends Form
{
    public ?Warehouse $warehouse = null;

    public $branch;
    public $name;
    public $code;

    public function rules()
    {
        return [
            'branch' => 'required|exists:branches,id',
            'name' => 'required|string|max:150',
            'code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('warehouses', 'code')->ignore($this->warehouse?->id)
            ],
        ];
    }

    public function setWarehouse(Warehouse $warehouse)
    {
        $this->warehouse = $warehouse;
        $this->branch = $warehouse->branch_id;
        $this->name = $warehouse->name;
        $this->code = $warehouse->code;
    }

    public function store()
    {
        $this->validate();

        $data = $this->collectData();
        Warehouse::create($data);

        $this->resetForm();
    }

    public function update()
    {
        $this->validate();

        $data = $this->collectData();
        $this->warehouse->update($data);

        $this->resetForm();
    }

    public function delete()
    {
        $this->warehouse->delete();

        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset(['branch', 'name', 'code', 'warehouse']);
    }

    private function collectData()
    {
        return [
            'branch_id' => $this->branch,
            'name' => $this->name,
            'code' => $this->code,
        ];
    }
}
