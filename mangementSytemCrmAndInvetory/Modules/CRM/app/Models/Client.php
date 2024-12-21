<?php

namespace Modules\CRM\Models;

use App\Models\Attachment;
use App\Models\BaseEntity;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends BaseEntity
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        $this->fillable = array_merge($this->fillable, [
            "name", "email", "phone", "address"
        ]);

        parent::__construct($attributes);
    }

    protected static function boot(){
        parent::boot();

        static::deleting(function ($client) {
            $client->user()->delete();
        });

        static::updated(function ($client) {
            if ($client->isDirty(['name', 'email'])) {
                $client->user()->update([
                    'name' => $client->name,
                    'email' => $client->email,
                ]);
            }
        });
    }

    function user(){
        return $this->morphOne(User::class, "model");
    }

    /**
     * The attributes that are mass assignable.
     */
    // protected static function newFactory(): ClientFactory
    // {
    //     // return ClientFactory::new();
    // }
}
