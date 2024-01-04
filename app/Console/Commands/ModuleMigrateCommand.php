<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class ModuleMigrateCommand extends Command
{
    protected $signature = 'module:migrate';

    protected $description = 'Migrate all modules';

    public function handle()
    {
        $this->error('This will delete your old users table.');

        // Ask the user for confirmation
        if ($this->confirm('Do you wish to continue?')) {
            // Run migrations for all modules
            Artisan::call('migrate', ['--path' => 'Modules/*/Database/Migrations']);
            
            $this->info('Modules migrated successfully!');
        } else {
            $this->info('Modules Migrate Aborted');
        }
    }
}
