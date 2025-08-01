<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

 $this->command->info('🚀 Début du seeding...');

        // Ordre important : les entités d'abord
        $this->call([
            EntiteSeeder::class,
            NatureEvenementSeeder::class,
            LocationSeeder::class,
            ImpactSeeder::class,
            DefaultUserSeeder::class,
            ListeDiffusionSeeder::class,
        ]);

        $this->command->info('✅ Seeding terminé avec succès!');
        $this->command->info('');
        $this->command->info(' Résumé des données créées:');
        $this->command->info('   • Entités: 5');
        $this->command->info('   • Natures d\'événements: ~30');
        $this->command->info('   • Localisations: ~25');
        $this->command->info('   • Impacts: 5');
        $this->command->info('   • Utilisateurs: 6');
        $this->command->info('   • Listes de diffusion: 5');
        $this->command->info('');
        $this->command->info(' Compte admin:');
        $this->command->info('   Email: mouhamed.faye@seter.sn');
        $this->command->info('   Password: SETER@2025');
    }
}
