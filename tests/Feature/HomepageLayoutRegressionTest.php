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


    public function test_customer_shell_uses_bootstrap_five_and_does_not_load_the_legacy_theme(): void
    {
        $response = $this->get('/');

        $response->assertOk()
            ->assertSee('bootstrap@5.3.8/dist/css/bootstrap.min.css', false)
            ->assertSee('frontend/css/gsk-vibrant-v6.css', false)
            ->assertSee('frontend/css/gsk-premium-v8.css', false)
            ->assertDontSee('frontend/css/gsk-arcade-v7.css', false)
            ->assertDontSee('frontend/css/gsk-frontend-v4.css', false)
            ->assertDontSee('site-theme.css', false)
            ->assertDontSee('Delhi Bazaar', false)
            ->assertDontSee('03:15 PM', false)
            ->assertSame(1, substr_count($response->getContent(), 'name="theme-color"'))
            ->assertDontSee('static-home.css', false)
            ->assertDontSee('static-home.js', false)
            ->assertDontSee('bootstrap-icons', false);
    }

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
            ->assertSee('gsk-home-hero', false)
            ->assertSee('v6-hero-grid', false)
            ->assertSee('class="v6-daily-grid"', false)
            ->assertSee('class="v6-market-grid"', false)
            ->assertDontSee('v5-home-grid', false)
            ->assertSee('class="v6-mobile-dock"', false)
            ->assertDontSee('gsk-v3-result-card', false)
            ->assertDontSee('gsk-v3-taskbar', false)
            ->assertSee('id="today-results"', false)
            ->assertSee('id="markets"', false)
            ->assertSee('id="market-charts"', false);

        $html = $response->getContent();
        $heroPosition = strpos($html, 'gsk-home-hero');
        $topPosition = strpos($html, 'data-test-section="top-managed"');
        $resultsPosition = strpos($html, 'id="today-results"');
        $afterResultsPosition = strpos($html, 'data-test-section="after-results"');
        $marketsPosition = strpos($html, 'id="markets"');
        $chartsPosition = strpos($html, 'id="market-charts"');

        $this->assertNotFalse($heroPosition);
        $this->assertNotFalse($topPosition);
        $this->assertNotFalse($resultsPosition);
        $this->assertNotFalse($afterResultsPosition);
        $this->assertNotFalse($marketsPosition);
        $this->assertNotFalse($chartsPosition);
        $this->assertLessThan($topPosition, $heroPosition);
        $this->assertLessThan($resultsPosition, $topPosition);
        $this->assertLessThan($afterResultsPosition, $resultsPosition);
        $this->assertLessThan($marketsPosition, $afterResultsPosition);
        $this->assertLessThan($chartsPosition, $marketsPosition);
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
        $featuredStart = strpos($html, 'class="v6-hero-feature"');
        $dailySectionStart = strpos($html, 'id="today-results"', $featuredStart);
        $featuredHtml = substr($html, $featuredStart, $dailySectionStart - $featuredStart);
        $this->assertStringContainsString('Market Beta', $featuredHtml);
        $this->assertStringContainsString('456', $featuredHtml);
        $this->assertStringNotContainsString('Market Alpha', $featuredHtml);
    }
}
