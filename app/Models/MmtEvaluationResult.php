<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MmtEvaluationResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'mmt_evaluation_id',
        'assessment_item_id',
        'side',
        'grade',
    ];

    protected $casts = [
        'grade' => 'integer',
    ];

    public function mmtEvaluation(): BelongsTo
    {
        return $this->belongsTo(MmtEvaluation::class);
    }

    public function assessmentItem(): BelongsTo
    {
        return $this->belongsTo(AssessmentItem::class);
    }
}