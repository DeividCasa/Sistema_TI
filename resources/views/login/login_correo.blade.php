@extends('layouts.auth')

@section('titulo', 'Iniciar sesión — Leo José')

@section('left')
  <div class="left-tag"><span></span>Acceso seguro</div>
  <h1 class="left-h">Diseña tu<br>uniforme<br><em>ideal</em></h1>
  <p class="left-p">Ingresa a tu cuenta para crear, personalizar y hacer seguimiento de tus pedidos deportivos.</p>

  <div class="steps">
    <div class="step-row">
      <div class="step-col">
        <div class="step-dot on">1</div>
        <div class="step-line"></div>
      </div>
      <div class="step-label">
        <strong>Correo electrónico</strong>
        <span>Verificamos que tu cuenta exista</span>
      </div>
    </div>
    <div class="step-row">
      <div class="step-col">
        <div class="step-dot off">2</div>
      </div>
      <div class="step-label off">
        <strong>Contraseña</strong>
        <span>Acceso a tu panel personal</span>
      </div>
    </div>
  </div>
@endsection

@section('card')
  <div class="card-icon">
    <svg viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
  </div>

  <h2>Ingresa tu correo</h2>
  <p>Paso 1 de 2 — Verificamos que tengas una cuenta registrada.</p>

  <form action="{{ route('login.verificar-correo') }}" method="POST">
    @csrf
    <div class="field">
      <label for="email">Correo electrónico</label>
      <input
        type="email"
        id="email"
        name="email"
        placeholder="ejemplo@correo.com"
        value="{{ old('email') }}"
        class="{{ $errors->has('email') ? 'is-error' : '' }}"
        autofocus
        autocomplete="email"
      >
      @error('email')
        <div class="field-error">{{ $message }}</div>
      @enderror
      <div class="field-error" id="email-client-err" style="display:none;">Ingresa un correo válido (ej. nombre@correo.com).</div>
    </div>

    <button type="submit" class="btn">
      Continuar
      <svg viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
    </button>
  </form>

  <div class="login-link">
    ¿No tienes cuenta? <a href="{{ route('registro') }}">Regístrate gratis</a>
  </div>

  <a href="{{ route('inicio') }}" class="back-link">← Volver al inicio</a>
@endsection

@push('scripts')
<script>
  const emailInput = document.getElementById('email');
  const emailErr = document.getElementById('email-client-err');
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

  function validarEmail() {
    const valido = emailRegex.test(emailInput.value.trim());
    emailInput.classList.toggle('is-error', emailInput.value.trim() !== '' && !valido);
    emailErr.style.display = (emailInput.value.trim() !== '' && !valido) ? 'block' : 'none';
    return valido;
  }

  emailInput.addEventListener('input', validarEmail);
  emailInput.addEventListener('blur', validarEmail);

  emailInput.closest('form').addEventListener('submit', function (e) {
    if (!validarEmail()) {
      e.preventDefault();
      emailInput.focus();
    }
  });
</script>
@endpush
