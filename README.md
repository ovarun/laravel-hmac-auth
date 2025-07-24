# 🔐 Laravel HMAC Auth Package

A secure, stateless HMAC authentication module for Laravel APIs — ideal for internal services, partner integrations, and multi-platform API consumers (Angular, .NET, Python, Drupal).

---

## 📦 Requirements

- PHP 8.0+
- Laravel 10.x
- Composer
- `.env` write access (optional)
- OpenSSL / `hash_pbkdf2` support

---

## 🚀 Installation

1. **Add repository to `composer.json`:**

```json
"repositories": [
  {
    "type": "vcs",
    "url": "https://bitbucket.org/ovarun/laravel-hmac-auth.git"
  }
]
```

2. **Require the package:**

```bash
composer require ovarun/laravel-hmac-auth
```

3. **Publish configuration and migration:**

```bash
php artisan vendor:publish --tag=hmac-auth
php artisan migrate
```

---

## ⚙️ Initial Setup

Run the interactive setup command to generate secure keys:

```bash
php artisan hmac:setup
```

This adds to your `.env`:

```
HMAC_SECRET_GENERATOR_KEY='...'
HMAC_SECRET_GENERATOR_SALT='...'
```

These values are used to deterministically generate HMAC client secrets.

---

## 🔐 Register HMAC Clients

Run the command to add a client:

```bash
php artisan hmac:client-create
```

It will ask for:
- **Client Name**
- (Optional) **Client ID**

Automatically:
- Sanitizes the `client_id`
- Generates a 256-bit secret using PBKDF2
- Stores in `hmac_clients` table

---

## 🛡 Middleware Usage

Protect your API routes by applying the middleware:

### Register in `Kernel.php`:

```php
'verify.hmac' => \Ovarun\HmacAuth\Http\Middleware\VerifyHmacSignature::class,
```

### Apply to Routes:

```php
Route::middleware('verify.hmac')->group(function () {
    Route::post('/api/secure-endpoint', [ApiController::class, 'handle']);
});
```

---

## 📤 Client Authentication

Clients must send headers:

```
X-CLIENT-ID: partner-app-1
X-TIMESTAMP: 2025-07-24T14:05:00Z
X-SIGNATURE: HMAC_SHA256(timestamp + method + path + body)
```

Signature is calculated as:

```php
$message = $timestamp . $method . $path . $body;
$signature = hash_hmac('sha256', $message, $clientSecret);
```

---

## 🧪 Testing / Security

- Use HTTPS at all times.
- Rotate client secrets periodically.
- Limit timestamp skew via `config/hmac.php` (`timestamp_tolerance_seconds`).
- Enforce IP whitelisting or rate-limiting as needed.

---

## 📄 License

MIT © Ovarun Dev Team
