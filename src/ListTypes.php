<?php

namespace FuriousDeveloper\LaraBit;

use Illuminate\Console\Command;

class ListTypes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'larabit:types';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List the Blade Template types LaraBit can create';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $rows = [];

        foreach (Templates::available() as $type => $source) :
            $rows[] = [$type, $source];
        endforeach;

        $this->table(['Type', 'Source'], $rows);
        $this->line('Custom templates folder: ' . Templates::publishedPath());

        return self::SUCCESS;
    }
}
