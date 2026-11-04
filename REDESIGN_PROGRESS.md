# GSK Laravel customer website — complete product redesign v3

Base: `main` at `724ac8f5eea66efe4a8a1b116c1d7eaf651abdc9`
Working branch: `redesign/gsk-complete-product-v3`
Draft PR: https://github.com/viratvishaligeek/gsk-game-laravel/pull/4

## First-priority frontend defect

- The merged `resources/views/frontend/market.blade.php` contained literal backslash-n sequences inside an inline JavaScript block after `if (payload.month_label)`.
- Those characters are not JavaScript line separators and make the inline script invalid, breaking the market archive page's client-side month/year result update.
- Fixed the malformed block on the v3 branch using actual JavaScript line breaks.
- The market archive defect is confirmed. It has not been proven to be the cause of a homepage-wide loading failure.

## Implemented in v3

### Homepage and customer navigation
- [x] Recomposed the homepage into a live-result hero, latest-market spotlight, daily result board, market directory, monthly record room, historical archive, FAQ and information notice.
- [x] Kept the admin-managed homepage section locations and existing Blade partials.
- [x] Rebuilt the desktop/mobile header with a new brand treatment, live-board navigation, Bootstrap collapse menu and announcement ticker.
- [x] Rebuilt the footer with a dedicated chart CTA, responsive information columns, configured contact/social links and the existing WhatsApp contact modal.
- [x] Aligned navigation anchors with the new homepage section IDs.

### Customer-facing pages
- [x] Market archive: new record-room hero, period controls, date-wise result table and archive navigation; existing AJAX endpoint and month/year behavior retained.
- [x] Monthly records: rebuilt the homepage's date-by-market record room and individual market archive pages; existing market AJAX endpoint and month/year behavior retained.
- [x] Verified current web routes: there is no registered `charts.index` / `charts.data` public route. New navigation points to the existing homepage monthly-record anchor and per-market archives; no backend route or new API was invented. The standalone `charts.blade.php` remains unchanged because no current public route serves it.
- [x] Information and CMS pages: new editorial hero, document layout, contextual navigation and responsive CMS content.
- [x] Community feed: new community hero, live post/reply counts, discussion cards and recent-reply previews.
- [x] Community detail: dedicated original-post panel, reply thread and reply composer.
- [x] Community creation: topic/title/message authoring steps and clearer approval notice.
- [x] Community login/registration: dedicated member access presentation; existing routes, CSRF and form actions retained.

### Framework and protection
- [x] Bootstrap 5.3.8 remains the frontend framework; no competing UI framework added.
- [x] Changes are limited to customer-facing Blade templates, customer frontend CSS and this progress document.
- [x] No admin views/assets, controllers, routes, middleware, authentication rules, APIs, database schema or backend services were changed.

## Deployment investigation
- The frontend controller's fallback canonical URL is `https://galisattakings.com`.
- That public domain responds, but the page returned does not match the current GSK Blade homepage in this repository. A deployment / document-root / cache / branch mismatch is possible; the exact cause is not confirmed.
- Need the production hosting document root, deployed Git branch/commit, rewrite configuration and server error log to diagnose any homepage-wide outage conclusively.

## Validation still required
- [x] Blade compilation and PHP syntax checks passed on the first v3 CI run.
- [x] Removed invalid `charts.index` navigation calls and redirected them to the registered homepage monthly-record anchor / market archives.
- [x] Restored the existing `gsk-home-hero` selector required by the homepage regression test while retaining the v3 visual styling.
- [ ] Rerun focused frontend regression tests after the section-order correction.
- [ ] Run focused public-route and market-archive AJAX tests.
- [ ] Run the full Laravel test suite after the route-link correction and compare failures against the exact v3 base commit.
- [ ] Browser-check homepage, market archive, charts, information/CMS and community pages at mobile/tablet/desktop widths.
- [ ] Verify market and chart AJAX filters after the markup changes.
- [ ] Confirm cPanel document root, deployed branch/commit, rewrite rules, asset paths, cache and Laravel logs.
- [ ] Capture genuine before/after screenshots.

## Guardrails
- v3 is based on current `main`; no changes were pushed to `main`.
- Keep Bootstrap 5.3.x.
- Do not modify Laravel admin or backend resources.
- Do not merge until the live deployment issue, regression suite and browser visual checks are resolved.
