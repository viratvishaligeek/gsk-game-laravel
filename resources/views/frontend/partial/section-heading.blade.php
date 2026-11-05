<header class="v5-section-heading {{ $class ?? '' }}">
  <div class="v5-section-heading-copy">
    <p class="v5-kicker">{{ $eyebrow ?? 'RESULT DIRECTORY' }}</p>
    <h2>{!! $title !!}</h2>
    @isset($description)<p>{{ $description }}</p>@endisset
  </div>
  @isset($action)<a class="v5-section-heading-action" href="{{ $action['url'] }}">{{ $action['label'] }} <span aria-hidden="true">↗</span></a>@endisset
</header>
