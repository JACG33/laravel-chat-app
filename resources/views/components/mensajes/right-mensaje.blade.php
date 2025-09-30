
<div class="grid gap-2 justify-end">
  <div class="flex gap-2">
    <div class="bg-blue-600 rounded-tl-md rounded-br-md rounded-bl-md p-2">{{$mensaje->mensaje}}</div>
  </div>
  <div class="flex justify-end">
    <span class="mr-2">{{$mensaje->created_at->format('h:i a')}}</span>
  </div>
</div>