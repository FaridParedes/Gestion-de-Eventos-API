<?php

namespace App\Http\Controllers\API;

use App\Models\Favorito;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class FavoritoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
    }

    public function misFavoritos(){
        try {
            $usuario = Auth::user();

            $favoritos = Favorito::where('userId', $usuario->id)
            ->get();

            if($favoritos->isEmpty()){
                return response()->json([
                    'code'=>400, 
                    'mensaje'=> 'no hay favoritos',
                ]);  
            } else{
                return response()->json([
                    'code'=>200, 
                    'data'=> $favoritos,
                ], 200);  
            }

              

        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500); 
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validacion = $request->validate([
            'userId' => 'required',
            'eventoId' => 'required'
        ]);

        if(!$validacion){
            return response()->json([
                'code'=>400, 
                'mensaje'=> "noValidacion",
            ], 400);
        }else{
            $favorito = Favorito::create([
                'userId' => $validacion['userId'],
                'eventoId' => $validacion['eventoId']
            ]);
            return response()->json([
                'code'=>200, 
                'data'=> $favorito,
            ], 200);
        }
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $favorito = Favorito::find($id);
            $favorito->delete();
            return response()->json([
                'code' => 200,
                'data' => 'favoritoEliminado'
            ]);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500); 
        }
    }
}
