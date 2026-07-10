<div class="visor-3d" id="visor-3d">
  <div class="visor-label">
    <i class="fas fa-cube" style="margin-right:6px;"></i>Vista 3D en vivo
  </div>
  <!-- UN SOLO canvas 3D para todo el uniforme -->
  <div class="visor-fila-canvas" id="visor-wrap-camiseta" style="flex:1;position:relative;min-height:0;">
    <canvas id="canvas-3d"></canvas>
    <div class="visor-hint-3d">Arrastra para girar</div>
    <div class="visor-flechas">
      <button type="button" class="visor-flecha visor-flecha-up" title="Girar arriba" onclick="rotarVisor3D('up')"><i class="fas fa-chevron-up"></i></button>
      <div class="visor-flechas-fila">
        <button type="button" class="visor-flecha" title="Girar izquierda" onclick="rotarVisor3D('left')"><i class="fas fa-chevron-left"></i></button>
        <button type="button" class="visor-flecha" title="Girar derecha" onclick="rotarVisor3D('right')"><i class="fas fa-chevron-right"></i></button>
      </div>
      <button type="button" class="visor-flecha visor-flecha-down" title="Girar abajo" onclick="rotarVisor3D('down')"><i class="fas fa-chevron-down"></i></button>
    </div>
  </div>
</div>