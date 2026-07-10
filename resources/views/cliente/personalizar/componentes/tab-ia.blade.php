    <div id="tab-ia" class="tab-content">
      <div class="p-title">Generar con IA</div>
      <div class="p-sub">Describe tu diseño (colores, rayas, etc.) y la IA lo aplica directo a tu prenda.</div>
      <textarea class="p-textarea" id="ia-prompt" placeholder="Ej: camiseta roja con rayas blancas"></textarea>
      <button class="p-btn p-btn-primary" onclick="generarIA()">
        <i class="fas fa-wand-magic-sparkles"></i> Generar diseño
      </button>
      <div class="ia-loading" id="ia-loading">
        <i class="fas fa-spinner fa-spin" style="margin-right:6px;"></i> Generando...
      </div>
      <div class="ia-resultado" id="ia-resultado" style="display:none;"></div>
    </div>
