@extends('layouts.auth')
@section('title', 'Atur Ulang Kata Sandi - PawRise')

@section('hero_title', 'Buat Kata Sandi Baru')
@section('hero_subtitle', 'Masukkan kata sandi baru Anda untuk mengamankan dan mengakses akun kembali.')
@section('hero_image', asset('attached_assets/masuk.png'))

@section('tab_login_class', '')
@section('tab_register_class', '')

@section('subtitle', 'Setel Ulang Kata Sandi')

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger mb-4" style="font-size: 0.9rem;">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('password.store') }}" class="d-grid gap-3">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div>
            <label class="form-label fw-bold" style="font-size: 0.85rem;">Alamat Email</label>
            <input type="email" name="email" value="{{ old('email', $request->email) }}" class="form-control" style="background: #F8FAFC;" required readonly>
        </div>

        <div>
            <label class="form-label fw-bold" style="font-size: 0.85rem;">Kata Sandi Baru</label>
            <div class="input-group">
                <input type="password" name="password" id="password" class="form-control border-end-0" style="background: #F8FAFC;" placeholder="Minimal 8 karakter" required autofocus>
                <span class="input-group-text bg-transparent border-start-0 text-muted" style="background: #F8FAFC; cursor: pointer;" onclick="togglePassword('password', this)">
                    <i class="bi bi-eye-slash"></i>
                </span>
            </div>
        </div>

        <div>
            <label class="form-label fw-bold" style="font-size: 0.85rem;">Konfirmasi Kata Sandi Baru</label>
            <div class="input-group">
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control border-end-0" style="background: #F8FAFC;" placeholder="Ulangi kata sandi baru" required>
                <span class="input-group-text bg-transparent border-start-0 text-muted" style="background: #F8FAFC; cursor: pointer;" onclick="togglePassword('password_confirmation', this)">
                    <i class="bi bi-eye-slash"></i>
                </span>
            </div>
        </div>

        <button type="submit" class="btn pr-btn-primary w-100 mt-3" style="font-weight: 600;">
            Atur Ulang Kata Sandi <i class="bi bi-check2-circle ms-1"></i>
        </button>
    </form>

    <div class="text-center mt-5" style="font-size: 0.9rem;">
        <a href="{{ route('login') }}" class="text-decoration-none fw-bold" style="color: #A16207;">
            Kembali ke Halaman Masuk
        </a>
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
