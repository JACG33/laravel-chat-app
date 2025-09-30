<x-layouts.app :title="__('Dashboard')">

  <dialog closedBy="any" id="modal_chat_files">
    <div>
      <div>
        <div></div>
        <div>
          <button type="button">Añadir más archivos</button>
        </div>
      </div>
      <div>
        <input type="text" id="mirror_message" placeholder="Añadir descripción">
      </div>
    </div>
  </dialog>



  <div class="h-full w-full overflow-hidden rounded-xl grid p-2 grid-rows-[1fr_80px] gap-2 border border-blue-500">
    <div id="messages" class="overflow-y-auto p-2 pt-8 flex flex-col gap-2 h-[calc(100dvh_-_210px)] relative">
      <div class="absolute inset-[0px_0px_auto_0px]">
        <div class="w-full flex justify-start items-center gap-2 text-sm">
          <div class="rounded-full p-1 w-6 h-6 flex justify-center items-center bg-indigo-500">
            J
          </div>
          <span>Joe Casañas</span>
        </div>
      </div>

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
      <input type="hidden" id="chat" value="{{$id_chat}}">
      <input type="hidden" id="userlogged" value="{{\Auth::user()->id}}">
      <div class="w-full h-full grid grid-cols-[1fr_minmax(75px,0.1fr)] gap-2 bg-gray-600 items-center p-2">
        <textarea class="outline-none w-full resize-none" name="" id="messageInput" placeholder="Escribe un mensaje..." required autocomplete="off"></textarea>
        <!-- <input type="text" class="outline-none w-full" id="messageInput" placeholder="Escribe un mensaje..." required autocomplete="off"> -->
        <div class="w-fit flex justify-end items-center gap-2">
          <button type="button" class=" text-white cursor-pointer p-1 rounded-full hover:bg-purple-400" title="Subir Archivo" id="btn_chat_file">
            <input hidden type="file" name="chat_file" id="chat_file">
            <flux:icon.paperclip />
          </button>
          <button type="submit" class=" text-white cursor-pointer p-1 rounded-full hover:bg-blue-400" title="Enviar">
            <flux:icon.send />
          </button>
        </div>
      </div>
    </form>
  </div>
  <script>
    document.addEventListener('DOMContentLoaded', () => {

      const form = document.getElementById('messageForm');
      const input = document.getElementById('messageInput');
      const messages = document.getElementById('messages');
      const id_chat = document.getElementById('chat');
      const userlogged = document.getElementById('userlogged').value;

      // Listen for messages
      window.Echo.channel('chat')
        .listen('MessageSent', (msj) => {
          console.log(msj)

          const messageElement = document.createElement('div');
          messageElement.className = 'grid gap-2 justify-end'
          let tmp = `            
            <div class="flex gap-2">
              <div class="bg-blue-600 rounded-tl-md rounded-br-md rounded-bl-md p-2">${msj.message}</div>
            </div>
            <div class="flex justify-end">
              <span class="mr-2">${msj.time}</span>
            </div>
          `

          if (userlogged != msj.user_id) {
            console.log(userlogged, msj.user_id)
            tmp = `            
              <div class="flex gap-2">
                <div class="w-10 h-10 rounded-full bg-yellow-500"></div>
                <div class="bg-zinc-600 rounded-tr-md rounded-br-md rounded-bl-md p-2">${msj.message}</div>
              </div>
              <div>
                <span class="ml-14">${msj.time}</span>
              </div>
          `
            messageElement.className = 'grid gap-2'
          }


          messageElement.innerHTML = tmp
          messages.appendChild(messageElement);
        });

      // Handle form submission
      form.addEventListener('submit', (e) => {
        e.preventDefault();
        fetch(`/chat/send/${id_chat.value}`, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            body: JSON.stringify({
              message: input.value,
              id_chat: id_chat.value
            })
          })
          .then(response => response.json())
          .then(data => {
            input.value = '';
          });
      });

      // Handle Click
      document.addEventListener("click", e => {
        const {
          target
        } = e

        if (target.closest("[id=btn_chat_file]")) {
          target.closest("[id=btn_chat_file]").querySelector("input").click()
        }
      })
    })
  </script>
</x-layouts.app>