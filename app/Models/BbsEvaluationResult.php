<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BbsEvaluationResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'bbs_evaluation_id',
        'item_number',
        'score',
    ];

    protected $casts = [
        'item_number' => 'integer',
        'score' => 'integer',
    ];

    public function bbsEvaluation(): BelongsTo
    {
        return $this->belongsTo(BbsEvaluation::class);
    }
}