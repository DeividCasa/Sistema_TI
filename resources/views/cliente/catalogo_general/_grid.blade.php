@forelse($productos as $producto)
  <a href="{{ $producto['url'] }}" class="product-card producto-item" data-tipo="{{ $producto['tipo'] }}">
    <div class="product-image">
      @if($producto['imagen'])
        <img src="{{ $producto['imagen'] }}" alt="{{ $producto['nombre'] }}" loading="lazy">
      @else
        <div class="no-img"><i class="fas fa-tshirt"></i></div>
      @endif
      <span class="badge-new">{{ $producto['badge'] }}</span>
    </div>
    <div class="product-body">
      <div class="product-name">{{ $producto['nombre'] }}</div>
      <div style="font-size:0.72rem;font-weight:600;color:var(--blue);margin-bottom:2px;">
        {{ ['hombre' => 'Para Hombre', 'mujer' => 'Para Mujer'][$producto['genero'] ?? null] ?? 'Unisex' }}
      </div>
      <div class="product-price">${{ number_format($producto['precio'], 2) }}</div>
      @if(!empty($producto['tallas']))
        <div class="product-sizes">
          @foreach($producto['tallas'] as $talla)
            <span>{{ strtoupper($talla) }}</span>
          @endforeach
        </div>
      @endif
      <div class="buy-btn"><i class="fas fa-shopping-cart"></i> Ver</div>
    </div>
  </a>
@empty
@endforelse
