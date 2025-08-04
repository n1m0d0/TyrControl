<?php

namespace App\Livewire\Forms;

use App\Models\Supplier;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class SupplierForm extends Form
{
    public ?Supplier $supplier = null;

    public $name;
    public $contact;
    public $address;
    public $phone;
    public $email;

    public function rules()
    {
        return [
            'name' => 'required|string|max:150',
            'contact' => 'required|string|max:150',
            'address' => 'required|string|max:150',
            'phone' => [
                'required',
                'string',
                'regex:/^\d{8}$/',
                Rule::unique('suppliers')->ignore($this->supplier?->id)
            ],
            'email' => [
                'required',
                'email',
                'min:8',
                Rule::unique('suppliers')->ignore($this->supplier?->id)
            ],
        ];
    }

    public function setsupplier(Supplier $supplier)
    {
        $this->supplier = $supplier;
        $this->name = $supplier->name;
        $this->contact = $supplier->contact;
        $this->address = $supplier->address;
        $this->phone = $supplier->phone;
        $this->email = $supplier->email;
    }

    public function store()
    {
        $this->validate();

        $data = $this->collectData();
        Supplier::create($data);

        $this->resetForm();
    }

    public function update()
    {
        $this->validate();

        $data = $this->collectData();
        $this->supplier->update($data);

        $this->resetForm();
    }

    public function delete()
    {
        $this->supplier->delete();

        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset(['name', 'contact', 'address', 'phone', 'email', 'supplier']);
    }

    private function collectData()
    {
        return [
            'name' => $this->name,
            'contact' => $this->contact,
            'address' => $this->address,
            'phone' => $this->phone,
            'email' => $this->email,
        ];
    }
}
