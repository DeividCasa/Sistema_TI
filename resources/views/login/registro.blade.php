@extends('layouts.auth')

@section('titulo', 'Registro — Leo José')

@section('left')
  <div class="left-tag"><span></span>Únete ahora</div>
  <h1 class="left-h">Crea tu<br>cuenta<br><em>gratis</em></h1>
  <p class="left-p">Diseña uniformes deportivos únicos, haz seguimiento de tus pedidos y mucho más.</p>

  <div class="benefits">
    <div class="benefit-item">
      <div class="benefit-icon">
        <svg viewBox="0 0 24 24"><path d="M12 20h9M16.5 3.5a2.121 2.121 0 013 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
      </div>
      <span class="benefit-text">Diseña tu uniforme en 3D</span>
    </div>
    <div class="benefit-item">
      <div class="benefit-icon">
        <svg viewBox="0 0 24 24"><path d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
      </div>
      <span class="benefit-text">Genera diseños con IA</span>
    </div>
    <div class="benefit-item">
      <div class="benefit-icon">
        <svg viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
      </div>
      <span class="benefit-text">Sigue el estado de tus pedidos</span>
    </div>
  </div>
@endsection

@section('card')
  <div class="card-icon">
    <svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2M12 11a4 4 0 100-8 4 4 0 000 8z"/></svg>
  </div>

  <h2>Crear cuenta</h2>
  <p>Completa el formulario para registrarte.</p>

  <form action="{{ route('registro.store') }}" method="POST">
    @csrf

    <div class="field">
      <label>Cédula</label>
      <div class="input-wrap">
        <input type="text" id="reg-cedula" name="cedula" value="{{ old('cedula') }}"
          placeholder="1710034065" maxlength="10" inputmode="numeric"
          class="{{ $errors->has('cedula') ? 'is-error' : '' }}">
        <div class="spinner" id="spinner-cedula"></div>
      </div>
      @error('cedula') <div class="field-error">{{ $message }}</div> @enderror
      <div class="field-error" id="err-cedula" style="display:none;">Ingresa una cédula válida de 10 dígitos.</div>
      <div class="field-hint" id="hint-cedula" style="display:none;"></div>
    </div>

    <div class="grid-2">
      <div class="field">
        <label>Nombre</label>
        <input type="text" id="reg-nombre" name="nombre" value="{{ old('nombre') }}"
          placeholder="Juan" class="{{ $errors->has('nombre') ? 'is-error' : '' }}">
        @error('nombre') <div class="field-error">{{ $message }}</div> @enderror
        <div class="field-error" id="err-nombre" style="display:none;">Solo letras, sin números.</div>
      </div>
      <div class="field">
        <label>Apellido</label>
        <input type="text" id="reg-apellido" name="apellido" value="{{ old('apellido') }}"
          placeholder="Pérez" class="{{ $errors->has('apellido') ? 'is-error' : '' }}">
        @error('apellido') <div class="field-error">{{ $message }}</div> @enderror
        <div class="field-error" id="err-apellido" style="display:none;">Solo letras, sin números.</div>
      </div>
    </div>

    <div class="field">
      <label>Correo electrónico</label>
      <input type="email" id="reg-email" name="email" value="{{ old('email') }}"
        placeholder="tucorreo@ejemplo.com" class="{{ $errors->has('email') ? 'is-error' : '' }}">
      @error('email') <div class="field-error">{{ $message }}</div> @enderror
      <div class="field-error" id="err-email" style="display:none;">Ingresa un correo válido.</div>
    </div>

    <div class="grid-2">
      <div class="field">
        <label>Teléfono</label>
        <input type="text" name="telefono" value="{{ old('telefono') }}"
          placeholder="0999999999">
      </div>
      <div class="field">
        <label>Ciudad</label>
        <input type="text" name="ciudad" value="{{ old('ciudad') }}"
          placeholder="Quito">
      </div>
    </div>

    <div class="grid-2">
      <div class="field">
        <label>Contraseña</label>
        <div class="input-wrap">
          <input type="password" id="reg-password" name="password"
            placeholder="••••••••" class="{{ $errors->has('password') ? 'is-error' : '' }}">
          <button type="button" class="toggle-btn" onclick="togglePass('reg-password', this)" title="Ver contraseña" tabindex="-1">
            <svg viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
          </button>
        </div>
        @error('password') <div class="field-error">{{ $message }}</div> @enderror
        <div class="field-error" id="err-password" style="display:none;">Debe tener mayúscula, minúscula, número y símbolo (ej: Deivid21$).</div>
      </div>
      <div class="field">
        <label>Confirmar</label>
        <div class="input-wrap">
          <input type="password" id="reg-password-confirm" name="password_confirmation"
            placeholder="••••••••">
          <button type="button" class="toggle-btn" onclick="togglePass('reg-password-confirm', this)" title="Ver contraseña" tabindex="-1">
            <svg viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
          </button>
        </div>
        <div class="field-error" id="err-password-confirm" style="display:none;">Las contraseñas no coinciden.</div>
      </div>
    </div>

    <div class="field terminos-field">
      <label class="remember" style="margin-bottom:0;">
        <input type="checkbox" id="reg-terminos" name="terminos" value="1" {{ old('terminos') ? 'checked' : '' }}>
        Acepto los <a href="#" id="link-terminos">términos y condiciones</a> del tratamiento de mis datos.
      </label>
      @error('terminos') <div class="field-error">{{ $message }}</div> @enderror
      <div class="field-error" id="err-terminos" style="display:none;">Debes aceptar los términos y condiciones para continuar.</div>
    </div>

    <button type="submit" class="btn">
      Crear cuenta
      <svg viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
    </button>
  </form>

  <div class="login-link">
    ¿Ya tienes cuenta? <a href="{{ route('login.paso1') }}">Inicia sesión</a>
  </div>
