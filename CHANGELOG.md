# Changelog

## [2.0.3] - 2026-10-24

### Added
- Code of conduct, security policy, and issue and pull request templates.

No code changes.

## [2.0.2] - 2026-10-24

### Changed
- The `basic` and `advance` templates set `<html lang>` from the app locale instead of a hard-coded `en` (idea from [#2](https://github.com/ragib-mrb/larabit/pull/2) by @kambizzandi).

## [2.0.1] - 2026-10-24

### Fixed
- Restored the `FuriousDeveloper\LaraBit` namespace and the `furious-developer/lara-bit` package name in `composer.json`. 2.0.0 shipped with a `RagibMrb\LaraBit` namespace by mistake. The package itself was always published as `furious-developer/lara-bit`.
- Updated the Packagist description and keywords, and added homepage and support links.

## [2.0.0] - 2026-10-24

### Changed
- **Breaking:** the command is renamed from `make:view` to `larabit:view`, so it no longer overrides Laravel's built-in `make:view` (Laravel 10+).
- **Breaking:** requires PHP 8.2+ and Laravel 12 or 13.
- The command now returns a failure exit code when the file already exists, the template type is unknown, or the name is invalid. It no longer calls `exit`.
- Folders are only created after the template has rendered successfully.
- The bundled `basic` and `advance` templates use a title generated from the view name instead of "Your Title".
- Console output uses Laravel's standard component style.

### Added
- New template types: `layout`, `page` and `component`.
- Create several views in one run: `larabit:view home about contact`.
- `--extends` and `--section` options for pages that extend a layout.
- `--force` (`-f`) to overwrite existing views.
- Template variables: `$view`, `$name`, `$title`, `$extends`, `$section`.
- Prompts for the view name when it is missing.
- `larabit:types` command listing bundled and published template types.
- Unknown types report the available types.
- `MIT` LICENSE file (the package was already declared MIT in `composer.json`).
- `larabit-views` publish tag: `php artisan vendor:publish --tag=larabit-views`.
- Validation for names with empty segments (e.g. `folder..index`).
- Test suite (PHPUnit + Orchestra Testbench) and a GitHub Actions matrix.

## [1.0.5] and earlier

See the [tags on GitHub](https://github.com/ragib-mrb/larabit/tags).

[2.0.3]: https://github.com/ragib-mrb/larabit/compare/v2.0.2...v2.0.3
[2.0.2]: https://github.com/ragib-mrb/larabit/compare/v2.0.1...v2.0.2
[2.0.1]: https://github.com/ragib-mrb/larabit/compare/v2.0.0...v2.0.1
[2.0.0]: https://github.com/ragib-mrb/larabit/compare/v1.0.5...v2.0.0
[1.0.5]: https://github.com/ragib-mrb/larabit/releases/tag/v1.0.5
