# GSK Laravel customer website — complete product redesign v3

Base: `main` at `724ac8f5eea66efe4a8a1b116c1d7eaf651abdc9`
Working branch: `redesign/gsk-complete-product-v3`
Draft PR: pending creation

## First-priority frontend defect

- The merged `resources/views/frontend/market.blade.php` contained literal backslash-n sequences inside an inline JavaScript block after `if (payload.month_label)`.
- Those characters are not JavaScript line separators and make the inline script invalid. This breaks the market archive page's client-side month/year result update.
- Fixed the malformed block on the v3 branch using actual JavaScript line breaks.
- This is a confirmed market-archive script defect. It has not been proven to be the reason the homepage itself fails to open.

## Repository and deployment investigation

- Inspected the web routes, customer layout, homepage Blade view, customer header/footer and frontend controller.
- The customer layout loads Bootstrap 5.3.8 and the GSK customer stylesheet; the homepage route is `/` and market archives use `/result/{slug}`.
- The controller's default canonical URL is `https://galisattakings.com`. A public page at that domain responds, but the returned page composition/content does not match the current GSK Blade homepage in this repository. This suggests a deployment / document-root / cache / branch mismatch may exist; it is not yet proven which one.
- Need the production hosting document root, deployed Git branch/commit and server error log to conclusively diagnose any homepage-wide outage.

## Scope and protection

- The v3 code change so far is limited to the customer-facing market archive Blade view.
- No admin views/assets, controllers, routes, middleware, authentication, APIs, database schema or backend services were changed.
- Bootstrap 5.3.x remains the required framework.

## Validation still required

- [ ] Run Blade compilation and PHP syntax checks on v3.
- [ ] Run focused public-route and monthly-chart tests.
- [ ] Run the full Laravel test suite and compare failures against the exact v3 base commit.
- [ ] Browser-check homepage, market archive, monthly charts, CMS/information and community pages at mobile/tablet/desktop widths.
- [ ] Verify AJAX month/year switching after the JavaScript fix.
- [ ] Confirm cPanel document root, deployed branch/commit, rewrite rules, asset paths, cache and Laravel logs.
- [ ] Capture genuine before/after screenshots.

## Guardrails
- v3 is based on the current `main`; no changes were pushed to `main`.
- Keep Bootstrap 5.3.x; do not introduce another CSS framework.
- Do not modify the Laravel admin panel or backend business logic.
- Do not merge until the live deployment issue, regression suite and browser visual checks are resolved.
