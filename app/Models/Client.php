<?php

namespace App\Models;

use App\Enums\DocumentTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'name',
        'document_type',
        'document_identifier',
    ];

    protected function casts(): array
    {
        return [
            'document_type' => DocumentTypeEnum::class,
        ];
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }
}
