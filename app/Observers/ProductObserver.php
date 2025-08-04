<?php

namespace App\Observers;

use App\Models\Product;

class ProductObserver
{
    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product): void
    {
        $product->code = $this->generarCodigoEAN13($product->id);
        $product->saveQuietly();
    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "restored" event.
     */
    public function restored(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "force deleted" event.
     */
    public function forceDeleted(Product $product): void
    {
        //
    }

    protected function generarCodigoEAN13($code)
    {
        // 1. Rellenar con ceros a la izquierda hasta tener 12 dígitos
        $base = str_pad($code, 12, '0', STR_PAD_LEFT);

        // 2. Calcular el dígito verificador (checksum)
        $suma = 0;
        for ($i = 0; $i < 12; $i++) {
            $digito = (int) $base[$i];
            $suma += ($i % 2 === 0) ? $digito : $digito * 3;
        }
        $resto = $suma % 10;
        $verificador = ($resto === 0) ? 0 : 10 - $resto;

        // 3. Concatenar el dígito verificador al final
        return $base . $verificador;
    }
}
