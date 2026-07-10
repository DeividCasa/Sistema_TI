<div id="tab-colores" class="tab-content active">
  <div class="p-title">Colores</div>
  <div class="p-sub">Cambia el color de fondo de la zona activa.</div>

  <!-- Indicador zona activa camiseta -->
  <div class="zona-activa-badge" id="zona-badge">
    <span class="dot" id="zona-dot" style="background:#ffd835"></span>
    <span id="zona-nombre">Parte delantera</span>
  </div>

  <div class="p-label">Colores estándar</div>
  <div class="color-grid" id="swatches-global"></div>

  <div class="p-sep"></div>
  <div class="p-label">Color personalizado</div>
  <div style="display:flex;align-items:center;gap:10px;">
    <input type="color" id="color-custom" value="#ffd835"
      style="width:44px;height:44px;border-radius:10px;border:1.5px solid var(--border);cursor:pointer;padding:2px;background:var(--bg-2);"
      oninput="aplicarColorCustom(this.value)">
    <div style="flex:1;">
      <div style="font-size:.78rem;font-weight:600;color:var(--text-2);">Hex</div>
      <input class="p-input" id="hex-input" type="text" value="#ffd835" maxlength="7"
        style="margin-bottom:0;font-family:monospace;" oninput="aplicarHex(this.value)">
    </div>
  </div>

  <!-- ══════════════════════════════════════
       ACCESORIOS DEL UNIFORME
       Pantaloneta/Medias son parte del modeloCompleto.glb (camiseta) y
       no existen en chompa.glb — solo tiene sentido ofrecerlos cuando la
       prenda activa es la camiseta. El Pantalón es al revés: viene en su
       propio GLB (pantalonDeportivo.glb) y solo aplica a la chompa
       (ver #accesorios-chompa-wrap y ACCESORIOS.pantalon en accesorios.js).
  ══════════════════════════════════════ -->
  <div class="p-sep"></div>
  <div class="p-label">Accesorios del uniforme</div>

  <div id="accesorios-camiseta-wrap">
    <div class="p-sub" style="margin-bottom:10px;">Se mostrarán en el visor 3D junto a la camiseta.</div>

    <div style="display:flex;gap:8px;margin-bottom:10px;">
      <button id="acc-btn-pantaloneta" class="acc-btn" type="button" onclick="toggleAccesorio('pantaloneta')">
        <i class="fas fa-person-running" style="font-size:.8rem;"></i>Pantaloneta
      </button>
      <button id="acc-btn-medias" class="acc-btn" type="button" onclick="toggleAccesorio('medias')">
        <i class="fas fa-socks" style="font-size:.8rem;"></i>Medias
      </button>
    </div>
  </div>

  <div id="accesorios-chompa-wrap" style="display:none;">
    <div class="p-sub" style="margin-bottom:10px;">Se mostrará en el visor 3D debajo de la chompa.</div>
    <div style="display:flex;gap:8px;margin-bottom:10px;">
      <button id="acc-btn-pantalon" class="acc-btn" type="button" onclick="toggleAccesorio('pantalon')">
        <i class="fas fa-socks" style="font-size:.8rem;"></i>Pantalón
      </button>
    </div>
  </div>

  <!-- ─── PANEL PANTALÓN (chompa) ─── -->
  <div id="acc-panel-pantalon" style="display:none;">
    <div class="color-grid" id="acc-swatches-pantalon"></div>
    <div style="display:flex;align-items:center;gap:8px;margin:8px 0 4px;">
      <input type="color" id="acc-picker-pantalon" value="#2f2f2f"
        style="width:36px;height:36px;border-radius:8px;border:1.5px solid var(--border);cursor:pointer;padding:2px;background:var(--bg-2);"
        oninput="aplicarColorAccesorioActivo('pantalon',this.value)">
      <input class="p-input" id="acc-hex-pantalon" type="text" value="#2f2f2f" maxlength="7"
        style="margin-bottom:0;font-family:monospace;flex:1;"
        oninput="if(/^#[0-9a-fA-F]{6}$/.test(this.value)) aplicarColorAccesorioActivo('pantalon',this.value)">
    </div>
    <div class="p-sep"></div>
  </div>

  <!-- ─── PANEL PANTALONETA ─── -->
  <div id="acc-panel-pantaloneta" style="display:none;">

    <!-- Selector de zona activa — antes cada zona repetía su propia
         paleta completa; ahora comparten una sola paleta abajo. -->
    <div style="display:flex;gap:6px;margin-bottom:10px;" id="acc-zona-selector-pantaloneta">
      <button class="destino-btn active" type="button" onclick="seleccionarZonaAccesorio('pantaloneta','colorPantaloneta',this)">
        <span class="acc-dot" id="acc-dot-colorPantaloneta" style="background:#1565c0;"></span> Cuerpo
      </button>
      <button class="destino-btn" type="button" onclick="seleccionarZonaAccesorio('pantaloneta','colorParteAbajoPant',this)">
        <span class="acc-dot" id="acc-dot-colorParteAbajoPant" style="background:#0d47a1;"></span> Parte baja
      </button>
    </div>

    <div class="color-grid" id="acc-swatches-pantaloneta"></div>
    <div style="display:flex;align-items:center;gap:8px;margin:8px 0 4px;">
      <input type="color" id="acc-picker-pantaloneta" value="#1565c0"
        style="width:36px;height:36px;border-radius:8px;border:1.5px solid var(--border);cursor:pointer;padding:2px;background:var(--bg-2);"
        oninput="aplicarColorAccesorioActivo('pantaloneta',this.value)">
      <input class="p-input" id="acc-hex-pantaloneta" type="text" value="#1565c0" maxlength="7"
        style="margin-bottom:0;font-family:monospace;flex:1;"
        oninput="if(/^#[0-9a-fA-F]{6}$/.test(this.value)) aplicarColorAccesorioActivo('pantaloneta',this.value)">
    </div>

    <div class="p-sep"></div>
  </div>

  <!-- ─── PANEL MEDIAS ─── -->
  <div id="acc-panel-medias" style="display:none;">

    <div style="display:flex;gap:6px;margin-bottom:10px;" id="acc-zona-selector-medias">
      <button class="destino-btn active" type="button" onclick="seleccionarZonaAccesorio('medias','colorMedias',this)">
        <span class="acc-dot" id="acc-dot-colorMedias" style="background:#b71c1c;"></span> Cuerpo
      </button>
      <button class="destino-btn" type="button" onclick="seleccionarZonaAccesorio('medias','colorPartearribaMedias',this)">
        <span class="acc-dot" id="acc-dot-colorPartearribaMedias" style="background:#7f0000;"></span> Parte arriba
      </button>
    </div>

    <div class="color-grid" id="acc-swatches-medias"></div>
    <div style="display:flex;align-items:center;gap:8px;margin:8px 0 4px;">
      <input type="color" id="acc-picker-medias" value="#b71c1c"
        style="width:36px;height:36px;border-radius:8px;border:1.5px solid var(--border);cursor:pointer;padding:2px;background:var(--bg-2);"
        oninput="aplicarColorAccesorioActivo('medias',this.value)">
      <input class="p-input" id="acc-hex-medias" type="text" value="#b71c1c" maxlength="7"
        style="margin-bottom:0;font-family:monospace;flex:1;"
        oninput="if(/^#[0-9a-fA-F]{6}$/.test(this.value)) aplicarColorAccesorioActivo('medias',this.value)">
    </div>

    <div class="p-sep"></div>
  </div>

</div>