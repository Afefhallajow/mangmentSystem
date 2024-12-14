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
        Schema::create('work_hours_reports', function (Blueprint $table) {
            MigrateHelper::addBaseEntityColumns($table);
            $table->foreignId('employee')->constrained("users")->onDelete('cascade');
            $table->date("report_date")->useCurrent();
            $table->decimal('worked_hours', 10, 5)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_hours_reports');
    }
};
