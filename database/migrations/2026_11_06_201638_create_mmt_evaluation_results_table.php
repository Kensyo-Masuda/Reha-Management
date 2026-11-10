<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mmt_evaluation_results', function (Blueprint $table) {
            $table->id();

            $table->foreignId('mmt_evaluation_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('assessment_item_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->enum('side', ['right', 'left']);
            $table->unsignedTinyInteger('grade')->nullable();

            $table->timestamps();

            $table->unique([
                'mmt_evaluation_id',
                'assessment_item_id',
                'side',
            ], 'mmt_result_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mmt_evaluation_results');
    }
};