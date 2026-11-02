<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'code',
        'full_name',
        'age',
        'gender',
        'height_cm',
        'weight_kg',
        'asa_class',
        'is_pregnant',
        'respiratory_condition',
        'surgery_note',
    ];

    protected function casts(): array
    {
        return [
            'is_pregnant' => 'boolean',
            'height_cm' => 'decimal:1',
            'weight_kg' => 'decimal:1',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function calculations(): HasMany
    {
        return $this->hasMany(PatientCalculation::class);
    }
}
