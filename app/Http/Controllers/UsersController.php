<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Users;

class UsersController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nombres' => 'required|string',
            'apellidos' => 'required|string',
            'email' => 'required|email',
            'telefono' => 'required|string',
            'direccion' => 'required|string',
        ]);

        $existeCorreo = Users::where('email', $request->email)->first();

        if ($existeCorreo) {
            return response()->json(['message' => 'El correo electrónico ya está registrado'], 200);
        }

        $usuario = new Users();
        $usuario->nombres = $request->nombres;
        $usuario->apellidos = $request->apellidos;
        $usuario->email = $request->email;
        $usuario->telefono = $request->telefono;
        $usuario->direccion = $request->direccion;
        $usuario->deleteUsers = false;
        $usuario->save();

        return response()->json(['message' => 'Usuario creado exitosamente', 'usuario' => $usuario], 201);
    }

    public function index()
    {
        $usuarios = Users::where('deleteUsers', false)->get();
        return response()->json(['usuarios' => $usuarios], 200);
    }

    public function show($id)
    {
        $usuario = Users::find($id);

        if (!$usuario) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        return response()->json(['usuario' => $usuario], 200);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombres' => 'required|string',
            'apellidos' => 'required|string',
            'email' => 'required|email',
            'telefono' => 'required|string',
            'direccion' => 'required|string',
        ]);
        $existeCorreo = Users::where('email', $request->email)->where('id', '!=', $id)->first();

        if ($existeCorreo) {
            return response()->json(['message' => 'El correo electrónico ya está registrado en otro usuario'], 200);
        }

        $usuario = Users::findOrFail($id);

        $usuario->update([
            'nombres' => $request->nombres,
            'apellidos' => $request->apellidos,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
        ]);

        return response()->json(['message' => 'Usuario actualizado exitosamente', 'usuario' => $usuario], 201);
    }

    public function destroy($id)
    {
        $usuario = Users::find($id);

        if (!$usuario) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        $usuario->deleteUsers = true;
        $usuario->save();

        return response()->json(['message' => 'Usuario eliminado exitosamente'], 200);
    }
}
