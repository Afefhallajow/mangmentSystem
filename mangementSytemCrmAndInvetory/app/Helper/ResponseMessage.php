<?php
namespace App\Helper;

class ResponseMessage{

    public static function successResponse($message, $data, $code){
        return response()->json(["message" => $message, "data" => $data], $code);
    }
}
