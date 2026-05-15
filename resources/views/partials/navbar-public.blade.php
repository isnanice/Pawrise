<nav class="pr-navbar">
    <div class="container d-flex align-items-center justify-content-between">

        {{-- Brand / Logo --}}
        <a href="{{ route('home') }}" class="pr-brand">
            <img src="{{ asset('attached_assets/navbar.png') }}" width="30" height="30" alt="PawRise" class="me-1">
            <span style="color: var(--pr-orange);">PawRise</span>
        </a>

        {{-- Nav Links (center) --}}
        <div class="d-none d-md-flex align-items-center pr-nav-links">
            {{-- Katalog: tampil untuk SEMUA user (guest dan logged-in) --}}
            @auth
                <a href="{{ route('catalog.index') }}" class="pr-nav-link {{ request()->routeIs('catalog.*') ? 'active' : '' }}">Katalog</a>
            @endauth
            <a href="{{ route('about') }}" class="pr-nav-link {{ request()->routeIs('about') ? 'active' : '' }}">Tentang Kami</a>
            <a href="{{ route('education') }}" class="pr-nav-link {{ request()->routeIs('education') ? 'active' : '' }}">Edukasi</a>
            <a href="{{ route('help') }}" class="pr-nav-link {{ request()->routeIs('help') ? 'active' : '' }}">Bantuan</a>
        </div>

        {{-- Right side: Auth --}}
        <div class="d-flex align-items-center gap-2">
            @auth
                {{-- Logged-in: separator + avatar icon dropdown --}}
                <span class="d-none d-md-inline" style="color:var(--pr-border); font-size:1.2rem;">|</span>
                <div class="dropdown">
                    <a class="d-inline-flex align-items-center justify-content-center text-decoration-none"
                       data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false"
                       style="width:38px;height:38px;border-radius:50%;background:var(--pr-orange-light);border:2px solid var(--pr-orange);overflow:hidden;">
                        <img src="{{ auth()->user()->profilePhotoUrl() }}"
                             alt="{{ auth()->user()->name }}"
                             style="width:100%;height:100%;object-fit:cover;"
                             onerror="this.style.display='none';this.parentElement.innerHTML='<i class=\'bi bi-person-fill\' style=\'color:var(--pr-orange);font-size:1.1rem;\'></i>'">
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3 mt-2" style="min-width:200px;">
                        <li class="px-3 py-2 border-bottom">
                            <div class="fw-bold" style="font-size:.9rem;">{{ auth()->user()->name }}</div>
                            <div class="text-muted" style="font-size:.78rem;">{{ auth()->user()->email }}</div>
                        </li>
                        @if(auth()->user()->isShelter())
                            <li><a class="dropdown-item py-2" href="{{ route('shelter.dashboard') }}">
                                <i class="bi bi-grid me-2 text-muted"></i>Dashboard</a></li>
                            <li><a class="dropdown-item py-2" href="{{ route('shelter.animals.index') }}">
                                <i class="bi bi-clipboard-check me-2 text-muted"></i>Kelola Hewan</a></li>
                            <li><a class="dropdown-item py-2" href="{{ route('shelter.applications.index') }}">
                                <i class="bi bi-file-earmark-text me-2 text-muted"></i>Permohonan</a></li>
                        @else
                            <li><a class="dropdown-item py-2" href="{{ route('user.profile') }}">
                                <i class="bi bi-person me-2 text-muted"></i>Profil Saya</a></li>
                            <li><a class="dropdown-item py-2" href="{{ route('user.applications') }}">
                                <i class="bi bi-clipboard-check me-2 text-muted"></i>Status Adopsi</a></li>
                            <li><a class="dropdown-item py-2" href="{{ route('user.favorites') }}">
                                <i class="bi bi-heart me-2 text-muted"></i>Hewan Disukai</a></li>
                        @endif
                        <li><hr class="dropdown-divider my-1"></li>
                        <li><a class="dropdown-item py-2 text-danger" href="{{ route('logout.show') }}">
                            <i class="bi bi-box-arrow-right me-2"></i>Keluar</a></li>
                    </ul>
                </div>
            @else
                {{-- Guest: Masuk (outline) + Daftar (orange pill) --}}
                <a href="{{ route('login') }}" class="pr-nav-btn-outline">Masuk</a>
                <a href="{{ route('register') }}" class="pr-nav-btn-primary">Daftar</a>
            @endauth
        </div>

    </div>
</nav>
