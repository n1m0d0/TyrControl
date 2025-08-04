<?php

namespace App\Models;

use App\Enums\BoxSessionStatusEnum;
use App\Observers\BoxSessionObserver;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy([BoxSessionObserver::class])]
class BoxSession extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'box_id',
        'user_id',
        'opening_amount',
        'closing_amount',
        'expected_amount',
        'difference',
        'opened_at',
        'closed_at',
        'status',
        'opening_notes',
        'closing_notes'
    ];

    protected function casts(): array
    {
        return [
            'opening_amount' => 'decimal:2',
            'closing_amount' => 'decimal:2',
            'expected_amount' => 'decimal:2',
            'difference' => 'decimal:2',
            'opened_at' => 'datetime',
            'closed_at' => 'datetime',
            'status' => BoxSessionStatusEnum::class,
        ];
    }

    protected function openingAmount(): Attribute
    {
        return Attribute::make(
            get: fn($value) => number_format($value, 2, '.', ''),
            set: fn($value) => round($value, 2)
        );
    }

    protected function closingAmount(): Attribute
    {
        return Attribute::make(
            get: fn($value) => number_format($value, 2, '.', ''),
            set: fn($value) => round($value, 2)
        );
    }

    protected function expectedAmount(): Attribute
    {
        return Attribute::make(
            get: fn($value) => number_format($value, 2, '.', ''),
            set: fn($value) => round($value, 2)
        );
    }

    protected function difference(): Attribute
    {
        return Attribute::make(
            get: fn($value) => number_format($value, 2, '.', ''),
            set: fn($value) => round($value, 2)
        );
    }

    protected function openedAt(): Attribute
    {
        return Attribute::make(
            get: fn($value) => Carbon::parse($value),
            set: fn($value) => Carbon::parse($value)->format('Y-m-d H:i:s'),
        );
    }

    protected function closedAt(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value ? Carbon::parse($value) : null,
            set: fn($value) => $value ? Carbon::parse($value)->format('Y-m-d H:i:s') : null,
        );
    }

    public function box()
    {
        return $this->belongsTo(Box::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}
