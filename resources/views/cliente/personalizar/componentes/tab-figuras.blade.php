    <div id="tab-figuras" class="tab-content">
      <div class="p-title">Agregar figuras</div>
      <div class="p-sub">Inserta figuras y acomÃ³dalas encima de la prenda.</div>

      <div class="p-label">Color de figura</div>
      <div class="color-grid" id="swatches-figura"></div>
      <div style="display:flex;align-items:center;gap:10px;margin-top:8px;">
        <input type="color" id="figura-color" value="#000000"
          style="width:44px;height:44px;border-radius:10px;border:1.5px solid var(--border);cursor:pointer;padding:2px;background:var(--bg-2);"
          oninput="cambiarColorFigura(this.value)">
        <div style="flex:1;">
          <div style="font-size:.78rem;font-weight:600;color:var(--text-2);">Hex</div>
          <input class="p-input" id="figura-hex-input" type="text" value="#000000" maxlength="7"
            style="margin-bottom:0;font-family:monospace;"
            oninput="if(/^#[0-9a-fA-F]{6}$/.test(this.value)) cambiarColorFigura(this.value)">
        </div>
      </div>

      <div class="p-label">Formas</div>
      <div class="shape-grid">
        <button class="shape-btn" onclick="agregarFigura('rect')" title="RectÃ¡ngulo"><i class="fas fa-square"></i><span>Cuadro</span></button>
        <button class="shape-btn" onclick="agregarFigura('circle')" title="CÃ­rculo"><i class="fas fa-circle"></i><span>CÃ­rculo</span></button>
        <button class="shape-btn" onclick="agregarFigura('triangle')" title="TriÃ¡ngulo"><i class="fas fa-play"></i><span>TriÃ¡ngulo</span></button>
        <button class="shape-btn" onclick="agregarFigura('star')" title="Estrella"><i class="fas fa-star"></i><span>Estrella</span></button>
        <button class="shape-btn" onclick="agregarFigura('line')" title="LÃ­nea"><i class="fas fa-minus"></i><span>LÃ­nea</span></button>
        <button class="shape-btn" onclick="agregarFigura('heart')" title="CorazÃ³n"><i class="fas fa-heart"></i><span>CorazÃ³n</span></button>
      </div>
    </div>
