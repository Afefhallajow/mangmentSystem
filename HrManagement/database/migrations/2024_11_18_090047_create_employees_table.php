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
        Schema::create('employees', function (Blueprint $table) {
            MigrateHelper::addBaseEntityColumns($table);
            $table->decimal('salary', 15, 5)->default(0);
            $table->string("contract_type");
            $table->foreignId('user_id')->constrained("users")->onDelete('cascade');
            $table->string("email");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
