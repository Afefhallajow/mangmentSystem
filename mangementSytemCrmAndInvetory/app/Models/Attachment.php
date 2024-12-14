<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Attachment extends Model {

    protected $fillable = ["model_id", "path", "model_type"];

    public function getFullUrlAttribute()
    {
        return $this->path;
    }
    public function model(): \Illuminate\Database\Eloquent\Relations\BelongsTo{
        return $this->morphTo();
    }
}
