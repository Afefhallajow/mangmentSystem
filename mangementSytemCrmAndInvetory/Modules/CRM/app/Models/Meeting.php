<?php

namespace Modules\CRM\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\CRM\Database\Factories\MeetingFactory;

class Meeting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ["client_id", "scheduled_at", "agenda"];

    // protected static function newFactory(): MeetingFactory
    // {
    //     // return MeetingFactory::new();
    // }
    public function client(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Client::class, "client_id");
    }
}
