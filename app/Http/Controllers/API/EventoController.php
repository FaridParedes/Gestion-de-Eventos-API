<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Evento;
use App\Models\Imagen;
use App\Models\Favorito;
use App\Models\Asistente;
use App\Models\Categoria;
use App\Models\Comentario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Notifications\EventoProximoNotification;
use App\Notifications\EventoActualizadoNotification;

class EventoController extends Controller
{
    public function notificarEventosProximos(){
        try {
            $ahora = Carbon::now();
            $proximas24Horas = $ahora->copy()->addHours(24);
            $eventosProximos = Evento::where('start', '>', $ahora)
            ->where('start', '<=', $proximas24Horas)
            ->get();
    
            foreach ($eventosProximos as $evento) {
                $asistentes = Asistente::where('eventoId', $evento->id)->get();
                if($asistentes->isEmpty()){
                    $mensaje = "No hay asistentes";
                } else{
                    foreach ($asistentes as $asistente) {
        
                        $asistente->notify(new EventoProximoNotification($evento));
                    }
                    $mensaje = "Notificacion enviada";
                }
            }
    
            return response()->json([
                'code'=>200, 
                'data'=> 'evento actualizado',
                'mensaje' => $mensaje
            ], 200);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500);
        }

    }


    public function index()
    {
        $now = Carbon::now();

        $Eventos = Evento::select(
            "eventos.id",
            "eventos.title",
            "eventos.description",
            "eventos.allDay",
            "eventos.start",
            "eventos.end",
            "eventos.session",
            "eventos.ubication",
            "eventos.enlace",
            "eventos.organizadorId",
            "users.name as organizador",
            "users.lastName as organizadorLastName",
            "categorias.name as categoria",
            "eventos.estadoId"
        )->join("users", "users.id", "=", "eventos.organizadorId")
        ->join("categorias", "categorias.id", "=", "eventos.categoriaId")
        ->where("eventos.start", ">", $now)
        ->paginate(6);
        
        foreach ($Eventos as $Evento) {
            $imagenes = Imagen::select("imagenes.imagen as imagen")
            ->where("imagenes.eventoId", "=", $Evento->id)
            ->pluck('imagen')->toArray();

            $Evento->imagen = $imagenes;
        }

        if(count($Eventos)== 0){
            return response()->json([
                'code'=>400, 
                'data'=> "No hay eventos",
            ], 400);
        } else{
            return response()->json([
                'code'=>200, 
                'data'=> $Eventos,
            ], 200);    
        }
    }

    public function myevents(){
        try {
            $usuario = Auth::user();

            $MyEvents = Evento::select(
                "eventos.id",
                "eventos.title",
                "eventos.description",
                "eventos.allDay",
                "eventos.start",
                "eventos.end",
                "eventos.session",
                "eventos.ubication",
                "eventos.enlace",
                "users.name as organizador",
                "users.lastName as organizadorLastName",
                "categorias.name as categoria",
                "eventos.estadoId"
            )->join("users", "users.id", "=", "eventos.organizadorId")
            ->join("categorias", "categorias.id", "=", "eventos.categoriaId")
            ->where("organizadorId", $usuario->id)->paginate(6);

            foreach ($MyEvents as $Evento) {
                $imagenes = Imagen::select("imagenes.imagen as imagen")
                ->where("imagenes.eventoId", "=", $Evento->id)
                ->pluck('imagen')->toArray();

                $Evento->imagen = $imagenes;
            }
            
            if($MyEvents == null){
                return response()->json([
                    'code'=>400, 
                    'data'=> "El usuario no posee eventos",
                ], 400);
            } else{
                return response()->json([
                    'code'=>200, 
                    'data'=> $MyEvents,
                ], 200);    
            }

        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500); 
        }

    }

    public function myEventsCalendar(){
        try {
            $usuario = Auth::user();

            $MyEvents = Evento::select(
                "eventos.id",
                "eventos.title",
                "eventos.description",
                "eventos.allDay",
                "eventos.start",
                "eventos.end",
                "eventos.session",
                "eventos.ubication",
                "eventos.enlace",
                "users.name as organizador",
                "categorias.name as categoria",
                "eventos.estadoId"
            )->join("users", "users.id", "=", "eventos.organizadorId")
            ->join("categorias", "categorias.id", "=", "eventos.categoriaId")
            ->where("organizadorId", $usuario->id)->get();

            foreach ($MyEvents as $Evento) {
                $imagenes = Imagen::select("imagenes.imagen as imagen")
                ->where("imagenes.eventoId", "=", $Evento->id)
                ->pluck('imagen')->toArray();

                $Evento->imagen = $imagenes;
            }
            
            if($MyEvents == null){
                return response()->json([
                    'code'=>400, 
                    'data'=> "El usuario no posee eventos",
                ], 400);
            } else{
                return response()->json([
                    'code'=>200, 
                    'data'=> $MyEvents,
                ], 200);    
            }

        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500); 
        }
    }

    public function misAsistenciasCalendar(){
        try {
            $user = Auth::user();

            $Eventos = Evento::select(
                "eventos.id",
                "eventos.title",
                "eventos.allDay",
                "eventos.start",
                "eventos.end",
            )->whereExists(function ($query) use ($user) {
                $query->select(DB::raw(1))
                    ->from('asistentes')
                    ->whereColumn('asistentes.eventoId', 'eventos.id')
                    ->where('asistentes.userId', $user->id);
            })->get();

            if($Eventos->isEmpty()){
                return response()->json([
                    'code'=> 400,
                    'mensaje' => 'No se encontraron eventos',
                ]);
            }else{
                return response()->json([
                    'code'=>200, 
                    'data'=> $Eventos,
                ], 200); 
            }

        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500); 
        }
    }

    public function misAsistencias(){
        try {
            $user = Auth::user();
            $Eventos = Evento::select(
                "eventos.id",
                "eventos.title",
                "eventos.description",
                "eventos.allDay",
                "eventos.start",
                "eventos.end",
                "eventos.session",
                "eventos.ubication",
                "eventos.enlace",
                "eventos.organizadorId",
                "users.name as organizador",
                "users.lastName as organizadorLastName",
                "categorias.name as categoria",
                "asistentes.id as asistencia",
                "eventos.estadoId"
            )->join("users", "users.id", "=", "eventos.organizadorId")
            ->join("categorias", "categorias.id", "=", "eventos.categoriaId")
            ->join("asistentes", "asistentes.eventoId", "eventos.id")
            ->whereColumn('eventos.id','asistentes.eventoId')
            ->where('asistentes.userId', $user->id)
            ->paginate(6);
            
            foreach ($Eventos as $Evento) {
                $imagenes = Imagen::select("imagenes.imagen as imagen")
                ->where("imagenes.eventoId", "=", $Evento->id)
                ->pluck('imagen')->toArray();
    
                $Evento->imagen = $imagenes;
            }

            if($Eventos->isEmpty()){
                return response()->json([
                    'code'=> 400,
                    'mensaje' => 'No se encontraron eventos',
                ]);
            }else{
                return response()->json([
                    'code'=>200, 
                    'data'=> $Eventos,
                ], 200); 
            }



        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500); 
        }
    }

    public function store(Request $request)
    {
        $validacion = $request->validate([
            'title'=> 'required',
            'description'=> 'string',
            'allDay'=> 'required',
            'start'=> 'required|date',
            'end' => 'required|date',
            'session' => 'integer',
            'ubication' => 'string',
            'enlace' => 'string',
            'organizadorId' => 'required',
            'categoriaId'=>'required',
            'estadoId'=> 'required',
            'imagenes' => 'nullable | array',
            'imagenes.*' => 'string'
        ]);
        $imagenes = $validacion['imagenes'];
        $fechaInicio = $validacion['start'];
        if (strtotime($fechaInicio) < time()) {
            return response()->json([
                'code' => 400,
                'mensaje' => 'fechaAnterior',
            ]);
        }

        if(!$validacion){
            return response()->json([
                'code'=>400, 
                'data'=> $validacion,
            ], 400);
        } else{ 
            if(count($imagenes)>0){
               $evento = Evento::create([
                'title'=> $validacion["title"],
                'description'=> $validacion["description"],
                'allDay'=> $validacion["allDay"],
                'start'=> $validacion["start"],
                'end'=> $validacion["end"],
                'session'=> $validacion["session"],
                'ubication'=> $validacion["ubication"],
                'enlace'=> $validacion["enlace"],
                'organizadorId'=> $validacion["organizadorId"],
                'categoriaId'=> $validacion["categoriaId"],
                'estadoId'=> $validacion["estadoId"]
                ]); 

                foreach ($imagenes as $value) {
                    Imagen::create([
                        'imagen' => $value,
                        'eventoId' => $evento->id
                    ]);
                };
                return response()->json([
                    'code'=>200, 
                    'data'=> $evento,
                    'imagenes' => $imagenes
                ], 200);
            }
            else{
                $evento = Evento::create([
                    'title'=> $validacion["title"],
                    'description'=> $validacion["description"],
                    'allDay'=> $validacion["allDay"],
                    'start'=> $validacion["start"],
                    'end'=> $validacion["end"],
                    'session'=> $validacion["session"],
                    'ubication'=> $validacion["ubication"],
                    'enlace'=> $validacion["enlace"],
                    'organizadorId'=> $validacion["organizadorId"],
                    'categoriaId'=> $validacion["categoriaId"],
                    'estadoId'=> $validacion["estadoId"]
                    ]); 
                return response()->json([
                    'code'=>200, 
                    'data'=> $evento,
                ], 200);
            }   
        }
    }

    public function show(string $id)
    {
        $evento= Evento::find($id);
        $imagenes = Imagen::where('eventoId', $evento->id)
        ->pluck('imagen')->toArray();

        $evento->imagenes = $imagenes;
        if($evento){
            return response()->json([
                'code' => 200,
                'data' => $evento
            ]);
        } else{
            return response()->json([
                'code' => 404,
                'data' => 'Evento no encontrado'    
            ], 404);
        }
    }

    public function informacion(string $id){
        try{
            $Eventos = Evento::select(
                "eventos.id",
                "eventos.title",
                "eventos.description",
                "eventos.allDay",
                "eventos.start",
                "eventos.end",
                "eventos.session",
                "eventos.ubication",
                "eventos.enlace",
                "eventos.organizadorId",
                "users.name as organizador",
                "users.lastName as organizadorLastName",
                "categorias.name as categoria",
                "eventos.estadoId"
            )->join("users", "users.id", "=", "eventos.organizadorId")
            ->join("categorias", "categorias.id", "=", "eventos.categoriaId")
            ->where('eventos.id', $id)
            ->get();
            
            foreach ($Eventos as $Evento) {
                $imagenes = Imagen::select("imagenes.imagen as imagen")
                ->where("imagenes.eventoId", "=", $Evento->id)
                ->pluck('imagen')->toArray();

                $Evento->imagen = $imagenes;
            }
            if($Eventos == null){
                return response()->json([
                    'code'=>400, 
                    'data'=> "No se encontro la informacion",
                ], 400);
            } else{
                return response()->json([
                    'code'=>200, 
                    'data'=> $Eventos,
                ], 200);    
            }

        } catch(\throwable $th){
            return response()->json($th->getMessage(), 500);
        }
        


    }

    public function update(Request $request, string $id)
    {
        $validacion = $request->validate([
            'title'=> 'required',
            'description'=> 'string',
            'allDay'=> 'required',
            'start'=> 'required',
            'end' => 'required',
            'session' => 'integer',
            'ubication' => 'string',
            'enlace' => 'string',
            'organizadorId' => 'required',
            'categoriaId'=>'required',
            'estadoId'=> 'required',
            'imagenes' => 'nullable|array',
            'imagenes.*' => 'string',
            'imagenesBorrar' => 'nullable|array',
            'imagenesBorrar.*' => 'string'
        ]);
        $fechaInicio = $validacion['start'];
        if (strtotime($fechaInicio) < time()) {
            return response()->json([
                'code' => 400,
                'mensaje' => 'fechaAnterior',
            ]);
        }

        $imagenes = $validacion['imagenes'];
        $imagenesBorrar = $validacion['imagenesBorrar'];

        if(!$validacion){
            return response()->json([
                'code'=>400, 
                'data'=> "Error en la validación",
            ], 400);
        } else{
            $evento = Evento::find($id);
            if($evento){
                $evento->update([
                    'title'=> $validacion["title"],
                    'description'=> $validacion["description"],
                    'allDay'=> $validacion["allDay"],
                    'start'=> $validacion["start"],
                    'end'=> $validacion["end"],
                    'session'=> $validacion["session"],
                    'ubication'=> $validacion["ubication"],
                    'enlace'=> $validacion["enlace"],
                    'organizadorId'=> $validacion["organizadorId"],
                    'categoriaId'=> $validacion["categoriaId"],
                    'estadoId'=> $validacion["estadoId"]
                    ]);
                if(count($imagenesBorrar) > 0){
                    foreach ($imagenesBorrar as $imagenBase64) {
                        $imagen = Imagen::where('eventoId', $id)
                        ->where('imagen', $imagenBase64)
                        ->first();
                        if ($imagen) {
                            $imagen->delete();
                        }
                    }
                } if(count($imagenes)>0){
                    foreach ($imagenes as $value) {
                        Imagen::create([
                            'imagen' => $value,
                            'eventoId' => $evento->id
                        ]);
                    }
                }

                $asistentes = Asistente::where('eventoId', $id)->get();
                if($asistentes->isEmpty()){
                    $mensaje = "No posee asistentes";
                }  else{
                    foreach ($asistentes as $asistente) {
    
                        $asistente->notify(new EventoActualizadoNotification($evento));
                    }
                    $mensaje = "Notificacion enviada";
                }
                
                    
                return response()->json([
                    'code'=>200, 
                    'data'=> 'evento actualizado',
                    'mensaje' => $mensaje
                ], 200);
            }
        }
    }

    public function filtro(Request $request){

        $query = Evento::query();
        
        $ubicaciones = $request->input('ubicaciones');
        $categorias = $request->input('categoriasId');
        $fecha = $request->input('fecha');
        
        if($ubicaciones){
            $query->whereIn('ubication',$ubicaciones);
        }
        if($fecha){
            $query->whereDate('start', '=', $fecha);
        }
        if($categorias){
            $query->whereIn('categoriaId',$categorias);
        }

        $eventosFiltrados = $query
        ->join('users', 'eventos.organizadorId', '=', 'users.id')
        ->join('categorias', 'eventos.categoriaId', '=', 'categorias.id')
        ->select(
            'eventos.*',
            "users.name as organizador",
            "users.lastName as organizadorLastName",
            "categorias.name as categoria",
        )
        ->get();
        
        foreach ($eventosFiltrados as $Evento) {
            $imagenes = Imagen::select("imagenes.imagen as imagen")
            ->where("imagenes.eventoId", "=", $Evento->id)
            ->pluck('imagen')->toArray();

            $Evento->imagen = $imagenes;
        }

        if($eventosFiltrados->isEmpty()){
            return response()->json([
                'code'=> 400,
                'data' => 'No se encontraron eventos',
            ]);
        } else{
            return response()->json([
                'code'=> 200,
                'data' => $eventosFiltrados
            ], 200);
        }
    }

    public function filtroMisEventos(Request $request){

        $validacion = $request->validate([
            'userId' => 'required'
        ]);

        if(!$validacion){
            return response()->json([
                'code'=> 400,
                'data' => 'No pasaste validacion',
            ]);
        } else {
            $query = Evento::query();
        
            $ubicaciones = $request->input('ubicaciones');
            $categorias = $request->input('categoriasId');
            $fecha = $request->input('fecha');
            $userId = $request->input('userId');
            
            if($ubicaciones){
                $query->whereIn('ubication',$ubicaciones);
            }
            if($fecha){
                $query->whereDate('start', '=', $fecha);
            }
            if($categorias){
                $query->whereIn('categoriaId',$categorias);
            }

            $eventosFiltrados = $query
            ->join('users', 'eventos.organizadorId', '=', 'users.id')
            ->join('categorias', 'eventos.categoriaId', '=', 'categorias.id')
            ->select(
                'eventos.*',
                "users.name as organizador",
                "users.lastName as organizadorLastName",
                "categorias.name as categoria",
            )->where('eventos.organizadorId', $userId)
            ->get();
        
            foreach ($eventosFiltrados as $Evento) {
                $imagenes = Imagen::select("imagenes.imagen as imagen")
                ->where("imagenes.eventoId", "=", $Evento->id)
                ->pluck('imagen')->toArray();

                $Evento->imagen = $imagenes;
             }


            if($eventosFiltrados->isEmpty()){
                return response()->json([
                    'code'=> 400,
                    'data' => 'No se encontraron eventos',
                ]);
            } else{
                return response()->json([
                    'code'=> 200,
                    'data' => $eventosFiltrados
                ], 200);
            }
        }

    }

    public function destroyAsistencia(string $id){
        try {
            $eliminarAsistencia = Asistente::where('id', $id)->first();
            $eliminarAsistencia->delete();
            return response()->json([
                'code' => 200,
                'data' => 'asistencia eliminada'
            ]);
            
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500); 
        }
    }

    public function destroy(string $id)
    {
        try{
            $eliminarImagenes = Imagen::where('eventoId', $id)->get();
            $eliminarFavoritos = Favorito::where('eventoId', $id)->get();
            $eliminarComentarios = Comentario::where('eventId', $id)->get();
            $eliminarAsistentes = Asistente::where('eventoId', $id)->get();

            if(count($eliminarImagenes)>0){
                foreach ($eliminarImagenes as $imagen) {
                    $imagen->delete();
                }
            }

            if(count($eliminarFavoritos)>0){
                foreach ($eliminarFavoritos as $favorito) {
                    $favorito->delete();
                }
            }

            if(count($eliminarComentarios)>0){
                foreach ($eliminarComentarios as $comentario) {
                    $comentario->delete();
                }
            }

            if(count($eliminarAsistentes)>0){
                foreach ($eliminarAsistentes as $asistente) {
                    $asistente->delete();
                }
            }

            $evento = Evento::find($id);
            $evento->delete();

            return response()->json([
                'code' => 200,
                'data' => 'evento eliminado'
            ]);
        
        } catch(\Throwable $th) { 
            return response()->json($th->getMessage(), 500); 
        }

    }

    public function ubicaciones(){
        $ubicaciones = Evento::distinct([
            "eventos.ubication"
        ])->pluck('ubication');
        return response()->json([
            'code' => 200,
            'data'=> $ubicaciones
        ]);
    }
}
