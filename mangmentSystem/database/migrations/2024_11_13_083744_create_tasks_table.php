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
        Schema::create('tasks', function (Blueprint $table) {
            \App\Helper\MigrateHelper::addBaseEntityColumns($table);
            $table->Text("content");
            $table->foreignId('project_id')->constrained()->onDelete('cascade'); // Link to projects table
            $table->String('status')->default(\App\Extra\TaskStatus::Pending->value);
            $table->String('priority')->default(\App\Extra\Priority::Normal->value);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
