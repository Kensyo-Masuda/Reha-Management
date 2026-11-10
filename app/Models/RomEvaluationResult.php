<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RomEvaluationResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'rom_evaluation_id',
        'assessment_item_id',
        'side',
        'movement_type',
        'angle',
    ];

    protected $casts = [
        'angle' => 'decimal:1',
    ];

    public function romEvaluation(): BelongsTo
    {
        return $this->belongsTo(RomEvaluation::class);
    }

    public function assessmentItem(): BelongsTo
    {
        return $this->belongsTo(AssessmentItem::class);
    }
}