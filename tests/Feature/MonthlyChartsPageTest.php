<?php

namespace Tests\Feature;

use App\Models\Game;
use App\Models\Result;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MonthlyChartsPageTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    public function test_chart_data_filters_by_game_month_and_year(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-10-27 12:00:00', config('app.timezone')));

        $first = $this->createGame('Chart Market A', 'chart-market-a', 1);
        $second = $this->createGame('Chart Market B', 'chart-market-b', 2);
        Result::query()->create(['game_id' => $first->id, 'game_date' => '2026-10-03', 'type' => 'jodi', 'number' => '42']);
        Result::query()->create(['game_id' => $second->id, 'game_date' => '2026-10-03', 'type' => 'jodi', 'number' => '81']);
        Result::query()->create(['game_id' => $first->id, 'game_date' => '2025-10-03', 'type' => 'jodi', 'number' => '17']);

        $this->getJson(route('charts.data', ['game_id' => $first->id, 'year' => 2026, 'month' => 10]))
            ->assertOk()
            ->assertJsonPath('month_label', 'October 2026')
            ->assertJsonPath('games.0.id', $first->id)
            ->assertJsonPath('rows.2.values.0.number', '42')
            ->assertJsonPath('rows.2.values.0.status', 'published');

        $this->getJson(route('charts.data', ['year' => 2025, 'month' => 10]))
            ->assertOk()
            ->assertJsonPath('month_label', 'October 2025')
            ->assertJsonPath('rows.2.values.0.number', '17')
            ->assertJsonPath('rows.2.values.1.number', null);
    }

    public function test_homepage_renders_database_backed_games_and_result_counts(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-10-27 12:00:00', config('app.timezone')));
        $game = $this->createGame('Homepage Test Market', 'homepage-test-market', 1);
        Result::query()->create(['game_id' => $game->id, 'game_date' => '2026-10-27', 'type' => 'jodi', 'number' => '64']);

        $response = $this->get(route('index'));

        $response->assertOk()
            ->assertSee('Homepage Test Market')
            ->assertSee('64')
            ->assertSee('Published result records');
    }

    private function createGame(string $name, string $slug, int $serial): Game
    {
        return Game::query()->create([
            'name' => $name,
            'slug' => $slug,
            'result_time' => '18:00:00',
            'play_start' => '00:00:00',
            'play_end' => '23:59:59',
            'status' => 'active',
            'serial' => $serial,
            'reward' => 1,
        ]);
    }
}
