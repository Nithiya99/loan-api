<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function createUser(Request $req){
        $user = new User;
        $user->name = $req->name;
        $user->user_type = $req->user_type;
        $result=$user->save();
        if($result){
            return $user;
            
            // return $result;
        } else {
            return ["Result"=>"User creation Failed!"];
        }
    }
}
