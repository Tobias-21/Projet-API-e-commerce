<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreUserRequest;

class AuthController extends Controller
{
    public function login(LoginRequest $request){ 

        $request->validated($request->all());

        if(!Auth::attempt($request->only(['email','password']))){

            return \response()->json([
                'error' => true,
                'message' => 'Ces informations ne coresspondent à aucun de nos enregistrement'
            ],401);
        }

        $user = User::where('email', $request->email)->firstOrFail();

        return \response()->json([
            'success' => true,
            'message' => 'Vous êtes connecté avec succès',
            'user' => $user,
            'token' => $user->createToken('API token of '. $user->name)->plainTextToken
        ]);
        
    }

    public function register(StoreUserRequest $request){

        $request->validated($request->all());

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \bcrypt($request->password),
            'phone' => $request->phone,
        ]);

        return \response()->json([
            'success' => true,
            'message' => 'Votre compte a été créé avec succès',
            'user' => $user,
            'token' => $user->createToken('API token of '. $user->name)->plainTextToken
        ]);
    }

    public function logout(Request $request){

        $request->user()->currentAccessToken()->delete();

        return \response()->json([
            "success" => true,
            "message" => "Vous êtes déconnecté avec succès"
        ]);
    }

}
