<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Chat;
use App\Models\ChatUsuarios;
use App\Models\Mensajes;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Usuarios
        $usuarios = User::whereNot('id', Auth::user()->id)->paginate(10);

        // Chats
        $chats = DB::table('chats')
            ->distinct('chat_usuarios.id_chat')
            ->select('chats.id', 'chats.nombre', 'chat_usuarios.id_chat')
            ->where('id_usuario', '=', Auth::user()->id)
            ->leftJoin('chat_usuarios', 'chats.id', '=', 'chat_usuarios.id_chat')
            ->paginate(10);

        return view('chat.index', compact('usuarios', 'chats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
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
    public function show(Chat $chat)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Chat $chat)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Chat $chat)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chat $chat)
    {
        $chat->delete();
        ChatUsuarios::where('id_chat', "$chat->id")->delete();
        Mensajes::where('id_chat', "$chat->id")->delete();


        return redirect()->route('chat.index');
    }

    function crearChat(Request $request)
    {

        if (!empty($request->nombre_grupo)) {
            $nuevoChat = Chat::create([
                'nombre' => $request->nombre_grupo,
                'tipo_chat' => 'grupo',
            ]);

            $users_id = explode(',', $request->users_id);

            $users = [];

            foreach ($users_id as $key) {
                $users[] = [
                    'id_chat' => $nuevoChat->id,
                    'id_usuario' => $key,
                ];
            }
            $users[] = [
                'id_chat' => $nuevoChat->id,
                'id_usuario' => Auth::user()->id,
            ];

            $chatUsuarios = ChatUsuarios::insert($users);

            return redirect()->route('chat.conversacion', $nuevoChat->id);
        }


        $usuario = User::where('id', "=", "$request->usuario")->get()->first();

        $nuevoChat = Chat::create([
            'nombre' => $usuario->name,
            'tipo_chat' => 'mensaje',
        ]);

        $chatUsuarios = ChatUsuarios::insert(
            [
                [
                    'id_chat' => $nuevoChat->id,
                    'id_usuario' => $request->usuario,
                ],
                [
                    'id_chat' => $nuevoChat->id,
                    'id_usuario' => Auth::user()->id,
                ]
            ]
        );

        return redirect()->route('chat.conversacion', $nuevoChat->id);
    }

    function conversacion(Request $request)
    {

        $chat = DB::table('chats')
            ->leftJoin('chat_usuarios', 'chat_usuarios.id_chat', 'chats.id')
            ->where('chats.id', "$request->id_chat")
            ->where('chat_usuarios.id_usuario', Auth::user()->id)
            ->select('chats.*')
            ->get()->first();

        if (empty($chat))
            return redirect()->route('chat.index');

        $data_to_select=[
            'id_usuario',
            'mensaje as message',
            'archivo_mensaje as files',
            'created_at as date',
            'created_at as time',
        ];

        // Numero de mensajes a mostrar
        $number_msgs = 30;

        // Retornar los mensajes en formato JSON
        if ($request->ajax() && $request->header("x-type") == "message") {
            $mensajes = Mensajes::with('getUser')
                ->select($data_to_select)
                ->where('id_chat', "$chat->id")
                ->orderBy('id', 'DESC')
                ->simplePaginate($number_msgs);
            return response()->json($mensajes);
        }

        // Buscar los ultimos mensajes del chat
        $mensajes = Mensajes::with('getUser')
            ->where('id_chat', "$chat->id")
            ->orderBy('id', 'DESC')
            ->simplePaginate($number_msgs);
        // Aplicar un reverse a los mensajes
        $mensajes = $mensajes->setCollection(
            $mensajes->getCollection()->reverse()
        );

        return view('chat.conversacion.index', compact('mensajes', 'chat'));
    }

    function sendMessage(Request $request)
    {
        // $request->validate(['message' => 'required|string']);

        $user = Auth::user();
        $message = $request->input('message');
        $id_chat = $request->id_chat;

        $files_list = [];
        if ($request->file()) {
            foreach ($request->file('file') as $key) {
                $nombre_archivo = time() . '-' . $key->getClientOriginalName();
                $files_list[] = $this->uploadFilesChat($key, $nombre_archivo);
            }
        }

        $files_list = implode(',', $files_list);

        try {
            $mensaje = Mensajes::create([
                'id_chat' => $id_chat,
                'id_usuario' => Auth::user()->id,
                'mensaje' => $message,
                'tipo_mensaje' => 'message',
                'archivo_mensaje' => $files_list
            ]);

            event(new MessageSent($user, $mensaje));

            return response()->json(['status' => 201, 'message' => 'created']);
        } catch (\Throwable $th) {
            // return response()->json(['status' => 400, 'message' => $th->getMessage()], 400);
            return response()->json(['status' => 400, 'message' => 'error'], 400);
        }
    }


    private function uploadFilesChat($file, $nombre_archivo)
    {

        $ruta = storage_path('app/public/chat_files');
        // Crear el directorio si no existe
        if (!File::exists($ruta)) {
            File::makeDirectory($ruta, 0777, true, true);
        }

        // Subir el archivo
        $path_file = $file->storeAs('chat_files', $nombre_archivo, 'public');
        return $path_file;
    }
}
