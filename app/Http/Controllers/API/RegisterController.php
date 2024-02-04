<?php

namespace App\Http\Controllers\API;


use Hash;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
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
        $input = $request->all();

        $email = $input['email'];

        $user = User::where('email', $email)->first();

        if($user){
            return response()->json([
                'status' => false,
                'message' => 'El correo ya existe en la base de datos.'
            ]);
        }

        User::create([
            'name'=> $input['name'],
            'lastName'=> $input['lastName'],
            'email'=> $input['email'],
            'fecNac'=> $input['fecNac'],
            'rol'=> $input['rol'],
            'password'=> Hash::make($input['password']),
        ]);
        return response()->json([
            'status'=> true,
            'message'=> "Registro correcto"
        ]);
        
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
        //
    }
}
