<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\JsonResponse;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    // Unauthorized Error Response
    public function returnUnauthorizedResponse(){
        return response() -> json([
            'message' => 'You are unauthorized to perform this action.',
            'error' => true,
            'code' => 401
        ], 401);
    }

    // Error response with 
    public function returnErrorRespose($msg){
        return response() -> json([
            'message' => $msg,
            'error' => true,
            'code' => 500
        ], 500);
    }

    public function returnSuccessResponse($msg, $data){
        return response() -> json([
            'message' => $msg,
            'result' => $data,
            'code' => 200
        ], 200);
    }
}
