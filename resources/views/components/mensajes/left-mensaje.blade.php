<div class="grid grid-cols-[40px_1fr] gap-2 justify-start items-end">
  @php
  $rgb = rand(0,255).",".rand(0,255).",".rand(0,255);
  @endphp
  <div class="w-10 h-10 rounded-full p-1 flex justify-center items-center" style="background-color:rgb({{$rgb}});">
    {{$mensaje->getUser->name[0]}}
  </div>
  <div class="grid gap-1 bg-zinc-600 rounded-tr-md rounded-br-md rounded-tl-md p-2 w-full max-w-2xs">
    <small class="text-[12px]">{{$mensaje->getUser->name}}</small>
    @if(!empty($mensaje->archivo_mensaje))
    <x-mensajes.files-of-message :mensaje="$mensaje" />
    @endif
    <p>{{$mensaje->mensaje}}</p>
    <div class="flex justify-end items-center">
      <small class="text-[12px]">{{$mensaje->created_at->format('h:i a')}}</small>
    </div>
  </div>
</div>