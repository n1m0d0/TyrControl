<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'contact',
        'address',
        'phone',
        'email',
    ];

    public function batches(): HasMany
    {
        return $this->hasMany(Batch::class);
    }
}
