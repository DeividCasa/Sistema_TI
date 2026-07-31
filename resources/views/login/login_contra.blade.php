@extends('layouts.auth')

@section('titulo', 'Contraseña — Leo José')

@section('left')
  <div class="left-tag"><span></span>Casi listo</div>
  <h1 class="left-h">Un paso<br>más y<br><em>entras</em></h1>
  <p class="left-p">Tu correo fue verificado correctamente. Solo falta tu contraseña para acceder.</p>

  <div class="user-card">
    <div class="user-avatar">{{ strtoupper(substr($nombre ?? 'U', 0, 1)) }}</div>
    <div class="user-info">
      <strong>{{ $nombre ?? 'Usuario' }}</strong>
      <span>Cuenta verificada ✓</span>
    </div>
  </div>

  <div class="steps">
    <div class="step-row">
      <div class="step-col">
        <div class="step-dot done">✓</div>
        <div class="step-line"></div>
      </div>
      <div class="step-label done">
        <strong>Correo electrónico</strong>
        <span>Verificado correctamente</span>
      </div>
    </div>
    <div class="step-row">
      <div class="step-col">
        <div class="step-dot on">2</div>
      </div>
      <div class="step-label">
        <strong>Contraseña</strong>
        <span>Ingresa tu clave de acceso</span>
      </div>
    </div>
  </div>
@endsection

@section('card')
  <div class="card-icon">
    <svg viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
  </div>

  <div class="email-chip">
    <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
    {{ $email }}
  </div>

  <h2>Tu contraseña</h2>
  <p>Paso 2 de 2 — Ingresa tu contraseña para acceder a tu cuenta.</p>

  <form action="{{ route('login.verificar-contrasena') }}" method="POST">
    @csrf
    <div class="field">
      <label for="password">Contraseña</label>
      <div class="input-wrap">
        <input
          type="password"
          id="password"
          name="password"
          placeholder="••••••••"
          class="{{ $errors->has('password') ? 'is-error' : '' }}"
          autofocus
          autocomplete="current-password"
          oninput="checkStrength(this.value)"
        >
        <button type="button" class="toggle-btn" onclick="togglePass('password', this)" title="Ver contraseña" tabindex="-1">
          <svg viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
        </button>
      </div>

      <div class="strength-bar">
        <span id="s1"></span><span id="s2"></span><span id="s3"></span><span id="s4"></span>
      </div>
      <div class="strength-label" id="s-label"></div>

      @error('password')
        <div class="field-error">{{ $message }}</div>
      @enderror
      <div class="field-error" id="password-client-err" style="display:none;">Mínimo 6 caracteres.</div>
    </div>

    <div style="text-align:right; margin-bottom:18px;">
      <a href="{{ route('password.solicitar') }}" style="font-size:0.82rem; color:var(--blue); font-weight:600; text-decoration:none;">¿Olvidaste tu contraseña?</a>
    </div>

    <label class="remember">
      <input type="checkbox" name="remember">
      Recordarme en este dispositivo
    </label>

    <button type="submit" class="btn">
      Ingresar
      <svg viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
    </button>
  </form>

  <a href="{{ route('login.paso1') }}" class="back-link">← Usar otro correo</a>
@endsection

@push('scripts')
<script>
  function checkStrength(val) {
    const bars   = [s1, s2, s3, s4];
    const labels = ['', 'Débil', 'Regular', 'Buena', 'Fuerte'];
    const colors = ['', '#EF4444', '#F59E0B', '#0E6B4F', '#22C55E'];
    let score = 0;
    if (val.length >= 6)  score++;
    if (val.length >= 10) score++;
    if (/[A-Z]/.test(val) && /[0-9]/.test(val)) score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;

    bars.forEach((b, i) => {
      b.style.background = i < score ? colors[score] : '#E7E2D9';
    });
    document.getElementById('s-label').textContent = val.length ? labels[score] : '';
    document.getElementById('s-label').style.color  = colors[score];
  }

  const passwordInput = document.getElementById('password');
  const passwordErr = document.getElementById('password-client-err');

  function validarPassword() {
    const valido = passwordInput.value.length >= 6;
    passwordInput.classList.toggle('is-error', passwordInput.value.length > 0 && !valido);
    passwordErr.style.display = (passwordInput.value.length > 0 && !valido) ? 'block' : 'none';
    return valido;
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
