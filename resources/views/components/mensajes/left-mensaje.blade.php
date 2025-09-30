
<div class="grid gap-2">
  <div class="flex gap-2">
    <div class="w-10 h-10 rounded-full bg-yellow-500"></div>
    <div class="bg-zinc-600 rounded-tr-md rounded-br-md rounded-bl-md p-2">{{$mensaje->mensaje}}</div>
  </div>
  <div>
    <span class="ml-14">{{$mensaje->created_at->format('h:i a')}}</span>
  </div>
</div>