<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\JsonResponse;

class Controller extends BaseController
{
    const PAID = "PAID";
    const PENDING = "PENDING";
    const APPROVED = "APPROVED";
    
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
            'code' => 400
        ], 400);
    }

    public function returnSuccessResponse($msg, $data){
        return response() -> json([
            'message' => $msg,
            'result' => $data,
            'code' => 200
        ], 200);
    }

    public function returnResponse($msg, $code, $err){
        $output = array(
            'error' => $err,
            'code' => $code
        ); 
        $output = array_merge($msg, $output);

        return response() -> json([$output], $code);
    }


    public function returnPositiveRespose($msg, $code){
        $successOutput = array(
            'error' => false,
            'code' => $code
        ); 
        $successOutput = array_merge($msg, $successOutput);

        return response() -> json([$errorOutput], $code);
    }   

    public function returnErrRespose($msg, $code){
        $errorOutput = array(
            'error' => true,
            'code' => $code
        ); 
        $errorOutput = array_merge($msg, $errorOutput);

        return response() -> json([$errorOutput], $code);
    }   


}
