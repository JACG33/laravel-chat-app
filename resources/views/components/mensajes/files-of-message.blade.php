@php
$files=explode(",",$mensaje->archivo_mensaje);
@endphp

<div>
  @forelse($files as $file)
  @php
  $exte=explode('.',$file);
  @endphp
  @if(end($exte)!='png')
  <a href="{{asset('storage/'.$file)}}">archivo</a>
  @else
  <img loading="lazy" class="rounded-md object-cover h-40 w-40" width="90" height="90" src="{{asset('storage/'.$file)}}" alt="" srcset="">
  @endif
  @empty
  @endforelse
</div>