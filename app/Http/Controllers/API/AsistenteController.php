<?php

namespace App\Http\Controllers\API;

use App\Models\Asistente;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AsistenteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Asistente::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validacion = $request->validate([
            'name' => 'required',
            'lastName' => 'required',
            'email' => 'required',
            'registro' => 'required',
            'userId' => 'nullable | integer',
            'eventoId' => 'required',
        ]);
        $userId = $validacion['userId'];
        $correo = $validacion['email'];

        if(!$validacion){
            return response()->json([
                'code'=>400, 
                'data'=> $validacion,
            ], 400);
        } else{
            try{
                if($userId == null){
                    $parametro = Asistente::where('email', $correo)->first();
                    if($parametro){
                        return response()->json([
                            'code'=>200, 
                            'data'=> "AsistenciaExistente",
                        ], 200);
                    }else{
                        $asistente = Asistente::create([
                            'name' => $validacion['name'],
                            'lastName' => $validacion['lastName'],
                            'email' => $validacion['email'],
                            'registro' => $validacion['registro'],
                            'userId' => $validacion['userId'],
                            'eventoId' => $validacion['eventoId'],
    
                        ]);
                        return response()->json([
                            'code'=>200, 
                            'data'=> $asistente,
                        ], 200);
                    }

                } else {
                    $existe = Asistente::where('userId', $userId)
                    ->where('eventoId', $validacion['eventoId'])->first();
                    if($existe){
                        return response()->json([
                            'code' => 200,
                            'data' => 'AsistenciaExistente',
                        ], 200);
                    } else {
                        $asistente = Asistente::create([
                            'name' => $validacion['name'],
                            'lastName' => $validacion['lastName'],
                            'email' => $validacion['email'],
                            'registro' => $validacion['registro'],
                            'userId' => $validacion['userId'],
                            'eventoId' => $validacion['eventoId'],
    
                        ]);
                        return response()->json([
                            'code'=>200, 
                            'data'=> $asistente,
                        ], 200);
                    }
                }
            } catch(\throwable $th){
                return response()->json([
                    'code'=>400, 
                    'data'=> $request,
                    'msg' => $th
                ], 400);
            }

        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $asistentes = Asistente::select([
                'asistentes.name',
                'asistentes.lastName',
                'asistentes.email',
                'asistentes.registro',
                'asistentes.userId',
                'asistentes.eventoId'
            ])->where('asistentes.eventoId', $id)->get();

            return response()->json([
                'code' => 200,
                'data' => $asistentes
            ],200);
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
