<?php

namespace Modules\CRM\Models;

use Extra\InteractionStatus;
use Extra\InteractionType;
use http\Exception\InvalidArgumentException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// use Modules\CRM\Database\Factories\ClientInteractionFactory;

class ClientInteraction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ["client_id", "type", "status", "details"];
    protected $casts = [
        "type" => InteractionType::class,
        "status" => InteractionStatus::class
    ];

    public function setTypeAttribute($value){
        if (InteractionType::tryFrom($value)){
            throw new InvalidArgumentException("invalid value for type " .$value);
        }
        $this->attributes["type"] = $value;
    }

    public function setStatusAttribute($value){
        if (InteractionStatus::tryFrom($value)){
            throw new InvalidArgumentException("invalid value for status " .$value);
        }
        $this->attributes["status"] = $value;
    }

    // protected static function newFactory(): ClientInteractionFactory
    // {
    //     // return ClientInteractionFactory::new();
    // }
    public function client(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Client::class, "client_id");
    }
}
