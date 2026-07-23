@extends('layouts.catalogo')

@section('titulo', 'Leo José | Inicio')

@section('contenido')

{{-- ── BANNER PRINCIPAL ─────────────────────────────────────────── --}}
<div class="hero-split reveal">
  <div class="hero-text">
    <div>
      <h1>{{ $info->banner_titulo ?: ($info->nombre_local ?? 'Leo José') }}</h1>
      <p>{{ $info->banner_subtitulo ?: $info->descripcion }}</p>
      <a href="{{ route('cliente.catalogo.index') }}" class="btn-primary" style="padding:13px 28px;font-size:0.95rem;">
        Ver catálogo
        <svg viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
      </a>
    </div>
  </div>
  <div class="hero-image">
    @if($info->imagen_path)
      <img src="{{ asset('storage/'.$info->imagen_path) }}" alt="{{ $info->nombre_local }}">
    @else
      <div class="hero-image-fallback"></div>
    @endif
  </div>
</div>

{{-- ── CATEGORÍAS DESTACADAS (banda verde) ──────────────────────── --}}
<div class="full-bleed" style="background:var(--band-green);">
  <div class="full-bleed-inner">
    <div class="sec-header reveal">
      <div class="sec-title" style="color:var(--band-green-strong);">Explora por categoría</div>
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(220px, 1fr));gap:20px;">
      @foreach([
        ['label' => 'Toda la ropa',        'imagen' => $info->categoria_ropa_imagen,     'categoria' => 'todos'],
        ['label' => 'Uniformes escolares', 'imagen' => $info->categoria_uniforme_imagen, 'categoria' => 'uniforme'],
        ['label' => 'Chompas',             'imagen' => $info->categoria_chompa_imagen,   'categoria' => 'chompa'],
      ] as $cat)
        <a href="{{ route('cliente.catalogo.index', $cat['categoria'] !== 'todos' ? ['categoria' => $cat['categoria']] : []) }}"
           class="reveal" style="position:relative;overflow:hidden;height:180px;border-radius:var(--radius);display:flex;align-items:flex-end;text-decoration:none;box-shadow:var(--shadow-md);
           @if($cat['imagen'])
             background:linear-gradient(rgba(15,23,42,0.15),rgba(15,23,42,0.75)),url('{{ asset('storage/'.$cat['imagen']) }}') center/cover no-repeat;
           @else
             background:linear-gradient(135deg,var(--band-green),var(--ink));
           @endif
           ">
          <span style="padding:18px;font-family:var(--font-d);font-size:1.15rem;font-weight:800;color:#fff;">
            {{ $cat['label'] }}
          </span>
        </a>
      @endforeach
    </div>
  </div>
</div>

{{-- ── PRODUCTOS DESTACADOS ─────────────────────────────────────── --}}
@if($destacados->isNotEmpty())
  <div style="max-width:1400px;margin:0 auto;padding:40px 32px;">
    <div class="sec-header reveal">
      <div class="sec-title">Productos destacados</div>
      <a href="{{ route('cliente.catalogo.index') }}" class="sec-link">Ver todo el catálogo →</a>
    </div>
    <div class="products-grid">
      @include('cliente.catalogo_general._grid', ['productos' => $destacados])
    </div>
  </div>
@endif

{{-- ── TESTIMONIOS (banda azul) ─────────────────────────────────── --}}
<div class="full-bleed" style="background:var(--band-blue);">
  <div class="full-bleed-inner">
    <div class="sec-header reveal">
      <div class="sec-title" style="color:var(--band-blue-strong);">Lo que dicen nuestros clientes</div>
      <a href="{{ route('cliente.testimonios.create') }}" class="sec-link" style="color:var(--band-blue-strong);">⭐ Danos tu opinión</a>
    </div>

    @if($testimonios->isEmpty())
      <div class="card card-pad reveal" style="text-align:center;">
        <p style="color:var(--text-2);font-size:0.9rem;">Aún no tenemos opiniones publicadas — ¡sé el primero en compartir tu experiencia!</p>
        <a href="{{ route('cliente.testimonios.create') }}" class="btn-primary" style="margin-top:14px;">Dejar mi opinión</a>
      </div>
    @else
      <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(260px, 1fr));gap:20px;">
        @foreach($testimonios as $testimonio)
          <div class="card card-pad reveal">
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:12px;">
              @if($testimonio->imagen)
                <img src="{{ asset('storage/'.$testimonio->imagen) }}" alt="{{ $testimonio->nombre_cliente }}"
                  style="width:46px;height:46px;border-radius:50%;object-fit:cover;border:1px solid var(--border);">
              @else
                <div style="width:46px;height:46px;border-radius:50%;background:var(--accent);
                  display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-family:var(--font-d);">
                  {{ strtoupper(mb_substr($testimonio->nombre_cliente, 0, 1)) }}
                </div>
              @endif
              <div>
                <div style="font-weight:700;color:var(--text-1);font-size:0.9rem;">{{ $testimonio->nombre_cliente }}</div>
                @if($testimonio->calificacion)
                  <div style="color:#F59E0B;font-size:0.78rem;">
                    {{ str_repeat('★', $testimonio->calificacion) }}{{ str_repeat('☆', 5 - $testimonio->calificacion) }}
                  </div>
                @endif
              </div>
            </div>
            <p style="color:var(--text-2);font-size:0.88rem;line-height:1.6;">"{{ $testimonio->texto }}"</p>
          </div>
        @endforeach
      </div>
    @endif
  </div>
</div>

{{-- ── INFO DEL LOCAL (banda vino) ──────────────────────────────── --}}
<div class="full-bleed" style="background:var(--band-cream);">
  <div class="full-bleed-inner">
    <div class="local-info-wrap">
      <div class="local-info-intro reveal">
        <h2>{{ $info->visitanos_titulo ?: 'Visítanos' }}</h2>
        <p>{{ $info->visitanos_texto ?: 'Conoce el local, resuelve tus dudas o pasa a recoger tu pedido — estos son nuestros datos de contacto.' }}</p>
      </div>

      <div class="local-info-items">
        @if($info->direccion)
          <div class="local-info-card reveal">
            <div class="local-info-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8"><path d="M21 10c0 7-9 12-9 12s-9-5-9-12a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
            </div>
            <div class="local-info-label">Dirección</div>
            <div class="local-info-value">{{ $info->direccion }}</div>
          </div>
        @endif

        @if($info->horario)
          <div class="local-info-card reveal">
            <div class="local-info-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            </div>
            <div class="local-info-label">Horario</div>
            <div class="local-info-value">{{ $info->horario }}</div>
          </div>
        @endif

        @if($info->telefono)
          <div class="local-info-card reveal">
            <div class="local-info-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72c.127.96.362 1.903.7 2.81a2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.338 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg>
            </div>
            <div class="local-info-label">Teléfono</div>
            <div class="local-info-value">{{ $info->telefono }}</div>
          </div>
        @endif

        @if($info->email_contacto)
          <div class="local-info-card reveal">
            <div class="local-info-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke-width="1.8"><path d="M22 6l-10 7L2 6"/><rect x="2" y="4" width="20" height="16" rx="2"/></svg>
            </div>
            <div class="local-info-label">Correo</div>
            <div class="local-info-value">{{ $info->email_contacto }}</div>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>

@endsection
