<?php

namespace FuriousDeveloper\LaraBit\Tests;

use FuriousDeveloper\LaraBit\LaraBit;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class LaraBitTest extends TestCase
{
    public function test_it_creates_a_basic_template_in_the_views_root()
    {
        $this->artisan('larabit:view', ['name' => 'larabit-root-test'])->assertSuccessful();

        $file = resource_path('views/larabit-root-test.blade.php');
        $this->assertFileExists($file);
        $this->assertStringContainsString('Basic Blade Template Created Using: LaraBit', file_get_contents($file));
    }

    public function test_it_creates_nested_folders_from_dot_notation()
    {
        $this->artisan('larabit:view', ['name' => 'larabit-test.one.two.index'])->assertSuccessful();

        $this->assertFileExists(resource_path('views/larabit-test/one/two/index.blade.php'));
    }

    public function test_it_uses_the_advance_template_with_the_type_shortcut()
    {
        $this->artisan('larabit:view', ['name' => 'larabit-test.page', '-t' => 'advance'])->assertSuccessful();

        $this->assertStringContainsString(
            'Advance Blade Template Created Using: LaraBit',
            file_get_contents(resource_path('views/larabit-test/page.blade.php'))
        );
    }

    public function test_it_does_not_overwrite_an_existing_file()
    {
        $file = resource_path('views/larabit-test/existing.blade.php');
        File::ensureDirectoryExists(dirname($file));
        File::put($file, 'original');

        $this->artisan('larabit:view', ['name' => 'larabit-test.existing'])->assertFailed();

        $this->assertSame('original', file_get_contents($file));
    }

    public function test_it_fails_on_an_unknown_type_without_creating_folders()
    {
        $this->artisan('larabit:view', ['name' => 'larabit-test.missing.index', '--type' => 'does-not-exist'])->assertFailed();

        $this->assertDirectoryDoesNotExist(resource_path('views/larabit-test'));
    }

    public function test_it_rejects_names_with_empty_segments()
    {
        $this->artisan('larabit:view', ['name' => 'larabit-test..index'])->assertFailed();

        $this->assertDirectoryDoesNotExist(resource_path('views/larabit-test'));
    }

    public function test_it_uses_custom_templates_from_the_published_folder()
    {
        File::ensureDirectoryExists(resource_path('views/vendor/larabit'));
        File::put(resource_path('views/vendor/larabit/custom.blade.php'), '<p>custom template</p>');

        // The published folder is only picked up when the provider boots.
        $this->refreshApplication();

        $this->artisan('larabit:view', ['name' => 'larabit-test.custom', '--type' => 'custom'])->assertSuccessful();

        $this->assertSame('<p>custom template</p>', file_get_contents(resource_path('views/larabit-test/custom.blade.php')));
    }

    public function test_it_publishes_the_bundled_templates()
    {
        $this->artisan('vendor:publish', ['--tag' => 'larabit-views'])->assertSuccessful();

        $this->assertFileExists(resource_path('views/vendor/larabit/basic.blade.php'));
        $this->assertFileExists(resource_path('views/vendor/larabit/advance.blade.php'));
    }

    public function test_it_does_not_replace_laravels_make_view_command()
    {
        $commands = Artisan::all();

        $this->assertNotInstanceOf(LaraBit::class, $commands['make:view']);
        $this->assertInstanceOf(LaraBit::class, $commands['larabit:view']);
    }
}
