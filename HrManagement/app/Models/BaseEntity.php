<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class BaseEntity extends Model{
    protected $fillable = ["id", "name", "version", "creator"];
    protected $attributes = [
        "version" => 0,
    ];

    protected $with = ["creator"];
    protected static function booted(){

        // Automatically increment version on update

        static::creating(function ($model) {
            $model->creator = Auth::user()->id; // Use the authenticated user's ID
        });

        static::updating(function ($model) {
            $model->version = $model->version + 1;
        });
    }

    public function creator(){
        return $this->belongsTo(User::class, 'creator')
            ->select('id', 'name', 'email');
    }
}
