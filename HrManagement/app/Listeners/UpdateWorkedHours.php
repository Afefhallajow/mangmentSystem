<?php

namespace App\Listeners;

use App\Events\AttendanceCheckedOut;
use App\Models\HrMangement\WorkHoursReport;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;
use SebastianBergmann\CodeCoverage\Report\Xml\Report;

class UpdateWorkedHours
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(AttendanceCheckedOut $event): void{
        $attendance = $event->attendance;
        if (!$attendance->check_out_time)return;

        $checkInTime = Carbon::parse($attendance->check_in_time);
        $checkOutTime = Carbon::parse($attendance->check_out_time);
        $workedHour = $checkInTime->diffInMinutes($checkOutTime)/60;
        $report = WorkHoursReport::where("employee", Auth::user()->id)
            ->whereMonth('report_date', Carbon::now()->month) // Check if the month is the same as the current month
            ->whereYear('report_date', Carbon::now()->year)  // Check if the year is the same as the current year
            ->first();
        echo $workedHour;
        if (!$report){
            echo "from if  ";
            $report = WorkHoursReport::create([
                "name" => Auth::user()->name,
                "employee" => Auth::user()->id,
                "worked_hours" => $workedHour != null ? $workedHour : 0,
                "report_date" => now()->toDateString()
            ]);
            echo $report;
        } else {
            $report->worked_hours += $workedHour;
            $report->save();
        }
    }
}
