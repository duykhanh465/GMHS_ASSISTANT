<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Drug extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'drug_category_id',
        'name',
        'generic_name',
        'unit',
        'dose_text',
        'dose_min_per_kg',
        'dose_max_per_kg',
        'weight_basis',
        'preparation',
        'indication',
        'timing',
        'monitoring',
        'warning',
        'translations',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'translations' => 'array',
            'dose_min_per_kg' => 'decimal:3',
            'dose_max_per_kg' => 'decimal:3',
            'is_active' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(DrugCategory::class, 'drug_category_id');
    }

    public function dilutions(): HasMany
    {
        return $this->hasMany(DrugDilution::class);
    }

    public function sources(): MorphToMany
    {
        return $this->morphToMany(Source::class, 'sourceable')
            ->withPivot('priority')
            ->withTimestamps();
    }
}