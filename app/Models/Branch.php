<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'company_id',
        'name',
        'address',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function boxes(): HasMany
    {
        return $this->hasMany(Box::class);
    }

    public function warehouses(): HasMany
    {
        return $this->hasMany(Warehouse::class);
    }
}
