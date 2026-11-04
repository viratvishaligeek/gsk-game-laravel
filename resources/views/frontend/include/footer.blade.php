<footer class="gsk-site-footer">
  <div class="container py-5">
    <div class="row g-4">
      <div class="col-lg-5">
        <a class="gsk-footer-brand" href="{{ route('index') }}">
          @if (filled(setting('site_logo')) && is_file(public_path('logos/' . basename(setting('site_logo')))))
            <img src="{{ asset('logos/' . basename(setting('site_logo'))) }}" width="48" height="48" alt="{{ setting('title') }}">
          @else <span>🎯</span> @endif
          <span><strong>{{ setting('title') }}</strong><small>{{ setting('site_tagline') }}</small></span>
        </a>
        @if (filled(setting('footer_description')))
          <p class="gsk-footer-description mt-3">{{ setting('footer_description') }}</p>
        @endif
        <div class="d-grid gap-2 mt-3">
          @if (filled(setting('email')))<a class="gsk-footer-contact" href="mailto:{{ setting('email') }}">✉️ {{ setting('email') }}</a>@endif
          @if (filled(setting('contact_phone')))<a class="gsk-footer-contact" href="tel:{{ preg_replace('/[^0-9+]/', '', setting('contact_phone')) }}">📞 {{ setting('contact_phone') }}</a>@endif
          @if (filled(setting('contact_address')))<span class="gsk-footer-contact">📍 {{ setting('contact_address') }}</span>@endif
        </div>
      </div>
      <div class="col-6 col-lg-3">
        <h2 class="gsk-footer-heading">Explore</h2>
        <ul class="list-unstyled gsk-footer-links">
          <li><a href="{{ route('index') }}#today-results">🏆 Today's results</a></li>
          <li><a href="{{ route('index') }}#markets">🎮 Market directory</a></li>
          <li><a href="{{ route('index') }}#market-charts">📊 Monthly charts</a></li>
          <li><a href="{{ route('index') }}#records">📚 Historical records</a></li>
          <li><a href="{{ route('community.index') }}">👥 Community</a></li>
          <li><a href="{{ route('sitemap') }}">🗺️ Sitemap</a></li>
        </ul>
      </div>
      <div class="col-6 col-lg-4">
        <h2 class="gsk-footer-heading">Information</h2>
        <ul class="list-unstyled gsk-footer-links">
          @foreach ($navPages ?? collect() as $navPage)
            <li><a href="{{ route('page', ['page' => $navPage->slug]) }}">{{ $navPage->name }} ↗</a></li>
          @endforeach
        </ul>
        <h2 class="gsk-footer-heading mt-4">Connect</h2>
        <div class="d-flex flex-wrap gap-2">
          @foreach (['facebook'=>'Facebook','instagram'=>'Instagram','youtube'=>'YouTube','telegram'=>'Telegram','whatsapp'=>'WhatsApp'] as $socialKey=>$socialLabel)
            @if (filled(setting('social_'.$socialKey)) && filter_var(setting('social_'.$socialKey), FILTER_VALIDATE_URL) && in_array(parse_url(setting('social_'.$socialKey), PHP_URL_SCHEME), ['http','https'], true))
              <a class="gsk-social-link" href="{{ setting('social_'.$socialKey) }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $socialLabel }}">{{ ['facebook'=>'📘','instagram'=>'📸','youtube'=>'▶️','telegram'=>'✈️','whatsapp'=>'💬'][$socialKey] }}</a>
            @endif
          @endforeach
        </div>
      </div>
    </div>
  </div>
  <div class="gsk-footer-bottom"><div class="container py-3 d-flex flex-column flex-md-row justify-content-between gap-2">
    <small>{{ setting('copyright_text', '© ' . date('Y') . ' ' . setting('title')) }}</small><small>Information and historical records only.</small>
  </div></div>
</footer>
<button class="gsk-whatsapp-float" type="button" data-bs-toggle="modal" data-bs-target="#whatsappContactModal" aria-label="Open WhatsApp contact">💬</button>
  <div class="modal fade" id="whatsappContactModal" tabindex="-1" aria-labelledby="whatsappContactModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content border-0 rounded-4 overflow-hidden">
        <div class="modal-header bg-success text-white border-0 p-4">
          <div><h2 class="modal-title fs-5 fw-bold mb-1" id="whatsappContactModalLabel">Game schedule</h2><p class="small mb-0">Chat with us on WhatsApp</p></div>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-4">
          <p class="small text-uppercase fw-bold text-secondary mb-3">Market timing reference</p>
          <div class="list-group list-group-flush">
            <div class="list-group-item d-flex justify-content-between gap-3 px-0"><span>Delhi Bazaar</span><strong>03:15 PM</strong></div>
            <div class="list-group-item d-flex justify-content-between gap-3 px-0"><span>Shree Ganesh</span><strong>04:45 PM</strong></div>
            <div class="list-group-item d-flex justify-content-between gap-3 px-0"><span>Faridabad</span><strong>06:15 PM</strong></div>
            <div class="list-group-item d-flex justify-content-between gap-3 px-0"><span>Ghaziabad</span><strong>10:00 PM</strong></div>
            <div class="list-group-item d-flex justify-content-between gap-3 px-0"><span>Gali</span><strong>11:50 PM</strong></div>
            <div class="list-group-item d-flex justify-content-between gap-3 px-0"><span>Disawar</span><strong>05:15 AM</strong></div>
          </div>
        </div>
        <div class="modal-footer border-0 p-4 pt-0">
          @if (filled(setting('social_whatsapp')) && filter_var(setting('social_whatsapp'), FILTER_VALIDATE_URL) && in_array(parse_url(setting('social_whatsapp'), PHP_URL_SCHEME), ['http','https'], true))
            <a class="btn btn-success w-100 rounded-pill" href="{{ setting('social_whatsapp') }}" target="_blank" rel="noopener noreferrer">💬 Contact us on WhatsApp</a>
          @else
            <p class="small text-secondary mb-0">WhatsApp contact details are not configured yet.</p>
          @endif
        </div>
      </div>
    </div>
  </div>
