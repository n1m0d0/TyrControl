<?php

namespace App\Livewire\Forms;

use App\Models\Batch;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class BatchForm extends Form
{
    public ?Batch $batch = null;

    public $supplier;
    public $product;
    public $code;
    public $amount_of_packs;
    public $amount_of_units_per_pack;
    public $total_units;
    public $price_per_pack;
    public $price_per_unit;
    public $expiration_date;

    public function rules()
    {
        return [
            'supplier' => 'required|exists:suppliers,id',
            'product' => 'required|exists:products,id',
            'code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('batches', 'code')->ignore($this->batch?->id)
            ],
            'amount_of_packs' => 'required|integer|min:1',
            'amount_of_units_per_pack' => 'required|integer|min:1',
            'price_per_pack' => [
                'required',
                'numeric',
                'min:0',
                'regex:/^\d+(\.\d{1,2})?$/'
            ],
            'expiration_date' => 'nullable|date',
        ];
    }

    public function setBatch(Batch $batch)
    {
        $this->batch = $batch;
        $this->supplier = $batch->supplier_id;
        $this->product = $batch->product_id;
        $this->code = $batch->code;
        $this->amount_of_packs = $batch->amount_of_packs;
        $this->amount_of_units_per_pack = $batch->amount_of_units_per_pack;
        $this->price_per_pack = $batch->price_per_pack;
        $this->expiration_date = $batch->expiration_date->format('Y-m-d');
    }

    public function store()
    {
        $this->validate();

        $data = $this->collectData();
        Batch::create($data);

        $this->resetForm();
    }

    public function update()
    {
        $this->validate();

        $data = $this->collectData();
        $this->batch->update($data);

        $this->resetForm();
    }

    public function delete()
    {
        $this->batch->delete();

        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset(['supplier', 'product', 'code', 'amount_of_packs', 'amount_of_units_per_pack', 'price_per_pack', 'expiration_date', 'batch']);
    }

    private function collectData()
    {
        return [
            'supplier_id' => $this->supplier,
            'product_id' => $this->product,
            'code' => $this->code,
            'amount_of_packs' => $this->amount_of_packs,
            'amount_of_units_per_pack' => $this->amount_of_units_per_pack,
            'total_units' => $this->amount_of_packs * $this->amount_of_units_per_pack,
            'price_per_pack' => $this->price_per_pack,
            'price_per_unit' => $this->price_per_pack / $this->amount_of_units_per_pack,
            'expiration_date' => $this->expiration_date,
        ];
    }
}
