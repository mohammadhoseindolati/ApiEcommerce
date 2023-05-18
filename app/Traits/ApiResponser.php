<?php

namespace App\Traits ;

trait ApiResponser {

    protected function successResponse($data , $code , $message = null){
        return response()->json([
            'status' => 'success' ,
            'message' => $message ,
            'data' => $data
        ] , $code) ;
    }

    protected function errorResponse($message, $code ){
        return response()->json([
            'status' => 'error' ,
            'message' => $message ,
            'data' => ''
        ] , $code) ;
    }
}
