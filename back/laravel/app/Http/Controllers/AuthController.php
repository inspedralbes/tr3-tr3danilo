<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'No estás registrado'], 401);
        }
        $token = $user->createToken('api_access')->plainTextToken;
        $response = [
            'user_id' => $user->id, // Incluir el ID del usuario en la respuesta
            'token' => $token,
            'message' => 'Inicio de sesión exitoso'
        ];

        return response()->json($response, 200);
    }
    /**
     * Handle user registration.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:3',
        ]);
    
        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
    
        $user->save();
    
        $token = $user->createToken('api_access')->plainTextToken;
    
        $response = [
            'user' => $user,
            'token' => $token,
            'message' => 'Usuario registrado con éxito'
        ];
    
        return response()->json($response, 201);
    }
}
