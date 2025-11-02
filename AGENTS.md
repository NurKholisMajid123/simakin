# Agent Guidelines for Simakin Cleaning Management System

## Development Commands
- **Build**: `npm run build` or `composer run setup` (full setup)
- **Development**: `composer run dev` (starts server, queue, logs, and vite)
- **Lint**: `./vendor/bin/pint` (Laravel Pint for PHP formatting)
- **Test**: `composer test` or `php artisan test`
- **Single test**: `php artisan test --filter TestMethodName` or `php artisan test tests/Feature/ExampleTest.php`

## Code Style Guidelines
- **PHP**: Follow PSR-12, use Laravel Pint for formatting
- **Naming**: Use Indonesian for models (Ruangan, Tugas) and English for methods
- **Models**: Use `$fillable` for mass assignment, `$casts` for type conversion
- **Controllers**: Extend base Controller, use dependency injection
- **Views**: Use Blade with `compact()` for data passing
- **Database**: Use migrations with descriptive names, follow Laravel conventions
- **Testing**: Use PHPUnit, arrange-act-assert pattern, test both Feature and Unit
- **Frontend**: Use Vite with Tailwind CSS, follow component structure in views/
- **Error Handling**: Use Laravel's built-in validation and exception handling