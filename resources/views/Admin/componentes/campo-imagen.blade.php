@php
  $inputId  = 'imagen-' . str_replace('_', '-', $name);
  $dropId   = 'drop-area-' . str_replace('_', '-', $name);
  $previewId = 'preview-' . str_replace('_', '-', $name);
  $actualId = 'actual-' . str_replace('_', '-', $name);
  $hint     = $hint ?? 'JPG, PNG o WEBP — máximo 4MB';
  $large    = $large ?? false;
  $aspect   = $aspect ?? '16/9';
@endphp

<div style="margin-bottom:18px;">
  <label style="display:block;font-size:0.78rem;font-weight:600;color:var(--text-2);text-transform:uppercase;letter-spacing:0.03em;margin-bottom:7px;">
    {{ $label }}
  </label>

  @if($large)

    @if(!empty($currentUrl))
      <div id="{{ $actualId }}">
        <img src="{{ $currentUrl }}" alt="" style="width:100%;aspect-ratio:{{ $aspect }};object-fit:cover;border-radius:12px;border:1px solid var(--border);display:block;margin-bottom:8px;">
        <button type="button"
          onclick="document.getElementById('{{ $dropId }}').style.display='flex';document.getElementById('{{ $actualId }}').style.display='none';"
          style="background:none;border:none;color:var(--blue);font-weight:600;font-size:0.8rem;cursor:pointer;padding:0;text-decoration:underline;">
          Cambiar imagen
        </button>
      </div>
    @endif

    <label for="{{ $inputId }}" id="{{ $dropId }}" style="display:{{ !empty($currentUrl) ? 'none' : 'flex' }};flex-direction:column;align-items:center;justify-content:center;
      gap:10px;width:100%;aspect-ratio:{{ $aspect }};border:1.5px dashed var(--border-2);border-radius:12px;
      background:var(--bg-3);cursor:pointer;transition:all var(--tr);text-align:center;padding:16px;">
      <svg viewBox="0 0 24 24" style="width:34px;height:34px;stroke:var(--blue);fill:none;stroke-width:1.5;stroke-linecap:round;stroke-linejoin:round;">
        <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/>
      </svg>
      <span style="font-weight:600;font-size:0.9rem;color:var(--text-1);">Haz clic para seleccionar una imagen</span>
      <span style="font-size:0.76rem;color:var(--text-3);">{{ $hint }}</span>
      <input type="file" id="{{ $inputId }}" name="{{ $name }}" accept="image/*"
        onchange="previsualizarArchivo(this, '{{ $previewId }}', '{{ $dropId }}')" style="display:none;">
    </label>
    <div id="{{ $previewId }}" style="display:none;margin-top:10px;"></div>

  @else

    @if(!empty($currentUrl))
      <div id="{{ $actualId }}" style="display:flex;gap:16px;align-items:center;padding:16px;border:1.5px solid var(--border);border-radius:12px;background:var(--bg-3);">
        <img src="{{ $currentUrl }}" alt="" style="width:64px;height:64px;object-fit:cover;border-radius:10px;border:1px solid var(--border);flex-shrink:0;">
        <div style="flex:1;min-width:0;">
          <div style="font-weight:700;font-size:0.9rem;color:var(--text-1);">Imagen actual</div>
          <button type="button"
            onclick="document.getElementById('{{ $dropId }}').style.display='flex';document.getElementById('{{ $actualId }}').style.display='none';"
            style="margin-top:6px;background:none;border:none;color:var(--blue);font-weight:600;font-size:0.8rem;cursor:pointer;padding:0;text-decoration:underline;">
            Cambiar imagen
          </button>
        </div>
      </div>
    @endif

    <label for="{{ $inputId }}" id="{{ $dropId }}" style="display:{{ !empty($currentUrl) ? 'none' : 'flex' }};flex-direction:column;align-items:center;justify-content:center;
      gap:8px;padding:22px 16px;border:1.5px dashed var(--border-2);border-radius:12px;
      background:var(--bg-3);cursor:pointer;transition:all var(--tr);text-align:center;">
      <svg viewBox="0 0 24 24" style="width:26px;height:26px;stroke:var(--blue);fill:none;stroke-width:1.6;stroke-linecap:round;stroke-linejoin:round;">
        <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/>
      </svg>
      <span style="font-weight:600;font-size:0.86rem;color:var(--text-1);">Haz clic para seleccionar una imagen</span>
      <span style="font-size:0.74rem;color:var(--text-3);">{{ $hint }}</span>
      <input type="file" id="{{ $inputId }}" name="{{ $name }}" accept="image/*"
        onchange="previsualizarArchivo(this, '{{ $previewId }}', '{{ $dropId }}')" style="display:none;">
    </label>
    <div id="{{ $previewId }}" style="display:none;margin-top:10px;"></div>

  @endif
</div>
