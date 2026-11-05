<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // A previous test may run the site resolver and mutate this process-wide
        // config value. Start each test from this repository's own site context.
        $defaultSiteKey = config('sites.default', 'gali-satta-kings');
        $defaultSite = config('sites.sites.' . $defaultSiteKey, config('sites.current', []));

        config([
            'sites.current_key' => $defaultSiteKey,
            'sites.current' => array_merge($defaultSite, ['key' => $defaultSiteKey]),
        ]);
    }
}
