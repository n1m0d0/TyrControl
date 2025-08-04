<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Box extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'branch_id',
        'name',
        'code',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function sale(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    public function boxSessions(): HasMany
    {
        return $this->hasMany(BoxSession::class);
    }
}
