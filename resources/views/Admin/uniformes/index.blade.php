@extends('Admin.panel_admin')

@section('titulo', 'Uniformes Escolares')
@section('page-title', 'Uniformes Escolares')
@section('sidebar-display', 'display:flex')
@section('sidebar-margin', 'var(--sidebar-w)')

@section('contenido')

@if(session('success'))
  <div class="badge-success" style="display:block;padding:12px 18px;border-radius:10px;margin-bottom:20px;font-size:0.85rem;font-weight:500;">
    {{ session('success') }}
  </div>
@endif

<div class="sec-header reveal">
  <div class="sec-title">Uniformes Escolares</div>
  <a href="{{ route('admin.uniformes.create') }}" class="btn-primary" style="text-decoration:none;">+ Nuevo uniforme</a>
</div>

<div class="card reveal" style="overflow:hidden;">
  <table class="admin-table">
    <thead>
      <tr>
        <th>Foto</th>
        <th>Nombre</th>
        <th>Tipo de tela</th>
        <th>Tallas y precios</th>
        <th>Estado</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      @forelse($uniformes as $uniforme)
        <tr>
          <td>
            <img src="{{ asset('storage/' . $uniforme->imagen) }}" alt="{{ $uniforme->nombre }}" class="cell-thumb">
          </td>
          <td class="cell-strong">{{ $uniforme->nombre }}</td>
          <td>{{ $uniforme->tipo_tela }}</td>
          <td>
            <div style="display:flex;flex-wrap:wrap;gap:6px;">
              @foreach($uniforme->tallas as $talla)
                <span style="background:var(--blue-soft);border:1px solid var(--blue-border);color:var(--blue);
                             padding:3px 9px;border-radius:6px;font-size:0.75rem;font-weight:600;
                             {{ !$talla->disponible ? 'opacity:0.4;text-decoration:line-through;' : '' }}">
                  {{ $talla->talla }} — ${{ number_format($talla->precio, 2) }}
                </span>
              @endforeach
            </div>
          </td>
          <td>
            @if($uniforme->activo)
              <span class="badge badge-success">Activo</span>
            @else
              <span class="badge badge-danger">Inactivo</span>
            @endif
          </td>
          <td class="cell-actions" style="white-space:nowrap;">
            <a href="{{ route('admin.uniformes.edit', $uniforme->id) }}">Editar</a>
            <form action="{{ route('admin.uniformes.destroy', $uniforme->id) }}" method="POST" style="display:inline;"
                  onsubmit="return confirm('¿Seguro que deseas eliminar este uniforme?');">
              @csrf
              @method('DELETE')
              <button type="submit" class="link-danger">Eliminar</button>
            </form>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="6" class="cell-empty">
            Aún no hay uniformes registrados. Crea el primero con el botón "+ Nuevo uniforme".
          </td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>

@endsection
