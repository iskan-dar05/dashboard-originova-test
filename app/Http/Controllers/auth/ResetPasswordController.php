<?php

namespace App\Http\Controllers\auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Models\User;
// use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
class ResetPasswordController extends Controller
{
    public function reset(Request $request)
    {
        $request->validate([
            'token'=>'required',
            'email'=>'required|email',
            'password'=>'required|string|confirmed|min:6'
        ]);
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function(User $user, string $password){
                $user->forceFill([
                    'password' => bcrypt($password),
                    'remember_token' => Str::random(60)
                ])->save();
            }
        );
        return $status === Password::RESET_LINK_SENT ? response()->json([
            'message'=>__($status) 
        ], 200) : response()->json([
            'message'=>__($status)
        ], 400);
    }
}
