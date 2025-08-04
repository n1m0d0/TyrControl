<?php

namespace App\Livewire\Forms;

use App\Models\Branch;
use Livewire\Attributes\Validate;
use Livewire\Form;

class BranchForm extends Form
{
    public ?Branch $branch = null;

    public $company;
    public $name;
    public $address;

    public function rules()
    {
        return [
            'company' => 'required|exists:companies,id',
            'name' => 'required|string|max:150',
            'address' => 'required|string|max:150',
        ];
    }

    public function setBranch(Branch $branch)
    {
        $this->branch = $branch;
        $this->company = $branch->company_id;
        $this->name = $branch->name;
        $this->address = $branch->address;
    }

    public function store()
    {
        $this->validate();

        $data = $this->collectData();
        Branch::create($data);

        $this->resetForm();
    }

    public function update()
    {
        $this->validate();

        $data = $this->collectData();
        $this->branch->update($data);

        $this->resetForm();
    }

    public function delete()
    {
        $this->branch->delete();

        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset(['company', 'name', 'address', 'branch']);
    }

    private function collectData()
    {
        return [
            'company_id' => $this->company,
            'name' => $this->name,
            'address' => $this->address,
        ];
    }
}
