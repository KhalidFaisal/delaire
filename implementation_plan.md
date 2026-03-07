# Refactor Inline CSS Colors

## Goal
Replace all hardcoded inline CSS colors in the frontend (`resources/views/main_view`) with:
-   `var(--primary-color)`
-   `var(--secondary-color)`
-   `black`
-   `white`

## Scope
-   Directory: `d:\Personal\website\Pinkush\resources\views\main_view`
-   Target: Inline `style="..."` attributes containing `color` or `background-color`.

## Strategy
1.  **Identify**: Use grep to find all occurrences.
2.  **Categorize**:
    -   Red/Pink/Brand colors -> `var(--primary-color)` or `var(--secondary-color)` (Context dependent: usually Pinkush uses pink/red as primary).
    -   Dark text -> `black` or `var(--text-color)` if available, otherwise `black`.
    -   Light text/bg -> `white`.
3.  **Execute**: Replace individually or in bulk where safe.

## Files to Check (Likely Candidates)
-   `footer.blade.php` (Saw some styles there earlier)
-   `header.blade.php`
-   `index.blade.php`
-   `shop/product` blades.

## Notes
-   Be careful with dynamic styles (e.g., `background-image`).
-   Verify if `var(--primary-color)` is actually defined in the CSS (assumed yes based on previous tasks).
