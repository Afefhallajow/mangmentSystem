<?php

namespace App\Http\Controllers\ProjectController;

use App\Extra\Priority;
use App\Extra\TaskStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\Project;
use App\Models\Project\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class TaskController extends Controller
{
    public function __construct(Task $model)
    {
        parent::__construct($model);
    }

    public function getAll()
    {$tasks = Task::with(['project:id,name,creator'])->get();
    return response()->json(
            $tasks,
            Response::HTTP_OK);
    }

    public function getTaskStatus(){
        return response()->json(TaskStatus::getAll(), ResponseAlias::HTTP_OK);
    }

    public function getTaskPriority(){
        return response()->json(Priority::getAll(), ResponseAlias::HTTP_OK);
    }

    public function addTaskToUsers(Request $request){
        $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'user_ids' => 'required|array', // Ensure it's an array
            'user_ids.*' => 'exists:users,id', // Each user ID must exist in the 'users' table
            'user_ids' => function ($attribute, $value, $fail) use ($request) {
                // Loop through each user ID in the 'user_ids' array
                foreach ($request->user_ids as $userId) {
                    // Check if the user is already assigned to the task
                    $task = Task::find($request->task_id);
                    if ($task && $task->users()->where('user_id', $userId)->exists()) {
                        $fail("User with ID $userId is already assigned to this task.");
                    }
                }
            },
        ]);

        $task = Task::find($request->task_id);
        $task->users()->attach( $task->users()->attach($request->user_ids));
        return response()->json(['message' => 'Users assigned successfully']);
    }

    public function getReportForProjects(){
        return  Project::collection(Project::get());
    }
}
