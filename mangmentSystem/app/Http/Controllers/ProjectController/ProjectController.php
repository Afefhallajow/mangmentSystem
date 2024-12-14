<?php

namespace App\Http\Controllers\ProjectController;

use App\Http\Controllers\Controller;
use App\Models\Project\Project;
use Illuminate\Http\Response;

class ProjectController extends Controller
{
    public function __construct(Project $model)
    {
        parent::__construct($model);
    }

    public function getTasksForProject($id){
        return response()->json(Project::find($id)->tasks, Response::HTTP_OK);
    }
}
