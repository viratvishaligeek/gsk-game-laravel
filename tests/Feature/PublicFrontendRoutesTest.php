<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class PublicFrontendRoutesTest extends TestCase
{
    public function test_public_frontend_pages_and_data_routes_are_registered(): void
    {
        foreach ([
            'index',
            'charts.index',
            'charts.data',
            'frontend.market',
            'frontend.page',
            'information',
            'community.index',
            'community.show',
            'community.create',
            'community.store',
            'robots',
            'sitemap',
        ] as $routeName) {
            $this->assertTrue(Route::has($routeName), "Expected route [{$routeName}] to be registered.");
        }
    }

    public function test_monthly_chart_data_endpoint_is_read_only_and_rate_limited(): void
    {
        $route = Route::getRoutes()->getByName('charts.data');

        $this->assertNotNull($route);
        $this->assertContains('GET', $route->methods());
        $this->assertContains('throttle:60,1', $route->middleware());
    }

    public function test_public_destructive_maintenance_endpoints_are_not_registered(): void
    {
        $unsafeUris = collect(Route::getRoutes()->getRoutes())
            ->filter(fn ($route) => preg_match('/(^|\/)(migrate(?:-fresh)?|optimize-clear|storage-link|seed|reset-permissions)(\/|$)/i', $route->uri()))
            ->pluck('uri')
            ->values();

        $this->assertSame([], $unsafeUris->all());
    }
}
