# GSK Laravel website v2 redesign progress

Working branch: `redesign/gsk-product-experience-v2`
Draft PR: https://github.com/viratvishaligeek/gsk-game-laravel/pull/3

## Completed implementation

### Homepage and navigation
- [x] Homepage market directory is a horizontal, swipeable market explorer rather than the old vertical market-card list.
- [x] Added All / Published / Awaiting filters that combine with market-name search.
- [x] Added direct Markets navigation in the customer header.
- [x] Added a five-destination mobile quick-navigation dock for Home, Results, Markets, Charts and Community.
- [x] Existing homepage hierarchy, featured result content, results, archive and FAQ sections retained from the earlier customer redesign; the current branch has been reviewed against those sections.

### Customer-facing inner pages
- [x] Market archive: added contextual links to monthly charts, other markets and community.
- [x] Market archive AJAX: month label now updates with the fetched response rather than remaining stale after period changes.
- [x] Monthly charts: added contextual links to today's results, market directory and community.
- [x] Dynamic information pages: added routes to current results, charts and community.
- [x] Information/FAQ page: added contextual discovery navigation.
- [x] Community index and discussion detail remain separate feed/detail journeys; detail now links back to discussions and post creation.
- [x] Community post creation now explains the Topic → Title → Message authoring flow.
- [x] Community member login/registration now includes clearer entry guidance.
- [x] Footer now has a task-oriented quick-navigation rail for Today's results, Markets, Monthly charts and Community; existing contact/social links and Bootstrap modal interactions remain present.

### Framework and resources
- [x] Bootstrap 5.3.8 remains the frontend framework.
- [x] New styling extends the existing customer design-system stylesheet; no competing CSS framework was introduced.
- [x] All modifications are limited to customer-facing Blade templates and `public/frontend/css/gsk-design-system.css`.

## Validation status
- [x] Blade compilation passed after the current view updates.
- [x] PHP syntax and focused frontend layout regression checks passed on the earlier v2 commits.
- [x] Latest full GitHub Actions run completed: Blade compilation, generated PHP syntax and focused frontend layout regression checks passed; full suite remains failed as documented below.
- [x] Full Laravel suite is not green: latest run `41912392862` reported 56 passed / 14 failed (246 assertions). Failures are in `DisawarBusinessDateBoundaryTest`, `AdminBidAndSupportChatEnhancementTest`, `MonthlyChartsPageTest`, `PublicFrontendRoutesTest` and `SupportFeaturesRegressionTest`. These same test classes failed in the earliest v2 CI run too. Their test files and `routes/web.php` / `routes/api.php` are blob-identical to `main`, and this PR changes no backend/admin code. A dedicated test run on exact base commit `4457c6b` was not available, so a strict main-baseline comparison remains outstanding.
- [ ] Browser test homepage, market archives, charts, community and CMS pages at desktop and mobile widths.
- [ ] Verify AJAX market/month switching, header collapse, dock anchors and footer modal.
- [ ] Capture genuine before/after screenshots.

## Admin and backend protection
- [x] Changed files are customer frontend Blade templates and customer frontend CSS only.
- [x] No `resources/views/backend`, `public/backend`, admin controllers/assets, middleware, routes, authentication, database, API routes or backend services changed.
- [ ] Recheck changed-file list before further updates.

## Guardrails
- Do not modify Laravel admin or backend resources.
- Keep Bootstrap 5.3.8.
- Do not merge to `main` until latest CI, test baseline and browser visual review are complete.
