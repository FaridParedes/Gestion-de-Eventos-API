<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\Sanctum;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validacion = $request->validate([
            'id' => 'required',
            'name'=> 'required',
            'lastName'=> 'required',
            'fecNac'=> 'required',
            'fotoPerfil' => 'nullable | string'
        ]);

        $id = $validacion['id'];

        if(!$validacion){
            return response()->json([
                'code'=>400, 
                'data'=> $validacion,
            ], 400);
        } else{
            $usuario = User::find($id);
            if($usuario){
                 $usuario->update([
                     'name'=> $validacion["name"],
                     'lastName'=> $validacion["lastName"],
                     'fecNac'=> $validacion["fecNac"],
                     'fotoPerfil' => $validacion["fotoPerfil"],
                 ]);
                return response()->json([
                    'code'=> 200,
                    'message' => 'Usuario Actualizado',
                    'data' => $usuario
                ], 200);
            } else {
                return response()->json([
                    'code'=> 404,
                    'data' => 'Usuario no localizado',
                ], 404);
            }
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
