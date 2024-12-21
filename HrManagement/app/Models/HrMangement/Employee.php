<?php

namespace App\Models\HrMangement;

use App\Extra\ContractType;
use App\Models\BaseEntity;
use App\Models\User;
use InvalidArgumentException;

class Employee extends BaseEntity{
    public function __construct(array $attributes = [])
    {
        $this->fillable = array_merge($this->fillable, [
            "email", "salary", "contract_type", "address"
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

    protected $casts = [
        'contract_type' => ContractType::class
    ];

    public function setStatusAttribute($value): void
    {
        // Check if the value is a valid TaskStatus enum value
        if (!ContractType::tryFrom($value)) {
            throw new InvalidArgumentException("Invalid status: $value");
        }

        $this->attributes['contract_type'] = $value;
    }}
