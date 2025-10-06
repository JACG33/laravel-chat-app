<x-layouts.app :title="__('Dashboard')">

  <dialog closedBy="any" id="modal_chat_files" class="overflow-hidden rounded-xl w-[85%] md:w-[70%] h-[80dvh]">
    <div class="grid grid-rows-[1fr_100px] p-2 gap-2">
      <div class="grid grid-rows-[1fr_40px] h-[calc(80dvh_-_124px)]">
        <div class="h-[calc(80dvh_-_170px)] overflow-y-auto flex justify-around flex-wrap gap-4" id="preview_files_area"></div>
        <div class="w-full flex">
          <button class="m-auto p-2 rounded-md bg-green-500 text-white cursor-pointer" type="button" id="btn_add_chat_file">Añadir más archivos</button>
        </div>
      </div>
      <div>
        <textarea id="mirror_message" placeholder="Añadir descripción" class="resize-none outline-none w-full h-full p-2"></textarea>
      </div>
    </div>
  </dialog>



  <div class="h-full w-full overflow-hidden rounded-xl grid p-2 grid-rows-[1fr_80px] gap-2 border border-blue-500">
    <div id="messages" class="overflow-y-auto flex flex-col gap-4 h-[calc(100dvh_-_210px)] relative scroll-smooth">
      <div class="sticky py-1 inset-[0px_0px_auto_0px] bg-zinc-800">
        <div class="w-full flex justify-start items-center gap-2 text-sm">
          <div class="rounded-full p-1 w-6 h-6 flex justify-center items-center bg-indigo-500">
            {{$chat->nombre[0]}}
          </div>
          <span>{{$chat->nombre}}</span>
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
      <input type="hidden" id="chat" value="{{$chat->id}}">
      <input type="hidden" id="userlogged" value="{{\Auth::user()->id}}">
      <div class="w-full h-full grid grid-cols-[1fr_minmax(75px,0.1fr)] gap-2 bg-gray-600 items-center p-2">
        <textarea class="outline-none w-full resize-none p-1" name="" id="messageInput" placeholder="Escribe un mensaje..." required autocomplete="off"></textarea>
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
  <script type="module">
    import {
      files_extension
    } from "{{asset('js/files_extension.js')}}"
    document.addEventListener('DOMContentLoaded', () => {

      const form = document.getElementById('messageForm');
      const input = document.getElementById('messageInput');
      const messages = document.getElementById('messages');
      const id_chat = document.getElementById('chat');
      const userlogged = document.getElementById('userlogged').value;
      let files = []

      setTimeout(() => {
        messages.scrollTop = messages.scrollHeight
      }, 10);

      // Listen for messages
      window.Echo.channel('chat')
        .listen('MessageSent', (msj) => {
          console.log(msj)

          const messageElement = document.createElement('div');

          if (msj.files) {
            const divfiles = document.createElement('div')
            const message_files = msj.files.split(',')

            message_files.forEach((ele) => {
              const exte = ele.split('.')
              if (exte[exte.length - 1] == 'png') {
                divfiles.innerHTML += `
                  <img loading="lazy" class="rounded-md object-cover h-40 w-40" width="90" height="90" src="/storage/${ele}" alt="" srcset="">
                `
              } else {
                divfiles.innerHTML += `
                  <a href="/storage/${ele}">archivo</a>
                `
              }
            })

            messageElement.append(divfiles)
          }


          messageElement.className = 'grid gap-2 justify-end'
          let tmp = `
            <div class="mr-1.5 grid gap-2 justify-end items-end">
              <div class="grid gap-1 bg-blue-600 rounded-tr-md rounded-bl-md rounded-tl-md p-2 w-full max-w-2xs">
                <p>${msj.message}</p>
                <div class="flex justify-end items-center">
                  <small class="text-[12px]">${msj.time}</small>
                </div>
              </div>
            </div>
          `
          const color=`${Math.ceil(Math.random()*255)},${Math.ceil(Math.random()*255)},${Math.ceil(Math.random()*255)}`
          if (userlogged != msj.user_id) {
            console.log(userlogged, msj.user_id)
            tmp = `            
              <div class="grid grid-cols-[40px_1fr] gap-2 justify-start items-end">
                <div class="w-10 h-10 rounded-full p-1 flex justify-center items-center" style="background-color:rgb(${color});">
                  ${msj.user_name[0]}
                </div>
                <div class="grid gap-1 bg-zinc-600 rounded-tr-md rounded-br-md rounded-tl-md p-2 w-full max-w-2xs">
                  <small class="text-[12px]">${msj.user_name}</small>
                  <p>${msj.message}</p>
                  <div class="flex justify-end items-center">
                    <small class="text-[12px]">${msj.time}</small>
                  </div>
                </div>
              </div>
          `
            messageElement.className = 'grid gap-2 justify-start'
          }


          messageElement.innerHTML += tmp
          messages.appendChild(messageElement);
          messages.scrollTop = messages.scrollHeight
        });


      // Handle form submission
      form.addEventListener('submit', (e) => {
        e.preventDefault();
        const form = new FormData
        form.append('message', input.value)
        form.append('id_chat', id_chat.value)
        if (files.length > 0)
          files.forEach(element => {
            form.append('file[]', element)
          });
        fetch(`/chat/send/${id_chat.value}`, {
            method: 'POST',
            headers: {
              // 'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            body: form
            // body: JSON.stringify({
            //   message: input.value,
            //   id_chat: id_chat.value,
            //   files
            // })
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
          document.querySelector('#modal_chat_files').showModal()
        }

        if (target.closest("[id=btn_add_chat_file]")) {
          document.querySelector("#btn_chat_file").click()
        }
      })

      // Handle Change
      document.addEventListener('change', e => {
        const {
          target
        } = e

        if (target.id == 'chat_file') {
          if (target.files[0] != null) {
            files.push(target.files[0])

            console.log(files)


            loadPriviewFile({
              file: target.files[0],
              area_to_preview: 'preview_files_area'
            })

          }
        }
      })

      // Handle Input
      document.addEventListener('input', e => {
        const {
          target
        } = e

        if (target.id == "mirror_message") {
          document.querySelector("#messageInput").value = target.value
        }

        if (target.id == "messageInput") {
          document.querySelector("#mirror_message").value = target.value
        }
      })





      /**
       * Funcion que genera la preview del archivo a subir
       * @param {object} params objeto de parametro.
       * @param {HTMLInputElement.files} params.file archivo a procesar.
       * @param {string} params.area_to_preview lugar donde se mostrara la preview
       */
      function loadPriviewFile({
        file,
        area_to_preview
      }) {
        if (file.type.includes('image')) {
          let tmpurl = URL.createObjectURL(file)
          const div = document.createElement("div")
          div.className = "flex flex-col gap-1"

          const img = document.createElement('img')
          img.width = '90'
          img.height = '90'
          img.className = "rounded-md object-cover h-40 w-40"
          img.src = tmpurl

          div.append(img)

          const span = document.createElement("span")
          span.textContent = file.name

          div.append(span)

          document.querySelector(`#${area_to_preview}`).append(div)
        }
      }
    })
  </script>
</x-layouts.app>