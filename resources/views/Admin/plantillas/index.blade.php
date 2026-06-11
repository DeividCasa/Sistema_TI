@extends('Admin.panel_admin')

@section('titulo', 'Plantillas')
@section('page-title', 'Gestión de Plantillas')

@section('admin-content')
@section('sidebar-display', 'display:flex')
@section('sidebar-margin', 'var(--sidebar-w)')



@section('contenido')

{{-- Mensaje de éxito --}}
@if(session('success'))
  <div style="background:#DCFCE7;border:1px solid #BBF7D0;color:#15803D;padding:12px 18px;border-radius:10px;margin-bottom:20px;font-size:0.85rem;font-weight:500;">
    ✓ {{ session('success') }}
  </div>
@endif

{{-- Header --}}
<div class="sec-header reveal">
  <div class="sec-title">
    Plantillas
    <span class="sec-badge">{{ $plantillas->count() }} registradas</span>
  </div>
  <a href="{{ route('admin.plantillas.create') }}" class="btn-primary">
    <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
    Nueva plantilla
  </a>
</div>

{{-- Grid de plantillas --}}
@if($plantillas->isEmpty())
  <div class="card">
    <div class="empty-state">
      <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
      <p>No hay plantillas registradas. ¡Crea la primera!</p>
    </div>
  </div>
@else
  <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:16px;">
    @foreach($plantillas as $plantilla)
      <div class="card reveal" style="overflow:hidden;">
        {{-- Imagen --}}
        <div style="height:180px;background:var(--bg-3);display:flex;align-items:center;justify-content:center;overflow:hidden;position:relative;">
          @if($plantilla->imagen_preview)
            <img src="{{ asset('storage/'.$plantilla->imagen_preview) }}"
                 alt="{{ $plantilla->nombre }}"
                 style="width:100%;height:100%;object-fit:cover;">
          @else
            <svg viewBox="0 0 24 24" style="width:48px;height:48px;stroke:var(--text-3);fill:none;stroke-width:1.5;"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
          @endif
          {{-- Badge activa/inactiva --}}
          <div style="position:absolute;top:8px;right:8px;">
            @if($plantilla->activa)
              <span class="est est-listo">Activa</span>
            @else
              <span class="est est-entregado">Inactiva</span>
            @endif
          </div>
        </div>

        {{-- Info --}}
        <div style="padding:14px 16px;">
          <div style="font-family:var(--font-d);font-weight:700;font-size:0.92rem;color:var(--text-1);margin-bottom:4px;">
            {{ $plantilla->nombre }}
          </div>
          <div style="font-size:0.78rem;color:var(--text-3);text-transform:capitalize;margin-bottom:14px;">
            {{ $plantilla->tipo_prenda }}
          </div>

          {{-- Acciones --}}
          <div style="display:flex;gap:8px;">
            <a href="{{ route('admin.plantillas.edit', $plantilla->id) }}"
               class="btn-primary" style="padding:7px 14px;font-size:0.78rem;flex:1;justify-content:center;">
              Editar
            </a>
            <form action="{{ route('admin.plantillas.destroy', $plantilla->id) }}" method="POST"
                  onsubmit="return confirm('¿Eliminar esta plantilla?')">
              @csrf
              @method('DELETE')
              <button type="submit"
                style="padding:7px 14px;border-radius:9px;border:1px solid var(--border);
                background:var(--bg-3);color:var(--text-2);font-size:0.78rem;cursor:pointer;
                transition:all var(--tr);"
                onmouseover="this.style.background='#FEF2F2';this.style.color='#DC2626';this.style.borderColor='#FCA5A5'"
                onmouseout="this.style.background='var(--bg-3)';this.style.color='var(--text-2)';this.style.borderColor='var(--border)'">
                Eliminar
              </button>
            </form>
          </div>
        </div>
      </div>
    @endforeach
  </div>
@endif

@endsection