@extends('layouts.app')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

<div class="login-wrap">
  <div class="login-card">

    <a href="{{ url('/') }}">
        <img class="brand-logo" src="{{ asset('img/Logo.png') }}" alt="Logo">
    </a>

    <h1 class="login-title">{{ __('Login') }}</h1>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        {{-- EMAIL --}}
        <div class="mb-3 text-start">
            <label for="email" class="form-label">
                {{ __('E-Mail Address') }}
            </label>

            <input id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                class="form-control @error('email') is-invalid @enderror"
                required
                autocomplete="email"
                autofocus>

            @error('email')
                <span class="invalid-feedback d-block">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- PASSWORD --}}
        <div class="mb-2 text-start">
            <label for="password" class="form-label">
                {{ __('Password') }}
            </label>

            <div class="input-group">
                <input id="password"
                    type="password"
                    name="password"
                    class="form-control @error('password') is-invalid @enderror"
                    required
                    autocomplete="current-password">

                <span class="input-group-text" onclick="togglePass()">👁️</span>
            </div>

            @error('password')
                <span class="invalid-feedback d-block">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- REMEMBER --}}
        <div class="form-check text-start mb-2">
            <input class="form-check-input"
                type="checkbox"
                name="remember"
                id="remember"
                {{ old('remember') ? 'checked' : '' }}>

            <label class="form-check-label" for="remember">
                {{ __('Remember Me') }}
            </label>
        </div>

        {{-- FORGOT PASSWORD --}}
        @if (Route::has('password.request'))
            <a class="forgot" href="{{ route('password.request') }}">
                {{ __('Forgot Your Password?') }}
            </a>
        @endif

        <button type="submit" class="btn-login">
            {{ __('Login') }}
        </button>

        <div class="mt-3" style="font-size:13px;">
            {{ __("Don't have an account?") }}
            <a href="{{ route('register') }}" style="color:#000;font-weight:600;">
                <strong>{{ __('Register') }}</strong>
            </a>
        </div>

    </form>
  </div>
</div>

<script>
function togglePass(){
    const input = document.getElementById('password');
    input.type = input.type === 'password' ? 'text' : 'password';
}
</script>

<style>
:root{
  --turquesa:#0A9A9E;
  --borde:#d1d5db;
}

body{
  background:#f3f4f6;
  font-family:'Poppins', sans-serif;
}

/* Oculta navbar solo en login */
.navbar{
  display:none;
}

.login-wrap{
  min-height:100vh;
  display:flex;
  align-items:center;
  justify-content:center;
  padding:40px 16px;
  animation:fadeIn .6s ease;
}

@keyframes fadeIn{
  from{opacity:0;transform:translateY(10px);}
  to{opacity:1;transform:translateY(0);}
}

.login-card{
  width:380px;
  text-align:center;
}

.brand-logo{
  width:180px;
  margin-bottom:15px;
}

.login-title{
  font-size:26px;
  font-weight:600;
  margin-bottom:20px;
  color:#333;
}

.form-label{
  font-size:13px;
}

.form-control{
  border:1px solid var(--borde);
  border-radius:4px;
  padding:10px;
  font-size:14px;
  box-shadow:none !important;
}

.input-group-text{
  background:#fff;
  cursor:pointer;
}

.btn-login{
  background:var(--turquesa);
  border:none;
  width:100%;
  color:#fff;
  padding:10px;
  border-radius:4px;
  font-weight:600;
}

.btn-login:hover{
  filter:brightness(.95);
}

.forgot{
  display:block;
  text-align:left;
  font-size:12px;
  margin:10px 0;
  text-decoration:none;
  color:#333;
}

.form-check-label{
  font-size:13px;
}
</style>

@endsection