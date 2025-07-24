# 🔐 Laravel HMAC Auth Package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ovarun/laravel-hmac-auth.svg?style=flat-square)](https://packagist.org/packages/ovarun/laravel-hmac-auth)
[![Total Downloads](https://img.shields.io/packagist/dt/ovarun/laravel-hmac-auth.svg?style=flat-square)](https://packagist.org/packages/ovarun/laravel-hmac-auth)

Secure, stateless HMAC authentication for Laravel APIs — built for partner APIs, internal microservices, and multi-platform consumers (Angular, .NET, Python, Drupal).

---

## 📦 Requirements

- PHP 8.0+
- Laravel 10.x
- Composer
- OpenSSL / `hash_pbkdf2` support

---

## 🚀 Installation

Install directly via Packagist:

```bash
composer require ovarun/laravel-hmac-auth
```

Publish the config and migrations:

```bash
php artisan vendor:publish --tag=hmac-auth
php artisan migrate
```

---

## ⚙️ Initial Setup

Run the interactive setup to auto-generate secure HMAC key and salt:

```bash
php artisan hmac:setup
```

This adds to your `.env`:

```
HMAC_SECRET_GENERATOR_KEY='...'
HMAC_SECRET_GENERATOR_SALT='...'
```

These are used for deterministic, secure secret generation per client.

---

## 🔐 Register HMAC Clients

Register a new client via:

```bash
php artisan hmac:client-create
```

- Prompts for Client Name (and optional Client ID)
- Normalizes ID (lowercase, hyphenated, clean)
- Generates PBKDF2-based 256-bit secret
- Saves to `hmac_clients` table

---

## 🛡 Middleware Usage

Apply HMAC protection to your API routes.

### Register Middleware in `app/Http/Kernel.php`:

```php
'verify.hmac' => \Ovarun\HmacAuth\Http\Middleware\VerifyHmacSignature::class,
```

### Use in Routes:

```php
Route::middleware('verify.hmac')->group(function () {
    Route::post('/api/secure-endpoint', [SecureController::class, 'handle']);
});
```

---

## 📤 Client-Side Authentication

Clients must include these HTTP headers:

```
X-CLIENT-ID: partner-app-1
X-TIMESTAMP: 2025-07-24T14:05:00Z
X-SIGNATURE: {hmac_sha256_signature}
```

Signature is built from:

```text
$message = $timestamp . $method . $path . $body
```

Then:

```php
hash_hmac('sha256', $message, $clientSecret);
```

---

## 🧪 Security Best Practices

- Always use HTTPS
- Rotate secrets on a schedule
- Use short timestamp tolerance (`config/hmac.php`)
- Pair with IP whitelisting or rate limits
- Never log or expose the secret in responses

---

## 📄 License

MIT © [arun o v](https://packagist.org/packages/ovarun/laravel-hmac-auth)
