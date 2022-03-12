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

    public function returnResponse($msg, $code, $err){
        $output = array(
            'error' => $err,
            'code' => $code
        ); 
        $output = array_merge($msg, $output);

        return response() -> json([$output], $code);
    }
}
