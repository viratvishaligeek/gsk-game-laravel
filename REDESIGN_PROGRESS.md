# GSK customer frontend redesign — v4 continuation

## Repository baseline
- Default branch: `main`
- Baseline commit: `984fc3610ac93b22e8a5102cd5c6b6087147cefa`
- Existing redesign PRs #1, #3 and #4 are already merged into `main`. PR #4's merged state supersedes its older draft/in-progress wording.
- Current continuation branch: `redesign/gsk-frontend-rebuild-2026`

## Existing customer frontend already present on main
The merged work has already replaced the customer-facing shell and page structures. It includes:
- Homepage hero, latest-result spotlight, daily results board, market search/filter directory, monthly result matrix, historical archive index, FAQ, information notice and all configured managed-homepage insertion points.
- Responsive header, announcement bar, mobile quick-navigation dock, footer, social/contact links and WhatsApp schedule modal.
- Market archive with date/month/year controls and the existing AJAX result endpoint.
- Monthly charts workspace with market/month/year filters and the existing read-only chart data endpoint.
- CMS/information pages, FAQ page and community feed, discussion detail, post creation and member login/registration.
- Original brand colors retained: navy/blue with gold accents.
- Bootstrap 5.3.8 retained; the project has no new frontend framework dependency.

## v4 continuation changes
- [x] Added `public/frontend/css/gsk-frontend-v4.css`, a separate visual refinement layer for the customer frontend. It preserves the existing navy/blue and gold brand palette, adds distinct treatments for the hero, result board, market directory, archive matrix, FAQ, document pages, community, forms, footer and mobile navigation, and includes reduced-motion and safe-area handling.
- [x] Loaded the v4 stylesheet after the existing design-system stylesheet in `resources/views/frontend/include/app.blade.php`; existing Blade selectors and behavior remain intact.
- [x] Removed duplicate viewport and theme-color metadata from the shared frontend layout; those values remain supplied by the existing metadata partial.
- [x] Rebuilt the customer-facing 404 view using the existing frontend shell and real destinations (home, charts and community).
- [x] Added responsive styling for the 404 experience.
- [x] No controller, model, service, migration, route, API, middleware, authentication, payment, admin view or backend asset was changed in this continuation.

## Frontend inventory
Customer-facing Blade templates under `resources/views/frontend`:
- `index.blade.php` — homepage and configured homepage sections.
- `market.blade.php` — individual market archive.
- `charts.blade.php` — monthly multi-market charts.
- `information.blade.php` and `page.blade.php` — FAQ and CMS content.
- `include/app.blade.php`, `include/meta.blade.php`, `include/header.blade.php`, `include/footer.blade.php` — shared customer shell, SEO, navigation and footer.
- `community/index.blade.php`, `community/show.blade.php`, `community/create.blade.php`, `community/auth.blade.php` — community journeys.
- `partial/homepage-sections.blade.php`, `partial/market-card.blade.php`, `partial/section-heading.blade.php` — reusable dynamic presentation components.
- `resources/views/errors/404.blade.php` — shared customer-facing not-found page, now styled with the customer shell.

## Existing dynamic integrations retained
- Admin-managed homepage sections and configured insertion positions.
- Database-driven markets, latest result, previous result, scheduled time and monthly matrix.
- Existing market archive POST/AJAX contract, CSRF token and response fields.
- Existing monthly chart GET data endpoint and query parameter names.
- Dynamic CMS content, FAQ content, metadata, logos, social links, contact settings and app-download URL.
- Existing community pagination, posts, replies, author data, CSRF forms, validation errors and auth endpoints.
- Existing named routes and Bootstrap collapse/accordion/modal behavior.

## Validation status
- [x] Repository tree and all customer Blade templates inventoried.
- [x] Existing brand tokens and page/section hierarchy inspected.
- [x] Protected backend areas reviewed for this continuation's changed-file scope.
- [ ] GitHub Actions Blade compilation and Laravel regression workflow for the v4 pull request.
- [ ] Browser verification at 360px, 390px, 768px, 1024px and desktop widths.
- [ ] Interactive verification of market search/filter, market archive AJAX, monthly chart filters/history, Bootstrap navigation/accordion/modal, community forms and pagination.
- [ ] Production asset loading and real-device visual check.
- [ ] Capture before/after screenshots.

## Known baseline test caveat from the previously merged v3 report
The v3 report recorded 60 passing and 10 failing tests in its full-suite run, across business-date, admin bidding/support and support-feature test classes. That report did not establish an exact `main` baseline for those failures. The v4 continuation must not label them pre-existing without a same-commit baseline comparison.

## Scope guardrails
- Do not modify `app/`, `routes/`, `database/`, `resources/views/backend/`, authentication, payment, APIs or business logic.
- Do not remove existing dynamic Blade bindings or frontend interactions.
- Do not merge or deploy until the new CI run and browser-level checks are reviewed.
