<?php

namespace App\Http\Controllers;

use App\Extra\TaskStatus;
use App\Models\BaseEntity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class testController extends Controller
{
    public function __construct(BaseEntity $model)
    {
        parent::__construct($model);
    }

    public function test (){
        return TaskStatus::Done->name;
    }

}
