<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CheckDatabaseConnection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
     protected $signature = 'db:check';
    protected $description = 'Vérifier la connexion à la base de données';
    // protected $signature = 'app:check-database-connection';

    /**
     * The console command description.
     *
     * @var string
     */
    // protected $description = 'Vérifier la connexion à la base de données';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        try {
            DB::connection()->getPdo();
            DB::connection()->select('SELECT 1');

            $this->info('✅ Base de données accessible');
            return 0;

        } catch (\Exception $e) {
            $this->error('❌ Base de données inaccessible: ' . $e->getMessage());
            return 1;
        }
    }
    }

