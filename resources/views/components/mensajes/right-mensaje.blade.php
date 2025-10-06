<div class="mr-1.5 grid gap-2 justify-end items-end">
  <div class="grid gap-1 bg-blue-600 rounded-tr-md rounded-bl-md rounded-tl-md p-2 w-full max-w-2xs">
    @if(!empty($mensaje->archivo_mensaje))
    <x-mensajes.files-of-message :mensaje="$mensaje" />
    @endif
    <p>{{$mensaje->mensaje}}</p>
    <div class="flex justify-end items-center">
      <small class="text-[12px]">{{$mensaje->created_at->format('h:i a')}}</small>
    </div>
  </div>
</div>