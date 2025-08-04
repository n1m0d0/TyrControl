<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'box_session_id',
        'client_id',
        'sale_date',
        'total',
    ];

    protected function casts(): array
    {
        return [
            'sale_date' => 'datetime',
            'total' => 'decimal:2',
        ];
    }

    protected function saleDate(): Attribute
    {
        return Attribute::make(
            get: fn($value) => Carbon::parse($value),
            set: fn($value) => Carbon::parse($value)->toDateTimeString(),
        );
    }

    protected function total(): Attribute
    {
        return Attribute::make(
            get: fn($value) => number_format($value, 2, '.', ''),
            set: fn($value) => round($value, 2)
        );
    }

    public function boxSession():BelongsTo
    {
        return $this->belongsTo(BoxSession::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function details(): HasMany
    {
        return $this->hasMany(Detail::class);
    }
}
