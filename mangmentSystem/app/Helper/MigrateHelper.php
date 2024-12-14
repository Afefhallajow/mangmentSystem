<?php


namespace App\Helper;


use Illuminate\Database\Schema\Blueprint;

class MigrateHelper
{
    public static function addBaseEntityColumns(Blueprint $table){
        $table->id();
        $table->text("name")->nullable();
        $table->bigInteger("version");
        $table->foreignId('creator')->nullable()->constrained('users')->onDelete('set null');
        $table->timestamps();
    }
}
