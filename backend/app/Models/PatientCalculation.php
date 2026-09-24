<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatientCalculation extends Model
{
    protected $fillable = [
        'patient_id',
        'user_id',
        'input_snapshot',
        'bmi',
        'ibw',
        'pbw',
        'adjbw',
        'dosing_weight',
        'maintenance_fluid',
        'mac_value',
        'ett_size',
        'lma_size',
        'ventilator_result',
        'drug_result',
        'vasopressor_result',
        'engine_version',
        'calculated_at',
    ];

    protected function casts(): array
    {
        return [
            'input_snapshot' => 'array',
            'ventilator_result' => 'array',
            'drug_result' => 'array',
            'vasopressor_result' => 'array',
            'bmi' => 'decimal:2',
            'ibw' => 'decimal:2',
            'pbw' => 'decimal:2',
            'adjbw' => 'decimal:2',
            'dosing_weight' => 'decimal:2',
            'maintenance_fluid' => 'decimal:2',
            'mac_value' => 'decimal:2',
            'calculated_at' => 'datetime',
        ];
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
