# Current Stock Items Feature Walkthrough

(Previous sections remain unchanged)

### 11. Domain Link Update
Updated the footer link for Byte Engineers.
-   Replaced `https://byteengineers.me/` with `https://byteengrs.com/` in all footer templates.

### 12. Frontend Footer Update
-   **Dynamic Brand Name**: usage of `{{ optional($portfolio)->company_name }}` ensures the footer displays the Brand Name set in the Portfolio/General Settings.
-   **Byte Engineers Link**: Updated the "Designed by Byte Engineers" text to be a clickable link pointing to `https://byteengrs.com/`.

## Verification
1.  **Footer Link**: Scroll to the bottom of the frontend (user view).
2.  **Brand Name**: Ensure it displays the Company Name set in the backend (Portfolio settings).
3.  **Link Click**: Click "Byte Engineers". It should open `https://byteengrs.com/` in a new tab.
