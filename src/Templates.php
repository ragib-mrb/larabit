<?php

namespace FuriousDeveloper\LaraBit;

class Templates
{
    /**
     * Folder that holds the published (and custom) templates.
     */
    public static function publishedPath()
    {
        return resource_path('views/vendor/larabit');
    }

    /**
     * All template types, keyed by name, with where each one comes from.
     *
     * @return array<string, string>
     */
    public static function available()
    {
        $types = [];

        foreach (glob(__DIR__ . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . '*.blade.php') ?: [] as $file) :
            $types[basename($file, '.blade.php')] = 'bundled';
        endforeach;

        foreach (glob(static::publishedPath() . DIRECTORY_SEPARATOR . '*.blade.php') ?: [] as $file) :
            $type = basename($file, '.blade.php');
            $types[$type] = isset($types[$type]) ? 'published (overrides bundled)' : 'published';
        endforeach;

        ksort($types);

        return $types;
    }
}
