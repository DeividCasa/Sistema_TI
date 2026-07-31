@extends('layouts.auth')

@section('titulo', 'Código de acceso — Leo José')

@section('left')
  <div class="left-tag"><span></span>Verificación de administrador</div>
  <h1 class="left-h">Confirma<br>que eres<br><em>tú</em></h1>
  <p class="left-p">Por seguridad, el acceso al panel de administración requiere un código enviado a tu correo en cada inicio de sesión.</p>

  <div class="steps">
    <div class="step-row">
      <div class="step-col"><div class="step-dot done">✓</div><div class="step-line"></div></div>
      <div class="step-label done"><strong>Correo electrónico</strong><span>Verificado correctamente</span></div>
    </div>
    <div class="step-row">
      <div class="step-col"><div class="step-dot done">✓</div><div class="step-line"></div></div>
      <div class="step-label done"><strong>Contraseña</strong><span>Verificada correctamente</span></div>
    </div>
    <div class="step-row">
      <div class="step-col"><div class="step-dot on">3</div></div>
      <div class="step-label"><strong>Código de acceso</strong><span>Revisa tu correo</span></div>
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

  <h2>Ingresa el código</h2>
  <p>Te enviamos un código de 6 dígitos a tu correo. Ingrésalo para completar el acceso.</p>

  <form action="{{ route('login.verificar-codigo') }}" method="POST">
    @csrf
    <div class="field">
      <label for="codigo" style="text-align:center;">Código de acceso</label>
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

    <button type="submit" class="btn">
      Verificar código
      <svg viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
    </button>
  </form>

  <div class="resend">
    ¿No recibiste el código?
    <form action="{{ route('login.reenviar-codigo') }}" method="POST" style="display:inline;">
      @csrf
      <button type="submit">Reenviar</button>
    </form>
  </div>

  <a href="{{ route('login.paso1') }}" class="back-link">← Usar otro correo</a>
@endsection

@push('scripts')
<script>
  const codigoInput = document.getElementById('codigo');
  codigoInput.addEventListener('input', function () {
    this.value = this.value.replace(/\D/g, '').slice(0, 6);
  });
</script>
@endpush
