<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class EmergencyProtocol extends Model
{
    protected $fillable = [
        'situation',
        'signs',
        'immediate_action',
        'adult_dose',
        'result_by_weight',
        'monitoring',
        'differential',
        'note',
        'source_ref',
        'translations',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'translations' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function sources(): MorphToMany
    {
        return $this->morphToMany(Source::class, 'sourceable')
            ->withPivot('priority')
            ->withTimestamps();
    }

    public function localized(?string $locale): array
    {
        $row = $this->toArray();
        $map = is_array($this->translations) ? ($this->translations[$locale ?? ''] ?? []) : [];
        foreach ($map as $k => $v) {
            if (array_key_exists($k, $row) && $v) {
                $row[$k] = $v;
            }
        }
        return $row;
    }
}
