<footer class="pr-footer">
    <div class="container">
        <div class="row align-items-start g-4">
            {{-- Brand --}}
            <div class="col-md-5">
                <a href="{{ route('home') }}" class="pr-brand mb-2 d-inline-flex">
                    <img src="{{ asset('attached_assets/footer.png') }}" width="26" height="26" alt="PawRise" class="me-1">
                    <span style="color: black;">PawRise</span>
                </a>
                <p class="pr-footer-tag mt-1 mb-0">© {{ date('Y') }} PawRise Indonesia. Connect Love, Saving Live.</p>
            </div>

            {{-- Column 2: Kebijakan Privasi & Syarat --}}
            <div class="col-6 col-md-3">
                <ul class="list-unstyled mb-0 pr-footer-list">
                    <li><a href="{{ route('privacy') }}">Kebijakan Privasi</a></li>
                    <li><a href="{{ route('terms') }}">Syarat & Ketentuan</a></li>
                </ul>
            </div>

            {{-- Column 3: Kontak Shelter & Gabung Relawan --}}
            <div class="col-6 col-md-4">
                <ul class="list-unstyled mb-0 pr-footer-list">
                    <li><a href="{{ route('shelterContact') }}">Kontak Shelter</a></li>
                    <li><a href="{{ route('volunteer') }}">Gabung Relawan</a></li>
                </ul>
            </div>
        </div>
    </div>
</footer>
