<?php

namespace App\Http\Controllers\subadmin;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Routing\Controllers\HasMiddleware;
use App\Jobs\SendNewPostNotification;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
class UserController extends Controller implements HasMiddleware
{

    public static function middleware()
    {
        return [
        new Middleware("auth:sanctum"),
             new Middleware('role:subadmin', ['only'=>'blockUser']),
            // new Middleware('permission:edit', ['only' => ['edit','update']]),
            // new Middleware('permission:delete', ['only' => ['destroy']]),
            // new Middleware('permission:create', ['only' => ['store']]),
        ];
    }




  
    /**
     * Show the form for creating a new resource.
     */
 
    /**
     * Show the form for editing the specified resource.
     */
    public function blockUser(User $user)
    {
             if($user->block){
                return response()->json(["message"=>"user alerdy blocked"])

            }else{
                $user->block = !$user->block;
                $user->save();
                dispatch(new SendNewPostNotification("bon block"));
            }
            return new UserResource($user);
        
    }

 
}