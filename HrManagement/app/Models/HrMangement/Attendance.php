<?php

namespace App\Models\HrMangement;

use App\Events\AttendanceCheckedOut;
use App\Models\BaseEntity;
use Illuminate\Support\Facades\Auth;

class Attendance extends BaseEntity
{
    protected $dispatchesEvents = [
        'updated' => AttendanceCheckedOut::class, // This will dispatch the event on model update
    ];
    protected static function booted(){
        parent::booted();
        static::creating(function ($model) {
            $model->name = Auth::user()->name;
            $model->employee = Auth::user()->id; // Use the authenticated user's ID
            $model->date = now()->toDateString();
            $model->check_in_time = now()->toTimeString();
        });
    }
}
