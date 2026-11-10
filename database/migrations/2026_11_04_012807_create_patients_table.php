<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rehabilitation_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->date('performed_at');
            $table->string('therapist');
            $table->text('content');
            $table->unsignedInteger('duration');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rehabilitation_records');
    }
};