<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'birth_date',
        'diagnosis',
        'medical_history',
    ];

    public function rehabilitationRecords(): HasMany
    {
        return $this->hasMany(RehabilitationRecord::class);
    }

    public function evaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class);
    }

    public function romEvaluations(): HasMany
{
    return $this->hasMany(RomEvaluation::class);
}

    public function mmtEvaluations(): HasMany
{
    return $this->hasMany(MmtEvaluation::class);
}

    public function bbsEvaluations(): HasMany
{
    return $this->hasMany(BbsEvaluation::class);
}

}