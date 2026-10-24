<?php

namespace FuriousDeveloper\LaraBit\Tests;

use FuriousDeveloper\LaraBit\LaraBit;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class LaraBitTest extends TestCase
{
    public function test_it_creates_a_basic_template_in_the_views_root()
    {
        $this->artisan('larabit:view', ['name' => ['larabit-root-test']])->assertSuccessful();

        $file = resource_path('views/larabit-root-test.blade.php');
        $this->assertFileExists($file);
        $this->assertStringContainsString('Basic Blade Template Created Using: LaraBit', file_get_contents($file));
    }

    public function test_it_uses_a_title_generated_from_the_name()
    {
        $this->artisan('larabit:view', ['name' => ['larabit-test.user-profile']])->assertSuccessful();

        $this->assertStringContainsString(
            '<title>User Profile</title>',
            file_get_contents(resource_path('views/larabit-test/user-profile.blade.php'))
        );
    }

    public function test_it_creates_nested_folders_from_dot_notation()
    {
        $this->artisan('larabit:view', ['name' => ['larabit-test.one.two.index']])->assertSuccessful();

        $this->assertFileExists(resource_path('views/larabit-test/one/two/index.blade.php'));
    }

    public function test_it_creates_several_views_at_once()
    {
        $this->artisan('larabit:view', ['name' => ['larabit-test.home', 'larabit-test.about', 'larabit-test.contact']])->assertSuccessful();

        $this->assertFileExists(resource_path('views/larabit-test/home.blade.php'));
        $this->assertFileExists(resource_path('views/larabit-test/about.blade.php'));
        $this->assertFileExists(resource_path('views/larabit-test/contact.blade.php'));
    }

    public function test_it_uses_the_advance_template_with_the_type_shortcut()
    {
        $this->artisan('larabit:view', ['name' => ['larabit-test.page'], '-t' => 'advance'])->assertSuccessful();

        $this->assertStringContainsString(
            'Advance Blade Template Created Using: LaraBit',
            file_get_contents(resource_path('views/larabit-test/page.blade.php'))
        );
    }

    public function test_it_creates_a_layout_with_blade_directives()
    {
        $this->artisan('larabit:view', ['name' => ['larabit-test.layouts.app'], '--type' => 'layout'])->assertSuccessful();

        $content = file_get_contents(resource_path('views/larabit-test/layouts/app.blade.php'));
        $this->assertStringContainsString("@yield('content')", $content);
        $this->assertStringContainsString("@stack('scripts')", $content);
        $this->assertStringContainsString('{{ csrf_token() }}', $content);
    }

    public function test_extends_creates_a_page_that_extends_the_layout()
    {
        $this->artisan('larabit:view', [
            'name' => ['larabit-test.user-profile'],
            '--extends' => 'layouts.admin',
            '--section' => 'main',
        ])->assertSuccessful();

        $content = file_get_contents(resource_path('views/larabit-test/user-profile.blade.php'));
        $this->assertStringContainsString("@extends('layouts.admin')", $content);
        $this->assertStringContainsString("@section('title', 'User Profile')", $content);
        $this->assertStringContainsString("@section('main')", $content);
        $this->assertStringContainsString('@endsection', $content);
    }

    public function test_the_page_type_defaults_to_the_app_layout()
    {
        $this->artisan('larabit:view', ['name' => ['larabit-test.home'], '--type' => 'page'])->assertSuccessful();

        $this->assertStringContainsString(
            "@extends('layouts.app')",
            file_get_contents(resource_path('views/larabit-test/home.blade.php'))
        );
    }

    public function test_it_creates_an_anonymous_component()
    {
        $this->artisan('larabit:view', ['name' => ['larabit-test.components.alert'], '--type' => 'component'])->assertSuccessful();

        $content = file_get_contents(resource_path('views/larabit-test/components/alert.blade.php'));
        $this->assertStringContainsString('@props([])', $content);
        $this->assertStringContainsString('{{ $attributes }}', $content);
        $this->assertStringContainsString('{{ $slot }}', $content);
    }

    public function test_it_does_not_overwrite_an_existing_file()
    {
        $file = resource_path('views/larabit-test/existing.blade.php');
        File::ensureDirectoryExists(dirname($file));
        File::put($file, 'original');

        $this->artisan('larabit:view', ['name' => ['larabit-test.existing']])
            ->expectsOutputToContain('already exists')
            ->assertFailed();

        $this->assertSame('original', file_get_contents($file));
    }

    public function test_force_overwrites_an_existing_file()
    {
        $file = resource_path('views/larabit-test/existing.blade.php');
        File::ensureDirectoryExists(dirname($file));
        File::put($file, 'original');

        $this->artisan('larabit:view', ['name' => ['larabit-test.existing'], '--force' => true])->assertSuccessful();

        $this->assertStringContainsString('Basic Blade Template Created Using: LaraBit', file_get_contents($file));
    }

    public function test_it_still_creates_the_other_views_when_one_already_exists()
    {
        File::ensureDirectoryExists(resource_path('views/larabit-test'));
        File::put(resource_path('views/larabit-test/existing.blade.php'), 'original');

        $this->artisan('larabit:view', ['name' => ['larabit-test.existing', 'larabit-test.new']])->assertFailed();

        $this->assertSame('original', file_get_contents(resource_path('views/larabit-test/existing.blade.php')));
        $this->assertFileExists(resource_path('views/larabit-test/new.blade.php'));
    }

    public function test_it_fails_on_an_unknown_type_without_creating_folders()
    {
        $this->artisan('larabit:view', ['name' => ['larabit-test.missing.index'], '--type' => 'does-not-exist'])
            ->expectsOutputToContain('Available types: advance, basic, component, layout, page')
            ->assertFailed();

        $this->assertDirectoryDoesNotExist(resource_path('views/larabit-test'));
    }

    public function test_it_rejects_names_with_empty_segments()
    {
        $this->artisan('larabit:view', ['name' => ['larabit-test..index']])->assertFailed();

        $this->assertDirectoryDoesNotExist(resource_path('views/larabit-test'));
    }

    public function test_it_prompts_for_the_name_when_it_is_missing()
    {
        $this->artisan('larabit:view')
            ->expectsQuestion('What should the view be named?', 'larabit-test.prompted')
            ->assertSuccessful();

        $this->assertFileExists(resource_path('views/larabit-test/prompted.blade.php'));
    }

    public function test_it_uses_custom_templates_from_the_published_folder()
    {
        File::ensureDirectoryExists(resource_path('views/vendor/larabit'));
        File::put(resource_path('views/vendor/larabit/custom.blade.php'), '<h1>{{ $title }}</h1>');

        // The published folder is only picked up when the provider boots.
        $this->refreshApplication();

        $this->artisan('larabit:view', ['name' => ['larabit-test.custom-page'], '--type' => 'custom'])->assertSuccessful();

        $this->assertSame('<h1>Custom Page</h1>', file_get_contents(resource_path('views/larabit-test/custom-page.blade.php')));
    }

    public function test_it_publishes_the_bundled_templates()
    {
        $this->artisan('vendor:publish', ['--tag' => 'larabit-views'])->assertSuccessful();

        foreach (['basic', 'advance', 'layout', 'page', 'component'] as $type) :
            $this->assertFileExists(resource_path("views/vendor/larabit/{$type}.blade.php"));
        endforeach;
    }

    public function test_it_lists_bundled_and_published_types()
    {
        File::ensureDirectoryExists(resource_path('views/vendor/larabit'));
        File::put(resource_path('views/vendor/larabit/custom.blade.php'), 'custom');
        File::put(resource_path('views/vendor/larabit/basic.blade.php'), 'basic');

        $this->artisan('larabit:types')
            ->expectsTable(['Type', 'Source'], [
                ['advance', 'bundled'],
                ['basic', 'published (overrides bundled)'],
                ['component', 'bundled'],
                ['custom', 'published'],
                ['layout', 'bundled'],
                ['page', 'bundled'],
            ])
            ->assertSuccessful();
    }

    public function test_it_does_not_replace_laravels_make_view_command()
    {
        $commands = Artisan::all();

        $this->assertNotInstanceOf(LaraBit::class, $commands['make:view']);
        $this->assertInstanceOf(LaraBit::class, $commands['larabit:view']);
    }
}
