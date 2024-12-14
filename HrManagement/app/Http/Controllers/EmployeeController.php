<?php

namespace App\Http\Controllers;

use App\Models\HrMangement\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class EmployeeController extends Controller
{
    public function __construct(Employee $model)
    {
        parent::__construct($model);
    }


    public function save(Request $request){
        $request->validate([
            'email' => "required|email|unique:users,email",
            'name' => "required",
            'salary' => "required",
            "contract_type" => "required",
        ]);
/*        if (User::where("email", $request->email)->first()){
            return response().json(["message" => "user already found"], ResponseAlias::HTTP_BAD_REQUEST);
        }*/
        return DB::transaction(function () use ($request){
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make("test"),
            ]);

            $employee = Employee::create([
                'name' => $request->name,
                'email' => $request->email,
                'user_id' => $user->id,
                'salary' => $request->salary,
                'contract_type' => $request->contract_type
            ]);
            return response()->json(["message" => "done", "data" => $employee], 201);
        });
    }
}
