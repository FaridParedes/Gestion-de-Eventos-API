<?php

namespace App\Http\Controllers\API;

use Hash;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Sanctum\Sanctum;
use App\Mail\ResetPasswordEmail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Foundation\Auth\ResetsPasswords;
use App\Notifications\ResetPasswordNotification;




class LoginController extends Controller
{

    public function check(Request $request){
        $credentials = $request -> validate([
            'email'=>['required', 'email'],
            'password' => ['required'],
        ]);

        $email = $credentials['email'];
        $usuario = User::where('email',$email)->first();

        if(Auth::attempt($credentials)){
            $user = Auth::User();
            $token = $user->createToken('token')->plainTextToken;
            $cookie = cookie('jwt', $token, 60*24);

            // return response()->json([
            //     'status'=> true,
            //     'message' => 'Success',
            //     'body' => $user
            // ]);
                return response()->json([
                    'status'=> true,
                    'access_token' => $token,
                    'user' => $user
                ])->withCookie($cookie);
        }
        return response()->json([
            'status'=> false,
            'message'=> 'Fail',
        ]);
    }

    public function googleCheck(Request $request){
        if (auth()->check() && auth()->user()->id == $request->userId) {
            $user = Auth::user();
            $token = auth()->user()->createToken('token')->plainTextToken;
            return response()->json([
                'status'=> true,
                'access_token' => $token,
                'user' => $user
                ]);
        } else {
            return response()->json([
                'status'=> false,
                'message'=> 'Fail',
            ], 401);
        }
    }

    public function user(){
        return Auth::user();
    }

    public function logout( Request $request){
        try {
            $user = User::find($request->id);
            if(!$user){
                return response()->json([
                    'code' => 400,

                ],400);
            } else{
                Auth::guard('web')->logout();
                return response()->json([
                    'code' => 200,
                    'data' => 'Se cerró la sesión correctamente'
                ],200);
            }
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500);
        }
    }

    public function forgotPassword(Request $request){
        $validacion = $request->validate([
            'email' => 'required|email'
        ]);
        if(!$validacion){
            return response()->json([
                'code'=>400, 
                'mensaje'=> "errorValidacion",
            ], 400);
        } else{
            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return response()->json(['error' => 'Correo electrónico no encontrado'], 400);
            }
            $status = Password::sendResetLink(
                $request->only('email')
            );
        
            if ($status === Password::RESET_LINK_SENT) {
                // Obtenemos el token recién creado
                $token = Password::getRepository()->create($this->getCredentials($request));
        
                // Generamos la URL con el token y el email
                $resetLink = "http://localhost:8080/forgot-password/{$request->email}/$token";
        
                // Enviamos el correo electrónico
                Mail::to($request->email)->send(new ResetPasswordEmail($resetLink));
        
                return response()->json(['message' => 'Link de restablecimiento de contraseña enviado por correo']);
            } else {
                return response()->json(['message' => 'No se pudo enviar el enlace de restablecimiento de contraseña']);
            }
        }
    }

    protected function getCredentials(Request $request)
    {
        return $request->only('email');
    }

    public function resetPassword(){

    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
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
