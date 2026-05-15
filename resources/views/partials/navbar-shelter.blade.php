<nav class="pr-navbar">
    <div class="container d-flex align-items-center justify-content-between">
        <a href="{{ route('shelter.dashboard') }}" class="pr-brand">
            <img src="{{ asset('attached_assets/navbar.png') }}" width="30" height="30" alt="PawRise" class="me-1">
            <span style="color: var(--pr-orange);">PawRise</span>
        </a>

        <div class="d-flex align-items-center gap-3">
            <a href="#" class="text-dark"><i class="bi bi-bell" style="font-size:1.2rem"></i></a>
            <span class="text-muted">|</span>
            <div class="dropdown">
                <a class="d-inline-flex align-items-center gap-2 text-decoration-none text-dark" data-bs-toggle="dropdown" href="#" role="button">
                    <img src="{{ auth()->user()->profilePhotoUrl() }}" alt="" width="32" height="32" class="rounded-circle border" style="border-color: var(--pr-orange) !important;">
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                    <li><a class="dropdown-item" href="{{ route('shelter.dashboard') }}"><i class="bi bi-grid me-2"></i>Dashboard</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item text-danger" href="{{ route('logout.show') }}"><i class="bi bi-box-arrow-right me-2"></i>Keluar</a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>