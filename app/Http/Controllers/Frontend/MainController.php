<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\Game;
use App\Models\HomePage;
use App\Models\Page;
use App\Models\Result;
use App\Services\AppSettingsService;
use App\Services\MonthlyChartService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function index(Request $request, MonthlyChartService $charts)
    {
        $now = now()->timezone(config('app.timezone'));
        $selectedYear = (int) $now->format('Y');
        $selectedMonth = (int) $now->format('n');
        [$monthStart, $monthEnd, $monthDays] = $charts->monthDates($selectedYear, $selectedMonth);
        $games = Game::query()
            ->where('status', 'active')
            ->orderBy('serial')
            ->orderBy('id')
            ->get(['id', 'name', 'slug', 'serial', 'result_time', 'last_result']);

        $frontendResults = $this->getFrontendResults();
        $featuredGame = collect($frontendResults)->sort(function (array $a, array $b) {
            $timestampOrder = strcmp((string) ($b['latest_result_at'] ?? ''), (string) ($a['latest_result_at'] ?? ''));

            return $timestampOrder !== 0
                ? $timestampOrder
                : ((int) $a['serial'] <=> (int) $b['serial']);
        })->first();
        $featuredGameId = $featuredGame['id'] ?? null;
        $remainingFrontendResults = collect($frontendResults)
            ->reject(fn(array $game) => $featuredGameId !== null && (int) $game['id'] === (int) $featuredGameId)
            ->values()
            ->all();
        $marketResults = collect($remainingFrontendResults)->map(fn(array $game) => collect($game)->only([
            'id',
            'name',
            'slug',
            'serial',
            'time',
            'today',
            'yesterday',
        ])->all())->all();
        $homepageSections = HomePage::query()->where('status', 'active')->orderBy('id')->get(['id', 'title', 'content', 'location', 'background'])->groupBy('location');
        $faqs = Faq::query()->where('is_active', true)->whereIn('platform', ['web', 'both'])->whereNull('page_slug')->whereNull('game_id')->orderBy('sort_order')->orderBy('id')->get(['id', 'question', 'answer']);
        $publishedResultsQuery = Result::query()
            ->where('type', 'jodi')
            ->whereNotNull('game_date')
            ->whereDate('game_date', '<=', $now->toDateString())
            ->whereNotNull('number')
            ->where('number', '<>', '')
            ->whereHas('game', fn ($query) => $query->where('status', 'active'));
        $publishedResultCount = (clone $publishedResultsQuery)->count();
        $oldestResultDate = (clone $publishedResultsQuery)->min('game_date');
        $chunkSize = $charts->chunkSize();
        $monthlyTables = $charts->homepageTables($games, $monthStart, $monthEnd, $monthDays, $chunkSize);
        $monthLabel = $charts->monthLabel($selectedYear, $selectedMonth);

        $settings = app(AppSettingsService::class);
        $siteName = trim((string) $settings->value('title', config('app.name', 'Gali Satta Kings')));
        $homeCanonical = rtrim((string) $settings->value('canonical_url', config('app.url', url('/'))), '/') . '/';
        $homeDescription = trim((string) $settings->value('homepage_meta_description', $settings->value('meta_description', 'Browse published result records and historical monthly charts.')));
        $configuredDownloadUrl = trim((string) $settings->value('app_download_url', ''));
        $appDownloadUrl = filter_var($configuredDownloadUrl, FILTER_VALIDATE_URL) && strtolower((string) parse_url($configuredDownloadUrl, PHP_URL_SCHEME)) === 'https'
            ? $configuredDownloadUrl
            : null;
        $organization = [
            '@type' => 'Organization',
            'name' => $siteName,
            'url' => $homeCanonical,
        ];
        $logoName = trim((string) $settings->value('site_logo', ''));
        if ($logoName !== '') {
            $organization['logo'] = asset('logos/' . basename($logoName));
        }
        $contactPhone = trim((string) $settings->value('contact_phone', ''));
        $contactEmail = trim((string) $settings->value('email', ''));
        if ($contactPhone !== '' || $contactEmail !== '') {
            $organization['contactPoint'] = array_filter([
                '@type' => 'ContactPoint',
                'telephone' => $contactPhone !== '' ? $contactPhone : null,
                'email' => $contactEmail !== '' ? $contactEmail : null,
                'contactType' => 'customer support',
            ]);
        }
        $homeGraph = [
            $organization,
            [
                '@type' => 'WebSite',
                'name' => $siteName,
                'url' => $homeCanonical,
            ],
            [
                '@type' => 'WebPage',
                'name' => trim((string) $settings->value('homepage_meta_title', $settings->value('meta_title', $siteName . ' | Results and Monthly Charts'))),
                'url' => $homeCanonical,
                'description' => $homeDescription,
                'isPartOf' => ['@type' => 'WebSite', 'name' => $siteName, 'url' => $homeCanonical],
            ],
        ];
        if ($faqs->isNotEmpty()) {
            $homeGraph[] = [
                '@type' => 'FAQPage',
                'mainEntity' => $faqs->map(fn($faq) => [
                    '@type' => 'Question',
                    'name' => strip_tags((string) $faq->question),
                    'acceptedAnswer' => ['@type' => 'Answer', 'text' => strip_tags((string) $faq->answer)],
                ])->values()->all(),
            ];
        }
        $homeSchema = ['@context' => 'https://schema.org', '@graph' => $homeGraph];

        return view('frontend.index', compact(
            'frontendResults',
            'games',
            'remainingFrontendResults',
            'featuredGame',
            'marketResults',
            'homepageSections',
            'appDownloadUrl',
            'faqs',
            'monthlyTables',
            'monthLabel',
            'chunkSize',
            'selectedYear',
            'selectedMonth',
            'homeSchema',
            'publishedResultCount',
            'oldestResultDate'
        ));
    }

    public function charts(Request $request, MonthlyChartService $charts)
    {
        $now = now()->timezone(config('app.timezone'));
        $filters = $request->validate([
            'game_id' => ['sometimes', 'nullable', 'integer', 'exists:games,id'],
            'year' => ['sometimes', 'nullable', 'regex:/^[0-9]{4}$/'],
            'month' => ['sometimes', 'nullable', 'integer', 'between:1,12'],
        ]);
        $year = (int) ($filters['year'] ?? $now->year);
        $month = (int) ($filters['month'] ?? $now->month);
        abort_if($year < 2000 || $year > $now->year || ($year === $now->year && $month > $now->month), 404);

        $games = Game::query()->where('status', 'active')->orderBy('serial')->orderBy('id')
            ->get(['id', 'name', 'slug', 'serial']);
        $selectedGameId = isset($filters['game_id']) && $filters['game_id'] !== '' ? (int) $filters['game_id'] : null;
        if ($selectedGameId !== null) {
            abort_unless($games->contains('id', $selectedGameId), 404);
            $games = $games->where('id', $selectedGameId)->values();
        }
        [$start, $end, $dates] = $charts->monthDates($year, $month);
        $rows = $charts->homepageRows($games, $start, $end, $dates)->groupBy(fn (array $row) => $row['date']->toDateString());
        $chartRows = $rows->map(function ($dayRows) use ($games) {
            $first = $dayRows->first();
            $values = [];
            foreach ($games as $game) {
                $item = $dayRows->firstWhere('game.id', $game->id);
                $values[$game->id] = $item['number'] ?? null;
            }

            return ['date' => $first['date'], 'values' => $values];
        })->values();
        $availableMonths = $charts->availableMonths($selectedGameId);
        $availableYears = array_values(array_unique(array_merge([(int) $now->year], array_map('intval', array_keys($availableMonths)))));
        rsort($availableYears);
        $monthLabel = $charts->monthLabel($year, $month);
        $publishedCount = Result::query()->where('type', 'jodi')
            ->whereIn('game_id', $games->pluck('id'))
            ->whereBetween('game_date', [$start->toDateString(), $end->toDateString()])
            ->whereNotNull('number')->where('number', '<>', '')
            ->whereDate('game_date', '<=', $now->toDateString())->count();

        return view('frontend.charts', compact('games', 'selectedGameId', 'year', 'month', 'availableYears', 'monthLabel', 'chartRows', 'publishedCount'));
    }

    public function chartData(Request $request, MonthlyChartService $charts): JsonResponse
    {
        $now = now()->timezone(config('app.timezone'));
        $filters = $request->validate([
            'game_id' => ['sometimes', 'nullable', 'integer', 'exists:games,id'],
            'year' => ['required', 'regex:/^[0-9]{4}$/'],
            'month' => ['required', 'integer', 'between:1,12'],
        ]);
        $year = (int) $filters['year'];
        $month = (int) $filters['month'];
        abort_if($year < 2000 || $year > $now->year || ($year === $now->year && $month > $now->month), 404);

        $games = Game::query()->where('status', 'active')->orderBy('serial')->orderBy('id')->get(['id', 'name', 'slug']);
        if (!empty($filters['game_id'])) {
            $games = $games->where('id', (int) $filters['game_id'])->values();
            abort_if($games->isEmpty(), 404);
        }
        [$start, $end, $dates] = $charts->monthDates($year, $month);
        $rows = $charts->homepageRows($games, $start, $end, $dates)->groupBy(fn (array $row) => $row['date']->toDateString());
        $payloadRows = $rows->map(function ($dayRows) use ($games) {
            $first = $dayRows->first();
            $values = [];
            foreach ($games as $game) {
                $item = $dayRows->firstWhere('game.id', $game->id);
                $values[] = [
                    'game_id' => $game->id,
                    'number' => $item['number'] ?? null,
                    'status' => $item['status'] ?? 'pending',
                ];
            }

            return ['date' => $first['date']->toDateString(), 'label' => $first['date']->format('d M'), 'values' => $values];
        })->values();

        return response()->json([
            'month_label' => $charts->monthLabel($year, $month),
            'games' => $games->map(fn (Game $game) => ['id' => $game->id, 'name' => $game->name, 'slug' => $game->slug])->values(),
            'rows' => $payloadRows,
        ])->header('Cache-Control', 'no-store');
    }

    public function market(Request $request, string $slug, MonthlyChartService $charts)
    {
        $game = Game::query()->where('status', 'active')->where('slug', $slug)->firstOrFail();
        $now = now()->timezone(config('app.timezone'));
        $year = (int) $now->format('Y');
        $month = (int) $now->format('n');

        if ($request->query('year') !== null || $request->query('month') !== null) {
            $queryData = $request->validate([
                'year' => ['sometimes', 'required', 'regex:/^[0-9]{4}$/'],
                'month' => ['sometimes', 'required', 'integer', 'between:1,12'],
            ]);
            $year = (int) ($queryData['year'] ?? $year);
            $month = (int) ($queryData['month'] ?? $month);
            abort_if($year < 2000 || $year > (int) $now->format('Y'), 404);
            abort_if($year === (int) $now->format('Y') && $month > (int) $now->format('n'), 404);
        }

        [$monthStart, $monthEnd, $monthDays] = $charts->monthDates($year, $month);
        $results = $charts->gameRows($game, $monthStart, $monthEnd, $monthDays);
        $monthLabel = $charts->monthLabel($year, $month);
        $currentYear = (int) $now->format('Y');
        $currentMonth = (int) $now->format('n');
        $availableMonths = $charts->availableMonths($game->id);
        $availableYears = array_map('intval', array_keys($availableMonths));
        $availableYears[] = $currentYear;
        $availableYears = array_values(array_unique(array_filter($availableYears, fn(int $availableYear) => $availableYear >= 2000 && $availableYear <= $currentYear)));
        rsort($availableYears);
        $canonical = rtrim((string) app(AppSettingsService::class)->value('canonical_url', config('app.url', url('/'))), '/')
            . '/result/' . $game->slug;
        $faqs = Faq::query()->where('is_active', true)->whereIn('platform', ['web', 'both'])
            ->where(function ($query) use ($game) {
                $query->where(fn ($scope) => $scope->whereNull('page_slug')->whereNull('game_id'))
                    ->orWhere('game_id', $game->id);
            })->orderBy('sort_order')->orderBy('id')->get(['id', 'question', 'answer']);

        return response()
            ->view('frontend.market', compact('game', 'year', 'month', 'monthLabel', 'results', 'canonical', 'currentYear', 'currentMonth', 'availableYears', 'faqs'))
            ->header('X-Robots-Tag', $request->query->count() > 0 ? 'noindex, follow' : 'index, follow');
    }

    /**
     * AJAX endpoint for monthly results. It deliberately returns only the
     * requested active game's date/result pairs and never renders an indexable page.
     */
    public function chart(Request $request, MonthlyChartService $charts)
    {
        $data = $request->validate([
            'slug' => ['required', 'string', 'regex:/^[A-Za-z0-9-]+$/'],
            'year' => ['required', 'regex:/^[0-9]{4}$/'],
            'month' => ['required', 'integer', 'between:1,12'],
        ]);

        $game = Game::query()->where('status', 'active')->where('slug', $data['slug'])->firstOrFail();
        $now = now()->timezone(config('app.timezone'));
        $year = (int) $data['year'];
        $month = (int) $data['month'];
        abort_if($year < 2000 || $year > (int) $now->format('Y'), 404);
        abort_if($year === (int) $now->format('Y') && $month > (int) $now->format('n'), 404);

        [$monthStart, $monthEnd, $monthDays] = $charts->monthDates($year, $month);
        $results = $charts->gameRows($game, $monthStart, $monthEnd, $monthDays);

        return response()->json([
            'game' => ['name' => $game->name, 'slug' => $game->slug],
            'year' => $year,
            'month' => $month,
            'month_label' => $charts->monthLabel($year, $month),
            'days' => $results->map(fn(array $row) => [
                'day' => $row['date']->day,
                'result' => $row['number'],
                'status' => $row['status'],
            ])->values(),
        ])->header('Cache-Control', 'no-store');
    }

    /**
     * Render an active administrator-managed CMS page linked from the homepage navigation.
     */
    public function page(string $slug)
    {
        $page = Page::query()->where('slug', $slug)->where('status', 'active')->firstOrFail();

        $faqs = Faq::query()->where('is_active', true)->whereIn('platform', ['web', 'both'])
            ->where(function ($query) use ($page) {
                $query->where(fn ($scope) => $scope->whereNull('page_slug')->whereNull('game_id'))
                    ->orWhere('page_slug', $page->slug);
            })->orderBy('sort_order')->orderBy('id')->get(['id', 'question', 'answer']);

        return view('frontend.page', compact('page', 'faqs'));
    }

    public function information(string $page)
    {
        $pageAliases = [
            'about' => 'about-us',
            'contact' => 'whatsapp',
            'terms-and-conditions' => 'terms-conditions',
        ];
        $databaseSlug = $pageAliases[$page] ?? $page;
        $databasePage = Page::query()->where('slug', $databaseSlug)->where('status', 'active')->first();
        if ($databasePage && $page !== 'faq') {
            $pageFaqs = Faq::query()->where('is_active', true)->whereIn('platform', ['web', 'both'])
                ->where(function ($query) use ($page, $databaseSlug) {
                    $query->where(fn ($scope) => $scope->whereNull('page_slug')->whereNull('game_id'))
                        ->orWhereIn('page_slug', array_values(array_unique([$page, $databaseSlug])));
                })->orderBy('sort_order')->orderBy('id')->get(['id', 'question', 'answer']);

            return view('frontend.page', ['page' => $databasePage, 'faqs' => $pageFaqs]);
        }

        $pages = [
            'about' => ['About this site', 'Gali Satta Kings organizes stored Satta King results and historical monthly charts for reference. It does not publish predictions or guarantee future outcomes.'],
            'contact' => ['Contact', (string) app(AppSettingsService::class)->value('contact_address', 'For website or data-correction questions, use the contact channel configured by the site administrator.')],
            'faq' => ['Frequently asked questions', 'Use the market board to find published results. A dash means no result is available for the selected business date. Historical charts list stored records by market and month.'],
            'privacy-policy' => ['Privacy policy', 'This site should collect only the information needed to operate its features. Consult the site operator for details about deployment-specific logs, cookies, and retention practices.'],
            'terms-and-conditions' => ['Terms and conditions', 'Information is provided for reference only. Availability and correctness can vary; users are responsible for following applicable local laws.'],
            'disclaimer' => ['Disclaimer', (string) app(AppSettingsService::class)->value('disclaimer_content', 'Historical results are records, not predictions. No result or financial gain is guaranteed. Please follow applicable local laws and make responsible choices.')],
        ];
        abort_unless(isset($pages[$page]), 404);
        [$title, $copy] = $pages[$page];
        $faqs = $page === 'faq' ? Faq::query()->where('is_active', true)->whereIn('platform', ['web', 'both'])->where(function ($query) { $query->where(fn ($scope) => $scope->whereNull('page_slug')->whereNull('game_id'))->orWhere('page_slug', 'faq'); })->orderBy('sort_order')->orderBy('id')->get() : collect();

        return view('frontend.information', compact('page', 'title', 'copy', 'faqs'));
    }

    private function getFrontendResults(): array
    {
        $now = now()->timezone(config('app.timezone'));
        $games = Game::query()->where('status', 'active')->orderBy('serial')->orderBy('id')
            ->get(['id', 'name', 'slug', 'serial', 'result_time', 'last_result']);
        $businessDates = $this->businessDatesFor($games, $now);
        $resultDates = collect($businessDates)->flatMap(fn(array $dates) => array_values($dates))->unique()->values();
        $results = Result::query()->whereIn('game_id', $games->pluck('id'))->where('type', 'jodi')
            ->whereIn('game_date', $resultDates)->orderByDesc('id')->get(['game_id', 'game_date', 'number'])->groupBy('game_id');
        $latestPublishedResults = Result::query()
            ->whereIn('game_id', $games->pluck('id'))
            ->where('type', 'jodi')
            ->whereNotNull('game_date')
            ->whereDate('game_date', '<=', $now->toDateString())
            ->whereNotNull('number')
            ->where('number', '<>', '')
            ->orderByDesc('game_date')
            ->orderByDesc('id')
            ->get(['id', 'game_id', 'game_date', 'number'])
            ->unique('game_id')
            ->keyBy('game_id');
        $latestPublishedAt = Result::query()
            ->whereIn('game_id', $games->pluck('id'))
            ->where('type', 'jodi')
            ->whereNotNull('game_date')
            ->whereDate('game_date', '<=', $now->toDateString())
            ->whereNotNull('number')
            ->where('number', '<>', '')
            ->selectRaw('game_id, MAX(COALESCE(updated_at, created_at)) as latest_published_at')
            ->groupBy('game_id')
            ->pluck('latest_published_at', 'game_id');

        return $games->map(function (Game $game) use ($results, $businessDates, $latestPublishedAt, $latestPublishedResults) {
            $gameResults = $results->get($game->id, collect());
            $dates = $businessDates[$game->id];
            $todayResult = optional($gameResults->firstWhere('game_date', $dates['today']))->number;
            $yesterdayResult = optional($gameResults->firstWhere('game_date', $dates['yesterday']))->number;
            $latestResult = $latestPublishedResults->get($game->id);

            return [
                'id' => $game->id,
                'name' => $game->name,
                'slug' => $game->slug,
                'serial' => (int) $game->serial,
                'latest_result_at' => $latestPublishedAt->get($game->id),
                'latest_result' => $latestResult && filled($latestResult->number) ? (string) $latestResult->number : '--',
                'latest_result_date' => $latestResult?->game_date,
                'time' => $game->result_time ? Carbon::parse($game->result_time)->format('h:i A') : '—',
                'today' => $todayResult !== null && $todayResult !== '' ? (string) $todayResult : '--',
                'yesterday' => $yesterdayResult !== null && $yesterdayResult !== '' ? (string) $yesterdayResult : '--',
                'last_result' => $game->last_result !== null && $game->last_result !== '' ? (string) $game->last_result : '--',
            ];
        })->values()->toArray();
    }

    private function businessDatesFor($games, Carbon $now): array
    {
        return $games->mapWithKeys(function (Game $game) use ($now) {
            $current = $game->businessDate($now);

            return [$game->id => [
                'today' => $current,
                'yesterday' => Carbon::parse($current, config('app.timezone'))->subDay()->toDateString(),
            ]];
        })->all();
    }
}
