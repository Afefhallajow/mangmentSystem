<?php

namespace App\Models\HrMangement;

use App\Models\BaseEntity;
use Illuminate\Database\Eloquent\Model;

class WorkHoursReport extends BaseEntity
{
    public function __construct(array $attributes = []){
        $this->fillable = array_merge($this->fillable, [
            "employee", "worked_hours", "report_date"
        ]);
        parent::__construct($attributes);
    }

}
