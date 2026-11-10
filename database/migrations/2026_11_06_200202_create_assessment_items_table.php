<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assessment_items', function (Blueprint $table) {
            $table->id();
            $table->string('body_region');
            $table->string('movement');
            $table->integer('sort_order');
            $table->timestamps();

            $table->unique(['body_region', 'movement']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assessment_items');
    }
};