    <div id="tab-texto" class="tab-content">
      <div class="p-title">Agregar texto</div>
      <div class="p-sub">Escribe y arrastra el texto donde quieras.</div>

      <div class="p-label">Texto</div>
      <input class="p-input" id="input-texto" type="text" placeholder="Ej: REAL MADRID" maxlength="24" oninput="actualizarTextoLive()">

      <div class="p-label">NÃºmero</div>
      <input class="p-input" id="input-numero" type="number" placeholder="10" min="1" max="99" oninput="actualizarNumeroLive()">

      <div class="p-label">Tipo de letra</div>
      <select class="p-select" id="texto-font" onchange="cambiarFuenteTexto(this.value)">
        <option value="'DM Sans', sans-serif">DM Sans</option>
        <option value="'Outfit', sans-serif">Outfit</option>
        <option value="Arial, sans-serif">Arial</option>
        <option value="Impact, sans-serif">Impact</option>
        <option value="'Times New Roman', serif">Times New Roman</option>
        <option value="'Courier New', monospace">Courier New</option>
        <option value="'Brush Script MT', cursive">Brush Script</option>
      </select>

      <div class="p-label">Color del texto o nÃºmero</div>
      <div class="color-grid" id="swatches-texto"></div>
      <div style="display:flex;align-items:center;gap:10px;margin-top:8px;">
        <input type="color" id="texto-color-custom" value="#000000"
          style="width:44px;height:44px;border-radius:10px;border:1.5px solid var(--border);cursor:pointer;padding:2px;background:var(--bg-2);"
          oninput="aplicarColorTextoCustom(this.value)">
        <div style="flex:1;">
          <div style="font-size:.78rem;font-weight:600;color:var(--text-2);">Hex</div>
          <input class="p-input" id="texto-hex-input" type="text" value="#000000" maxlength="7"
            style="margin-bottom:0;font-family:monospace;" oninput="aplicarColorTextoHex(this.value)">
        </div>
      </div>

      <div class="p-label">TamaÃ±o de fuente</div>
      <input type="range" id="texto-size" min="14" max="80" value="32" style="width:100%" oninput="cambiarTamanoTexto(this.value)">

      <div class="p-sep"></div>
      <button class="p-btn p-btn-primary" onclick="agregarTexto()">
        <i class="fas fa-plus"></i> Agregar texto al diseÃ±o
      </button>
      <button class="p-btn p-btn-ghost" onclick="agregarNumero()">
        <i class="fas fa-hashtag"></i> Agregar nÃºmero al diseÃ±o
      </button>
    </div>
