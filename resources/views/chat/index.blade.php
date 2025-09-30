<x-layouts.app :title="__('Chats')">
  <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
    <div>
      <h2>Chats</h2>

      <ul class="flex flex-col gap-2 p-2">
        @forelse($chats as $chat)
        @php
        $rgb = rand(0,255).",".rand(0,255).",".rand(0,255);
        @endphp
        <li class="hover:bg-gray-600 rounded-xl p-2 flex gap-2 justify-between">
          <a href="{{route('chat.conversacion',$chat->id)}}" class="w-full flex justify-start items-center gap-2">
            <div class="rounded-full p-1 w-8 h-8 flex justify-center items-center" style="background-color:rgb({{$rgb}});">
              {{$chat->nombre[0]}}
            </div>
            <span>{{$chat->nombre}}</span>
          </a>
          <flux:dropdown position="bottom" align="end">
            <flux:button
              class="cursor-pointer"
              icon-trailing="ellipsis-horizontal" />

            <flux:menu>

              <flux:menu.radio.group>
                <form action="{{route('chat.destroy',$chat->id)}}" method="post">
                  @csrf
                  @method('DELETE')
                  <flux:button icon="trash" class="w-full cursor-pointer" type='submit'>{{ __('Eliminar Chat') }}</flux:button>
                </form>
              </flux:menu.radio.group>

            </flux:menu>
          </flux:dropdown>
        </li>
        @empty
        <li>No tienes chats</li>
        @endforelse
      </ul>
    </div>

    <dialog id="modal_users_to_chat" class="rounded-xl h-[calc(100dvh_-_200px)] w-[90%] md:w-[70%]" closedBy='any'>
      <div class="p-2 flex flex-col gap-2">
        <div class="flex justify-end">
          <button type="button" id="close_modal_users_to_chat" class="rounded-full cursor-pointer text-red-500 border-1 border-red-500">
            <flux:icon.x />
          </button>
        </div>
        <div>
          <form action="{{route('chat.registrar',0)}}" class="w-full flex flex-col gap-2">
            @csrf
            <div>
              <input type="hidden" name="users_id" id="users_id">
              <label class="ml-2" for="seacrh_user">Buscar Usuario</label>
              <input type="search" class="w-full p-2 bg-zinc-800 rounded-xl" name="seacrh_user" id="seacrh_user" placeholder="Buscar Usuario" required>
            </div>

            <button type="button" class="rounded-xl p-2 bg-blue-600 text-white m-auto w-fit cursor-pointer" id="create_chat_group">o crea un grupo</button>

            <div class="hidden w-full flex flex-col gap-2" id="name_chat_group">
              <div>
                <label class="ml-2" for="nombre_grupo">Nombre del Grupo</label>
                <input type="text" class="w-full p-2 bg-zinc-800 rounded-xl" name="nombre_grupo" id="nombre_grupo" placeholder="Nombre del Grupo" required>
              </div>
              <button type="submit" class="p-2 rounded-xl bg-green-500 text-white cursor-pointer">Crear</button>
              <button type="button" class="rounded-xl p-2 bg-red-600 text-white m-auto w-fit cursor-pointer" id="cancel_create_chat_group">Cancelar</button>
            </div>
          </form>

        </div>

        <!-- Usuarios -->
        <div>
          <span id="send_to_span">Enviar un nuevo mensaje a</span>
          <ul class="flex flex-col gap-2 p-2" id="users_list">
            @forelse($usuarios as $usuario)
            @php
            $rgb = rand(0,255).",".rand(0,255).",".rand(0,255);
            @endphp
            <li data-userid="{{$usuario->id}}" data-username="{{$usuario->name}}" class="hover:bg-gray-600 rounded-xl cursor-pointer">
              <a href="{{route('chat.registrar',$usuario->id)}}" class="p-2 flex justify-start items-center gap-2">
                <div class="rounded-full p-1 w-8 h-8 flex justify-center items-center" style="background-color:rgb({{$rgb}});">
                  {{$usuario->name[0]}}
                </div>
                <span>{{$usuario->name}}</span>
              </a>
            </li>
            @empty
            @endforelse
          </ul>
        </div>
      </div>
    </dialog>

    <button
      type="button"
      id="new_chat"
      class="flex justify-center gap-2 items-center cursor-pointer rounded-xl p-2 bg-blue-600 text-white shadow-2xl absolute bottom-3 right-3 z-10">
      <span>Nuevo Chat</span>
      <flux:icon.message-circle-plus />
    </button>
  </div>



  <script type="module">
    document.addEventListener("DOMContentLoaded", () => {
      let send_to_span_original_text = ''
      document.addEventListener('click', e => {
        const {
          target
        } = e

        if (target.closest('[id=new_chat]')) {
          document.querySelector('#modal_users_to_chat').showModal()
        }

        if (target.closest('[id=close_modal_users_to_chat]')) {
          document.querySelector('#modal_users_to_chat').close()
        }

        if (target.closest('[id=create_chat_group]')) {
          send_to_span_original_text = send_to_span_original_text == '' ? document.querySelector("#send_to_span").textContent : send_to_span_original_text

          document.querySelector("#send_to_span").textContent = 'Seleccione los usuarios para crear grupo'
          target.closest('[id=create_chat_group]').classList.add('hidden')
          document.querySelector("#name_chat_group").classList.remove('hidden')
          
          document.querySelector("#users_list").querySelectorAll("a").forEach(e => {
            e.setAttribute('data-href', e.href)
            e.removeAttribute('href')
          })

          document.querySelector('#seacrh_user').inert = true
        }

        if (target.closest('[id=cancel_create_chat_group]')) {
          document.querySelector("#send_to_span").textContent = send_to_span_original_text
          document.querySelector("#name_chat_group").classList.add('hidden')
          document.querySelector("#create_chat_group").classList.remove('hidden')

          document.querySelector("#users_list").querySelectorAll("a").forEach(e => {
            e.href = e.dataset.href
            e.removeAttribute('data-href')
          })


          document.querySelector('#seacrh_user').removeAttribute('inert')
          document.querySelector('#seacrh_user').value = ''
          document.querySelector('#users_id').value = ''
        }


        if (target.closest('[data-username]')) {
          target.closest('[data-username]').classList.add('bg-green-500/10')

          const id_user = target.closest('[data-username]').dataset.userid
          const name_user = target.closest('[data-username]').dataset.username

          // Nombres de Usuarios
          let usersname = document.querySelector('#seacrh_user').value.split(',')

          const findusername = usersname.find(ele => ele == name_user)
          if (findusername) {
            usersname = usersname.filter(ele => ele != findusername)
            target.closest('[data-username]').classList.remove('bg-green-500/10')
          } else usersname.push(name_user)
          usersname = usersname.filter(ele => ele != '')

          // Ids de Usuarios
          let usersid = document.querySelector('#users_id').value.split(',')

          const finduserid = usersid.find(ele => ele == id_user)
          if (finduserid) {
            usersid = usersid.filter(ele => ele != finduserid)
            target.closest('[data-username]').classList.remove('bg-green-500/10')
          } else usersid.push(id_user)
          usersid = usersid.filter(ele => ele != '')

          document.querySelector('#seacrh_user').value = usersname.join(',')
          document.querySelector('#users_id').value = usersid.join(',')

        }


      })




    })
  </script>



</x-layouts.app>