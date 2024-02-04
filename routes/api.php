<?php

use App\Mail\Invitacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MailController;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\LoginController;
use App\Http\Controllers\API\EstadoController;
use App\Http\Controllers\API\EventoController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\API\FavoritoController;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\AsistenteController;
use App\Http\Controllers\API\CategoriaController;
use App\Http\Controllers\API\ComentarioController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware('auth:sanctum')->group(function(){
    Route::get('/user',[LoginController::class, 'user']);
    Route::post('/logout',[LoginController::class, 'logout']);
    Route::put('/user/update',[UserController::class, 'update']);
    Route::post('/events/new',[EventoController::class, 'store']);
    Route::get('/user/events',[EventoController::class, 'myevents']);
    Route::get('/user/events/all',[EventoController::class, 'myEventsCalendar']);
    Route::post('/user/events/filter', [EventoController::class, 'filtroMisEventos']);
    Route::get('/user/events/misasistencias',[EventoController::class, 'misAsistencias']);
    Route::delete('/user/events/misasistencias/destroy/{id}', [EventoController::class, 'destroyAsistencia']);
    Route::get('/events/show/{id}',[EventoController::class, 'show']);
    Route::put('/events/update/{id}',[EventoController::class, 'update']);
    Route::delete('/events/destroy/{id}',[EventoController::class, 'destroy']);
   
    
    Route::get('/user/favoritos', [FavoritoController::class, 'misFavoritos']);
    Route::post('/user/favoritos/add',[FavoritoController::class, 'store']);
    Route::delete('/user/favoritos/destroy/{id}',[FavoritoController::class, 'destroy']);

    Route::post('/comentario/new', [ComentarioController::class, 'store']);

    Route::post('/enviar/mail', [MailController::class, 'enviarInvitacion']);
    Route::post('/google/login/check',[LoginController::class, 'googleCheck']);

    Route::get('/notificar', [EventoController::class, 'notificarEventosProximos']);
    Route::get('/user/asistencias/all', [EventoController::class, 'misAsistenciasCalendar']);
});

Route::post('/registro',[RegisterController::class, 'store']);
Route::post('/login/auth',[LoginController::class,'check']);


Route::get('/categorias',[CategoriaController::class, 'index']);
Route::get('/estados',[EstadoController::class, 'index']);
Route::get('/events', [EventoController::class, 'index']);
Route::get('/event/show/{id}', [EventoController::class, 'informacion']);
Route::get('/ubicaciones', [EventoController::class, 'ubicaciones']);
Route::post('/events/filter',[EventoController::class, 'filtro']);

Route::post('/asistente/new',[AsistenteController::class, 'store']);
Route::get('/asistentes',[AsistenteController::class, 'index']);
Route::get('/asistentes/{id}',[AsistenteController::class, 'show']);

Route::get('/comentarios/{id}', [ComentarioController::class, 'show']);

