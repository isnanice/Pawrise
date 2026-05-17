@extends('layouts.user')
@section('title', 'Keluar - PawRise')
@section('content')

<div class="d-flex align-items-center justify-content-center" style="min-height: 60vh;">
    <div class="text-center p-5"
         style="background: #fff; border: 1px solid var(--pr-border); border-radius: 20px; width: 100%; max-width: 340px;">

        {{-- Icon --}}
        <div style="width: 72px; height: 72px; border-radius: 50%; background: var(--pr-orange-light);
                    display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
            <img src="{{ asset('attached_assets/navbar.png') }}" alt="PawRise Logo" style="width: 36px; height: 36px; object-fit: contain;">
        </div>

        <h4 class="fw-bold mb-2">Keluar dari PawRise?</h4>
        <p style="color: var(--pr-text-muted); font-size: .9rem; line-height: 1.6;">
            Apakah Anda yakin ingin keluar? Kami akan merindukan Anda dan teman-teman berbulu di sini.
        </p>

        <div class="d-flex flex-column gap-2 mt-4">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="btn pr-btn-primary w-100"
                        style="border-radius: 12px; padding: 12px; font-size: .95rem;">
                    Ya, Keluar
                </button>
            </form>

            <a href="{{ route('user.profile') }}"
               class="btn w-100"
               style="border-radius: 12px; padding: 12px; font-size: .95rem;
                      border: 1.5px solid var(--pr-border); color: var(--pr-text-muted); font-weight: 600;">
                Batal
            </a>
        </div>
    </div>
</div>

@endsection