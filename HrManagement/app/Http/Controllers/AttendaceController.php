<?php

namespace App\Http\Controllers;

use App\Helper\ResponseMessage;
use App\Models\HrMangement\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendaceController extends Controller
{
    public function __construct(Attendance $model){
        parent::__construct($model);
    }

    public function Save(Request $request){
        $oldEntity =Attendance::where("employee", Auth::user()->id)
            ->where("date", now()->toDateString())
            ->whereNull("check_out_time")
            ->first();
        if ($oldEntity) {
            $oldEntity->check_out_time = now()->toTimeString();
            $oldEntity->save();
            return ResponseMessage::successResponse("done", $this->model, 201);
        }
        $newEntity = new Attendance();
        $newEntity->save();
        return ResponseMessage::successResponse("done", $newEntity, 201);
    }
}
