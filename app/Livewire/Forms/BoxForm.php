<?php

namespace App\Livewire\Forms;

use App\Models\Box;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class BoxForm extends Form
{
    public ?Box $box = null;

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
                Rule::unique('boxes', 'code')->ignore($this->box?->id)
            ],
        ];
    }

    public function setBox(Box $box)
    {
        $this->box = $box;
        $this->branch = $box->branch_id;
        $this->name = $box->name;
        $this->code = $box->code;
    }

    public function store()
    {
        $this->validate();

        $data = $this->collectData();
        Box::create($data);

        $this->resetForm();
    }

    public function update()
    {
        $this->validate();

        $data = $this->collectData();
        $this->box->update($data);

        $this->resetForm();
    }

    public function delete()
    {
        $this->box->delete();

        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset(['branch', 'name', 'code', 'box']);
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
