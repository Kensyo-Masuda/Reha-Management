<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RehabilitationRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'performed_at',
        'therapist',
        'content',
        'duration',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
}