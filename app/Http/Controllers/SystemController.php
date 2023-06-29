<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;
use App\Models\User;

class SystemController extends Controller
{
  public function login(Request $request)
  {
    if(Auth::attempt(['login'=>$request->login,"password"=>$request->password])){
      $token = "Bearer " . Auth::user()->createToken($request->login)->plainTextToken;
      return response()->json([
          "token" => $token
      ]);
    }
    return response()->json([
      "login or password is wrong!!!!"
    ]);
  }

  public function register(Request $request)
  {

    $validator = Validator::make($request->all(), [
      "first_name"=>["required"],
      "last_name"=>["required"],
      "login"=>["required", "unique:users"],
      "password"=>["required", "confirmed"]
    ]);


    if ($validator->fails()){
      return response()->json([
        'error'=>$validator->errors()
      ]);
    } else {
      User::create([
        "login"=>$request->login,
        "password"=>bcrypt($request->password),
        "first_name"=>$request->first_name,
        "last_name"=>$request->last_name,
        "registration_date"=>now(),
        "birth_date"=>$request->birth_date
      ]);

      if(Auth::attempt(["login"=>$request->login,"password"=>$request->password])){
        $token = "Bearer " . Auth::user()->createToken("Bearer")->plainTextToken;
        return response()->json([
          "token" => $token
        ]);
      }
    }

  }
}
