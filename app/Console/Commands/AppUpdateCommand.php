<?php

namespace App\Console\Commands;

use App\Services\UpdateService;
use Illuminate\Console\Command;

class AppUpdateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update für die App';

    /**
     * Execute the console command.
     */
    public function handle(UpdateService $updateService)
    {

        // Los geht'S
        $this->info('🚀 Update Version ' . config('hpm.version') . ' started.');

        // Migrationen durchführen
        $this->call('migrate', [
            '--force' => true, // needed in production (no confirmation prompt)
        ]);
        $this->info('✅ Migrations finished.');


        $updateService->initialize($this);
        $this->info('✅ UpdateService initialized.');

        $updateService->updateHomepageStructures($this);
        $this->info('✅ Homepage structures updated.');

        $this->info('✅ UpdateService finished sucessfully!');
    }
}
