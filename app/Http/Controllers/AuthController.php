<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use App\Http\Requests\StoreUserRequest;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function index(){
        
        return response()->json([
            'users' => User::paginate(10)
        ]);
    }


    public function login(LoginRequest $request){ 

        $request->validated($request->all());

        if(!Auth::attempt($request->only(['email','password']),$request->filled('remember'))){

            return \response()->json([
                'error' => true,
                'message' => 'This information does not match any of our records.'
            ],401);
        }

        $user = User::where('email', $request->email)->firstOrFail();

        return \response()->json([
            'success' => true,
            'message' => 'You\'re login successfully',
            'user' => $user,
            'token' => $user->createToken('API token of '. $user->name)->plainTextToken
        ]);
        
    }

    public function register(StoreUserRequest $request){

        $request->validated();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \bcrypt($request->password),
            'phone' => $request->phone,
            'role' => $request->role
        ]);

        return \response()->json([
            'success' => true,
            'message' => 'Account created successfully',
            'user' => $user,
            'token' => $user->createToken('API token of '. $user->name)->plainTextToken
        ]);
    }

    public function forgotPassword(Request $request){
        $request->validate(['email' => 'required|email|exists:users,email']);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::ResetLinkSent 
        ?  response()->json(['status' => __($status)])
        : response()->json(['email' => __($status)]); 
    }

    public function resetPassword(Request $request){

        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));
    
                $user->save();
    
                event(new PasswordReset($user));
            }
        );

        return $status === Password::PasswordReset
        ? response()->json(['status' =>  __($status) ])
        : response()->json(['email' => [__($status)]]);
    }

    public function logout(Request $request){

        $request->user()->currentAccessToken()->delete();

        return \response()->json([
            "success" => true,
            "message" => "You're logout successfully"
        ]);
    }

    public function manage(Request $request){

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'status' => 'required|in:actif,suspend'
        ]);

        $user = User::find($request->user_id);

        if(!$user){
           return response()->json([
                'message' => 'User not found'
            ]);
        }

        $user->update([
            'status' => $request->status
        ]);

        return response()->json([
            'message' => 'User\'s status updated successfully',
            'user' => $user
        ]);
    }

    public function role(Request $request){

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|in:admin'
        ]);

        $user = User::find($request->user_id);

        if(!$user){
           return response()->json([
                'message' => 'User not found'
            ]);
        }

        $user->update([
            'role' => $request->role
        ]);

        return response()->json([
            'message' => 'User\'s role updated successfully',
            'user' => $user
        ]);
    }
}
