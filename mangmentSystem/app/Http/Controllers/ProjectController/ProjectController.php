<?php

namespace App\Http\Controllers\ProjectController;

use App\Extra\TaskStatus;
use App\Http\Controllers\Controller;
use App\Models\Project\Project;
use App\Models\Project\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    public function __construct(Project $model)
    {
        parent::__construct($model);
    }

    public function getTasksForProject($id){
        return response()->json(Project::find($id)->tasks, Response::HTTP_OK);
    }

    public function getProjectsStatus(Request $request){
        if (!$request->date){
            $date = Carbon::now()->subMonth();
        } else {
            $date = Carbon::parse($request->date);
        }
        $projectsSummary = Project::select(
            DB::raw('COUNT(*) as total_projects'),
            DB::raw('SUM(CASE
                     WHEN frozen_tasks_count > 0 THEN 1
                     WHEN tasks_count > 0 AND tasks_count = done_tasks_count THEN 1
                     WHEN tasks_count > 0 AND tasks_count = pending_tasks_count THEN 1
                     WHEN active_tasks_count > 0 THEN 1
                     ELSE 0
                 END) as categorized_projects'),
            DB::raw('SUM(CASE WHEN frozen_tasks_count > 0 THEN 1 ELSE 0 END) as freezing_projects'),
            DB::raw('SUM(CASE WHEN frozen_tasks_count = 0 AND tasks_count > 0 AND tasks_count = done_tasks_count THEN 1 ELSE 0 END) as done_projects'),
            DB::raw('SUM(CASE WHEN frozen_tasks_count = 0 AND active_tasks_count = 0 AND tasks_count > 0 AND tasks_count = pending_tasks_count THEN 1 ELSE 0 END) as pending_projects'),
            DB::raw('SUM(CASE WHEN frozen_tasks_count = 0 AND active_tasks_count > 0 THEN 1 ELSE 0 END) as active_projects')
        )
            ->leftJoinSub(
                Task::select(
                    'project_id',
                    DB::raw('COUNT(*) as tasks_count'),
                    DB::raw('SUM(CASE WHEN status = "done" THEN 1 ELSE 0 END) as done_tasks_count'),
                    DB::raw('SUM(CASE WHEN status = "freeze" THEN 1 ELSE 0 END) as frozen_tasks_count'),
                    DB::raw('SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending_tasks_count'),
                    DB::raw('SUM(CASE WHEN status = "active" THEN 1 ELSE 0 END) as active_tasks_count')
                )
                    ->groupBy('project_id'),
                'tasks',
                'projects.id',
                '=',
                'tasks.project_id'
            )
            ->first();
        return $projectsSummary;
    }
}