@endsection

@section('modales')
  <div class="modal-overlay-terminos" id="modal-terminos">
    <div class="modal-terminos-caja">
      <div class="modal-terminos-titulo">Términos y condiciones</div>
      <div class="modal-terminos-cuerpo">
        <p><strong>1. Datos que recopilamos.</strong> Al registrarte en Leo José solicitamos tu cédula, nombre, apellido, correo, teléfono y ciudad, con el único fin de identificarte, gestionar tus pedidos y darte soporte.</p>
        <p><strong>2. Uso de tu información.</strong> Tus datos se usan exclusivamente para procesar pedidos, contactarte sobre el estado de tus compras/diseños y mejorar nuestro servicio. No vendemos ni compartimos tu información con terceros ajenos al negocio.</p>
        <p><strong>3. Consentimiento.</strong> De acuerdo con la Ley Orgánica de Protección de Datos Personales del Ecuador, necesitamos tu autorización expresa para almacenar y tratar tus datos personales. Sin tu aceptación no podemos crear tu cuenta.</p>
        <p><strong>4. Tus derechos.</strong> Puedes solicitar en cualquier momento la actualización o eliminación de tus datos escribiéndonos a través de los canales de contacto del local.</p>
        <p><strong>5. Seguridad.</strong> Tu contraseña se almacena cifrada y no tenemos acceso a ella en texto plano.</p>
      </div>
      <div class="modal-terminos-acciones">
        <button type="button" class="btn-terminos-cancelar" id="btn-terminos-cancelar">Cancelar</button>
        <button type="button" class="btn-terminos-aceptar" id="btn-terminos-aceptar">Acepto</button>
      </div>
    </div>
  </div>
@endsection

