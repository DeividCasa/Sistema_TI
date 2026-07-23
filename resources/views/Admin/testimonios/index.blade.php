@extends('Admin.panel_admin')

@section('titulo', 'Testimonios')
@section('page-title', 'Testimonios')
@section('sidebar-display', 'display:flex')
@section('sidebar-margin', 'var(--sidebar-w)')

@section('contenido')

<style>
    .admin-header {
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 1.8rem; flex-wrap: wrap; gap: 1rem;
    }
    .admin-title {
        font-size: 1.6rem; font-weight: 700; color: var(--text-1); margin: 0;
        display: flex; align-items: baseline; gap: 0.75rem; flex-wrap: wrap;
    }
    .admin-badge {
        background: var(--bg-3); color: var(--text-2); padding: 0.25rem 0.75rem;
        font-size: 0.8rem; font-weight: 500; border: 1px solid var(--border);
    }
    .admin-badge.limite { background: var(--accent-soft); color: var(--accent); border-color: var(--accent-border); font-weight: 700; }
    .testimonios-grid {
        display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1.5rem; margin-top: 0.5rem;
    }
    .testimonio-card {
        background: var(--bg-2); border: 1px solid var(--border);
        padding: 1.2rem; display: flex; flex-direction: column; gap: 0.6rem;
    }
    .testimonio-head { display: flex; align-items: center; gap: 10px; }
    .testimonio-avatar {
        width: 44px; height: 44px; border-radius: 50%; background: var(--bg-3);
        border: 1px solid var(--border); object-fit: cover; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; color: var(--text-3);
    }
    .testimonio-nombre { font-weight: 700; font-size: 0.92rem; color: var(--text-1); }
    .testimonio-estrellas { color: #F59E0B; font-size: 0.8rem; }
    .testimonio-texto { font-size: 0.85rem; color: var(--text-2); line-height: 1.5; }
    .estado-badge {
        font-size: 0.7rem; font-weight: 600; padding: 0.2rem 0.7rem;
        display: inline-block; border: 1px solid transparent; align-self: flex-start;
    }
    .est-pendiente { background: #FEF3C7; color: #92400E; border-color: #FDE68A; }
    .est-aprobado { background: #dcfce7; color: #166534; border-color: #bbf7d0; }
    .est-rechazado { background: #ffe4e2; color: #b91c1c; border-color: #fecaca; }
    .est-mostrando { background: var(--blue-soft); color: var(--blue); border-color: var(--blue-border); }
    [data-theme="dark"] .est-pendiente { background: #78350f; color: #fde68a; border-color: #92400e; }
    [data-theme="dark"] .est-aprobado { background: #14532d; color: #86efac; border-color: #166534; }
    [data-theme="dark"] .est-rechazado { background: #7f1d1d; color: #fecaca; border-color: #991b1b; }
    .acciones { display: flex; gap: 0.5rem; margin-top: 0.4rem; flex-wrap: wrap; }
    .btn-accion {
        flex: 1; min-width: 90px; border: none; padding: 0.5rem;
        font-size: 0.75rem; font-weight: 600; text-align: center;
        text-decoration: none; cursor: pointer; transition: opacity 0.15s;
    }
    .btn-aprobar { background: #DCFCE7; color: #15803D; }
    .btn-rechazar { background: #FEE2E2; color: #991B1B; }
    .btn-activar { background: var(--blue); color: white; }
    .btn-desactivar { background: var(--bg-3); color: var(--text-2); border: 1px solid var(--border); }
    .btn-accion:hover { opacity: 0.85; }
    .btn-delete {
        background: transparent; border: 1px solid var(--border); padding: 0.5rem;
        font-size: 0.75rem; font-weight: 500; color: var(--text-2);
        cursor: pointer; transition: all 0.15s; width: 100%;
    }
    .btn-delete:hover { background: #fef2f2; color: #dc2626; border-color: #fecaca; }
    .empty-card { background: var(--bg-2); border: 1px solid var(--border); padding: 3rem; text-align: center; }
</style>

@if(session('success'))
  <div class="badge-success" style="display:block;padding:0.75rem 1rem;margin-bottom:1.5rem;font-size:0.85rem;border-radius:8px;">
    {{ session('success') }}
  </div>
@endif

@if($errors->any())
  <div style="background:#FEF2F2;border:1px solid #FECACA;color:#B91C1C;padding:0.75rem 1rem;margin-bottom:1.5rem;font-size:0.85rem;border-radius:8px;">
    @foreach($errors->all() as $error)
      <div>{{ $error }}</div>
    @endforeach
  </div>
@endif

<div class="admin-header">
    <div class="admin-title">
        Testimonios
        <span class="admin-badge">{{ $testimonios->count() }} recibidos</span>
        <span class="admin-badge limite">{{ $activos }} / {{ \App\Http\Controllers\Admin\TestimonioController::MAXIMO_ACTIVOS }} mostrándose en inicio</span>
    </div>
</div>

@if($testimonios->isEmpty())
    <div class="empty-card">
        <p style="color: var(--text-2);">Aún no hay testimonios enviados por clientes. Aparecerán aquí en cuanto alguien deje su opinión desde "Danos tu opinión".</p>
    </div>
@else
    <div class="testimonios-grid">
        @foreach($testimonios as $testimonio)
            <div class="testimonio-card">
                <div class="testimonio-head">
                    @if($testimonio->imagen)
                        <img src="{{ asset('storage/'.$testimonio->imagen) }}" alt="{{ $testimonio->nombre_cliente }}" class="testimonio-avatar">
                    @else
                        <div class="testimonio-avatar">{{ strtoupper(mb_substr($testimonio->nombre_cliente, 0, 1)) }}</div>
                    @endif
                    <div>
                        <div class="testimonio-nombre">{{ $testimonio->nombre_cliente }}</div>
                        @if($testimonio->calificacion)
                            <div class="testimonio-estrellas">{{ str_repeat('★', $testimonio->calificacion) }}{{ str_repeat('☆', 5 - $testimonio->calificacion) }}</div>
                        @endif
                    </div>
                </div>
                <div class="testimonio-texto">"{{ Str::limit($testimonio->texto, 160) }}"</div>

                <div style="display:flex;gap:6px;flex-wrap:wrap;">
                    @if($testimonio->estado === 'pendiente')
                        <span class="estado-badge est-pendiente">Pendiente de revisión</span>
                    @elseif($testimonio->estado === 'rechazado')
                        <span class="estado-badge est-rechazado">Rechazado</span>
                    @elseif($testimonio->activo)
                        <span class="estado-badge est-mostrando">Mostrándose en inicio</span>
                    @else
                        <span class="estado-badge est-aprobado">Aprobado</span>
                    @endif
                </div>

                <div class="acciones">
                    @if($testimonio->estado === 'pendiente')
                        <form action="{{ route('admin.testimonios.aprobar', $testimonio->id) }}" method="POST" style="flex:1;">
                            @csrf
                            <button type="submit" class="btn-accion btn-aprobar" style="width:100%;">Aprobar</button>
                        </form>
                        <form action="{{ route('admin.testimonios.rechazar', $testimonio->id) }}" method="POST" style="flex:1;">
                            @csrf
                            <button type="submit" class="btn-accion btn-rechazar" style="width:100%;">Rechazar</button>
                        </form>
                    @elseif($testimonio->estado === 'aprobado' && !$testimonio->activo)
                        <form action="{{ route('admin.testimonios.activar', $testimonio->id) }}" method="POST" style="flex:1;">
                            @csrf
                            <button type="submit" class="btn-accion btn-activar" style="width:100%;">Mostrar en inicio</button>
                        </form>
                    @elseif($testimonio->activo)
                        <form action="{{ route('admin.testimonios.desactivar', $testimonio->id) }}" method="POST" style="flex:1;">
                            @csrf
                            <button type="submit" class="btn-accion btn-desactivar" style="width:100%;">Quitar del inicio</button>
                        </form>
                    @elseif($testimonio->estado === 'rechazado')
                        <form action="{{ route('admin.testimonios.aprobar', $testimonio->id) }}" method="POST" style="flex:1;">
                            @csrf
                            <button type="submit" class="btn-accion btn-aprobar" style="width:100%;">Reconsiderar</button>
                        </form>
                    @endif
                </div>
                <form action="{{ route('admin.testimonios.destroy', $testimonio->id) }}" method="POST"
                      onsubmit="return confirm('¿Seguro que deseas eliminar este testimonio?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-delete">Eliminar</button>
                </form>
            </div>
        @endforeach
    </div>
@endif

@endsection
