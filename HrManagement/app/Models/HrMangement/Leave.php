<?php

namespace App\Models\HrMangement;

use App\Extra\ContractType;
use App\Extra\LeaveStatus;
use App\Extra\LeaveType;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class Leave extends Model
{
    public function __construct(array $attributes = [])
    {
        $this->fillable = array_merge($this->fillable, [
            "employee", "type", "status", "startDate", "endDate"
        ]);
        parent::__construct($attributes);
    }

    protected $casts = [
       "status" => LeaveStatus::class,
        "type" => LeaveType::class
    ];

    public function setStatusAttribute($value){
        if (!LeaveStatus::tryFrom($value)) {
            throw new InvalidArgumentException("Invalid status: $value");
        }
        $this->attributes["status"] = $value;
    }

    public function setSTypeAttribute($value) : void{
        if (!LeaveType::tryFrom($value)) {
            throw new InvalidArgumentException("Invalid Type: $value");
        }
        $this->attributes["type"] = $value;
    }
}
