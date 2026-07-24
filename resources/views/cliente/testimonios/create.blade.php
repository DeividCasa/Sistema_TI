@extends('layouts.catalogo')

@section('titulo', 'Danos tu opinión — Leo José')

@push('estilos')
<style>
  .star-rating {
    display: flex; flex-direction: row; justify-content: center; width: 100%;
    gap: 6px; margin: 8px 0 4px;
  }
  .star-rating input { display: none; }
  .star-rating label {
    font-size: 2.6rem; line-height: 1; color: var(--border-2);
    cursor: pointer; transition: color 0.15s, transform 0.15s;
  }
  .star-rating label:hover { transform: scale(1.1); }
  .star-rating label.active { color: #F59E0B; }
</style>
@endpush

@section('contenido')

<div style="max-width:560px;margin:0 auto;">

  <div class="card card-pad reveal" style="text-align:center;margin-bottom:20px;">
    <div style="width:56px;height:56px;border-radius:50%;background:var(--accent-soft);border:1.5px solid var(--accent-border);
      display:flex;align-items:center;justify-content:center;margin:0 auto 14px;">
      <svg viewBox="0 0 24 24" style="width:26px;height:26px;stroke:var(--accent);fill:none;stroke-width:1.8;">
        <path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/>
      </svg>
    </div>
    <h1 style="font-family:var(--font-d);font-size:1.5rem;font-weight:800;color:var(--text-1);margin-bottom:8px;">Apóyanos con tu opinión</h1>
    <p style="color:var(--text-2);font-size:0.9rem;">Tu experiencia ayuda a otros clientes a confiar en nosotros.</p>
  </div>

  @if(session('success'))
    <div style="background:#DCFCE7;border:1px solid #BBF7D0;color:#15803D;padding:12px 18px;border-radius:10px;margin-bottom:20px;font-size:0.85rem;font-weight:500;text-align:center;">
      {{ session('success') }}
    </div>
  @endif

  @if($errors->any())
    <div style="background:#FEF2F2;border:1px solid #FECACA;color:#B91C1C;padding:12px 18px;border-radius:10px;margin-bottom:20px;font-size:0.85rem;font-weight:500;">
      @foreach($errors->all() as $error)
        <div>{{ $error }}</div>
      @endforeach
    </div>
  @endif

  @if($yaOpino)
    <div class="card card-pad reveal" style="text-align:center;">
      <p style="color:var(--text-1);font-weight:700;margin-bottom:6px;">¡Ya nos dejaste tu opinión!</p>
      <p style="color:var(--text-2);font-size:0.88rem;">Gracias por tu tiempo — nuestro equipo la está revisando.</p>
      <a href="{{ route('cliente.catalogo.index') }}" class="btn-primary" style="margin-top:16px;">Volver al catálogo</a>
    </div>
  @else
    <div class="card card-pad reveal">
      <form action="{{ route('cliente.testimonios.store') }}" method="POST">
        @csrf

        <label style="display:block;text-align:center;font-size:0.78rem;font-weight:600;color:var(--text-2);text-transform:uppercase;letter-spacing:0.03em;margin-bottom:4px;">
          ¿Cómo calificas tu experiencia?
        </label>
        <div class="star-rating" id="star-rating">
          <input type="radio" id="star1" name="calificacion" value="1" {{ old('calificacion') == 1 ? 'checked' : '' }}><label for="star1" data-valor="1">★</label>
          <input type="radio" id="star2" name="calificacion" value="2" {{ old('calificacion') == 2 ? 'checked' : '' }}><label for="star2" data-valor="2">★</label>
          <input type="radio" id="star3" name="calificacion" value="3" {{ old('calificacion') == 3 ? 'checked' : '' }}><label for="star3" data-valor="3">★</label>
          <input type="radio" id="star4" name="calificacion" value="4" {{ old('calificacion') == 4 ? 'checked' : '' }}><label for="star4" data-valor="4">★</label>
          <input type="radio" id="star5" name="calificacion" value="5" {{ old('calificacion') == 5 ? 'checked' : '' }}><label for="star5" data-valor="5">★</label>
        </div>

        <div style="margin:22px 0 20px;">
          <label style="display:block;font-size:0.78rem;font-weight:600;color:var(--text-2);text-transform:uppercase;letter-spacing:0.03em;margin-bottom:7px;">
            Cuéntanos tu experiencia
          </label>
          <textarea name="texto" rows="4" maxlength="600"
            placeholder="¿Qué te pareció el producto, la atención, el tiempo de entrega...?"
            style="width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;
            font-family:var(--font-b);font-size:0.9rem;color:var(--text-1);background:var(--bg-2);outline:none;resize:vertical;">{{ old('texto') }}</textarea>
        </div>

        <button type="submit" class="btn-primary" style="width:100%;justify-content:center;">
          Enviar mi opinión
        </button>
      </form>
    </div>
  @endif

</div>

@endsection

@push('scripts')
<script>
  (function() {
    const contenedor = document.getElementById('star-rating');
    if (!contenedor) return;
    const labels = Array.from(contenedor.querySelectorAll('label'));

    function pintarHasta(valor) {
      labels.forEach(l => l.classList.toggle('active', parseInt(l.dataset.valor, 10) <= valor));
    }

    function valorSeleccionado() {
      const marcado = contenedor.querySelector('input:checked');
      return marcado ? parseInt(marcado.value, 10) : 0;
    }

    labels.forEach(label => {
      label.addEventListener('mouseenter', () => pintarHasta(parseInt(label.dataset.valor, 10)));
    });
    contenedor.addEventListener('mouseleave', () => pintarHasta(valorSeleccionado()));
    contenedor.querySelectorAll('input').forEach(input => {
      input.addEventListener('change', () => pintarHasta(valorSeleccionado()));
    });

    pintarHasta(valorSeleccionado());
  })();
</script>
@endpush
