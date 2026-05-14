@extends('layouts.auth')
@section('title', 'Masuk - PawRise')

@section('hero_title', 'Selamat Datang Kembali!')
@section('hero_subtitle', 'Lanjutkan perjalanan mencari teman selamanya')

@section('tab_login_class', 'active')
@section('tab_register_class', '')

@section('subtitle', 'Masuk ke Akun Anda')

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger mb-4">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('login.store') }}" class="d-grid gap-3">
        @csrf
        <div>
            <label class="form-label fw-bold" style="font-size: 0.85rem;">Alamat Email</label>
            <input type="email" name="email" value="{{ old('email') }}" class="form-control" style="background: #F8FAFC;" placeholder="contoh@email.com" required autofocus>
        </div>
        <div>
            <label class="form-label fw-bold" style="font-size: 0.85rem;">Kata Sandi</label>
            <div class="input-group">
                <input type="password" name="password" id="password" class="form-control border-end-0" style="background: #F8FAFC;" placeholder="Minimal 8 karakter" required>
                <span class="input-group-text bg-transparent border-start-0 text-muted" style="background: #F8FAFC; cursor: pointer;" onclick="togglePassword('password', this)">
                    <i class="bi bi-eye-slash"></i>
                </span>
            </div>
        </div>
        <div class="text-end mt-1">
            <a href="#" class="text-decoration-none" style="color: #A16207; font-size: 0.85rem;">Lupa kata sandi?</a>
        </div>
        <button type="submit" class="btn pr-btn-primary w-100 mt-3" style="font-weight: 600;">Masuk <i class="bi bi-arrow-right ms-1"></i></button>
    </form>

    <div class="text-center mt-5" style="font-size: 0.9rem;">
        <span class="text-muted">Belum punya akun?</span>
        <a href="{{ route('register') }}" class="text-decoration-none fw-bold" style="color: #A16207;">Daftar di sini</a>
    </div>

    <script>
        function togglePassword(inputId, iconSpan) {
            const input = document.getElementById(inputId);
            const icon = iconSpan.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            } else {
                input.type = 'password';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            }
        }
    </script>
@endsection
