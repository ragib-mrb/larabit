<?php

namespace FuriousDeveloper\LaraBit;

use Exception;
use Illuminate\Console\Command;


class LaraBit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * Laravel 10+ ships its own `make:view`, so LaraBit uses its own namespace.
     *
     * @var string
     */
    protected $signature = 'larabit:view
                            {name : The name of the Blade Template. [You can also include the folder name with the file name. For Eg: FolderOne.FolderTwo.FileName]}
                            {--t|type=basic : Define the Type of the Blade Template.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Blade Template in view folder';


    private $extension = '.blade.php';
    private $DS = DIRECTORY_SEPARATOR;

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $file = $this->getFile();

            if (file_exists($file)) :
                $this->newLine();
                $this->error('Oops!!! File already exists, Please use different name or rename existing file');
                $this->newLine();

                return self::FAILURE;
            endif;

            $this->createTemplate($file, $this->getContent());
        } catch (Exception $e) {
            $this->newLine();
            $this->error("Error! {$e->getMessage()}");
            $this->newLine();

            return self::FAILURE;
        }

        $this->newLine();
        $this->info('Hooray!!! New Blade Template Created Successfully. Happy Coding...');
        $this->newLine();
        $this->comment("Template Location: {$file}");
        $this->newLine();

        return self::SUCCESS;
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
     * Read the content from the Blade Template
     */
    private function getContent()
    {
        $type = $this->option('type') ?: 'basic';

        return view("larabit::{$type}")->render();
    }

    /**
     * Resolve the absolute path of the new template
     */
    private function getFile()
    {
        $path = explode('.', $this->argument('name'));

        if (in_array('', $path, true)) :
            throw new Exception('Invalid template name. Use dots only to separate folders, e.g. FolderOne.FileName');
        endif;

        $name = array_pop($path);

        return resource_path(implode($this->DS, array_merge(['views'], $path, [$name . $this->extension])));
    }
}
