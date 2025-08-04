<?php

namespace App\Models;

use App\Observers\ProductObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy([ProductObserver::class])]
class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id',
        'brand_id',
        'name',
        'description',
        'sku',
        'code',
        'price',
        'image',
        'minimum_stock',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
        ];
    }

    protected function price(): Attribute
    {
        return Attribute::make(
            get: fn($value) => number_format($value, 2, '.', ''),
            set: fn($value) => round($value, 2)
        );
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function batches(): HasMany
    {
        return $this->hasMany(Batch::class);
    }

    public function movements(): HasMany
    {
        return $this->hasMany(Movement::class);
    }

    public function inventories(): HasMany
    {
        return $this->hasMany(Inventory::class);
    }

    public function details(): HasMany
    {
        return $this->hasMany(Detail::class);
    }
}
