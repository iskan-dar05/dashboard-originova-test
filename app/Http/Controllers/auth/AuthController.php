<?php

namespace App\Http\Controllers\auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{
  
    public function login(Request $request)
    {
        $request->validate([
            'email'=>'required|email|exists:users',
            'password'=>'required'
        ]);
        $user = User::where('email', $request->email)->first();
        if(!$user || !Hash::check($request->password, $user->password)){
            return [
            'message'=>'The provided credentials are incorrect'
            ];
        }
        if ($user->blocked)
        {
            return "you are blocked";
        }
        $token = $user->createToken($user->name);
        return[
            'user'=>$user,
            'token'=>$token->plainTextToken
        ];
    }
    public function logout(Request $request)
    {
        $request()->user()->tokens()->delete();
        return "logged out";
    }
    public function sendResetPassword(Request $request)
    {
        $request->validate(['email'=>'required|email']);
        $status = Password::sendResetLink($request->only('email'));
        return $status === Password::RESET_LINK_SENT
            ? response()->json([
                'message'=>__($status)
            ], 200) : response()->json([
                'message'=>__($status)
            ], 400);
    }
}





















