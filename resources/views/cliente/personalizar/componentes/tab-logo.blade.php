    <div id="tab-logo" class="tab-content">
      <div class="p-title">Subir foto o logo</div>
      <div class="p-sub">Sube fotos, logos o imÃ¡genes y arrÃ¡stralas donde quieras sobre la prenda.</div>
      <div class="upload-zone" id="upload-zone" onclick="document.getElementById('input-logo').click()">
        <i class="fas fa-cloud-arrow-up"></i>
        <p>Haz clic o arrastra tu imagen aquÃ­<br><small>(PNG, JPG, WEBP â€” mÃ¡x 4 MB)</small></p>
      </div>
      <input type="file" id="input-logo" accept="image/*" style="display:none" onchange="subirLogo(event)" multiple>
      <div class="p-sep"></div>
      <div class="p-label">TamaÃ±o del logo</div>
      <input type="range" id="logo-scale" min="30" max="300" value="120" style="width:100%" oninput="escalarLogo(this.value)">

      <div class="p-sep"></div>
      <div class="p-label">Mis logos guardados</div>
      <div class="p-sub">Haz clic en un logo para agregarlo de nuevo al diseÃ±o. Solo tÃº puedes ver tus logos.</div>
      <div class="mis-logos-grid" id="mis-logos-grid">
        <div class="mis-logos-vacio" id="mis-logos-vacio">AÃºn no tienes logos guardados.</div>
      </div>
    </div>
