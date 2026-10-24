<p  align="center"><img  src="https://user-images.githubusercontent.com/36569722/136689176-8f0d31aa-f543-461c-a545-5c425231bb4e.png"  width="400"></p>

<p align="center">
<a href="https://github.com/ragib-mrb/larabit/actions/workflows/tests.yml"><img src="https://github.com/ragib-mrb/larabit/actions/workflows/tests.yml/badge.svg" alt="tests"></a>
<a href="https://packagist.org/packages/furious-developer/lara-bit"><img src="https://img.shields.io/packagist/v/furious-developer/lara-bit" alt="Packagist"></a>
<a href="LICENSE"><img src="https://img.shields.io/badge/license-MIT-blue" alt="MIT License"></a>
</p>

# LaraBit

LaraBit scaffolds Blade views from **starter templates** (pages, layouts, components, full HTML documents) that you can publish, edit, and extend with your own. Every new view starts with the boilerplate your project actually uses.

```bash
php artisan larabit:view layouts.app --type layout
php artisan larabit:view pages.home pages.about pages.contact --extends layouts.app
php artisan larabit:view components.alert --type component
```

> Laravel 10+ also ships a plain `php artisan make:view` that creates an empty file. LaraBit uses its own `larabit:view` command, so it never replaces the built-in one.

## Contents

- [Requirements](#requirements)
- [Installation](#installation)
- [Usage](#usage)
- [Template types](#template-types)
- [Custom templates](#custom-templates)
- [Command reference](#command-reference)
- [Upgrading from 1.x](#upgrading-from-1x)

## Requirements

| LaraBit | Laravel | PHP |
|---|---|---|
| 2.x | 12, 13 | 8.2+ (Laravel 13 needs 8.3+) |
| 1.x | 8, 9 | 7.3+ |

1.x registers `make:view`, which overrides Laravel's own `make:view` on Laravel 10 and later. Upgrade to 2.x on current Laravel.

## Installation

```bash
composer require furious-developer/lara-bit --dev
```

The service provider is registered automatically.

## Usage

### Create a view

```bash
php artisan larabit:view index
```

This creates `resources/views/index.blade.php` and prints its path. Run the command without a name and LaraBit asks for one.

### Create views inside folders

Use the same dot notation as `view()`. Missing folders are created.

```bash
php artisan larabit:view admin.users.index
# resources/views/admin/users/index.blade.php
```

### Create several views at once

```bash
php artisan larabit:view pages.home pages.about pages.contact
```

Each view is created independently. If one already exists, the others are still created and the command exits with a failure code.

### Create a page that extends a layout

```bash
php artisan larabit:view pages.user-profile --extends layouts.admin --section main
```

```blade
@extends('layouts.admin')

@section('title', 'User Profile')

@section('main')
    <!-- Page Blade Template Created Using: LaraBit -->
@endsection
```

`--extends` switches the default type to `page`. `--section` defaults to `content`.

### Overwrite an existing view

LaraBit never overwrites a file unless you pass `--force` (or `-f`).

## Template types

| Type | Creates |
|---|---|
| `basic` (default) | A minimal HTML5 document |
| `advance` | An HTML5 document with SEO, Open Graph, favicon, stylesheet and script placeholders |
| `layout` | A layout with `@yield` for the title and main section, `@stack('styles')` / `@stack('scripts')`, a CSRF meta tag and the app locale |
| `page` | A child view with `@extends`, a title section and a content section |
| `component` | An anonymous component with `@props` and `{{ $slot }}`. Create it under `components.` to use it as `<x-...>` |

Pick one with `--type` (or `-t`). List every available type, including your own, with:

```bash
php artisan larabit:types
```

## Custom templates

Publish the bundled templates to `resources/views/vendor/larabit` and edit them:

```bash
php artisan vendor:publish --tag=larabit-views
```

A published file with the same name as a bundled type replaces it. Any other Blade file in that folder becomes a new type:

```bash
# resources/views/vendor/larabit/livewire-page.blade.php
php artisan larabit:view pages.dashboard --type livewire-page
```

### Template variables

Templates are rendered through Blade when the view is created, with these variables:

| Variable | Example (`admin.user-profile`) |
|---|---|
| `$view` | `admin.user-profile` |
| `$name` | `user-profile` |
| `$title` | `User Profile` |
| `$extends` | `--extends` value, default `layouts.app` |
| `$section` | `--section` value, default `content` |

Because the template itself is rendered, write `@@` before a directive and `@{{ }}` for echoes that should stay in the generated view:

```blade
@@extends('{{ $extends }}')
<h1>@{{ $user->name }}</h1>
```

## Command reference

```text
php artisan larabit:view <name>... [options]

  -t, --type=TYPE        Template type (default: basic, or page with --extends)
      --extends=LAYOUT   Layout the page extends (default: layouts.app)
      --section=NAME     Section used by the page and layout templates (default: content)
  -f, --force            Overwrite existing views

php artisan larabit:types    List available template types
```

## Upgrading from 1.x

- The command is now `larabit:view` (previously `make:view`).
- It exits with a failure code when a view exists, the template type is unknown, or a name is invalid.
- The bundled templates now use the generated `$title` instead of "Your Title".
- Templates you published with 1.x keep working unchanged.

```bash
composer require furious-developer/lara-bit:^2.0 --dev
```

See [CHANGELOG.md](CHANGELOG.md) for details.

## Contributing

Contributions are welcome. See [CONTRIBUTING.md](CONTRIBUTING.md) and the [Code of Conduct](CODE_OF_CONDUCT.md). Report security issues privately as described in [SECURITY.md](SECURITY.md).

## License

LaraBit is open-source software licensed under the [MIT license](LICENSE).
