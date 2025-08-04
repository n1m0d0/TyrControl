<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Batch extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'supplier_id',
        'product_id',
        'code',
        'amount_of_packs',
        'amount_of_units_per_pack',
        'total_units',
        'available_units',
        'price_per_pack',
        'price_per_unit',
        'expiration_date',
    ];

    protected function casts(): array
    {
        return [
            'price_per_pack' => 'decimal:2',
            'price_per_unit' => 'decimal:2',
            'expiration_date' => 'date',
        ];
    }

    protected function pricePerPack(): Attribute
    {
        return Attribute::make(
            get: fn($value) => number_format($value, 2, '.', ''),
            set: fn($value) => round($value, 2)
        );
    }

    protected function pricePerUnit(): Attribute
    {
        return Attribute::make(
            get: fn($value) => number_format($value, 2, '.', ''),
            set: fn($value) => round($value, 2)
        );
    }

    protected function expirationDate(): Attribute
    {
        return Attribute::make(
            get: fn($value) => Carbon::parse($value),
            set: fn($value) => Carbon::parse($value)->toDateString(),
        );
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
