# GSK customer frontend — structural rebuild v5

## Working branch
- Branch: `redesign/gsk-frontend-rebuild-2026`
- The earlier PR #5 was merged to `main`; this branch continues from that merged redesign history.
- This v5 work is a separate structural reconstruction, not a continuation of the old v4 CSS styling approach.

## Structural redesign implemented
- [x] New standalone presentation system: `public/frontend/css/gsk-structural-v5.css`.
- [x] Shared shell now loads Bootstrap 5 and the new v5 stylesheet; the old v4 stylesheet is no longer loaded.
- [x] Rebuilt header DOM: brand block, separate top strip, primary navigation list, custom mobile menu toggle and announcement ticker.
- [x] Rebuilt homepage DOM: asymmetric hero and featured-result stage, quick destination rail, daily result register, split market explorer, historical matrix, archive index, editorial FAQ and disclaimer strip.
- [x] Rebuilt dynamic market card markup and admin-managed homepage section wrapper.
- [x] Rebuilt market archive DOM into a market-period control rail and separate date-wise record workspace.
- [x] Rebuilt monthly charts DOM into a distinct chart hero, filter console and output workspace.
- [x] Rebuilt information and CMS page layouts into editorial document layouts with contextual side navigation.
- [x] Rebuilt community feed into a numbered discussion stream with separate community navigation.
- [x] Rebuilt discussion detail into a post-and-replies reading column plus a separate reply composer.
- [x] Rebuilt discussion creation into an instruction rail and dedicated authoring sheet.
- [x] Rebuilt login/registration into a split-screen community access experience.
- [x] Rebuilt the 404 page into a large typographic error composition.
- [x] Updated homepage regression assertions to test the new structural composition and verify that old repeated homepage result-card/taskbar markup is absent.

## Dynamic and interaction contracts preserved
- Homepage database-driven results, featured market, previous result, schedule, game list, monthly tables, FAQs, homepage settings and schema data.
- Admin-managed homepage content locations: first_place, second_place, second_place_another, third_place, third_place_another, before_footer and after_footer.
- Existing homepage anchors: today-results, markets, monthly-records and market-charts.
- Existing market search/filter selectors and result-state data attributes.
- Market archive POST endpoint, CSRF header, request payload (slug/year/month), response fields (days/day/result/month_label), abort handling and live status.
- Monthly chart GET endpoint, query parameter names (game_id/year/month), response fields, history navigation and live table updates.
- Existing community routes, post/reply data, pagination, author information, CSRF fields, validation display and form limits.
- Existing metadata, canonical/robots output, Open Graph/Twitter metadata and structured data.
- Existing WhatsApp contact modal, configured contact/social links and download URL.

## Backend boundary
No changes in this v5 work to controllers, models, services, migrations, database, routes, APIs, middleware, authentication logic, payment/transaction logic, game logic, admin views or backend assets. No new dependency was introduced.

## Changed frontend files
- `public/frontend/css/gsk-structural-v5.css` (new)
- `resources/views/frontend/include/app.blade.php`
- `resources/views/frontend/include/header.blade.php`
- `resources/views/frontend/include/footer.blade.php`
- `resources/views/frontend/index.blade.php`
- `resources/views/frontend/market.blade.php`
- `resources/views/frontend/charts.blade.php`
- `resources/views/frontend/information.blade.php`
- `resources/views/frontend/page.blade.php`
- `resources/views/frontend/community/index.blade.php`
- `resources/views/frontend/community/show.blade.php`
- `resources/views/frontend/community/create.blade.php`
- `resources/views/frontend/community/auth.blade.php`
- `resources/views/frontend/community/partials/game-summary.blade.php`
- `resources/views/frontend/community/partials/posts.blade.php`
- `resources/views/frontend/partial/section-heading.blade.php`
- `resources/views/frontend/partial/homepage-sections.blade.php`
- `resources/views/frontend/partial/market-card.blade.php`
- `resources/views/errors/404.blade.php`
- `tests/Feature/HomepageLayoutRegressionTest.php`

## Validation still required
- [ ] GitHub Actions Blade compilation and frontend regression tests for this v5 commit.
- [ ] Full Laravel suite and comparison with a same-commit `main` baseline for unrelated failures.
- [ ] Browser-level functional testing of search/filter, market AJAX, chart AJAX/history, mobile navigation, FAQ accordion, WhatsApp modal, community forms and pagination.
- [ ] Genuine before/after screenshots for homepage, market archive, charts, information/CMS, community feed and discussion detail at desktop and mobile sizes.
- [ ] Confirm production asset paths and check for console errors/overflow at 360px, 390px, 768px, 1024px and desktop.
- [ ] Review all rendered sections with real database content, including every managed homepage insertion point.

## Important screenshot limitation
The current GitHub integration can edit and validate repository files but does not provide a browser session or screenshot capture tool. Do not fabricate before/after screenshots or claim visual browser verification. Capture the required screenshots in a real browser once an accessible preview/deployment is available.

## Merge policy
Do not merge or deploy the v5 PR until CI, browser interaction tests and genuine before/after screenshots have been reviewed.