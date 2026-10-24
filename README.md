<p  align="center"><img  src="https://user-images.githubusercontent.com/36569722/136689176-8f0d31aa-f543-461c-a545-5c425231bb4e.png"  width="400"></p>

<p align="center">
<a href="https://github.com/ragib-mrb/larabit/actions/workflows/tests.yml"><img src="https://github.com/ragib-mrb/larabit/actions/workflows/tests.yml/badge.svg" alt="tests"></a>
<a href="https://packagist.org/packages/furious-developer/lara-bit"><img src="https://img.shields.io/packagist/v/furious-developer/lara-bit" alt="Packagist"></a>
<a href="LICENSE"><img src="https://img.shields.io/badge/license-MIT-blue" alt="MIT License"></a>
</p>

## About LaraBit

LaraBit is an Artisan command that creates Blade templates from **starter templates** that you can publish, edit, and extend with your own, so every new view starts with the boilerplate your project needs.

    php artisan larabit:view admin.users.index --type advance

> Laravel 10+ also ships a plain `php artisan make:view`. LaraBit uses its own `larabit:view` command so it never replaces the built-in one.

## Requirements

| LaraBit | Laravel | PHP |
|---|---|---|
| 2.x | 12, 13 | 8.2+ (Laravel 13 needs 8.3+) |
| 1.x | 8, 9 | 7.3+ |

1.x registers `make:view`, which overrides Laravel's own `make:view` on Laravel 10 and later. Upgrade to 2.x on current Laravel.

## Installation

    composer require furious-developer/lara-bit --dev

The service provider is registered automatically.

## Usage

### Create a template

    php artisan larabit:view index

This creates `resources/views/index.blade.php` and prints its absolute path. LaraBit never overwrites an existing file.

### Create a template inside folders

Use the same dot notation as `view()`. Missing folders are created.

    php artisan larabit:view layout.index
    php artisan larabit:view folder1.folder2.index

### Choose a template type

| Type | Contents |
|---|---|
| `basic` (default) | Minimal HTML5 page |
| `advance` | HTML5 page with SEO, Open Graph, favicon, stylesheet and script placeholders |

    php artisan larabit:view index --type advance
    php artisan larabit:view index -t advance

## Customize templates

Publish the bundled templates to `resources/views/vendor/larabit` and edit them:

    php artisan vendor:publish --tag=larabit-views

To add your own type, place any Blade file in that folder and pass its name as the type:

    # resources/views/vendor/larabit/example.blade.php
    php artisan larabit:view index --type example

Templates are rendered through Blade when the new file is created, so Blade directives inside a template run at that moment. Write `@@` or `@{{ }}` for output you want to keep in the generated file.

## Upgrading from 1.x

- The command is now `larabit:view` (previously `make:view`).
- It exits with a non-zero code when the file exists or the template type is unknown.
- Published templates in `resources/views/vendor/larabit` keep working unchanged.

See [CHANGELOG.md](CHANGELOG.md) for details.

## Contributing

See [CONTRIBUTING.md](CONTRIBUTING.md).

## License

LaraBit is open-source software licensed under the [MIT license](LICENSE).
