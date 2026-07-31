@extends('layouts.auth')

@section('titulo', 'Recuperar contraseña — Leo José')

@section('left')
  <div class="left-tag"><span></span>Recuperación de cuenta</div>
  <h1 class="left-h">¿Olvidaste<br>tu <em>contraseña</em>?</h1>
  <p class="left-p">Tranquilo, te enviaremos un código a tu correo para que puedas crear una nueva contraseña.</p>
@endsection

@section('card')
  <div class="card-icon">
    <svg viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
  </div>

  <h2>Recuperar contraseña</h2>
  <p>Ingresa el correo de tu cuenta y te enviaremos un código para restablecer tu contraseña.</p>

  <form action="{{ route('password.enviar-codigo') }}" method="POST">
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
    </div>

    <button type="submit" class="btn">
      Enviar código
      <svg viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
    </button>
  </form>

  <a href="{{ route('login.paso1') }}" class="back-link">← Volver a iniciar sesión</a>
@endsection
