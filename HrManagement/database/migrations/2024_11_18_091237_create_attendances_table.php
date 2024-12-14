<?php

use App\Helper\MigrateHelper;
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
        Schema::create('attendances', function (Blueprint $table) {
            MigrateHelper::addBaseEntityColumns($table);
            $table->date('date'); // تاريخ الحضور
            $table->time('check_in_time')->useCurrent(); // وقت تسجيل الدخول
            $table->time('check_out_time')->nullable();
            $table->foreignId('employee')->constrained("users")->onDelete('cascade'); // Link to projects table
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
