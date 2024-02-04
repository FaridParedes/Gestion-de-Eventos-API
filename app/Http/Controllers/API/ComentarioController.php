<?php

namespace App\Http\Controllers\API;

use App\Models\Comentario;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ComentarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validacion = $request->validate([
            'eventId' => 'required',
            'userId' => 'required',
            'comentario' => 'required',
            'fecha' => 'required',
        ]);

        if(!$validacion){
            return response()->json([
                'code'=>400, 
                'data'=> $validacion,
            ], 400);
        }else{
            $comentario = Comentario::create([
                'eventId' => $validacion['eventId'],
                'userId' => $validacion['userId'],
                'comentario' => $validacion['comentario'],
                'fecha' => $validacion['fecha'],
            ]);
            return response()->json([
                'code'=>200, 
                'data'=> $comentario,
            ], 200);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {   try {
        $comentarios =  Comentario::select([
            'comentarios.eventId',
            'comentarios.userId',
            'comentarios.comentario',
            'comentarios.fecha',
            'users.name as usuarioName',
            'users.lastName as usuarioLastName',
            'users.fotoPerfil as usuarioFoto'
        ])->join("users", "users.id", "=", "comentarios.userId")
        ->where('comentarios.eventId', $id)->get();

        return response()->json([
            'code'=>200, 
            'data'=> $comentarios,
        ], 200);   

    } catch (\Throwable $th) {
        return response()->json($th->getMessage(), 500);
    }



    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
