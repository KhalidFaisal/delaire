# Current Stock Items Feature Walkthrough

(Previous sections remain unchanged)

### 11. Domain Link Update
Updated the footer link for Byte Engineers.
-   Replaced `https://byteengineers.me/` with `https://byteengrs.com/` in all footer templates.

### 12. Frontend Footer Update
-   **Dynamic Brand Name**: usage of `{{ optional($portfolio)->company_name }}` ensures the footer displays the Brand Name set in the Portfolio/General Settings.
-   **Byte Engineers Link**: Updated the "Designed by Byte Engineers" text to be a clickable link pointing to `https://byteengrs.com/`.

### 13. Project Optimization
Performed targeted optimizations to improve performance without changing features.
-   **StockController (Admin)**: 
    -   **Before**: Ran database queries for *every single product size* to calculate sold counts and fetch history. (100 products = ~300 queries).
    -   **After**: Fetches all necessary data in **3 queries** total and maps it in memory. Drastic speed improvement for large catalogs.
-   **ProductController (Frontend)**:
    -   Added "Eager Loading" (`with(['sizes', 'brand'])`) to search and list pages.
    -   This ensures product details (like sizes/brands) are loaded in the initial query rather than triggering separate queries for each product card.

### 14. Email Sender Name Dynamic Configuration
Updated all custom Mailables to dynamically include the application name in the sender information, matching the default Laravel password reset emails.
-   **Mailables modified**:
    -   [AdminOrderPlacedMail.php](file:///d:/Personal/website/Online_Shop_V.2.2/app/Mail/AdminOrderPlacedMail.php)
    -   [OrderStatusUpdatedMail.php](file:///d:/Personal/website/Online_Shop_V.2.2/app/Mail/OrderStatusUpdatedMail.php)
    -   [OtpMail.php](file:///d:/Personal/website/Online_Shop_V.2.2/app/Mail/OtpMail.php)
    -   [UserOrderPlacedMail.php](file:///d:/Personal/website/Online_Shop_V.2.2/app/Mail/UserOrderPlacedMail.php)
-   **Change detail**: Replaced raw `from: config('mail.from.address')` with `new Address(config('mail.from.address'), config('mail.from.name'))` to ensure the name configured in `.env`/`config/mail.php` (which is dynamically bound to `APP_NAME`) is passed correctly to the email client.

## Verification
1.  **Frontend Speed**: Browse the "Shop" or Search pages. They should load snappily even with many products.
2.  **Admin Stock Page**: Go to **Inventory -> Current Stock Items**. It should load instantly, regardless of how many products/lot numbers exist.
3.  **Email Sender Name**: Send any order placement, status update, or OTP email. Verify that the email client displays the sender name as the application name (e.g. "De Laire") instead of "no-reply".
