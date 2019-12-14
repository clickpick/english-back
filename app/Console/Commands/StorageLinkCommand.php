<?php

namespace App\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Container\BindingResolutionException;

class StorageLinkCommand extends Command
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'storage:link';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a symbolic link from "public/storage" to "storage/app/public"';

    /**
     * Execute the console command.
     *
     * @return void
     * @throws BindingResolutionException
     */
    public function handle()
    {
        if (file_exists(base_path('public/storage'))) {
            $this->error('The "public/storage" directory already exists.');
            return;
        }
        $this->laravel->make('files')->link(
            storage_path('app/public'), base_path('public/storage')
        );
        $this->info('The [public/storage] directory has been linked.');
    }
}
