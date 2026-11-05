<?php

namespace Tests\Feature;

use App\Models\Banner;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BannerDestinationApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_games_api_returns_the_admin_configured_banner_destination(): void
    {
        $user = User::factory()->create(['status' => 'active']);

        Banner::query()->create([
            'name' => 'App promotion',
            'image' => 'banners/app-promotion.webp',
            'url' => 'https://example.org/promotion',
            'status' => 'active',
        ]);

        $this->actingAs($user, 'sanctum')
            ->getJson('/api/v1/games/list')
            ->assertOk()
            ->assertJsonPath('banners.0.url', 'https://example.org/promotion');
    }
}
