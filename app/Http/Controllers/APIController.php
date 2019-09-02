<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class APIController extends Controller
{
    public function appUserLogin(Request $request)
    {
        $user = User::where('username', $request->get('username'))->first();
        if ($user)
        {
            if(Hash::check($request->get('password'), $user->password)){
                return response()->json(["status"=>"success","data" =>$user]);
            }else{
                return response()->json(["status"=>"fail","message" =>'Invalid password']);

            }
        }else {
            return response()->json(["status"=>"fail","message" =>'Invalid Username']);
        }
    }

    public function appUserRegister(Request $request)
    {
        if(User::where('username', $request->get('username'))->get()->count() > 0)
        {
            return response()->json(["status"=>"fail","message" =>'The username is already in use']);
        }
        else
        {
            $user = User::create([
                "username"=>$request->username,
                "password" => bcrypt($request->password),
                "name" => $request->name,
                ]
            );
        }
    }
}
