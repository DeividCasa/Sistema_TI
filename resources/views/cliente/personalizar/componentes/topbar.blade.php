<header class="topbar">
  <a class="tb-logo" href="{{ route('cliente.inicio') }}">
    <img src="{{ asset('images/logo.png') }}" alt="Leo José" onerror="this.style.display='none'">
  </a>
  <span class="tb-sep">/</span>
  <input id="nombre-diseno" type="text" value="{{ $plantilla->nombre ?? 'Mi diseño' }}" maxlength="60">

  <div class="prenda-switch" role="tablist" aria-label="Tipo de prenda">
    <button class="prenda-btn active" id="btn-prenda-camiseta" type="button" onclick="cambiarPrenda('camiseta')">
      <i class="fas fa-tshirt"></i> Camiseta
    </button>
    <button class="prenda-btn" id="btn-prenda-chompa" type="button" onclick="cambiarPrenda('chompa')">
      <i class="fas fa-vest"></i> Chompa
    </button>
  </div>

  <button class="tb-icon" id="btn-undo" title="Deshacer" onclick="undo()">
    <i class="fas fa-rotate-left"></i>
  </button>
  <button class="tb-icon" id="btn-redo" title="Rehacer" onclick="redo()">
    <i class="fas fa-rotate-right"></i>
  </button>

  <div class="tb-right">
    <a class="tb-btn tb-ghost" href="{{ route('cliente.inicio') }}">
      <i class="fas fa-arrow-left"></i> Volver
    </a>
    <button class="tb-btn tb-primary" onclick="confirmarGuardarDiseno()">
      <i class="fas fa-floppy-disk"></i> Guardar
    </button>
  </div>
</header>

<div class="modal-overlay" id="modal-guardar">
  <div class="modal-caja">
    <div class="modal-titulo">¿Guardar diseño?</div>
    <p class="modal-texto">Se guardará tu diseño actual junto con capturas del modelo para que puedas verlo luego en "Mis diseños".</p>
    <div style="margin:14px 0;text-align:left;">
      <label for="genero-diseno" style="display:block;font-size:0.78rem;font-weight:600;color:var(--text-2);text-transform:uppercase;letter-spacing:0.03em;margin-bottom:7px;">
        ¿Para quién es este diseño?
      </label>
      <select id="genero-diseno" style="width:100%;padding:10px 12px;border:1.5px solid var(--border);border-radius:8px;font-family:var(--font-b);font-size:0.9rem;background:var(--bg-2);color:var(--text-1);outline:none;">
        <option value="unisex">Unisex</option>
        <option value="hombre">Para Hombre</option>
        <option value="mujer">Para Mujer</option>
      </select>
    </div>
    <div class="modal-acciones">
      <button class="tb-btn tb-ghost" onclick="cerrarModalGuardar()">No</button>
      <button class="tb-btn tb-primary" onclick="confirmarGuardarSi()">Sí, guardar</button>
    </div>
  </div>
</div>