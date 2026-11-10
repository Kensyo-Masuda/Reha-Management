<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MmtEvaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'evaluated_at',
        'evaluator',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function results(): HasMany
    {
        return $this->hasMany(MmtEvaluationResult::class);
    }
}