<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DrugDilution extends Model
{
    protected $fillable = [
        'drug_id',
        'label',
        'recipe_text',
        'concentration_value',
        'concentration_unit',
        'rate_formula_text',
        'rate_coefficient',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'concentration_value' => 'decimal:3',
            'rate_coefficient' => 'decimal:4',
            'is_active' => 'boolean',
        ];
    }

    public function drug(): BelongsTo
    {
        return $this->belongsTo(Drug::class);
    }
}
