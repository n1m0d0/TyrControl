<?php

namespace App\Models;

use App\Enums\MovementTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Movement extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'warehouse_id',
        'product_id',
        'movement_type',
        'quantity',
        'reason',
    ];

    protected function casts(): array
    {
        return [
            'movement_type' => MovementTypeEnum::class,
        ];
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
