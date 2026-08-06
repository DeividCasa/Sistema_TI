<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
  <title>@yield('titulo', 'Leo José | Catálogo Deportivo')</title>
  <link rel="icon" type="image/png" href="{{ asset('vendor/cozastore/images/icons/favicon.png') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,800;0,900;1,700&display=swap" rel="stylesheet">

  {{-- Plantilla CozaStore (Colorlib / ThemeWagon) — misma estructura y librerías que la demo original --}}
  <link rel="stylesheet" href="{{ asset('vendor/cozastore/vendor/bootstrap/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/cozastore/fonts/font-awesome-4.7.0/css/font-awesome.min.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/cozastore/fonts/iconic/css/material-design-iconic-font.min.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/cozastore/fonts/linearicons-v1.0.0/icon-font.min.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/cozastore/vendor/animate/animate.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/cozastore/vendor/css-hamburgers/hamburgers.min.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/cozastore/vendor/select2/select2.min.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/cozastore/vendor/slick/slick.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/cozastore/vendor/MagnificPopup/magnific-popup.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/cozastore/vendor/perfect-scrollbar/perfect-scrollbar.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/cozastore/css/util.css') }}">
  <link rel="stylesheet" href="{{ asset('vendor/cozastore/css/main.css') }}">
  {{-- Acento de marca LeoJoMa (verde) sobre la paleta original de la plantilla --}}
  <link rel="stylesheet" href="{{ asset('vendor/cozastore/css/leojoma-overrides.css') }}">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast@1.4.0/dist/css/iziToast.min.css">

  <style>
    /* Variables de color/tipografía — mismos valores que usa la plantilla
       (cl2/cl6/cl3, bg0/bg6, bor4) expuestas como variables para que las
       páginas que aún no fueron migradas 1:1 al markup de CozaStore
       (mis pedidos, pago, comprobante, etc.) sigan viéndose consistentes. */
    :root {
      --bg:           #F8F8F8;
      --bg-2:         #FFFFFF;
      --bg-3:         #F8F8F8;
      --border:       #EBEBEB;
      --border-2:     #DDDDDD;
      --text-1:       #222222;
      --text-2:       #555555;
      --text-3:       #999999;
      --blue:         #16A34A;
      --blue-h:       #12813B;
      --blue-soft:    #E7F8ED;
      --blue-border:  #B8E9C6;
      --blue-light:   #34C468;
      --blue-shadow:  rgba(22,163,74,0.25);
      --accent:       #F1592A;
      --accent-soft:  #FDEAE3;
      --accent-border:#F6C3AC;
      --ink:          #222222;
      --radius:       8px;
      --radius-sm:    6px;
      --font-d:       'PlayfairDisplay-Bold', serif;
      --font-b:       'Poppins-Regular', sans-serif;
      --shadow-sm:    0 1px 3px rgba(0,0,0,0.08);
      --shadow-md:    0 8px 24px rgba(0,0,0,0.1);
      --shadow-lg:    0 20px 50px rgba(0,0,0,0.16);
      --tr:           0.2s ease;
    }

    /* ══ Componentes compartidos (páginas que aún usan clases propias en vez
       del markup literal de la plantilla — mismas proporciones que CozaStore) ══ */
    .main-content-compat { max-width: 1200px; margin: 0 auto; padding: 40px 15px 60px; }
    .card { background: var(--bg-2); border: 1px solid var(--border); border-radius: 12px; box-shadow: var(--shadow-sm); }
    .card-pad { padding: 24px 28px; }
    .grid-2col { display: grid; grid-template-columns: var(--cols, 1fr 1fr); }
    @media (max-width: 760px) { .grid-2col { grid-template-columns: 1fr; } }
    .sec-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; flex-wrap: wrap; gap: 10px; }
    .sec-title { font-family: 'PlayfairDisplay-Bold'; font-size: 1.5rem; color: var(--text-1); display: flex; align-items: center; gap: 8px; }
    .sec-badge { font-size: 0.68rem; font-weight: 600; color: var(--blue); background: var(--blue-soft); border: 1px solid var(--blue-border); padding: 2px 9px; border-radius: 20px; }
    .sec-link { font-size: 0.8rem; font-weight: 600; color: var(--blue); text-decoration: none; }
    .sec-link:hover { opacity: 0.75; }
    .btn-primary {
      display: inline-flex; align-items: center; gap: 8px;
      padding: 11px 24px; border-radius: 24px;
      background: var(--blue); color: white; border: none;
      font-family: 'Poppins-SemiBold'; font-size: 0.85rem;
      cursor: pointer; text-decoration: none; transition: all var(--tr);
    }
    .btn-primary:hover { background: #222; }
    .btn-primary svg { width: 16px; height: 16px; }
    .btn-secondary {
      display: inline-flex; align-items: center; gap: 8px;
      padding: 11px 24px; border-radius: 24px;
      background: var(--bg-3); color: var(--text-2); border: 1px solid var(--border);
      font-family: 'Poppins-Medium'; font-size: 0.85rem;
      cursor: pointer; text-decoration: none; transition: all var(--tr);
    }
    .btn-secondary:hover { border-color: var(--blue-border); color: var(--blue); }
    .badge { display: inline-block; padding: 4px 10px; border-radius: 6px; font-size: 0.73rem; font-weight: 600; white-space: nowrap; }
    .badge-success { background: #DCFCE7; color: #15803D; }
    .badge-warning { background: #FEF3C7; color: #92400E; }
    .badge-info    { background: #DBEAFE; color: #1E40AF; }
    .badge-danger  { background: #FFE4E2; color: #B91C1C; }
    .badge-neutral { background: var(--bg-3); color: var(--text-2); border: 1px solid var(--border); }
    .est { display: inline-flex; align-items: center; gap: 5px; padding: 4px 10px; border-radius: 20px; font-size: 0.7rem; font-weight: 600; }
    .est::before { content:''; width:5px; height:5px; border-radius:50%; }
    .est-recibido   { background:#FEF3C7; color:#92400E; } .est-recibido::before   { background:#F59E0B; }
    .est-produccion { background:var(--blue-soft); color:var(--blue); } .est-produccion::before { background:var(--blue); }
    .est-listo      { background:#DCFCE7; color:#15803D; } .est-listo::before      { background:#22C55E; }
    .est-entregado  { background:var(--bg-3); color:var(--text-3); } .est-entregado::before  { background:var(--text-3); }
    .est-pendiente  { background:#FEE2E2; color:#991B1B; } .est-pendiente::before  { background:#EF4444; }
    .est-verificado { background:#DCFCE7; color:#15803D; } .est-verificado::before { background:#22C55E; }
    .empty-state { padding: 48px; text-align: center; }
    .empty-state svg { width: 40px; height: 40px; stroke: var(--border-2); margin: 0 auto 10px; display: block; }
    .empty-state p { font-size: 0.83rem; color: var(--text-3); }
    .reveal { opacity: 0; transform: translateY(14px); transition: opacity 0.5s ease, transform 0.5s ease; }
    .reveal.visible { opacity: 1; transform: translateY(0); }

    /* Extras propios que la plantilla no trae: WhatsApp flotante y lightbox de comprobantes */
    .whatsapp-wrap { position: fixed; bottom: 24px; right: 24px; z-index: 300; }
    .whatsapp-float {
      width: 56px; height: 56px; border-radius: 50%;
      background: #25D366; border: none; color: white;
      display: flex; align-items: center; justify-content: center;
      cursor: pointer; box-shadow: 0 12px 30px rgba(0,0,0,0.25);
      transition: transform 0.2s;
    }
    .whatsapp-float:hover { transform: scale(1.06); }
    .whatsapp-float svg { width: 28px; height: 28px; }
    .whatsapp-panel {
      position: absolute; bottom: calc(100% + 14px); right: 0;
      width: 280px; background: #fff; border: 1px solid #eee;
      border-radius: 14px; box-shadow: 0 12px 30px rgba(0,0,0,0.18);
      padding: 18px; display: none;
    }
    .whatsapp-wrap.open .whatsapp-panel { display: block; }
    .whatsapp-panel-titulo { font-family: 'Poppins-SemiBold'; font-size: 1rem; color: #222; margin-bottom: 14px; }
    .wa-row { display: flex; align-items: center; gap: 10px; font-size: 0.82rem; color: #6C6C6C; margin-bottom: 10px; }
    .wa-row svg { width: 16px; height: 16px; color: #999; flex-shrink: 0; }
    .wa-btn-enviar {
      display: flex; align-items: center; justify-content: center; gap: 8px;
      width: 100%; margin-top: 6px; padding: 10px; border-radius: 8px;
      background: #25D366; color: white; font-weight: 600; font-size: 0.85rem;
      text-decoration: none; transition: opacity 0.2s;
    }
    .wa-btn-enviar:hover { opacity: 0.9; }
    .wa-btn-enviar svg { width: 16px; height: 16px; }

    .lightbox-overlay {
      display: none; position: fixed; inset: 0; z-index: 3000;
      background: rgba(0,0,0,0.85);
      align-items: center; justify-content: center;
      padding: 40px; cursor: zoom-out;
    }
    .lightbox-overlay.open { display: flex; }
    .lightbox-overlay img { max-width: 92vw; max-height: 92vh; border-radius: 12px; box-shadow: 0 20px 60px rgba(0,0,0,0.5); cursor: default; }
    .lightbox-cerrar {
      position: absolute; top: 20px; right: 28px;
      background: rgba(255,255,255,0.12); border: none; color: white;
      width: 40px; height: 40px; border-radius: 50%; font-size: 1.6rem; line-height: 1;
      cursor: pointer; display: flex; align-items: center; justify-content: center;
      transition: background 0.15s;
    }
    .lightbox-cerrar:hover { background: rgba(255,255,255,0.25); }

    /* Badge "3D" del menú (reemplaza el "HOT" de la plantilla) */
    .label1[data-label1]::after { font-family: 'Poppins-Bold'; }

    /* Etiqueta de categoría sobre la imagen del producto (uniforme/chompa/camiseta...) */
    .block2-pic { position: relative; }
    .block2-badge {
      position: absolute; top: 12px; left: 12px; z-index: 2;
      background: var(--accent, #F1592A); color: #fff;
      font-family: 'Poppins-SemiBold'; font-size: 0.66rem; letter-spacing: 0.03em;
      text-transform: uppercase; padding: 4px 11px; border-radius: 20px;
    }

  </style>

  @stack('estilos')
</head>
<body class="@yield('body-class')">

@include('cliente.componentes.topbar-cliente')

@yield('contenido')

<!-- Footer -->
<footer class="bg3 p-t-75 p-b-32">
  <div class="container">
    <div class="row">
      <div class="col-sm-6 col-lg-3 p-b-50">
        <h4 class="stext-301 cl0 p-b-30">Categorías</h4>
        <ul>
          <li class="p-b-10"><a href="{{ route('cliente.catalogo.index') }}" class="stext-107 cl7 hov-cl1 trans-04">Toda la ropa</a></li>
          <li class="p-b-10"><a href="{{ route('cliente.catalogo.index', ['categoria' => 'uniforme']) }}" class="stext-107 cl7 hov-cl1 trans-04">Uniformes escolares</a></li>
          <li class="p-b-10"><a href="{{ route('cliente.catalogo.index', ['categoria' => 'chompa']) }}" class="stext-107 cl7 hov-cl1 trans-04">Chompas</a></li>
          <li class="p-b-10"><a href="{{ route('disenios.create') }}" class="stext-107 cl7 hov-cl1 trans-04">Diseña tu ropa</a></li>
        </ul>
      </div>

      <div class="col-sm-6 col-lg-3 p-b-50">
        <h4 class="stext-301 cl0 p-b-30">Ayuda</h4>
        <ul>
          <li class="p-b-10"><a href="{{ route('cliente.mis-pedidos') }}" class="stext-107 cl7 hov-cl1 trans-04">Mis pedidos</a></li>
          <li class="p-b-10"><a href="{{ route('cliente.testimonios.create') }}" class="stext-107 cl7 hov-cl1 trans-04">Danos tu opinión</a></li>
          <li class="p-b-10"><a href="{{ route('cliente.disenios.index') }}" class="stext-107 cl7 hov-cl1 trans-04">Mis diseños</a></li>
        </ul>
      </div>

      <div class="col-sm-6 col-lg-3 p-b-50">
        @php($infoFooter = \App\Models\InformacionLocal::actual())
        <h4 class="stext-301 cl0 p-b-30">Visítanos</h4>
        <p class="stext-107 cl7 size-201">
          {{ $infoFooter->direccion ?: 'Salcedo, Ecuador' }} ·
          {{ $infoFooter->telefono ?: '+593 99 250 2749' }}
        </p>
        <div class="p-t-27">
          <a href="#" class="fs-18 cl7 hov-cl1 trans-04 m-r-16"><i class="fa fa-facebook"></i></a>
          <a href="#" class="fs-18 cl7 hov-cl1 trans-04 m-r-16"><i class="fa fa-instagram"></i></a>
          <a href="https://wa.me/{{ $infoFooter->whatsapp_numero ?: '593992502749' }}" target="_blank" rel="noopener" class="fs-18 cl7 hov-cl1 trans-04 m-r-16"><i class="fa fa-whatsapp"></i></a>
        </div>
      </div>

      <div class="col-sm-6 col-lg-3 p-b-50">
        <h4 class="stext-301 cl0 p-b-30">Newsletter</h4>
        <form onsubmit="return false;">
          <div class="wrap-input1 w-full p-b-4">
            <input class="input1 bg-none plh1 stext-107 cl7" type="email" name="email" placeholder="tucorreo@ejemplo.com">
            <div class="focus-input1 trans-04"></div>
          </div>
          <div class="p-t-18">
            <button class="flex-c-m stext-101 cl0 size-103 bg1 bor1 hov-btn2 p-lr-15 trans-04">Suscribirme</button>
          </div>
        </form>
      </div>
    </div>

    <div class="p-t-40">
      <p class="stext-107 cl6 txt-center">
        © <script>document.write(new Date().getFullYear());</script> Creaciones Leo José de Salcedo. Todos los derechos reservados.
      </p>
    </div>
  </div>
</footer>

<!-- Back to top -->
<div class="btn-back-to-top" id="myBtn">
  <span class="symbol-btn-back-to-top">
    <i class="zmdi zmdi-chevron-up"></i>
  </span>
</div>

@include('cliente.componentes.whatsapp-flotante')

<!-- Lightbox: ver en grande fotos de productos y comprobantes de pago -->
<div class="lightbox-overlay" id="lightbox-overlay" onclick="cerrarLightbox()">
  <button type="button" class="lightbox-cerrar" onclick="cerrarLightbox()" aria-label="Cerrar">&times;</button>
  <img id="lightbox-img" src="" alt="Vista ampliada" onclick="event.stopPropagation()">
</div>

<script src="{{ asset('vendor/cozastore/vendor/jquery/jquery-3.2.1.min.js') }}"></script>
<script src="{{ asset('vendor/cozastore/vendor/bootstrap/js/popper.js') }}"></script>
<script src="{{ asset('vendor/cozastore/vendor/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('vendor/cozastore/vendor/select2/select2.min.js') }}"></script>
<script>
  $(".js-select2").each(function(){
    $(this).select2({ minimumResultsForSearch: 20, dropdownParent: $(this).next('.dropDownSelect2') });
  });
</script>
<script src="{{ asset('vendor/cozastore/vendor/slick/slick.min.js') }}"></script>
<script src="{{ asset('vendor/cozastore/js/slick-custom.js') }}"></script>
<script src="{{ asset('vendor/cozastore/vendor/MagnificPopup/jquery.magnific-popup.min.js') }}"></script>
<script>
  $('.gallery-lb').each(function() {
    $(this).magnificPopup({ delegate: 'a', type: 'image', gallery: { enabled: true }, mainClass: 'mfp-fade' });
  });
</script>
<script src="{{ asset('vendor/cozastore/vendor/isotope/isotope.pkgd.min.js') }}"></script>
<script src="{{ asset('vendor/cozastore/vendor/sweetalert/sweetalert.min.js') }}"></script>
<script src="{{ asset('vendor/cozastore/vendor/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
<script>
  $('.js-pscroll').each(function(){
    $(this).css('position','relative').css('overflow','hidden');
    var ps = new PerfectScrollbar(this, { wheelSpeed: 1, scrollingThreshold: 1000, wheelPropagation: false });
    $(window).on('resize', function(){ ps.update(); });
  });
</script>
<script src="{{ asset('vendor/cozastore/js/main.js') }}"></script>

<script>
  // Límite de cantidad en el carrito: el atributo max="100" del input no
  // impide escribir números más grandes, solo bloquea el envío del
  // formulario — esto recorta el valor en cuanto el cliente lo escribe.
  document.addEventListener('input', function (event) {
    const input = event.target;
    if (!(input instanceof HTMLInputElement) || !input.classList.contains('num-product')) return;
    const max = Number(input.max) || 100;
    const min = Number(input.min) || 1;
    if (input.value === '') return;
    const valor = Number(input.value);
    if (Number.isNaN(valor)) return;
    if (valor > max) input.value = max;
    else if (valor < min) input.value = min;
  });

  function toggleWhatsapp() {
    document.getElementById('whatsapp-wrap')?.classList.toggle('open');
  }
  document.addEventListener('click', function (event) {
    const wrap = document.getElementById('whatsapp-wrap');
    if (wrap && !wrap.contains(event.target)) wrap.classList.remove('open');
  });

  function abrirLightbox(src) {
    if (!src) return;
    document.getElementById('lightbox-img').src = src;
    document.getElementById('lightbox-overlay').classList.add('open');
  }
  function cerrarLightbox() {
    document.getElementById('lightbox-overlay').classList.remove('open');
    document.getElementById('lightbox-img').src = '';
  }
  document.addEventListener('keydown', e => { if (e.key === 'Escape') cerrarLightbox(); });

  // Animación de aparición al hacer scroll (páginas que aún usan la clase .reveal)
  const obsReveal = new IntersectionObserver(entries => {
    entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
  }, { threshold: 0.07 });
  document.querySelectorAll('.reveal').forEach(el => obsReveal.observe(el));

  // Vista previa de archivos subidos (comprobantes, fotos, etc.)
  function previsualizarArchivo(input, previewBoxId, dropAreaId) {
    const box = document.getElementById(previewBoxId);
    if (!box) return;
    const dropArea = dropAreaId ? document.getElementById(dropAreaId) : null;
    const file = input.files && input.files[0];

    if (!file) {
      box.style.display = 'none';
      box.innerHTML = '';
      if (dropArea) dropArea.style.display = 'flex';
      return;
    }

    if (dropArea) dropArea.style.display = 'none';
    box.style.display = 'block';

    const tamano = (file.size / (1024 * 1024)).toFixed(2) + ' MB';
    const idInput = input.id;

    const renderizar = (miniaturaHtml) => {
      box.innerHTML = `
        <div style="display:flex;gap:16px;align-items:center;padding:16px;border:1.5px solid #eee;border-radius:12px;background:#fafafa;max-width:100%;box-sizing:border-box;overflow:hidden;">
          ${miniaturaHtml}
          <div style="flex:1;min-width:0;">
            <div style="font-weight:700;font-size:0.9rem;color:#222;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${file.name}</div>
            <div style="font-size:0.78rem;color:#999;margin-top:2px;">${tamano}</div>
            ${idInput ? `<button type="button" onclick="quitarArchivoSeleccionado('${idInput}','${previewBoxId}'${dropAreaId ? `,'${dropAreaId}'` : ''})"
              style="margin-top:8px;background:none;border:none;color:#1F4B3F;font-weight:600;font-size:0.8rem;cursor:pointer;padding:0;text-decoration:underline;">
              Cambiar archivo
            </button>` : ''}
          </div>
        </div>`;
    };

    if (file.type.startsWith('image/')) {
      const reader = new FileReader();
      reader.onload = e => {
        renderizar(`<img src="${e.target.result}" alt="Vista previa" style="width:110px;height:110px;object-fit:cover;border-radius:10px;border:1px solid #eee;flex-shrink:0;">`);
      };
      reader.readAsDataURL(file);
    } else {
      renderizar(`<div style="width:110px;height:110px;border-radius:10px;background:#fff;border:1px solid #eee;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
          <i class="fa fa-file-pdf-o" style="font-size:40px;color:#EF4444;"></i>
        </div>`);
    }
  }

  function quitarArchivoSeleccionado(inputId, previewBoxId, dropAreaId) {
    const input = document.getElementById(inputId);
    if (input) input.value = '';
    const box = document.getElementById(previewBoxId);
    if (box) { box.style.display = 'none'; box.innerHTML = ''; }
    if (dropAreaId) {
      const area = document.getElementById(dropAreaId);
      if (area) area.style.display = 'flex';
    }
  }
</script>

<script src="https://cdn.jsdelivr.net/npm/izitoast@1.4.0/dist/js/iziToast.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    @if(session('success'))
      iziToast.success({ title: 'Listo', message: @json(session('success')), position: 'topRight', timeout: 5000, close: true });
    @endif
    @if(session('error'))
      iziToast.error({ title: 'Error', message: @json(session('error')), position: 'topRight', timeout: 6000, close: true });
    @endif
    @if($errors->any())
      iziToast.error({ title: 'Revisa lo siguiente', message: @json(implode(' · ', $errors->all())), position: 'topRight', timeout: 7000, close: true });
    @endif
    @if(session('whatsapp_url'))
      iziToast.success({
        title: 'WhatsApp listo', message: 'El mensaje está listo para enviar.',
        position: 'topRight', timeout: false, close: true,
        buttons: [['<button>Abrir WhatsApp</button>', function (instance, toast) {
          window.open(@json(session('whatsapp_url')), '_blank');
          instance.hide({ transitionOut: 'fadeOut' }, toast, 'button');
        }]],
      });
    @endif
  });

  document.addEventListener('submit', function (event) {
    const form = event.target;
    if (!(form instanceof HTMLFormElement) || !form.hasAttribute('data-confirm') || form.dataset.confirmado) return;
    event.preventDefault();
    iziToast.question({
      timeout: false, close: false, overlay: true, displayMode: 'once', id: 'confirmacion', zindex: 999,
      title: 'Confirmar', message: form.getAttribute('data-confirm'), position: 'center',
      buttons: [
        ['<button><b>Sí, continuar</b></button>', function (instance, toast) {
          instance.hide({ transitionOut: 'fadeOut' }, toast, 'button');
          form.dataset.confirmado = '1';
          form.submit();
        }, true],
        ['<button>Cancelar</button>', function (instance, toast) {
          instance.hide({ transitionOut: 'fadeOut' }, toast, 'button');
        }],
      ],
    });
  });
</script>

<style>
  .global-loader-overlay {
    position: fixed; inset: 0; z-index: 99999;
    background: rgba(255,255,255,.55);
    display: none; align-items: center; justify-content: center;
  }
  .global-loader-overlay.visible { display: flex; }
  .global-loader-spinner {
    width: 42px; height: 42px; border-radius: 50%;
    border: 4px solid rgba(0,0,0,.12);
    border-top-color: var(--accent, #F1592A);
    animation: global-loader-spin .7s linear infinite;
  }
  @keyframes global-loader-spin { to { transform: rotate(360deg); } }
  @media (prefers-color-scheme: dark) {
    .global-loader-overlay { background: rgba(20,20,20,.55); }
    .global-loader-spinner { border-color: rgba(255,255,255,.15); border-top-color: var(--accent, #F1592A); }
  }
</style>
<div class="global-loader-overlay" id="global-loader"><div class="global-loader-spinner"></div></div>
<script>
  (function () {
    const loader = document.getElementById('global-loader');
    if (!loader) return;
    function mostrarLoader() { loader.classList.add('visible'); }
    document.addEventListener('submit', function (event) {
      const form = event.target;
      if (!(form instanceof HTMLFormElement)) return;
      if (form.hasAttribute('data-confirm') && !form.dataset.confirmado) return;
      mostrarLoader();
    });
    document.addEventListener('click', function (event) {
      const link = event.target.closest('a[href]');
      if (!link || event.defaultPrevented || event.ctrlKey || event.metaKey || event.shiftKey) return;
      const href = link.getAttribute('href');
      if (!href || href.startsWith('#') || href.startsWith('javascript:') || link.target === '_blank' || link.hasAttribute('download')) return;
      mostrarLoader();
    });
    window.addEventListener('pageshow', function () { loader.classList.remove('visible'); });
  })();
</script>

@stack('scripts')

</body>
</html>
