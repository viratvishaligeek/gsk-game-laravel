# GSK Laravel website v2 redesign progress

Working branch: `redesign/gsk-product-experience-v2`
Draft PR: https://github.com/viratvishaligeek/gsk-game-laravel/pull/3

## Completed in this increment
- [x] Convert the homepage market directory to a horizontal, swipeable market explorer.
- [x] Add All / Published / Awaiting result filters.
- [x] Combine market search with result-state filters.
- [x] Add direct Markets navigation to the customer header.
- [x] Add a five-destination mobile quick-navigation dock.
- [x] Keep the changes within customer-facing frontend templates and assets.

## Validation
- [x] Blade compilation passed in GitHub Actions.
- [x] Generated PHP syntax checks passed in GitHub Actions.
- [x] Focused homepage regression tests passed in GitHub Actions.
- [ ] Full Laravel suite: 56 passed, 14 failed in the current run; failures include existing business-date, admin bidding/support, chart/public-route and support-feature tests. Establish baseline and triage separately before calling the full suite green.
- [ ] Browser testing on desktop and mobile breakpoints.
- [ ] Capture real before/after screenshots.
- [ ] Review all customer-facing inner pages and confirm no navigation regressions.
- [ ] Verify the mobile dock does not obscure floating support controls or page content.

## Admin protection
- [x] PR changed-file list contains only `resources/views/frontend/**` and `public/frontend/**`.
- [x] No admin views/assets, controllers, middleware, routes, database or API files were changed.
- [ ] Recheck the changed-file list before every further update.

## Guardrails
- Bootstrap 5.3.8 remains the frontend framework.
- Do not modify `resources/views/backend`, `public/backend`, admin controllers/routes/middleware/authentication, database or backend services.
- Do not merge to `main` until full regression and browser visual checks are reviewed.
