<?php

namespace App\Http\Controllers;

use App\Helper\ResponseMessage;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function getAllPermission(){
        return ResponseMessage::successResponse("done", Permission::all(), 200);
    }
    public function getAllPermissionForUser(){

        $user = Auth::user();
        if (!$user) return ResponseMessage::errorResponse("not found", 200);
        return ResponseMessage::successResponse(
            "done",
            $user->getAllPermissions()->pluck('name')->toArray(),
            200);
    }
}
