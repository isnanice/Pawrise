@extends('layouts.auth')
@section('title', 'Daftar - PawRise')

@section('hero_title', 'Selamat Datang!')
@section('hero_subtitle', 'Mulai perjalanan mencari teman selamanya')

@section('tab_login_class', '')
@section('tab_register_class', 'active')

@section('subtitle', 'Buat akun untuk memulai perjalanan Anda')

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger mb-4">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register.store') }}" class="d-grid gap-3">
        @csrf
        
        <div class="mb-2">
            <label class="form-label text-muted fw-bold" style="font-size: 0.75rem; letter-spacing: 0.5px;">MENDAFTAR SEBAGAI</label>
            <div class="pr-role-selector">
                <label class="pr-role-btn {{ old('role', 'adopter') === 'adopter' ? 'active' : '' }}" onclick="document.getElementById('shelter-fields').style.display='none'; document.querySelectorAll('.pr-role-btn').forEach(b => b.classList.remove('active')); this.classList.add('active');">
                    <input type="radio" name="role" value="adopter" class="d-none" {{ old('role', 'adopter') === 'adopter' ? 'checked' : '' }}>
                    <i class="bi bi-person-fill"></i> Calon Adopter
                </label>
                <label class="pr-role-btn {{ old('role') === 'shelter' ? 'active' : '' }}" onclick="document.getElementById('shelter-fields').style.display='block'; document.querySelectorAll('.pr-role-btn').forEach(b => b.classList.remove('active')); this.classList.add('active');">
                    <input type="radio" name="role" value="shelter" class="d-none" {{ old('role') === 'shelter' ? 'checked' : '' }}>
                    <i class="bi bi-house-door"></i> Shelter/Rescue
                </label>
            </div>
        </div>

        <div>
            <label class="form-label fw-bold" style="font-size: 0.85rem;">Nama Lengkap</label>
            <input type="text" name="name" value="{{ old('name') }}" class="form-control" style="background: #F8FAFC;" placeholder="Masukkan nama lengkap Anda" required>
        </div>
        <div>
            <label class="form-label fw-bold" style="font-size: 0.85rem;">Alamat Email</label>
            <input type="email" name="email" value="{{ old('email') }}" class="form-control" style="background: #F8FAFC;" placeholder="contoh@email.com" required>
        </div>
        <div>
            <label class="form-label fw-bold" style="font-size: 0.85rem;">Nomor Telepon</label>
            <input type="text" name="phone" value="{{ old('phone') }}" class="form-control" style="background: #F8FAFC;" placeholder="+62 812 3456 7890" required>
        </div>

        <div id="shelter-fields" style="display: {{ old('role') === 'shelter' ? 'block' : 'none' }};">
            <div class="mb-3">
                <label class="form-label fw-bold" style="font-size: 0.85rem;">Nama Shelter</label>
                <input type="text" name="shelter_name" value="{{ old('shelter_name') }}" class="form-control" style="background: #F8FAFC;" placeholder="Masukkan nama shelter">
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold" style="font-size: 0.85rem;">Kota</label>
                <input type="text" name="city" value="{{ old('city') }}" class="form-control" style="background: #F8FAFC;" placeholder="Kota domisili shelter">
            </div>
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

        <button type="submit" class="btn pr-btn-primary w-100 mt-3" style="font-weight: 600;">Daftar Sekarang <i class="bi bi-arrow-right ms-1"></i></button>
    </form>

    <div class="text-center mt-5" style="font-size: 0.9rem;">
        <span class="text-muted">Sudah punya akun?</span>
        <a href="{{ route('login') }}" class="text-decoration-none fw-bold" style="color: #A16207;">Masuk di sini</a>
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
