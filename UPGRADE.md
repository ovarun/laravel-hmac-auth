---

### 📄 `UPGRADE.md`

```markdown
# ⬆️ Upgrade Guide: Laravel HMAC Auth

---

## 🚨 From v1.x to v2.x

### 🧱 Requirements

| Version | PHP      | Laravel   |
|---------|----------|-----------|
| v1.x    | >= 8.0   | 10.x      |
| v2.x    | >= 8.3   | 11.x+     |

---

### 🆕 Changes in v2.x

- PHP 8.3+ required
- Laravel 11+ ready
- Improved secure key generation
- Cleaner service provider lifecycle

---

### 💡 How to Upgrade

1. Ensure your app is on **PHP 8.3+**
2. Run:
```bash
   composer require ovarun/laravel-hmac-auth:^2.0
```
3. Re-run:
```bash
   php artisan vendor:publish --tag=hmac-auth
```
4. Confirm .env includes:
```
   HMAC_SECRET_GENERATOR_KEY=...
   HMAC_SECRET_GENERATOR_SALT=...
```

---

### ⚠️ Compatibility Notes

- v2 is fully backward compatible with v1's database structure
- No data migration needed
- Middleware and client creation commands remain the same

---

### 📧 Need Help?

If you run into issues or need support using or upgrading the package:

📬 **Email:** mail@arunov.com  
🔗 **Packagist:** [Laravel HMAC Auth](https://packagist.org/packages/ovarun/laravel-hmac-auth)

I’ll get back to you as soon as possible.