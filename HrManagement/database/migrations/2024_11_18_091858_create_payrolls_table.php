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
        Schema::create('payrolls', function (Blueprint $table) {
            MigrateHelper::addBaseEntityColumns($table);
            $table->timestamp("payroll_date")->useCurrent();
            $table->bigInteger('bonus')->default(0);
            $table->integer('worked_hours')->default(0);
            $table->decimal('total', 15, 5)->default(0);
            $table->foreignId('employee')->constrained("users")->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
