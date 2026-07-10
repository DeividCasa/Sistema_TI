@extends('Admin.panel_admin')

@section('titulo', 'Chompas')
@section('page-title', 'Chompas')
@section('sidebar-display', 'display:flex')
@section('sidebar-margin', 'var(--sidebar-w)')

@section('contenido')

@if(session('success'))
  <div style="background:#DCFCE7;border:1px solid #BBF7D0;color:#15803D;padding:12px 18px;border-radius:10px;margin-bottom:20px;font-size:0.85rem;font-weight:500;">
    ✓ {{ session('success') }}
  </div>
@endif

<div class="sec-header reveal">
  <div class="sec-title">Chompas</div>
  <a href="{{ route('admin.chompas.create') }}" class="btn-primary" style="text-decoration:none;">+ Nueva chompa</a>
</div>

<div class="card reveal" style="overflow:hidden;">
  <table style="width:100%;border-collapse:collapse;font-size:0.87rem;">
    <thead>
      <tr style="background:var(--bg-3);text-align:left;">
        <th style="padding:12px 16px;color:var(--text-2);font-weight:600;">Foto</th>
        <th style="padding:12px 16px;color:var(--text-2);font-weight:600;">Nombre</th>
        <th style="padding:12px 16px;color:var(--text-2);font-weight:600;">Tipo de tela</th>
        <th style="padding:12px 16px;color:var(--text-2);font-weight:600;">Tallas y precios</th>
        <th style="padding:12px 16px;color:var(--text-2);font-weight:600;">Estado</th>
        <th style="padding:12px 16px;color:var(--text-2);font-weight:600;">Acciones</th>
      </tr>
    </thead>
    <tbody>
      @forelse($chompas as $chompa)
        <tr style="border-top:1px solid var(--border);">
          <td style="padding:10px 16px;">
            <img src="{{ asset('storage/' . $chompa->imagen) }}" alt="{{ $chompa->nombre }}"
                 style="width:56px;height:56px;object-fit:cover;border-radius:8px;border:1px solid var(--border);">
          </td>
          <td style="padding:10px 16px;font-weight:600;color:var(--text-1);">{{ $chompa->nombre }}</td>
          <td style="padding:10px 16px;color:var(--text-2);">{{ $chompa->tipo_tela }}</td>
          <td style="padding:10px 16px;">
            <div style="display:flex;flex-wrap:wrap;gap:6px;">
              @foreach($chompa->tallas as $talla)
                <span style="background:var(--blue-soft);border:1px solid var(--blue-border);color:var(--blue);
                             padding:3px 9px;border-radius:6px;font-size:0.75rem;font-weight:600;
                             {{ !$talla->disponible ? 'opacity:0.4;text-decoration:line-through;' : '' }}">
                  {{ $talla->talla }} → ${{ number_format($talla->precio, 2) }}
                </span>
              @endforeach
            </div>
          </td>
          <td style="padding:10px 16px;">
            @if($chompa->activo)
              <span style="background:#DCFCE7;color:#15803D;padding:4px 10px;border-radius:6px;font-size:0.75rem;font-weight:600;">Activa</span>
            @else
              <span style="background:#FEF2F2;color:#B91C1C;padding:4px 10px;border-radius:6px;font-size:0.75rem;font-weight:600;">Inactiva</span>
            @endif
          </td>
          <td style="padding:10px 16px;white-space:nowrap;">
            <a href="{{ route('admin.chompas.edit', $chompa->id) }}"
               style="color:var(--blue);text-decoration:none;font-weight:600;font-size:0.82rem;margin-right:12px;">Editar</a>
            <form action="{{ route('admin.chompas.destroy', $chompa->id) }}" method="POST" style="display:inline;"
                  onsubmit="return confirm('¿Seguro que deseas eliminar esta chompa?');">
              @csrf
              @method('DELETE')
              <button type="submit" style="background:none;border:none;color:#EF4444;font-weight:600;font-size:0.82rem;cursor:pointer;padding:0;">
                Eliminar
              </button>
            </form>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="6" style="padding:32px;text-align:center;color:var(--text-3);">
            Aún no hay chompas registradas. Crea la primera con el botón "Nueva chompa".
          </td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>

@endsection
