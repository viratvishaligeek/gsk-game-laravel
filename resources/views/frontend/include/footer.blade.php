<footer class="site-footer">
    <div class="text-center footer-games bg-light">
        <p class="text-success fw-bold">Published Game Results</p>
        <div class="footer-game-row">
            @forelse ($frontendResults as $gameResult)
                <a class="btn btn-outline-success btn-sm"
                    href="{{ route('frontend.market', ['slug' => $gameResult['slug']]) }}"><span class="gold">▸</span>
                    {{ $gameResult['name'] }} <span class="muted">({{ $gameResult['time'] }})</span></a>
            @empty
                <span class="muted">No active games are configured.</span>
            @endforelse
        </div>
    </div>

    <div class=" footer-main">
        <div>
            <a href="{{ route('index') }}" class="logo footer-logo"><img src="{{ $siteLogo }}"
                    alt="{{ $siteName }}" class="logo-img"></a>
            <p class="muted">{{ $siteDescription }}</p>
            @if ($contactPhone !== '')
                <p class="muted small">Contact: <a
                        href="tel:{{ preg_replace('/[^0-9+]/', '', $contactPhone) }}">{{ $contactPhone }}</a></p>
            @endif
            @if ($contactEmail !== '')
                <p class="muted small">Email: <a href="mailto:{{ $contactEmail }}">{{ $contactEmail }}</a></p>
            @endif
            <div class="d-flex flex-wrap gap-2">
                @foreach (['Facebook' => $socialFacebook, 'WhatsApp' => $socialWhatsApp, 'Instagram' => $socialInstagram, 'YouTube' => $socialYouTube, 'Telegram' => $socialTelegram] as $socialName => $socialUrl)
                    @if ($socialUrl !== '')
                        <a href="{{ $socialUrl }}" target="_blank"
                            rel="noopener noreferrer">{{ $socialName }}</a>
                    @endif
                @endforeach
            </div>
        </div>
        <div class="d-flex flex-row flex-wrap gap-2">
            <a class="btn btn-outline-primary btn-sm" href="{{ route('index') }}">Home</a>
            <a class="btn btn-outline-primary btn-sm" href="{{ route('charts.index') }}">Monthly charts</a>
            <a class="btn btn-outline-primary btn-sm" href="{{ route('community.index') }}">Community discussions</a>
            <a class="btn btn-outline-primary btn-sm" href="{{ route('information', ['page' => 'about']) }}">About</a>
            <a class="btn btn-outline-primary btn-sm"
                href="{{ route('information', ['page' => 'contact']) }}">Contact</a>
            <a class="btn btn-outline-primary btn-sm" href="{{ route('information', ['page' => 'faq']) }}">FAQ</a>
            <a class="btn btn-outline-primary btn-sm"
                href="{{ route('information', ['page' => 'privacy-policy']) }}">Privacy policy</a>
            <a class="btn btn-outline-primary btn-sm"
                href="{{ route('information', ['page' => 'terms-and-conditions']) }}">Terms and conditions</a>
            @foreach ($frontendResults as $gameResult)
                <a class="btn btn-outline-primary btn-sm"
                    href="{{ route('frontend.market', ['slug' => $gameResult['slug']]) }}"><span
                        class="gold">▸</span>
                    {{ $gameResult['name'] }} <span class="muted">{{ $gameResult['time'] }}</span></a>
            @endforeach
            <a class="btn btn-outline-primary btn-sm" target="_blank" href="{{ route('sitemap') }}">
                Sitemap
            </a>
        </div>

    </div>

    <div class="footer-bottom">
        <div class="">
            <p class="disclaimer"><strong>Disclaimer:</strong>
                {{ app(\App\Services\AppSettingsService::class)->value('disclaimer_content', 'This website is for informational purposes only. Historical results are records, not predictions. Please follow applicable local laws.') }}
            </p>
            <p class="copy">&copy; {{ now()->year }}
                {{ $copyrightText !== '' ? $copyrightText : $siteName . ' — All rights reserved.' }}</p>
        </div>
    </div>
</footer>
<button type="button" class="wa-float" data-wa-open aria-label="Open WhatsApp contact">
    <span class="wa-pulse" aria-hidden="true"></span>
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="32" height="32" fill="currentColor"
        aria-hidden="true">
        <path
            d="M24 4C12.95 4 4 12.95 4 24c0 3.55.93 6.9 2.56 9.8L4 44l10.44-2.53A19.87 19.87 0 0 0 24 44c11.05 0 20-8.95 20-20S35.05 4 24 4zm0 36c-3.18 0-6.17-.87-8.73-2.38l-.62-.37-6.2 1.5 1.55-5.98-.4-.65A15.93 15.93 0 0 1 8 24c0-8.82 7.18-16 16-16s16 7.18 16 16-7.18 16-16 16zm8.73-11.9c-.48-.24-2.83-1.4-3.27-1.56-.44-.16-.76-.24-1.08.24-.32.48-1.24 1.56-1.52 1.88-.28.32-.56.36-1.04.12-.48-.24-2.02-.74-3.85-2.36-1.42-1.27-2.38-2.83-2.66-3.31-.28-.48-.03-.74.21-.98.22-.22.48-.56.72-.84.24-.28.32-.56.48-.8.16-.32.08-.6-.04-.84-.12-.24-1.08-2.6-1.48-3.56-.39-.93-.78-.8-1.08-.82-.28-.01-.6-.01-.92-.01-.32 0-.84.12-1.28.6-.44.48-1.68 1.64-1.68 4s1.72 4.64 1.96 4.96c.24.32 3.38 5.16 8.2 7.24 1.15.5 2.04.8 2.74 1.02 1.15.36 2.2.31 3.02.19.92-.14 2.83-1.16 3.23-2.28.4-1.12.4-2.08.28-2.28-.12-.2-.44-.32-.92-.56z" />
    </svg>
</button>
<span class="wa-label" aria-hidden="true">WhatsApp</span>
<div class="wa-modal" id="wa-modal">
    <div class="wa-modal-card">
        <div class="wa-modal-head"><strong>{{ $siteName }}</strong><button type="button" data-wa-close
                aria-label="Close">×</button></div>
        <div class="wa-modal-body">
            @if ($socialWhatsApp !== '')
                <a class="btn" href="{{ $socialWhatsApp }}" target="_blank" rel="noopener noreferrer">Contact on
                    WhatsApp</a>
            @elseif ($contactPhone !== '')
                <a class="btn" href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $contactPhone) }}"
                    target="_blank" rel="noopener noreferrer">Contact on WhatsApp</a>
            @else
                <p class="muted">The site administrator has not configured a WhatsApp contact yet.</p>
            @endif
        </div>
    </div>
</div>