@push('estilos')
<style>
  .terminos-field .remember { align-items: flex-start; }
  .terminos-field .remember input[type="checkbox"] { margin-top: 2px; }
  .terminos-field a { color: var(--blue-h); font-weight: 600; text-decoration: underline; }

  .modal-overlay-terminos {
    display: none; position: fixed; inset: 0; z-index: 2000;
    background: rgba(20,22,26,0.55);
    align-items: center; justify-content: center; padding: 20px;
  }
  .modal-overlay-terminos.visible { display: flex; }
  .modal-terminos-caja {
    width: 100%; max-width: 460px; max-height: 82vh;
    background: var(--bg-2); border-radius: 16px;
    box-shadow: var(--shadow-lg);
    display: flex; flex-direction: column; overflow: hidden;
  }
  .modal-terminos-titulo {
    padding: 20px 24px 14px; font-family: var(--font-d); font-weight: 700;
    font-size: 1.15rem; color: var(--text-1); border-bottom: 1px solid var(--border);
  }
  .modal-terminos-cuerpo {
    padding: 16px 24px; overflow-y: auto; font-size: 0.85rem;
    color: var(--text-2); line-height: 1.65;
  }
  .modal-terminos-cuerpo p { margin-bottom: 12px; }
  .modal-terminos-cuerpo p:last-child { margin-bottom: 0; }
  .modal-terminos-acciones {
    display: flex; gap: 10px; justify-content: flex-end;
    padding: 14px 24px; border-top: 1px solid var(--border);
  }
  .btn-terminos-cancelar, .btn-terminos-aceptar {
    padding: 9px 18px; border-radius: 9px; font-family: var(--font-b);
    font-size: 0.85rem; font-weight: 600; cursor: pointer; border: none;
  }
  .btn-terminos-cancelar { background: var(--bg-3); color: var(--text-2); border: 1px solid var(--border); }
  .btn-terminos-cancelar:hover { border-color: var(--border-2); }
  .btn-terminos-aceptar { background: var(--blue); color: #fff; }
  .btn-terminos-aceptar:hover { background: var(--blue-h); }
</style>
@endpush

@push('scripts')
<script>
  const campos = {
    cedula:   document.getElementById('reg-cedula'),
    nombre:   document.getElementById('reg-nombre'),
    apellido: document.getElementById('reg-apellido'),
    email:    document.getElementById('reg-email'),
    password: document.getElementById('reg-password'),
    confirm:  document.getElementById('reg-password-confirm'),
  };
  const errores = {
    cedula:   document.getElementById('err-cedula'),
    nombre:   document.getElementById('err-nombre'),
    apellido: document.getElementById('err-apellido'),
    email:    document.getElementById('err-email'),
    password: document.getElementById('err-password'),
    confirm:  document.getElementById('err-password-confirm'),
  };
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  const nombreRegex = /^[\p{L}\s]+$/u;
  const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z\d]).{8,}$/;

  function marcarError(campo, esInvalido) {
    campos[campo].classList.toggle('is-error', esInvalido);
    errores[campo].style.display = esInvalido ? 'block' : 'none';
  }

  function validarRequerido(campo) {
    const invalido = campos[campo].value.trim() === '';
    marcarError(campo, invalido);
    return !invalido;
  }

  function validarNombre(campo) {
    const valor = campos[campo].value.trim();
    const invalido = valor === '' || !nombreRegex.test(valor);
    marcarError(campo, invalido);
    return !invalido;
  }

  function validarEmail() {
    const invalido = !emailRegex.test(campos.email.value.trim());
    marcarError('email', invalido);
    return !invalido;
  }

  function validarPassword() {
    const invalido = !passwordRegex.test(campos.password.value);
    marcarError('password', invalido);
    return !invalido;
  }

  function validarConfirmacion() {
    const invalido = campos.confirm.value !== campos.password.value;
    marcarError('confirm', invalido);
    return !invalido;
  }

  function validarCedula() {
    const valor = campos.cedula.value.trim();
    const invalido = !/^\d{10}$/.test(valor);
    marcarError('cedula', invalido);
    return !invalido;
  }

  campos.cedula.addEventListener('input', () => {
    campos.cedula.value = campos.cedula.value.replace(/\D/g, '').slice(0, 10);
  });

  // ── Autocompletado: al salir del campo cédula, si tiene 10 dígitos, se
  // consulta el nombre real (mejor esfuerzo — si el servicio no responde,
  // el usuario simplemente escribe su nombre a mano, sin bloquear nada).
  // Se recuerda si nombre/apellido vinieron del autocompletado (en vez de
  // solo mirar si están vacíos): así, si el cliente se equivoca y pone otra
  // cédula, el nuevo nombre SÍ reemplaza al anterior sin tener que recargar
  // la página — pero si el cliente ya editó el nombre a mano, se respeta.
  let autocompletado = { nombre: false, apellido: false };
  campos.nombre.addEventListener('input', () => { autocompletado.nombre = false; });
  campos.apellido.addEventListener('input', () => { autocompletado.apellido = false; });

  let ultimaCedulaConsultada = null;
  campos.cedula.addEventListener('blur', function () {
    const hint = document.getElementById('hint-cedula');
    hint.style.display = 'none';

    if (!validarCedula()) return;

    const cedulaActual = campos.cedula.value;
    if (cedulaActual === ultimaCedulaConsultada) return;

    const spinner = document.getElementById('spinner-cedula');
    spinner.style.display = 'block';

    fetch(@json(route('registro.consultar-cedula')), {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': @json(csrf_token()),
        'Accept': 'application/json',
      },
      body: JSON.stringify({ cedula: cedulaActual }),
    })
      .then(res => res.ok ? res.json() : Promise.reject())
      .then(data => {
        ultimaCedulaConsultada = cedulaActual;
        if (data.encontrado) {
          if (!campos.nombre.value.trim() || autocompletado.nombre) {
            campos.nombre.value = data.nombres;
            autocompletado.nombre = true;
          }
          if (!campos.apellido.value.trim() || autocompletado.apellido) {
            campos.apellido.value = data.apellidos;
            autocompletado.apellido = true;
          }
          hint.textContent = 'Nombre autocompletado desde tu cédula.';
          hint.style.display = 'block';
        }
      })
      .catch(() => { /* silencioso: el usuario completa el nombre manualmente */ })
      .finally(() => { spinner.style.display = 'none'; });
  });

  campos.nombre.addEventListener('blur', () => validarNombre('nombre'));
  campos.apellido.addEventListener('blur', () => validarNombre('apellido'));
  campos.email.addEventListener('input', validarEmail);
  campos.password.addEventListener('input', () => { validarPassword(); if (campos.confirm.value) validarConfirmacion(); });
  campos.confirm.addEventListener('input', validarConfirmacion);

  // ── Términos y condiciones: modal con "Acepto" / "Cancelar". El checkbox
  // también se puede marcar directo sin abrir el modal (por si el cliente
  // ya conoce los términos), pero el enlace siempre abre el detalle.
  const checkTerminos  = document.getElementById('reg-terminos');
  const errTerminos    = document.getElementById('err-terminos');
  const modalTerminos  = document.getElementById('modal-terminos');

  function validarTerminos() {
    const invalido = !checkTerminos.checked;
    errTerminos.style.display = invalido ? 'block' : 'none';
    return !invalido;
  }

  document.getElementById('link-terminos').addEventListener('click', function (e) {
    e.preventDefault();
    modalTerminos.classList.add('visible');
  });
  document.getElementById('btn-terminos-cancelar').addEventListener('click', function () {
    modalTerminos.classList.remove('visible');
  });
  document.getElementById('btn-terminos-aceptar').addEventListener('click', function () {
    checkTerminos.checked = true;
    validarTerminos();
    modalTerminos.classList.remove('visible');
  });
  modalTerminos.addEventListener('click', function (e) {
    if (e.target === modalTerminos) modalTerminos.classList.remove('visible');
  });
  checkTerminos.addEventListener('change', validarTerminos);

  campos.confirm.closest('form').addEventListener('submit', function (e) {
    const ok = [
      validarCedula(),
      validarNombre('nombre'),
      validarNombre('apellido'),
      validarEmail(),
      validarPassword(),
      validarConfirmacion(),
      validarTerminos(),
    ].every(Boolean);

    if (!ok) {
      e.preventDefault();
    }
  });
</script>
@endpush
