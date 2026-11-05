<footer class="v5-footer">
  <section class="v5-footer-cta"><div><span class="v5-kicker">THE ARCHIVE IS OPEN</span><h2>Take a closer look at the record.</h2><p>Move through monthly records and explore the history by market.</p></div><a href="{{ route('charts.index') }}">Explore monthly charts ↗</a></section>
  <div class="v5-footer-grid">
    <section>
      <a class="v5-footer-brand" href="{{ route('index') }}">
        @if (filled(setting('site_logo')) && is_file(public_path('logos/' . basename(setting('site_logo')))))
          <img src="{{ asset('logos/' . basename(setting('site_logo'))) }}" width="46" height="46" alt="{{ setting('title') }}">
        @else <span aria-hidden="true">G</span> @endif
        <span><strong>{{ setting('title') }}</strong><small>{{ setting('site_tagline') }}</small></span>
      </a>
      <p>{{ filled(setting('footer_description')) ? setting('footer_description') : 'A public destination for daily market updates and historical result records.' }}</p>
      <div class="contact">
        @if (filled(setting('email')))<a href="mailto:{{ setting('email') }}">✉ {{ setting('email') }}</a>@endif
        @if (filled(setting('contact_phone')))<a href="tel:{{ preg_replace('/[^0-9+]/', '', setting('contact_phone')) }}">☎ {{ setting('contact_phone') }}</a>@endif
        @if (filled(setting('contact_address')))<span>⌖ {{ setting('contact_address') }}</span>@endif
      </div>
    </section>
    <nav aria-label="Explore"><h2>Explore</h2><ul>
      <li><a href="{{ route('index') }}">Home</a></li><li><a href="{{ route('index') }}#today-results">Live board</a></li><li><a href="{{ route('index') }}#markets">Market directory</a></li><li><a href="{{ route('charts.index') }}">Monthly charts</a></li><li><a href="{{ route('community.index') }}">Community</a></li><li><a href="{{ route('information') }}">FAQs</a></li><li><a href="{{ route('sitemap') }}">Sitemap</a></li>
    </ul></nav>
    <nav aria-label="Information pages"><h2>Information</h2><ul>
      @foreach ($navPages ?? collect() as $navPage)<li><a href="{{ route('page', ['page' => $navPage->slug]) }}">{{ $navPage->name }} ↗</a></li>@endforeach
    </ul></nav>
    <section><h2>Stay connected</h2><p>Follow the official channels configured for {{ setting('title') }}.</p><div class="v5-socials">
      @foreach (['facebook'=>'Facebook','instagram'=>'Instagram','youtube'=>'YouTube','telegram'=>'Telegram','whatsapp'=>'WhatsApp'] as $socialKey=>$socialLabel)
        @if (filled(setting('social_'.$socialKey)) && filter_var(setting('social_'.$socialKey), FILTER_VALIDATE_URL) && in_array(parse_url(setting('social_'.$socialKey), PHP_URL_SCHEME), ['http','https'], true))
          <a href="{{ setting('social_'.$socialKey) }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $socialLabel }}">{{ ['facebook'=>'f','instagram'=>'◎','youtube'=>'▶','telegram'=>'➤','whatsapp'=>'◉'][$socialKey] }}</a>
        @endif
      @endforeach
    </div></section>
  </div>
  <div class="v5-footer-bottom"><span>{{ setting('copyright_text', '© ' . date('Y') . ' ' . setting('title')) }}</span><span>Information and historical records only · Please follow applicable laws.</span></div>
</footer>
<button class="v5-wa-float" type="button" data-bs-toggle="modal" data-bs-target="#whatsappContactModal" aria-label="Open WhatsApp contact">◉</button>
<div class="modal fade" id="whatsappContactModal" tabindex="-1" aria-labelledby="whatsappContactModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable"><div class="modal-content border-0 rounded-4 overflow-hidden">
    <div class="modal-header bg-success text-white border-0 p-4"><div><h2 class="modal-title fs-5 fw-bold mb-1" id="whatsappContactModalLabel">Game schedule</h2><p class="small mb-0">Chat with us on WhatsApp</p></div><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button></div>
    <div class="modal-body p-4"><p class="small text-uppercase fw-bold text-secondary mb-3">Market timing reference</p><div class="list-group list-group-flush">
      <div class="list-group-item d-flex justify-content-between gap-3 px-0"><span>Delhi Bazaar</span><strong>03:15 PM</strong></div>
      <div class="list-group-item d-flex justify-content-between gap-3 px-0"><span>Shree Ganesh</span><strong>04:45 PM</strong></div>
      <div class="list-group-item d-flex justify-content-between gap-3 px-0"><span>Faridabad</span><strong>06:15 PM</strong></div>
      <div class="list-group-item d-flex justify-content-between gap-3 px-0"><span>Ghaziabad</span><strong>10:00 PM</strong></div>
      <div class="list-group-item d-flex justify-content-between gap-3 px-0"><span>Gali</span><strong>11:50 PM</strong></div>
      <div class="list-group-item d-flex justify-content-between gap-3 px-0"><span>Disawar</span><strong>05:15 AM</strong></div>
    </div></div>
    <div class="modal-footer border-0 p-4 pt-0">
      @if (filled(setting('social_whatsapp')) && filter_var(setting('social_whatsapp'), FILTER_VALIDATE_URL) && in_array(parse_url(setting('social_whatsapp'), PHP_URL_SCHEME), ['http','https'], true))
        <a class="btn btn-success w-100 rounded-pill" href="{{ setting('social_whatsapp') }}" target="_blank" rel="noopener noreferrer">Contact us on WhatsApp</a>
      @else <p class="small text-secondary mb-0">WhatsApp contact details are not configured yet.</p> @endif
    </div>
  </div></div>
</div>
