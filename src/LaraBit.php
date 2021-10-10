<?php

namespace FuriousDeveloper\LaraBit;

use Exception;
use Illuminate\Console\Command;


class LaraBit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:view 
                            {name : The name of the Blade Template. [You can also include the folder name with the file name. For Eg: FolderOne.FolderTwo.FileName]}  
                            {--t|type= : Define the Type of the Blade Teplate.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Blade Template in view folder';

    /**
     * Create a new command instance.
     *
     * @return void
     */


    private $extension = '.blade.php';
    private $DS = DIRECTORY_SEPARATOR;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $this->createTemplate($this->getFile(), $this->getContent());
        } catch (Exception $e) {
            $this->newLine();
            $this->error("Error! {$e->getMessage()}");
            $this->newLine();
            exit;
        }

        return 0;
    }

    /**
     * Create new blade template
     */
    private function createTemplate($file, $content)
    {
        if (!file_exists($file)) :
            if (file_put_contents($file, $content)) :
                $this->newLine();
                $this->info('Hooray!!! New Blade Template Created Successfully. Happy Coding...');
                $this->newLine();
                $this->comment("Template Location: {$file}");
                $this->newLine();
            else :
                $this->newLine();
                $this->error('Error! Please check the views folder Permissions');
                $this->newLine();
            endif;
        else :
            $this->newLine();
            $this->error('Opss!!! File already exists, Please use different name or rename existing file');
            $this->newLine();
        endif;
    }

    /**
     * Read the content from the Blade Template
     */
    private function getContent()
    {

        $type = !empty($this->option('type')) ? $this->option('type') : 'basic';

        try {
            return view("larabit::{$type}")->render();
        } catch (Exception $e) {
            $this->newLine();
            $this->error("Error! {$e->getMessage()}");
            $this->newLine();
            exit();
        }
    }

    /**
     * Create the directories (if require)
     */
    private function getFile()
    {

        $path = explode('.', $this->argument('name'));
        $name = array_pop($path);
        $folders = implode($this->DS, $path);
        $destination = resource_path('views' . $this->DS . $folders);

        if (!is_dir($destination)) :
            if (!mkdir($destination, 0777, true)) :
                $this->newLine();
                $this->error('Error! Please check the views folder Permissions');
                $this->newLine();
                exit;
            endif;
        endif;

        return  empty($folders) ?
            $destination . $name . $this->extension
            : $destination . $this->DS . $name . $this->extension;
    }
}
