<?php

namespace App\Http\Controllers;


use App\Helper\ResponseMessage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use \Illuminate\Http\Request;

abstract class Controller{

    protected $model;

    public function __construct(Model $model){
        $this->model = $model;
    }

    public  function getAll(){
        return response()->json(
            $this->model->get(),
            Response::HTTP_OK);
    }

    public  function getOne($id){
        $temp = $this->model->find($id);
        if(!$temp)return response()->json("not found", Response::HTTP_BAD_REQUEST);
        return response()->json($temp, Response::HTTP_OK);
    }

    public function save(Request $object){
        $temp = new $this->model($object->all());
        $temp->save();
        return ResponseMessage::successResponse( "done",  $temp, 201);
    }

    public function update(Request $object){
        $temp = $this->model->find($object->id);
        if(!$temp)return response()->json("not found", Response::HTTP_BAD_REQUEST);
        $temp->fill($object->all());
        $temp->save();
        return response()->json($temp, 201);
    }
}
