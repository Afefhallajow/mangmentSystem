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
        Schema::create('leaves', function (Blueprint $table) {
            MigrateHelper::addBaseEntityColumns($table);
            $table->foreignId('employee')->constrained("user")->onDelete('cascade');
            $table->string("type")->default("annual");
            $table->String("status")->default("pending");
            $table->dateTime("startDate");
            $table->dateTime("endDate");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leaves');
    }
};
