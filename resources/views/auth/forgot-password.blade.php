@extends('layouts.auth')
@section('title', 'Lupa Kata Sandi - PawRise')

@section('hero_title', 'Lupa Kata Sandi?')
@section('hero_subtitle', 'Jangan khawatir, kami akan membantu memulihkan akses akun Anda dengan cepat.')
@section('hero_image', asset('attached_assets/masuk.png'))

@section('tab_login_class', '')
@section('tab_register_class', '')

@section('subtitle', 'Atur Ulang Kata Sandi')

@section('content')
    @if (session('status'))
        <div class="alert alert-success mb-4" role="alert" style="font-size: 0.9rem;">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger mb-4" style="font-size: 0.9rem;">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ $errors->first() }}
        </div>
    @endif

    <p class="text-muted mb-4" style="font-size: 0.85rem; line-height: 1.6;">
        Masukkan alamat email yang terdaftar. Kami akan mengirimkan tautan (link) untuk mengatur ulang kata sandi Anda secara aman.
    </p>

    <form method="POST" action="{{ route('password.email') }}" class="d-grid gap-3">
        @csrf
        <div>
            <label class="form-label fw-bold" style="font-size: 0.85rem;">Alamat Email</label>
            <input type="email" name="email" value="{{ old('email') }}" class="form-control" style="background: #F8FAFC;" placeholder="contoh@email.com" required autofocus>
        </div>
        <button type="submit" class="btn pr-btn-primary w-100 mt-2" style="font-weight: 600;">
            Kirim Tautan Atur Ulang <i class="bi bi-send ms-1"></i>
        </button>
    </form>

    <div class="text-center mt-5" style="font-size: 0.9rem;">
        <a href="{{ route('login') }}" class="text-decoration-none fw-bold" style="color: #A16207;">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Masuk
        </a>
    </div>
@endsection
