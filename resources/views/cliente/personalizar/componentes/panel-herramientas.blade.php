<!-- RAIL -->
<nav class="rail">
  <button class="rail-btn active" onclick="cambiarTool('colores',this)" title="Colores">
    <i class="fas fa-palette"></i><span>Colores</span>
  </button>
  <button class="rail-btn" onclick="cambiarTool('logo',this)" title="Logo">
    <i class="fas fa-image"></i><span>Logo</span>
  </button>
  <button class="rail-btn" onclick="cambiarTool('texto',this)" title="Texto">
    <i class="fas fa-font"></i><span>Texto</span>
  </button>
  <button class="rail-btn" onclick="cambiarTool('figuras',this)" title="Figuras">
    <i class="fas fa-shapes"></i><span>Figuras</span>
  </button>
  <button class="rail-btn" onclick="cambiarTool('ia',this)" title="IA">
    <i class="fas fa-wand-magic-sparkles"></i><span>IA</span>
  </button>
</nav>

<!-- PANEL IZQUIERDO -->
<aside class="panel">
  @include('cliente.personalizar.componentes.tab-colores')
  @include('cliente.personalizar.componentes.tab-logo')
  @include('cliente.personalizar.componentes.tab-texto')
  @include('cliente.personalizar.componentes.tab-figuras')
  @include('cliente.personalizar.componentes.tab-ia')
</aside>