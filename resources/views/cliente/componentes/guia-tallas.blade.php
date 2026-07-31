@once
<style>
  .btn-guia-tallas {
    display: inline-flex; align-items: center; gap: 6px;
    background: none; border: none; padding: 0; margin-left: 10px;
    color: var(--blue); font-size: 0.8rem; font-weight: 600;
    cursor: pointer; text-decoration: underline;
  }
  .btn-guia-tallas svg { width: 14px; height: 14px; }

  .guia-tallas-overlay {
    display: none; position: fixed; inset: 0; z-index: 3100;
    background: rgba(0,0,0,0.6);
    align-items: center; justify-content: center;
    padding: 24px;
  }
  .guia-tallas-overlay.open { display: flex; }
  .guia-tallas-modal {
    position: relative;
    width: 100%; max-width: 460px; max-height: 86vh; overflow-y: auto;
    background: var(--bg-2); border-radius: var(--radius);
    border: 1px solid var(--border); box-shadow: var(--shadow-lg);
    padding: 28px 28px 24px;
  }
  .guia-tallas-cerrar {
    position: absolute; top: 14px; right: 14px;
    width: 32px; height: 32px; border-radius: 50%;
    background: var(--bg-3); border: 1px solid var(--border);
    color: var(--text-2); font-size: 1.3rem; line-height: 1;
    cursor: pointer; display: flex; align-items: center; justify-content: center;
  }
  .guia-tallas-modal h3 {
    font-family: var(--font-d); font-size: 1.25rem; font-weight: 700;
    color: var(--text-1); margin-bottom: 10px; padding-right: 30px;
  }
  .guia-tallas-modal p { font-size: 0.87rem; color: var(--text-2); line-height: 1.6; margin-bottom: 12px; }
  .guia-tallas-modal ol { margin: 0 0 18px 18px; padding: 0; font-size: 0.87rem; color: var(--text-2); line-height: 1.8; }
  .guia-tallas-tabla { width: 100%; border-collapse: collapse; font-size: 0.82rem; margin-bottom: 14px; }
  .guia-tallas-tabla th, .guia-tallas-tabla td { padding: 8px 10px; text-align: center; border: 1px solid var(--border); }
  .guia-tallas-tabla th { background: var(--bg-3); color: var(--text-2); font-weight: 700; }
  .guia-tallas-tabla td:first-child, .guia-tallas-tabla th:first-child { font-weight: 700; color: var(--blue); }
  .guia-tallas-nota { font-size: 0.78rem; color: var(--text-3); margin-bottom: 0; }
</style>
@endonce

<button type="button" class="btn-guia-tallas" onclick="abrirGuiaTallas()">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 8h18v8H3z"/><path d="M7 8v3M11 8v3M15 8v3M19 8v3"/></svg>
  ¿Cuál es mi talla?
</button>

@once
<div class="guia-tallas-overlay" id="guia-tallas-overlay" onclick="if(event.target===this) cerrarGuiaTallas()">
  <div class="guia-tallas-modal">
    <button type="button" class="guia-tallas-cerrar" onclick="cerrarGuiaTallas()" aria-label="Cerrar">&times;</button>
    <h3>¿Cómo saber tu talla?</h3>
    <p>Con una cinta métrica, toma estas medidas sobre tu cuerpo (sin apretar) y compáralas con la tabla:</p>
    <ol>
      <li><strong>Pecho:</strong> rodea la parte más ancha del pecho, debajo de las axilas.</li>
      <li><strong>Cintura:</strong> rodea la parte más angosta de tu abdomen.</li>
      <li><strong>Estatura:</strong> mide desde el piso hasta la cabeza, sin zapatos.</li>
    </ol>
    <table class="guia-tallas-tabla">
      <thead>
        <tr><th>Talla</th><th>Pecho (cm)</th><th>Cintura (cm)</th><th>Estatura (cm)</th></tr>
      </thead>
      <tbody>
        <tr><td>XS</td><td>82–86</td><td>66–70</td><td>155–160</td></tr>
        <tr><td>S</td><td>87–91</td><td>71–75</td><td>160–165</td></tr>
        <tr><td>M</td><td>92–98</td><td>76–83</td><td>165–172</td></tr>
        <tr><td>L</td><td>99–105</td><td>84–91</td><td>172–178</td></tr>
        <tr><td>XL</td><td>106–113</td><td>92–100</td><td>178–184</td></tr>
        <tr><td>XXL</td><td>114–121</td><td>101–109</td><td>184–190</td></tr>
      </tbody>
    </table>
    <p class="guia-tallas-nota">Medidas referenciales. Si estás entre dos tallas, te recomendamos elegir la más grande para mayor comodidad.</p>
  </div>
</div>

<script>
  function abrirGuiaTallas() {
    document.getElementById('guia-tallas-overlay')?.classList.add('open');
  }
  function cerrarGuiaTallas() {
    document.getElementById('guia-tallas-overlay')?.classList.remove('open');
  }
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') cerrarGuiaTallas();
  });
</script>
@endonce
