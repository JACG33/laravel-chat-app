@php
$files=explode(",",$mensaje->archivo_mensaje);
@endphp

<div class="flex flex-wrap gap-1 relative">
  @forelse($files as $file)
  @php
  $exte=explode('.',$file);
  $splitname=explode('/',$file);
  $namefile=end($splitname);
  @endphp
  @if(in_array(end($exte),['png','jpg','jpeg']))
  <img loading="lazy" class="rounded-md object-cover h-40 w-40" width="90" height="90" src="{{asset('storage/'.$file)}}" alt="" srcset="">
  @else
  <a href="{{asset('storage/'.$file)}}" target="_blank" class="flex flex-col gap-1 relative">

    @if(str_contains($file,'doc')||str_contains($file,'docx'))
    <svg xmlns="http://www.w3.org/2000/svg" width="160" height="160" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="${className}">
      <path stroke="none" d="M0 0h24v24H0z" fill="none" />
      <path d="M14 3v4a1 1 0 0 0 1 1h4" />
      <path d="M5 12v-7a2 2 0 0 1 2 -2h7l5 5v4" />
      <path d="M5 15v6h1a2 2 0 0 0 2 -2v-2a2 2 0 0 0 -2 -2h-1z" />
      <path d="M20 16.5a1.5 1.5 0 0 0 -3 0v3a1.5 1.5 0 0 0 3 0" />
      <path d="M12.5 15a1.5 1.5 0 0 1 1.5 1.5v3a1.5 1.5 0 0 1 -3 0v-3a1.5 1.5 0 0 1 1.5 -1.5z" />
    </svg>
    @endif

    @if(str_contains($file,'pdf'))
    <svg xmlns="http://www.w3.org/2000/svg" width="160" height="160" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="${className}">
      <path stroke="none" d="M0 0h24v24H0z" fill="none" />
      <path d="M14 3v4a1 1 0 0 0 1 1h4" />
      <path d="M5 12v-7a2 2 0 0 1 2 -2h7l5 5v4" />
      <path d="M5 18h1.5a1.5 1.5 0 0 0 0 -3h-1.5v6" />
      <path d="M17 18h2" />
      <path d="M20 15h-3v6" />
      <path d="M11 15v6h1a2 2 0 0 0 2 -2v-2a2 2 0 0 0 -2 -2h-1z" />
    </svg>
    @endif

    @if(str_contains($file,'ppt'))
    <svg xmlns="http://www.w3.org/2000/svg" width="160" height="160" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="${className}">
      <path stroke="none" d="M0 0h24v24H0z" fill="none" />
      <path d="M14 3v4a1 1 0 0 0 1 1h4" />
      <path d="M14 3v4a1 1 0 0 0 1 1h4" />
      <path d="M5 18h1.5a1.5 1.5 0 0 0 0 -3h-1.5v6" />
      <path d="M11 18h1.5a1.5 1.5 0 0 0 0 -3h-1.5v6" />
      <path d="M16.5 15h3" />
      <path d="M18 15v6" />
      <path d="M5 12v-7a2 2 0 0 1 2 -2h7l5 5v4" />
    </svg>
    @endif

    @if(str_contains($file,'xlsx')||str_contains($file,'xls'))
    <svg xmlns="http://www.w3.org/2000/svg" width="160" height="160" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="${className}">
      <path stroke="none" d="M0 0h24v24H0z" fill="none" />
      <path d="M14 3v4a1 1 0 0 0 1 1h4" />
      <path d="M5 12v-7a2 2 0 0 1 2 -2h7l5 5v4" />
      <path d="M4 15l4 6" />
      <path d="M4 21l4 -6" />
      <path d="M17 20.25c0 .414 .336 .75 .75 .75h1.25a1 1 0 0 0 1 -1v-1a1 1 0 0 0 -1 -1h-1a1 1 0 0 1 -1 -1v-1a1 1 0 0 1 1 -1h1.25a.75 .75 0 0 1 .75 .75" />
      <path d="M11 15v6h3" />
    </svg>
    @endif
    
    {{substr($namefile, 0, 12)}}.{{end($exte)}}

  </a>
  @endif
  @empty
  @endforelse
</div>

<?php
// in_array()
