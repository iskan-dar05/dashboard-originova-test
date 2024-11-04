<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use Illuminate\Routing\Controllers\HasMiddleware;

class UserController extends Controller implements HasMiddleware
{

    public static function middleware()
    {
        return [
        new Middleware("auth:sanctum"),
         new Middleware('role:admin'),
        ];
    }


      public function index(Request $request)
    {
         $request->validate([
            'query' => 'required|string|min:1',
        ]);

        $query = $request->input('query');
        $users = User::where('name', 'like', '%' . $query . '%')->orWhere('email', 'like', '%' . $query . '%')->orWhere('block', 'like', '%' . $query . '%')->paginate();
        return new UserCollection($users);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //
    
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
       $input = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed',
            'roles' => 'required',
        ]);
        $input['password'] = Hash::make($input['password']);
        $user = User::create($input);
        $user->assignRole($input["roles"]);
        $token = $user->createToken($input["name"]);
        return [
            'user' => new UserResource($user),
            'token' => $token->plainTextToken
        ];
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

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

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $fields = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed',
            'roles' => 'required'
        ]);
        $user->name = $fields['name'];
        $user->email = $fields['email'];
        $user->password = $fields['password'];
        $user->syncRoles($fields['roles']);
        $user->save();
        return new UserResource($user);
    }

   

public function destroy(User $user)
    {
        $user->delete();
        return new UserResource($user);
    }
}
