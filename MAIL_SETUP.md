# Get OTP in Your Real Email Inbox

By default the app uses `log` — OTP is only written to `storage/logs/laravel.log`, **not sent to any mailbox**.

To receive the OTP in your **real email inbox**, set up SMTP in your `.env` using one of the options below.

---

## Option 1: Gmail (your own inbox)

1. In Gmail: **Google Account → Security → 2-Step Verification** (turn it on if needed).
2. Under **2-Step Verification**, open **App passwords** and create a new app password for "Mail".
3. In your project `.env`, set:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your.gmail@gmail.com
MAIL_PASSWORD=xxxx xxxx xxxx xxxx
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your.gmail@gmail.com
MAIL_FROM_NAME="Pinkush"
```

Use your real Gmail and the **16-character app password** (no spaces or with spaces both work).

4. Clear config and test:

```bash
php artisan config:clear
```

5. In the browser (with `APP_DEBUG=true`), open:

```
http://your-site/test-mail?email=your.gmail@gmail.com
```

If it works, you’ll see “Test OTP email sent…” and the email in your inbox (or spam). Then register again — OTP will go to the email you enter.

---

## Option 2: Mailtrap (testing only, no real inbox)

1. Sign up at [mailtrap.io](https://mailtrap.io) (free).
2. Create an inbox and copy the SMTP credentials (host, port, username, password).
3. In `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@pinkush.test
MAIL_FROM_NAME="Pinkush"
```

4. Run `php artisan config:clear` and visit `/test-mail?email=any@test.com`. The message will appear in your **Mailtrap inbox** (not in a real mailbox). Use this for testing; for real delivery use Gmail or another SMTP.

---

## Option 3: Keep using log (no inbox)

To only test without real email, keep:

```env
MAIL_MAILER=log
```

Then open `storage/logs/laravel.log` after registering and search for the 6-digit OTP or “Your Pinkush verification code”.

---

## Quick checklist

- [ ] `.env` has `MAIL_MAILER=smtp` (for Gmail or Mailtrap).
- [ ] `MAIL_USERNAME` and `MAIL_PASSWORD` are correct (Gmail = app password).
- [ ] `MAIL_FROM_ADDRESS` matches your Gmail when using Gmail.
- [ ] Run `php artisan config:clear` after changing `.env`.
- [ ] Test with `/test-mail?email=your@email.com` (only when `APP_DEBUG=true`).
