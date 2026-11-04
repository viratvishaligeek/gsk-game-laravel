<?php

namespace Tests\Feature;

use App\Models\Game;
use App\Models\HomePage;
use App\Models\Result;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomepageLayoutRegressionTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_sections_render_once_in_configured_positions_and_inactive_sections_stay_hidden(): void
    {
        HomePage::query()->create([
            'title' => 'Top managed section',
            'content' => '<div class="container" data-test-section="top-managed">Admin HTML</div>',
            'status' => 'active',
            'location' => 'first_place',
        ]);
        HomePage::query()->create([
            'title' => 'After results section',
            'content' => '<div data-test-section="after-results">After results HTML</div>',
            'status' => 'active',
            'location' => 'second_place_another',
        ]);
        HomePage::query()->create([
            'title' => 'Hidden section',
            'content' => '<div data-test-section="hidden-managed">Must not render</div>',
            'status' => 'inactive',
            'location' => 'first_place',
        ]);

        $response = $this->get('/');

        $response->assertOk()
            ->assertSee('data-test-section="top-managed"', false)
            ->assertSee('data-test-section="after-results"', false)
            ->assertDontSee('data-test-section="hidden-managed"', false)
            ->assertSee('class="home-intro', false)
            ->assertSee('class="live-hero"', false)
            ->assertSee('class="hero"', false)
            ->assertSee('class="lrb-section"', false);

        $html = $response->getContent();
        $this->assertLessThan(strpos($html, 'class="home-intro'), strpos($html, 'class="ticker"'));
        $this->assertLessThan(strpos($html, 'class="live-hero"'), strpos($html, 'class="home-intro'));
        $this->assertLessThan(strpos($html, 'class="hero"'), strpos($html, 'class="live-hero"'));
        $this->assertLessThan(strpos($html, 'class="lrb-section"'), strpos($html, 'class="hero"'));
        $this->assertLessThan(strpos($html, 'data-test-section="top-managed"'), strpos($html, 'class="live-hero"'));
        $this->assertLessThan(strpos($html, 'class="hero"'), strpos($html, 'data-test-section="top-managed"'));
        $this->assertLessThan(strpos($html, 'class="section alt"'), strpos($html, 'data-test-section="after-results"'));
        $this->assertGreaterThan(strpos($html, 'class="lrb-section"'), strpos($html, 'data-test-section="after-results"'));
        $this->assertSame(1, substr_count($html, 'data-test-section="top-managed"'));
        $this->assertSame(1, substr_count($html, 'data-test-section="after-results"'));
    }

    public function test_featured_market_uses_latest_published_activity_instead_of_a_hardcoded_market(): void
    {
        Carbon::setTestNow(now()->startOfDay()->addHours(12));

        $olderGame = Game::query()->create([
            'name' => 'Market Alpha',
            'slug' => 'market-alpha',
            'result_time' => '14:00:00',
            'play_start' => '10:00:00',
            'play_end' => '15:00:00',
            'status' => 'active',
            'serial' => 1,
        ]);
        Result::query()->create([
            'game_id' => $olderGame->id,
            'game_date' => now()->toDateString(),
            'type' => 'jodi',
            'number' => '123',
        ]);

        Carbon::setTestNow(now()->addHour());
        $latestGame = Game::query()->create([
            'name' => 'Market Beta',
            'slug' => 'market-beta',
            'result_time' => '16:30:00',
            'play_start' => '12:00:00',
            'play_end' => '18:00:00',
            'status' => 'active',
            'serial' => 2,
        ]);
        Result::query()->create([
            'game_id' => $latestGame->id,
            'game_date' => now()->toDateString(),
            'type' => 'jodi',
            'number' => '456',
        ]);
        Carbon::setTestNow();

        $response = $this->get('/');

        $response->assertOk()
            ->assertSee('Market Beta')
            ->assertSee('456')
            ->assertSee('/result/market-beta');

        $html = $response->getContent();
        $featuredStart = strpos($html, 'class="live-hero"');
        $featuredEnd = strpos($html, '<section class="hero"', $featuredStart);
        $featuredHtml = substr($html, $featuredStart, $featuredEnd - $featuredStart);
        $this->assertStringContainsString('Market Beta', $featuredHtml);
        $this->assertStringContainsString('456', $featuredHtml);
        $this->assertStringNotContainsString('Market Alpha', $featuredHtml);
    }
}
