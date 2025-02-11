<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthContoller extends Controller
{
    //
    
    public function login(Request $request)
    {
        $data=$request->validate(
        [
        'email'=>$request->email,
        'password'=>$request->password
        ]
        );
        
        if(auth()->attempt([
        'email'=>request()->input('email'),
        'password'=>request()->input('password'),
        ]))
    }
}
