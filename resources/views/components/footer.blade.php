<footer class="fm-footer">
    <div class="fm-container">
        <div class="row gy-5">
            <div class="col-12 col-md-3 text-center text-md-start">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('images/home/logo-footer.png') }}" alt="Flor de Marula" style="height: 95px; width: auto;">
                </a>
                <div class="d-flex gap-3 justify-content-center justify-content-md-start mt-3">
                    <a href="#" aria-label="Facebook"><img src="{{ asset('images/home/icon-facebook.png') }}" alt="" width="35" height="36"></a>
                    <a href="#" aria-label="Instagram"><img src="{{ asset('images/home/icon-instagram.png') }}" alt="" width="35" height="36"></a>
                    <a href="#" aria-label="TikTok"><img src="{{ asset('images/home/icon-tiktok.png') }}" alt="" width="35" height="36"></a>
                </div>
            </div>

            <div class="col-6 col-md-3">
                <p class="fm-footer__heading mb-3">Links Rápidos</p>
                <ul class="list-unstyled d-flex flex-column gap-2 fs-6">
                    <li><a href="{{ route('home') }}">Início</a></li>
                    <li><a href="{{ route('shop.index') }}">Loja</a></li>
                    <li><a href="{{ route('reviews.index') }}">Clientes Satisfeitos</a></li>
                    <li><a href="{{ route('historia.index') }}">Nossa História</a></li>
                </ul>
            </div>

            <div class="col-12 col-md-3">
                <p class="fm-footer__heading mb-3">Newsletter</p>
                <p class="mb-3">Inscreva-se em nossa Newsletter</p>
                <form method="POST" action="{{ route('newsletter.subscribe') }}" class="d-flex flex-column gap-2">
                    @csrf
                    <input
                        type="email"
                        name="email"
                        required
                        placeholder="O seu e-mail"
                        class="form-control rounded-2 border-0"
                        style="height: 49px;"
                    >
                    <button type="submit" class="btn fw-normal rounded-2" style="background: var(--fm-accent); height: 47px; color: var(--fm-black);">
                        Inscrever-se
                    </button>
                </form>
                @if (session('newsletter_success'))
                    <p class="small mt-2 mb-0" style="color: var(--fm-accent);">{{ session('newsletter_success') }}</p>
                @endif
                @error('email')
                    <p class="small mt-2 mb-0 text-danger">{{ $message }}</p>
                @enderror
            </div>

            <div class="col-6 col-md-3">
                <p class="fm-footer__heading mb-3">Contactos</p>
                <ul class="list-unstyled d-flex flex-column gap-2 fs-6">
                    <li><a href="tel:+244945960249">945960249</a></li>
                    <li><a href="mailto:flordemarula@gmail.com">flordemarula@gmail.com</a></li>
                    <li>Luanda, Talatona</li>
                </ul>
            </div>
        </div>
    </div>
</footer>
