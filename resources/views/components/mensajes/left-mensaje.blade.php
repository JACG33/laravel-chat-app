<div class="grid grid-cols-[40px_1fr] gap-2 justify-start items-end">
  <x-mensajes.user-icon :nombre="$mensaje->getUser->name" size="10" />
  <div class="grid gap-1 bg-zinc-600 rounded-tr-md rounded-br-md rounded-tl-md p-2 w-full max-w-3xl">
    <small class="text-[12px]">{{$mensaje->getUser->name}}</small>
    @if(!empty($mensaje->archivo_mensaje))
    <x-mensajes.files-of-message :mensaje="$mensaje" />
    @endif
    <div>{!!html_entity_decode($mensaje->mensaje)!!}</div>
    <div class="flex justify-end items-center">
      <small class="text-[12px]">{{$mensaje->created_at->format('h:i a')}}</small>
    </div>
  </div>
</div>