<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('base_entities', function (Blueprint $table) {
            $table->id();
            $table->text("name")->nullable();
            $table->bigInteger("version");
            $table->foreignId('creator')->nullable()->constrained("users"); // Foreign key
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('base_entities');
    }
};
