<?php

namespace App\Livewire\Forms;

use App\Enums\MovementTypeEnum;
use App\Models\Inventory;
use App\Models\Movement;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Enum;
use Livewire\Attributes\Validate;
use Livewire\Form;

class MovementForm extends Form
{
    public ?Movement $movement = null;

    public $warehouse;
    public $product;
    public $movement_type;
    public $quantity;
    public $reason;

    public function rules()
    {
        return [
            'warehouse' => 'required|exists:warehouses,id',
            'product' => 'required|exists:products,id',
            'movement_type' => ['required', new Enum(MovementTypeEnum::class)],
            'quantity' => 'required|integer|min:1',
            'reason' => 'nullable|string|max:255',
        ];
    }

    public function setMovement(Movement $movement)
    {
        $this->movement = $movement;
        $this->warehouse = $movement->warehouse_id;
        $this->product = $movement->product_id;
        $this->movement_type = $movement->movement_type;
        $this->quantity = $movement->quantity;
        $this->reason = $movement->reason;
    }

    public function store()
    {
        $this->validate();

        $data = $this->collectData();

        // Iniciar una transacción manualmente
        try {
            DB::transaction(function () use ($data) {
                if ($this->movement_type == MovementTypeEnum::ENTRY->value) {
                    // Obtener o crear el inventario para el producto
                    $inventory = Inventory::firstOrCreate([
                        'warehouse_id' => $this->warehouse,
                        'product_id' => $this->product
                    ]);

                    // Aumentar el stock
                    $inventory->stock += $this->quantity;
                    $inventory->save();

                    // Crear el movimiento de entrada
                    Movement::create($data);
                }

                if ($this->movement_type == MovementTypeEnum::EXIT->value) {
                    // Obtener o crear el inventario para el producto
                    $inventory = Inventory::firstOrCreate([
                        'warehouse_id' => $this->warehouse,
                        'product_id' => $this->product
                    ]);

                    // Validar que haya suficiente stock para la salida
                    if ($inventory->stock < $this->quantity) {
                        // Lanzar una excepción personalizada
                        throw new \Exception('No hay suficiente stock para realizar esta salida.');
                    }

                    // Reducir el stock
                    $inventory->stock -= $this->quantity;
                    $inventory->save();

                    // Crear el movimiento de salida
                    Movement::create($data);
                }
            });

            // Mostrar un mensaje de éxito
            toast()
                ->success('El registro se guardó correctamente.')
                ->push();
        } catch (\Exception $e) {
            // Mostrar un mensaje de error
            toast()
                ->danger('No hay suficiente stock para realizar esta salida.')
                ->push();
        }

        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset(['warehouse', 'product', 'movement_type', 'quantity', 'reason', 'movement']);
    }

    private function collectData()
    {
        return [
            'warehouse_id' => $this->warehouse,
            'product_id' => $this->product,
            'movement_type' => $this->movement_type,
            'quantity' => $this->quantity,
            'reason' => $this->reason,
        ];
    }
}
