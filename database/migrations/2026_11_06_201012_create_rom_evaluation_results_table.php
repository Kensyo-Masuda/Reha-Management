<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rom_evaluation_results', function (Blueprint $table) {
            $table->id();

            $table->foreignId('rom_evaluation_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('assessment_item_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->enum('side', ['right', 'left']);
            $table->enum('movement_type', ['active', 'passive']);
            $table->decimal('angle', 5, 1)->nullable();

            $table->timestamps();

            $table->unique([
                'rom_evaluation_id',
                'assessment_item_id',
                'side',
                'movement_type',
            ], 'rom_result_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rom_evaluation_results');
    }
};