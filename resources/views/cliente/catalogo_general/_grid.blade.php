@forelse($productos as $producto)
  <div class="col-sm-6 col-md-4 col-lg-3 p-b-35 producto-item" data-tipo="{{ $producto['tipo'] }}">
    <div class="block2">
      <div class="block2-pic hov-img0">
        @if($producto['imagen'])
          <img src="{{ $producto['imagen'] }}" alt="{{ $producto['nombre'] }}" loading="lazy">
        @else
          <img src="{{ asset('images/fondo.png') }}" alt="{{ $producto['nombre'] }}" loading="lazy">
        @endif

        <span class="block2-badge">{{ $producto['badge'] }}</span>

        <a href="{{ $producto['url'] }}" class="block2-btn flex-c-m stext-103 cl2 size-102 bg0 bor2 hov-btn1 p-lr-15 trans-04">
          Ver producto
        </a>
      </div>

      <div class="block2-txt flex-w flex-t p-t-14">
        <div class="block2-txt-child1 flex-col-l">
          <a href="{{ $producto['url'] }}" class="stext-104 cl4 hov-cl1 trans-04 p-b-6">
            {{ $producto['nombre'] }}
          </a>
          <span class="stext-107 cl3" style="text-transform:capitalize;">
            {{ ['hombre' => 'Para Hombre', 'mujer' => 'Para Mujer'][$producto['genero'] ?? null] ?? 'Unisex' }}
          </span>
          <span class="stext-105 cl3">${{ number_format($producto['precio'], 2) }}</span>
          @if(!empty($producto['tallas']))
            <span class="stext-107 cl3 p-t-4">
              Tallas: {{ collect($producto['tallas'])->map(fn($t) => strtoupper($t))->implode(' · ') }}
            </span>
          @endif
        </div>
      </div>
    </div>
  </div>
@empty
@endforelse
