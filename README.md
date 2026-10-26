# Digital Book runtime

This project targets PHP 8.4.27 or a later PHP 8.4 patch and Laravel 13. The lock file currently installs Laravel 13.38.0. Composer resolves dependencies against PHP 8.4.27 so updates remain compatible with the deployment runtime.

## Deployment after upgrading from Laravel 9

1. Back up the database, uploaded books, and environment configuration.
2. Configure both the web server and CLI to use PHP 8.4.27. Enable the extensions checked by `composer check-platform-reqs`, especially `fileinfo`, plus the database driver (`pdo_mysql` for MySQL). Enable `zip` for Composer archive extraction. The local PHP installation has these extensions available but disabled in its default php.ini.
3. Keep the existing `.env`, `APP_KEY`, database, and uploaded files. Install the committed dependencies with `composer install --no-dev --optimize-autoloader`.
4. Run `composer check-platform-reqs --no-dev`, `php artisan config:clear`, `php artisan route:clear`, and `php artisan view:clear`. No new database migration is required by this upgrade; the existing Sanctum migration already includes `expires_at`.
5. Rebuild production configuration/views as appropriate and restart PHP and any queue workers. Check login, book uploads, reading, cart, checkout, and password reset in staging before production deployment.

The existing application structure, routes, storage roots, session cookie name, and password reset table are retained. Sanctum uses its version 4 middleware configuration, and CSRF middleware uses Laravel 13's `PreventRequestForgery` implementation. The obsolete Ignition development dependency has been removed.

## Tests

Run `php artisan test` with `fileinfo`, `pdo_sqlite`, and `sqlite3` enabled. Tests use an in-memory database, a test-only encryption key, role fixtures, and skip the Vite manifest requirement. They do not require a production `.env` or access the production database.

For the current Windows PHP installation, the equivalent command without changing the global php.ini is:

```powershell
php -d extension=fileinfo -d extension=pdo_sqlite -d extension=sqlite3 vendor/phpunit/phpunit/phpunit
```

Validation on PHP 8.4.27: 24 tests and 56 assertions pass; route listing, Blade compilation, Composer validation, platform checks (with fileinfo enabled), and Composer security audit pass. Payment services and a live MySQL deployment require staging verification.

---
<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 2000 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the Laravel [Patreon page](https://patreon.com/taylorotwell).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Cubet Techno Labs](https://cubettech.com)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[Many](https://www.many.co.uk)**
- **[Webdock, Fast VPS Hosting](https://www.webdock.io/en)**
- **[DevSquad](https://devsquad.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[OP.GG](https://op.gg)**
- **[WebReinvent](https://webreinvent.com/?utm_source=laravel&utm_medium=github&utm_campaign=patreon-sponsors)**
- **[Lendio](https://lendio.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
