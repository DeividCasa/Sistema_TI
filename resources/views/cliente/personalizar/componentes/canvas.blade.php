<div class="editor-2d" id="editor-2d">

  <!-- TABS DE VISTAS (columna vertical izquierda) -->
  <div class="vista-tabs" id="vista-tabs"></div>

  <!-- ÁREA CENTRAL -->
  <div class="canvas-area" id="canvas-area">

    <!-- toolbar flotante -->
    <div class="obj-toolbar" id="obj-toolbar">
      <button class="obj-btn" onclick="moverArriba()" title="Traer al frente"><i class="fas fa-layer-group"></i></button>
      <button class="obj-btn" onclick="moverAtras()" title="Enviar atrás"><i class="fas fa-down-left-and-up-right-to-center"></i></button>
      <div class="obj-sep"></div>
      <button class="obj-btn" onclick="duplicarObj()" title="Duplicar"><i class="fas fa-copy"></i></button>
      <div class="obj-sep"></div>
      <button class="obj-btn danger" onclick="eliminarObj()" title="Eliminar"><i class="fas fa-trash"></i></button>
    </div>

    <!-- Selector destino (aparece cuando pantaloneta activa) -->
    <div class="selector-destino" id="selector-destino" style="display:none;">
      <span style="font-size:.7rem;font-weight:600;color:var(--text-3);">Agregar en:</span>
      <button class="destino-btn active" id="destino-btn-camiseta" onclick="setCanvasDestino('camiseta')">
        <i class="fas fa-tshirt"></i> Camiseta
      </button>
      <button class="destino-btn" id="destino-btn-pantaloneta" onclick="setCanvasDestino('pantaloneta')">
        <i class="fas fa-person-running"></i> Pantaloneta
      </button>
    </div>

    <!-- Fila: canvas principal + columna accesorios -->
    <div class="canvas-fila-principal">

      <!-- Canvas principal (camiseta/chompa) -->
      <div class="canvas-wrap" id="canvas-wrap">
        <canvas id="fabric-canvas"></canvas>
        <div class="drag-hint" id="drag-hint">Suelta aquí para agregar</div>
      </div>

      <!-- Columna accesorios -->
      <div class="acc-columna" id="acc-columna">

        <!-- Card pantaloneta: canvas Fabric.js editable -->
        <div class="acc-canvas-card acc-canvas-card-fabric" id="acc-card-pantaloneta" style="display:none;">
          <div class="acc-canvas-titulo">
            <span class="acc-canvas-dot" id="acc-canvas-dot-pantaloneta" style="background:#1565c0;"></span>
            Pantaloneta
            <span class="acc-canvas-hint">Arrastra diseños aquí</span>
          </div>
          <div class="acc-fabric-wrap" id="acc-fabric-wrap-pantaloneta">
            <canvas id="fabric-canvas-pantaloneta"></canvas>
          </div>
        </div>

        <!-- Card medias: solo color (SVG) -->
        <div class="acc-canvas-card" id="acc-card-medias" style="display:none;">
          <div class="acc-canvas-titulo">
            <span class="acc-canvas-dot" id="acc-canvas-dot-medias" style="background:#b71c1c;"></span>
            Medias
          </div>
          <div class="acc-canvas-svgwrap" id="acc-svg-medias"></div>
        </div>

        <!-- Card pantalón (chompa): solo color (SVG) -->
        <div class="acc-canvas-card" id="acc-card-pantalon" style="display:none;">
          <div class="acc-canvas-titulo">
            <span class="acc-canvas-dot" id="acc-canvas-dot-pantalon" style="background:#2f2f2f;"></span>
            Pantalón
          </div>
          <div class="acc-canvas-svgwrap" id="acc-svg-pantalon"></div>
        </div>

      </div>
    </div>

    <div class="canvas-label-bottom" id="canvas-label-bottom">Vista delantera — arrastra los elementos libremente</div>
  </div>
</div>