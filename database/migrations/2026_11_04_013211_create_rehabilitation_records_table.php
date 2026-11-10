<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rehabilitation_records', function (Blueprint $table) {
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->date('performed_at');
            $table->string('therapist');
            $table->text('content');
            $table->integer('duration');
        });
    }

    public function down(): void
    {
        Schema::table('rehabilitation_records', function (Blueprint $table) {
            $table->dropForeign(['patient_id']);
            $table->dropColumn([
                'patient_id',
                'performed_at',
                'therapist',
                'content',
                'duration',
            ]);
        });
    }
};