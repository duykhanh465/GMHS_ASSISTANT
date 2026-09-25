<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class VentilatorPreset extends Model
{
    protected $fillable = [
        'clinical_scenario',
        'parameter',
        'value_min',
        'value_max',
        'default_value',
        'value_display',
        'unit',
        'monitoring',
        'warning',
        'sort_order',
        'is_active',
    ];

    public function sources(): MorphToMany
    {
        return $this->morphToMany(Source::class, 'sourceable')
            ->withPivot('priority')
            ->withTimestamps();
    }
}
