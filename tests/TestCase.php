<?php

namespace FuriousDeveloper\LaraBit\Tests;

use FuriousDeveloper\LaraBit\LaraBitServiceProvider;
use Illuminate\Support\Facades\File;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [LaraBitServiceProvider::class];
    }

    protected function tearDown(): void
    {
        File::deleteDirectory(resource_path('views/larabit-test'));
        File::deleteDirectory(resource_path('views/vendor/larabit'));
        File::delete(resource_path('views/larabit-root-test.blade.php'));

        parent::tearDown();
    }
}
