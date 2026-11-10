<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bbs_evaluation_results', function (Blueprint $table) {
            $table->id();

            $table->foreignId('bbs_evaluation_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->unsignedTinyInteger('item_number');
            $table->unsignedTinyInteger('score')->nullable();

            $table->timestamps();

            $table->unique(
                ['bbs_evaluation_id', 'item_number'],
                'bbs_result_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bbs_evaluation_results');
    }
};