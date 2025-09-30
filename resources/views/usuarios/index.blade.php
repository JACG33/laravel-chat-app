<x-layouts.app :title="__('Dashboard')">
  <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
    <div>
      <h2>Usurios</h2>
    </div>
    <div>
      <table>
        <thead>
          <tr>
            <td>Nombre</td>
            <td>Mensaje</td>
          </tr>
        </thead>
        <tbody>
          @forelse($usuarios as $usuario)
          <tr>
            <td>{{$usuario->name}}</td>
            <td><a href="{{route('chat.registrar',$usuario->id)}}">Enviar mensaje</a></td>
          </tr>
          @empty
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</x-layouts.app>