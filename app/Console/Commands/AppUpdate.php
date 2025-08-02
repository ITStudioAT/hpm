<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class AppUpdate extends Command
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
    protected $description = 'Update the current app';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (! app()->environment('local')) {
            $this->error('❌ Abbruch: Dieser Befehl läuft nur im lokalen Modus.');
            return 1;
        }

        $installUpdateService = new \App\Services\InstallUpdateService();

        // Rollen checken und erstellen
        $installUpdateService->createRoles([
            'super_admin',
            'admin',
        ]);

        // User 1 muss existieren, wenn nicht, dann erstelle ihn
        if (User::count() === 0) {
            $this->info('ℹ️  Keine User gefunden. Erstelle neuen User …');

            // Führe den create:user‑Command aus
            $exitCode = $this->call('create:user');

            if ($exitCode === 0) {
                $this->info('✅  User erfolgreich angelegt.');
            } else {
                $this->error('❌  Fehler beim Anlegen des Users (Exit-Code: ' . $exitCode . ').');
                return $exitCode;
            }
        } else {
            $this->info('ℹ️  Es existieren bereits ' . User::count() . ' User(s). Kein weiterer User notwendig.');
        }


        // User 1 muss die Rolle super_admin haben, wenn nicht, dann weise sie zu
        $installUpdateService->makeUser_1_toSuperAdmin();


        $this->info('Alles gut gelaufen!');
    }
}
