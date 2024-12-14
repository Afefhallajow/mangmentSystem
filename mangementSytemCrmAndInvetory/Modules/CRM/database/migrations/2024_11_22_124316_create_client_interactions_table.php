<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\CRM\Extra\InteractionStatus;
use Modules\CRM\Extra\InteractionType;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('client_interactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->string('type')->default(InteractionType::Order->value); // 'order' أو 'complaint'
            $table->string('status')->default(InteractionStatus::Pending->value); // 'pending', 'in-progress', 'resolved'
            $table->text('details');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_interactions');
    }
};
