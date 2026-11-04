# GSK Laravel customer website — complete product redesign v3

Base: `main` at `724ac8f5eea66efe4a8a1b116c1d7eaf651abdc9`
Working branch: `redesign/gsk-complete-product-v3`
Draft PR: https://github.com/viratvishaligeek/gsk-game-laravel/pull/4

## Implemented in v3

### First-priority market archive fix
- [x] Fixed literal backslash-n sequences in the existing market archive inline JavaScript. The malformed characters made the script invalid and broke the month/year result-label update.
- [x] Kept the existing `frontend.chart` endpoint, payload and business-date result service unchanged.

### Homepage, header and footer
- [x] Recomposed the homepage into a live-result hero, latest-market spotlight, daily result board, market directory, monthly record room, historical archive, FAQ and information notice.
- [x] Preserved admin-managed homepage section locations and existing Blade partials, including the configured order around results and markets.
- [x] Preserved legacy anchors and the `gsk-home-hero` selector while applying the new visual design.
- [x] Rebuilt the responsive Bootstrap header with brand presentation, desktop/mobile navigation, Bootstrap collapse and announcement ticker.
- [x] Rebuilt the footer with a chart CTA, responsive information columns, configured contact/social links and the existing WhatsApp contact modal.

### Customer-facing pages
- [x] Market archive: new record-room hero, period controls, date-wise result table and contextual navigation; existing AJAX behavior retained.
- [x] Monthly Charts: added the missing public read-only chart page and data endpoint using the existing `MonthlyChartService`. The page renders a date-by-market matrix; the JSON endpoint returns month label, markets, date rows, numbers and statuses.
- [x] Added the `frontend.page` alias while retaining the existing `page` route name.
- [x] Added a public `information` route for the FAQ information view; non-FAQ slugs resolve through the existing active CMS page handler.
- [x] Information and CMS pages: new editorial hero, document layout, contextual navigation and responsive CMS content.
- [x] Community feed: new community hero, live post/reply counts, discussion cards and recent-reply previews.
- [x] Community detail: original-post panel, reply thread and reply composer.
- [x] Community creation: topic/title/message authoring steps and clearer approval notice.
- [x] Community login/registration: dedicated member access presentation; existing routes, CSRF and form actions retained.

### Security and scope
- [x] Removed the unauthenticated public `/optimize-clear` GET route, which exposed application cache clearing.
- [x] Bootstrap 5.3.8 remains the frontend framework; no competing UI framework added.
- [x] No admin views/assets, authentication rules, payment processing, existing API contracts, database schema, game rules or financial calculations changed.
- [x] Controller additions are read-only chart view/data actions using the existing chart service plus a public FAQ view action. Route additions are customer-facing chart and information routes plus a CMS alias. No admin route was added or modified.

## Deployment investigation
- The frontend controller's fallback canonical URL is `https://galisattakings.com`; my latest public fetch returned HTTP 502. This fetch result alone does not prove every visitor receives a 502.
- `public/index.php` loads `../vendor/autoload.php` and `../bootstrap/app.php`; `public/.htaccess` contains standard Laravel front-controller rewrites.
- There is no root-level `.htaccess` in this repository. The cPanel domain document root must point to the Laravel project's `public/` directory.
- The exact production cause still needs the actual cPanel document root, deployed branch/commit, PHP-FPM/Apache error log and `storage/logs/laravel.log`.

## Validation
- [x] Blade compilation and PHP syntax checks passed in CI runs #60, #66, #69, #71 and #73.
- [x] Focused homepage layout assertions passed after restoring the legacy hero selector and base managed-section order.
- [x] Full suite in CI #73: 55 passed / 15 failed. That run preceded the new chart route/controller actions and public maintenance-route removal, so those route-related failures require a fresh run.
- [ ] Rerun monthly chart route/data tests and public frontend route tests after the route additions.
- [ ] Rerun the full Laravel suite; compare any remaining failures against the exact base commit before attributing them.
- [ ] Browser-test homepage, charts, market archive, information/CMS and community pages at mobile/tablet/desktop widths.
- [ ] Verify chart and market AJAX filters and responsive tables.
- [ ] Confirm production document root, deployed branch/commit, rewrite rules, asset paths, cache and Laravel logs.
- [ ] Capture genuine before/after screenshots.

## Guardrails
- v3 is based on current `main`; no changes were pushed to `main`.
- No admin code was touched.
- No database schema, existing API contract, auth rule, game rule or financial logic was changed.
- Do not merge until the live deployment issue, regression suite and browser visual checks are resolved.
