<x-layouts.app :title="__('Dashboard')">
  <link rel="stylesheet" href="{{asset('/ckeditor5/ckeditor5.css')}}">

  <dialog closedBy="any" id="modal_chat_files" class="overflow-hidden rounded-xl w-[85%] md:w-[70%] h-[80dvh]">
    <div class="grid grid-rows-[40px_1fr] p-2 gap-2">
      <div class="flex items-center justify-end">
        <button type="button" id="btn_close_chat_file"  class="bg-red-500 p-1 rounded-md cursor-pointer text-white flex justify-center items-center">
          <flux:icon.x />
        </button>
      </div>
      <div class="grid grid-rows-[1fr_40px] h-[calc(80dvh_-_70px)]">
        <div class="overflow-y-auto flex justify-around flex-wrap gap-4" id="preview_files_area"></div>
        <div class="w-full flex">
          <button class="m-auto p-2 rounded-md bg-green-500 text-white cursor-pointer" type="button" id="btn_add_chat_file">Añadir más archivos</button>
        </div>
      </div>
    </div>
  </dialog>

  <div id="messages_section" class="h-[calc(100dvh_-_110px)] md:h-[calc(100dvh_-_65px)] w-full overflow-hidden rounded-xl grid p-2 grid-rows-[36px_1fr_auto] gap-2 border border-blue-500">
    <div class="sticky py-1 inset-[0px_0px_auto_0px] bg-zinc-800">
      <div class="w-full flex justify-start items-center gap-2 text-sm">
        <x-mensajes.user-icon :nombre="$chat->nombre" size="6" />
        <span>{{$chat->nombre}}</span>
      </div>
    </div>
    <div id="messages" class="overflow-y-auto flex flex-col gap-4  relative scroll-smooth">
      <div class="overflow-hidden hidden" inert hidden>{{$mensajes->links()}}</div>
      <x-mensajes.loader-spinner />
      @forelse($mensajes as $mensaje)
      @if($mensaje->id_usuario!=\Auth::user()->id)
      <x-mensajes.left-mensaje :mensaje="$mensaje" />
      @else
      <x-mensajes.right-mensaje :mensaje="$mensaje" />
      @endif
      @empty
      @endforelse
    </div>
    <form id="messageForm" class="w-full overflow-hidden rounded-xl">
      @csrf
      <input type="hidden" id="chat" value="{{$chat->id}}">
      <input type="hidden" id="userlogged" value="{{\Auth::user()->id}}">
      <div class="w-full h-full grid grid-cols-[1fr_minmax(75px,0.1fr)] gap-2 bg-gray-600 items-center p-2">
        <div style="color: #fff;" class="outline-none min-h-16 h-full max-h-20 md:max-h-32 text-white w-full resize-none p-1 rounded-md" name="" id="messageInput" placeholder="Escribe un mensaje..." required autocomplete="off"></div>
        <div class="w-fit flex justify-end items-center gap-2">
          <button type="button" class="relative  text-white cursor-pointer p-1 rounded-full hover:bg-purple-400" title="Subir Archivo" id="btn_chat_file">
            <span id="chat_count_files" class="hidden absolute -top-1.5 -right-1.5 min-w-4 min-h-3.5 text-[12px] rounded-full p-0.5 bg-blue-500 text-white"></span>
            <input hidden type="file" name="chat_file" id="chat_file">
            <flux:icon.paperclip />
          </button>
          <button type="submit" id="btn_send_message" class=" text-white cursor-pointer p-1 rounded-full hover:bg-blue-400" title="Enviar">
            <flux:icon.send />
          </button>
        </div>
      </div>
    </form>
  </div>
  <script type="module" src="{{asset('js/main.js')}}"></script>
  @vite(['resources/js/message.js'])
</x-layouts.app>