<?php

namespace FuriousDeveloper\LaraBit;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Illuminate\Support\Str;


class LaraBit extends Command implements PromptsForMissingInput
{
    /**
     * The name and signature of the console command.
     *
     * Laravel 10+ ships its own `make:view`, so LaraBit uses its own namespace.
     *
     * @var string
     */
    protected $signature = 'larabit:view
                            {name* : The name(s) of the Blade Template. [You can also include the folder name with the file name. For Eg: FolderOne.FolderTwo.FileName]}
                            {--t|type= : Define the Type of the Blade Template. [Default: basic, or page when --extends is used]}
                            {--extends= : The layout the new view extends (used by the page template). [Default: layouts.app]}
                            {--section=content : The section name used by the page and layout templates.}
                            {--f|force : Overwrite the file if it already exists.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new Blade Template(s) in view folder';


    private $extension = '.blade.php';
    private $DS = DIRECTORY_SEPARATOR;

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $type = $this->option('type') ?: ($this->option('extends') ? 'page' : 'basic');
        $types = Templates::available();

        if (!array_key_exists($type, $types)) :
            $this->components->error("Template type [{$type}] does not exist. Available types: " . implode(', ', array_keys($types)) . '.');

            return self::FAILURE;
        endif;

        $failed = false;

        foreach ($this->argument('name') as $name) :
            if (!$this->createView($name, $type)) :
                $failed = true;
            endif;
        endforeach;

        return $failed ? self::FAILURE : self::SUCCESS;
    }

    /**
     * Create a single view, reporting the result on the console
     */
    private function createView($name, $type)
    {
        try {
            $file = $this->getFile($name);

            if (file_exists($file) && !$this->option('force')) :
                $this->components->error("View [{$file}] already exists. Use --force to overwrite it.");

                return false;
            endif;

            $this->createTemplate($file, $this->getContent($name, $type));
        } catch (Exception $e) {
            $this->components->error($e->getMessage());

            return false;
        }

        $this->components->info("View [{$file}] created successfully.");

        return true;
    }

    /**
     * Create new blade template (and its folders, if required)
     */
    private function createTemplate($file, $content)
    {
        $directory = dirname($file);

        if (!is_dir($directory) && !mkdir($directory, 0777, true)) :
            throw new Exception('Please check the views folder Permissions');
        endif;

        if (file_put_contents($file, $content) === false) :
            throw new Exception('Please check the views folder Permissions');
        endif;
    }

    /**
     * Render the Blade Template with the variables every template can use
     */
    private function getContent($name, $type)
    {
        $segments = explode('.', $name);

        return view("larabit::{$type}", [
            'view' => $name,
            'name' => end($segments),
            'title' => Str::headline(end($segments)),
            'extends' => $this->option('extends') ?: 'layouts.app',
            'section' => $this->option('section') ?: 'content',
        ])->render();
    }

    /**
     * Resolve the absolute path of the new template
     */
    private function getFile($name)
    {
        $path = explode('.', $name);

        if (in_array('', $path, true)) :
            throw new Exception("Invalid template name [{$name}]. Use dots only to separate folders, e.g. FolderOne.FileName");
        endif;

        $file = array_pop($path);

        return resource_path(implode($this->DS, array_merge(['views'], $path, [$file . $this->extension])));
    }

    /**
     * Prompt for missing input arguments using the returned questions.
     *
     * @return array
     */
    protected function promptForMissingArgumentsUsing()
    {
        return [
            'name' => ['What should the view be named?', 'E.g. pages.about'],
        ];
    }
}
