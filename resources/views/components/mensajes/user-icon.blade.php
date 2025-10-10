@php
$color1=strlen($nombre)*7;
$color2=strlen($nombre)*12;
$color3=strlen($nombre)*18;

if($color1>255)
  $color1=$color1-253;


if($color2>255)
  $color2=$color2-245;

if($color3>255)
  $color3=$color3-225;

$rgb = $color1.",".$color2.",".$color3;
$size=$size*4;
@endphp
<div class="rounded-full p-1 flex justify-center items-center"
  style="background-color:rgb({{$rgb}}); height: {{$size}}px;width: {{$size}}px;">
  {{$nombre[0]}}
</div>