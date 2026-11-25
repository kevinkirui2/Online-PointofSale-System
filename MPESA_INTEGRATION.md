# M-Pesa Integration – POS

## Overview

Added Safaricom Daraja STK Push to the existing POS. Cash payments still work through the original checkout. Docker is only my development environment; the integration can also run on XAMPP or WAMP.

## New files in `mpesa/`

| File | Purpose |
| --- | --- |
| `stk_push.php` | Sends the STK Push to the customer's phone and saves the pending payment. |
| `stk_callback.php` | Receives Safaricom's payment response and saves the transaction details. |
| `daraja_query.php` | Queries Safaricom to independently confirm payment. |
| `payment_status.php` | Returns the payment status to the frontend and triggers reconciliation when needed. |
| `reconcile_payment.php` | Verifies the payment and calls the sale finalizer. |
| `finalize_sale.php` | Records the sale and products, deducts stock and prevents duplicate sales using a database transaction. |
| `stk_query.php` | Terminal utility for checking an existing payment. |
| `db.php` | Database helper file; confirm its exact role in the code. The payment endpoints use `server/connection.php`. |

## Changes to existing files

### `script3.js`
- Added Cash and M-Pesa checkout options.
- Prompts for the customer's phone number when M-Pesa is selected.
- Sends the STK Push request and checks payment status approximately every three seconds.
- Prevents repeated Pay Now clicks during the current payment attempt.
- Displays the POS receipt number after successful finalization.
- Handles failed, cancelled, timed-out and uncertain payments.

### `insert_sales.php`
- Remains responsible for cash sales.
- M-Pesa sales are handled separately by `finalize_sale.php` to avoid duplicate sales and stock deductions.
- Both paths use line totals in `sales_product.price`.
- No direct edits to this file were confirmed; check the Git diff for any local changes.

### `main.php`
- Reuses the existing Pay Now button. STK Push is only initiated when the cashier selects M-Pesa during checkout.

## Database changes

- Created `mpesa_payments` to store payment requests, callback details, cart snapshots, verification status and linked receipts.
- Added `mpesa_payment_id` to `sales` with a unique constraint to prevent duplicate M-Pesa sales.
- Removed `AUTO_INCREMENT` from `sales_product.reciept_no` because the receipt number comes from `sales`.
- Backed up `sales_product` before modifying it.
- Added `verified_at`, `finalized_at` and `last_query_at` to track verification, completion and query frequency.

Use the updated SQL schema when setting up the original database. Do not repeat migrations that have already been applied.

## How M-Pesa payment works

1. Cashier adds products and clicks Pay Now.
2. Cashier selects M-Pesa and enters the phone number.
3. Customer receives the STK Push and enters their PIN on their phone.
4. Safaricom sends the callback and the POS checks payment status.
5. STK Query independently confirms the payment.
6. The POS records the sale, deducts stock and displays the receipt number.

The M-Pesa transaction code is stored when received through the callback but is not required in the success popup.

## Running without Docker

1. Place the application files in your XAMPP `htdocs` folder or WAMP web directory.
2. Import the POS database and apply the updated SQL changes if needed.
3. Configure `server/connection.php` using your local MySQL credentials. Use `localhost` or `127.0.0.1` instead of the Docker hostname `db`, and keep the MySQLi connection variable `$db`.
4. Configure the Daraja sandbox credentials, `MPESA_ENV` and `MPESA_CALLBACK_URL` using the environment variable names in the PHP files. Plain PHP does not automatically load `.env` files.
5. Use a public HTTPS tunnel or hosted endpoint for `mpesa/stk_callback.php` so Safaricom can reach it.
6. Enable PHP MySQLi, cURL and OpenSSL, start Apache and MySQL, and test checkout.

Do not commit actual Daraja credentials or database passwords to GitHub.

## Testing notes

Successful end-to-end payments have been tested. Payment 16 received a successful callback but did not finalize within the polling window. A `reported_success` payment still requires independent verification. Reconcile unresolved payments before charging the customer again.

The integration is currently for sandbox testing, not confirmed production-ready.
