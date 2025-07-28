# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Architecture

This is a large, modular Laravel 11 application built using the `nwidart/laravel-modules` package. The application is divided into a core `app` directory and numerous feature-specific modules located in the `Modules/` directory. Each module is a self-contained unit with its own routes, controllers, models, and views, promoting a clean separation of concerns.

Key modules include:
- **`CertificateBuilder`**: Manages the creation and layout of certificates.
- **`CertificateRecognition`**: Handles certificate validation and recognition.
- **`Course`**: Core module for managing courses, categories, and levels.
- **`Coaching` & `Mentoring`**: Modules for managing coaching and mentoring functionalities.
- **`BasicPayment`**: Handles payment processing and integration with various gateways.
- **`User` Management**: Core user management is in `app/`, with additional customer-related logic in the `Customer` module.

The frontend is built using Vite, with configuration in `vite.config.js` and scripts in `package.json`.

### Certificate Generation and Signing Workflow

The certificate signing process is handled via an external service called "Bantara".

1.  **Request Initiation**: The process starts in `app/Http/Controllers/Frontend/StudentDashboardController.php` within the `requestSignCertificate` method.
2.  **PDF Generation**: This method generates a two-page PDF from Blade views (`frontend.student-dashboard.certificate.index` and `frontend.student-dashboard.certificate.summary`).
3.  **API Call**: The generated PDF is sent to the Bantara API for digital signing. The signers are retrieved from the `course_signers` table and sent along with the request.
4.  **Callback**: Bantara calls a webhook, `api/bantara-callback/{enrollmentID}`, which is handled by the `bantaraCallback` method in `app/Http/Controllers/Api/CertificateApiController.php`.
5.  **Storing Certificate**: The callback method receives the signed PDF and stores it in the `private` storage disk. The path is saved in the `certificate_path` column of the `enrollments` table.

## Common Commands

### Development Environment

To run the local development environment, which includes the PHP server, queue worker, log tailing, and Vite development server, use the following command from `composer.json`:

```bash
composer run dev
```

This concurrently executes `php artisan serve`, `php artisan queue:listen`, `php artisan pail`, and `npm run dev`.

### Frontend Assets

- To run the Vite development server: `npm run dev`
- To build frontend assets for production: `npm run build`

### Testing and Linting

- **Testing**: This project uses PHPUnit. Run the test suite with:
  ```bash
  ./vendor/bin/phpunit
  ```

- **Linting**: This project uses Laravel Pint for code style. Check for issues with:
  ```bash
  ./vendor/bin/pint --test
  ```
  And fix them with:
  ```bash
  ./vendor/bin/pint
  ```

### Database Seeding

To reset the database and run all seeders, use:

```bash
php artisan migrate:fresh --seed
```

The `README.md` also specifies additional custom commands for data synchronization:

```bash
php artisan unor:sync
php artisan instansi:sync
php artisan user:sync {instansi_id}
```
