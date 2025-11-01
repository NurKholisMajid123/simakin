# Agent Guidelines for Simakin Laravel Project

## Build/Test Commands
- **Run all tests**: `composer test`
- **Run single test**: `php artisan test --filter=TestName`
- **Code formatting**: `./vendor/bin/pint`
- **Build frontend**: `npm run build`
- **Dev server**: `npm run dev`

## Code Style Guidelines

### PHP (Laravel)
- **Imports**: Group by type (Laravel core, third-party, local classes)
- **Naming**: PascalCase for classes, camelCase for methods/properties
- **Indentation**: 4 spaces (per .editorconfig)
- **Types**: Use type hints and return types where possible
- **Error handling**: Use Laravel's exception handling and validation

### JavaScript
- **Style**: Airbnb base with Prettier
- **Semicolons**: None for .js files, semicolons for other JS
- **Quotes**: Single quotes preferred
- **Arrow functions**: Parens only when needed

### General
- **Line endings**: LF (Unix)
- **Encoding**: UTF-8
- **Trailing whitespace**: Trimmed (except .md files)
- **Final newlines**: Required