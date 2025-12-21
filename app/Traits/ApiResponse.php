<?php
namespace App\Traits;

trait ApiResponse{
    protected function success($data=null,$message='success',$code=200){
        return response()->json([
            'status'=> true,
            'message'=>'success',
            'data'=>$data,
        ],$code);
    }

    protected function serverError($message='internal server error',$code=500,$errors=[]){
        return response()->json([
            'status'=>false,
            'message'=>$message,
            'errors'=>$errors
        ],$code);
    } 

    protected function badRequest($message='bad request',$errors=[]){
        return $this->serverError($message,400,$errors);
    }

    protected function unauthorized($message='unauthorized',$code=401){
        return response()->json([
            'status'=>false,
            'message'=>'unauthorized'
        ],$code);
    }
}
