<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Source extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'document_type',
        'priority',
        'notes',
        'source_updated_at',
    ];

    protected function casts(): array
    {
        return [
            'source_updated_at' => 'date',
        ];
    }

    public function drugs(): MorphToMany
    {
        return $this->morphedByMany(Drug::class, 'sourceable')
            ->withPivot('priority')
            ->withTimestamps();
    }

    public function ventilatorPresets(): MorphToMany
    {
        return $this->morphedByMany(VentilatorPreset::class, 'sourceable')
            ->withPivot('priority')
            ->withTimestamps();
    }
}
