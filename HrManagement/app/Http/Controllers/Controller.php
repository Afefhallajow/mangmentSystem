<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use \Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

abstract class Controller
{
    protected Model $model;

    public function __construct(Model $model){
        $this->model = $model;
    }

    public function getAll(){
       return response().json($this->model->get(), ResponseAlias::HTTP_OK);
    }
    public function getOne($id){
        $temp = $this->model->find($id);
        if(!$temp)return response()->json("not found", ResponseAlias::HTTP_BAD_REQUEST);
        return response().json($temp, ResponseAlias::HTTP_OK);
    }

    public function Save(Request $request){
        $temp = new $this->model->fill($request->all());
        $temp->save();
        return response()->json("done", 201);
    }

    public function Update(Request $request){
        $temp = $this->model->find($request->id);
        if(!$temp)return response()->json("not found", Response::HTTP_BAD_REQUEST);
        $temp->fill($request->all());
        $temp->save();
        return response()->json($temp, 201);
    }

    public function Delete($id){
        $temp = $this->model->find($id);
        if(!$temp)return response()->json("not found", Response::HTTP_BAD_REQUEST);
        $temp->delete();
    }
}
