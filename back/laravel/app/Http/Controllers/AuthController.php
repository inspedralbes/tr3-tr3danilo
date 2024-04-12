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
            'user_id' => $user->id, 
            'token' => $token,
            'role' => $user->role, 
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
            'role' => 'nullable|in:user,admin',
        ]);

        $role = $request->input('role', 'user');

        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $role,
        ]);
    
        $user->save();
    
        $token = $user->createToken('api_access')->plainTextToken;
    
        $response = [
            'user' => $user,
            'token' => $token,
            'message' => 'Usuario registrado con éxito',
            'role' => $role,
        ];
    
        return response()->json($response, 201);
    }

    public function obtenRolUsuario($id)
    {
        $user = User::find($id);

    if ($user) {
        return response()->json(['role' => $user->role]);
    }

    return response()->json(['error' => 'Usuario no encontrado'], 404);
    }

    /**
     * Delete a user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function borrarUsuario($id)
    {
        $user = User::find($id);

        if ($user) {
            $user->delete();
            return response()->json(['message' => 'Usuario eliminado con éxito']);
        }

        return response()->json(['error' => 'Usuario no encontrado'], 404);
    }


    
}
