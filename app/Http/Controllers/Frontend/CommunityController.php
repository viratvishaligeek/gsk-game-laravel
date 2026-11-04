<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\CommunityPost;
use App\Models\CommunityReply;
use Carbon\Carbon;
use App\Models\Game;
use App\Services\AppSettingsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CommunityController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        $filters = $request->validate([
            'game_id' => ['sometimes', 'nullable', 'integer', Rule::exists('games', 'id')->where(fn ($query) => $query->where('status', 'active')->whereNull('deleted_at')->where('site_key', config('sites.current_key', config('sites.default', 'galisattakings'))))],
            'year' => ['sometimes', 'nullable', 'integer', 'between:2000,' . now()->timezone(config('app.timezone'))->year],
            'month' => ['sometimes', 'nullable', 'integer', 'between:1,12'],
        ]);

        $filteredPosts = CommunityPost::query()
            ->where('status', 'approved')
            ->when(!empty($filters['game_id']), fn ($builder) => $builder->where('game_id', $filters['game_id']))
            ->when(!empty($filters['year']), fn ($builder) => $builder->whereYear('created_at', $filters['year']))
            ->when(!empty($filters['month']), fn ($builder) => $builder->whereMonth('created_at', $filters['month']));

        $posts = (clone $filteredPosts)
            ->with([
                'game:id,name,slug',
                'author:id,name',
                'replies' => function ($query) {
                    $query->with('author:id,name')->latest('created_at')->limit(3);
                },
            ])
            ->withCount('replies')
            ->latest('created_at')
            ->paginate(15)
            ->withQueryString();

        $approvedCount = (clone $filteredPosts)->count();
        $replyCount = CommunityReply::query()
            ->whereHas('post', function ($builder) use ($filters) {
                $builder->where('status', 'approved')
                    ->when(!empty($filters['game_id']), fn ($query) => $query->where('game_id', $filters['game_id']))
                    ->when(!empty($filters['year']), fn ($query) => $query->whereYear('community_posts.created_at', $filters['year']))
                    ->when(!empty($filters['month']), fn ($query) => $query->whereMonth('community_posts.created_at', $filters['month']));
            })
            ->count();

        $games = Game::query()->where('status', 'active')->orderBy('serial')->orderBy('name')->get(['id', 'name']);
        $gameCounts = (clone $filteredPosts)
            ->selectRaw('game_id, COUNT(*) as posts_count')
            ->groupBy('game_id')
            ->pluck('posts_count', 'game_id');
        $gameSummaries = $games->map(fn (Game $game) => [
            'id' => $game->id,
            'name' => $game->name,
            'posts_count' => (int) $gameCounts->get($game->id, 0),
        ])->filter(fn (array $summary) => $summary['posts_count'] > 0)
            ->sortByDesc('posts_count')
            ->values();
        $gameCount = $gameSummaries->count();
        $maxPosts = max(1, (int) $gameSummaries->max('posts_count'));

        $currentYear = (int) now()->timezone(config('app.timezone'))->format('Y');
        $oldestPostDate = CommunityPost::query()->where('status', 'approved')->min('created_at');
        $oldestYear = $oldestPostDate ? (int) Carbon::parse($oldestPostDate)->year : $currentYear;
        $years = $oldestYear <= $currentYear ? range($currentYear, $oldestYear) : [$currentYear];

        if ($request->expectsJson()) {
            return response()->json([
                'html' => view('frontend.community.partials.posts', compact('posts'))->render(),
                'summary_html' => view('frontend.community.partials.game-summary', compact('gameSummaries', 'maxPosts'))->render(),
                'stats' => ['posts' => $approvedCount, 'replies' => $replyCount, 'games' => $gameCount],
            ]);
        }

        return view('frontend.community.index', compact(
            'posts', 'games', 'filters', 'years', 'approvedCount', 'replyCount', 'gameCount', 'gameSummaries', 'maxPosts'
        ));
    }

    public function create(): View
    {
        $games = Game::query()->where('status', 'active')->orderBy('serial')->orderBy('name')->get(['id', 'name']);

        return view('frontend.community.create', compact('games'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'game_id' => ['required', 'integer', Rule::exists('games', 'id')->where(fn($query) => $query->where('status', 'active')->whereNull('deleted_at')->where('site_key', config('sites.current_key', config('sites.default', 'galisattakings'))))],
            'title' => ['required', 'string', 'min:4', 'max:120'],
            'body' => ['required', 'string', 'min:10', 'max:5000'],
        ]);

        $title = trim(strip_tags($data['title']));
        $body = trim(strip_tags($data['body']));
        if ($title === '' || $body === '') {
            return back()->withInput()->withErrors(['body' => 'Please enter a title and meaningful written content.']);
        }

        $policy = app(AppSettingsService::class)->value('community_post_approval', 'manual');
        $status = $policy === 'automatic' ? 'approved' : 'pending';
        $post = CommunityPost::create([
            'game_id' => (int) $data['game_id'],
            'user_id' => Auth::guard('web')->id(),
            'title' => $title,
            'body' => $body,
            'status' => $status,
        ]);

        return $status === 'approved'
            ? redirect()->route('community.show', $post)->with('success', 'Your post has been published.')
            : redirect()->route('community.index')->with('success', 'Your post was submitted for administrator approval.');
    }

    public function show(int $post): View
    {
        $post = CommunityPost::query()
            ->where('status', 'approved')
            ->with(['game:id,name,slug', 'author:id,name'])
            ->withCount('replies')
            ->findOrFail($post);
        $replies = $post->replies()->with('author:id,name')->paginate(20);

        return view('frontend.community.show', compact('post', 'replies'));
    }

    public function reply(Request $request, int $post): RedirectResponse
    {
        $post = CommunityPost::query()->where('status', 'approved')->findOrFail($post);
        $data = $request->validate(['body' => ['required', 'string', 'min:2', 'max:2000']]);
        $body = trim(strip_tags($data['body']));
        if ($body === '') {
            return back()->withInput()->withErrors(['body' => 'Reply content cannot be empty.']);
        }

        $post->replies()->create([
            'user_id' => Auth::guard('web')->id(),
            'body' => $body,
        ]);

        return redirect()->route('community.show', $post->id)->with('success', 'Your reply was added.');
    }
}
