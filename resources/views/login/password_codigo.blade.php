@extends('layouts.auth')

@section('titulo', 'Nueva contraseña — Leo José')

@section('left')
  <div class="left-tag"><span></span>Correo enviado</div>
  <h1 class="left-h">Crea tu<br>nueva<br><em>contraseña</em></h1>
  <p class="left-p">Revisa tu bandeja de entrada, ingresa el código que te enviamos y define una nueva contraseña.</p>
@endsection

@section('card')
  <div class="card-icon">
    <svg viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
  </div>

  <div class="email-chip">
    <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
    {{ $email }}
  </div>

  <h2>Restablecer contraseña</h2>
  <p>Ingresa el código de 6 dígitos y tu nueva contraseña.</p>

  <form action="{{ route('password.restablecer') }}" method="POST">
    @csrf
    <div class="field">
      <label for="codigo" style="text-align:center;">Código de verificación</label>
      <input
        type="text"
        id="codigo"
        name="codigo"
        inputmode="numeric"
        maxlength="6"
        placeholder="000000"
        class="codigo-input {{ $errors->has('codigo') ? 'is-error' : '' }}"
        autofocus
        autocomplete="one-time-code"
      >
      @error('codigo')
        <div class="field-error" style="text-align:center;">{{ $message }}</div>
      @enderror
    </div>

    <div class="field">
      <label for="password">Nueva contraseña</label>
      <div class="input-wrap">
        <input
          type="password"
          id="password"
          name="password"
          placeholder="••••••••"
          class="{{ $errors->has('password') ? 'is-error' : '' }}"
          autocomplete="new-password"
        >
        <button type="button" class="toggle-btn" onclick="togglePass('password', this)" title="Ver contraseña" tabindex="-1">
          <svg viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
        </button>
      </div>
      @error('password')
        <div class="field-error">{{ $message }}</div>
      @enderror
      <div class="field-error" id="err-password" style="display:none;">Debe tener mayúscula, minúscula, número y símbolo (ej: Deivid21$).</div>
    </div>

    <div class="field">
      <label for="password_confirmation">Confirmar contraseña</label>
      <div class="input-wrap">
        <input
          type="password"
          id="password_confirmation"
          name="password_confirmation"
          placeholder="••••••••"
          autocomplete="new-password"
        >
        <button type="button" class="toggle-btn" onclick="togglePass('password_confirmation', this)" title="Ver contraseña" tabindex="-1">
          <svg viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
        </button>
      </div>
    </div>

    <button type="submit" class="btn">
      Cambiar contraseña
      <svg viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
    </button>
  </form>

  <div class="resend">
    ¿No recibiste el código?
    <form action="{{ route('password.reenviar-codigo') }}" method="POST" style="display:inline;">
      @csrf
      <button type="submit">Reenviar</button>
    </form>
  </div>

  <a href="{{ route('login.paso1') }}" class="back-link">← Volver a iniciar sesión</a>
@endsection

@push('scripts')
<script>
  document.getElementById('codigo').addEventListener('input', function () {
    this.value = this.value.replace(/\D/g, '').slice(0, 6);
  });

  const passwordInput = document.getElementById('password');
  const passwordErr = document.getElementById('err-password');
  const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z\d]).{8,}$/;

  function validarPassword() {
    const invalido = !passwordRegex.test(passwordInput.value);
    passwordInput.classList.toggle('is-error', invalido);
    passwordErr.style.display = invalido ? 'block' : 'none';
    return !invalido;
  }

  passwordInput.addEventListener('input', validarPassword);
  passwordInput.addEventListener('blur', validarPassword);

  passwordInput.closest('form').addEventListener('submit', function (e) {
    if (!validarPassword()) {
      e.preventDefault();
      passwordInput.focus();
    }
  });
</script>
@endpush
