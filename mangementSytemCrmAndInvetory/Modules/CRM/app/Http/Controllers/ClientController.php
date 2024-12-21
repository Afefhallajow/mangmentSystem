<?php

namespace Modules\CRM\Http\Controllers;

use App\Extra\UserType;
use App\Helper\ResponseMessage;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\CRM\Models\Client;
use Modules\CRM\Models\Employee;

class ClientController extends Controller{

    public function __construct(Client $model){
        parent::__construct($model);
    }

    public function Save(Request $request)
    {
        return DB::transaction(function () use ($request){
            Log::info($request->all());
            $client = Client::create([
               "name" => $request->name,
               "email" => $request->email,
               "phone" => $request->phone,
               "address" => $request->address,
            ]);
            $client->user()->create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                "type" => UserType::Client
            ]);
            return response()->json(['data' => $client]);
        });
    }

    public function getUsersCount(){
        return ResponseMessage::successResponse("done",
            [
                "UserCount" => User::count(),
                "ClientCount" => Client::count(),
                "EmployeeCount" => Employee::count()
            ], 200);

    }
}
