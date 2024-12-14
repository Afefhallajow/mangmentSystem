<?php

namespace App\Models\HrMangement;

use App\Extra\ContractType;
use App\Models\BaseEntity;
use InvalidArgumentException;

class Employee extends BaseEntity{
    public function __construct(array $attributes = [])
    {
        $this->fillable = array_merge($this->fillable, [
            "email", "salary", "contract_type", "user_id"
        ]);
        parent::__construct($attributes);
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
