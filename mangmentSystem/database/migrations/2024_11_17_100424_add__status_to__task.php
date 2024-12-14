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
        Schema::table('tasks', function (Blueprint $table) {
            $table->String('status')->default(\App\Extra\TaskStatus::Pending->value);
            $table->String('priority')->default(\App\Extra\Priority::Normal->value);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('_task', function (Blueprint $table) {
            //
        });
    }
};
