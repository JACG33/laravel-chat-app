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
        $id_chat = $request->id_chat;
        $mensajes = Mensajes::where('id_chat', "$id_chat")->orderBy('created_at', 'desc')->paginate(30);

        return view('chat.conversacion.index', compact('mensajes', 'id_chat'));
    }

    function sendMessage(Request $request)
    {
        $request->validate(['message' => 'required|string']);

        $user = Auth::user();
        $message = $request->input('message');
        $id_chat = $request->id_chat;

        $mensaje = Mensajes::create([
            'id_chat' => $id_chat,
            'id_usuario' => Auth::user()->id,
            'mensaje' => $message,
            'tipo_mensaje' => $message,
        ]);

        event(new MessageSent($user, $mensaje));

        return response()->json(['status' => 'Message sent!']);
    }
}
