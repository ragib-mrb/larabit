# Changelog

## [2.0.0] - Unreleased

### Changed
- **Breaking:** the command is renamed from `make:view` to `larabit:view`, so it no longer overrides Laravel's built-in `make:view` (Laravel 10+).
- **Breaking:** requires PHP 8.2+ and Laravel 12 or 13.
- The command now returns a failure exit code when the file already exists, the template type is unknown, or the name is invalid. It no longer calls `exit`.
- Folders are only created after the template has rendered successfully.

### Added
- `MIT` LICENSE file (the package was already declared MIT in `composer.json`).
- `larabit-views` publish tag: `php artisan vendor:publish --tag=larabit-views`.
- Validation for names with empty segments (e.g. `folder..index`).
- Test suite (PHPUnit + Orchestra Testbench) and a GitHub Actions matrix.

## [1.0.5] and earlier

See the [tags on GitHub](https://github.com/ragib-mrb/larabit/tags).

[2.0.0]: https://github.com/ragib-mrb/larabit/compare/v1.0.5...HEAD
[1.0.5]: https://github.com/ragib-mrb/larabit/releases/tag/v1.0.5
