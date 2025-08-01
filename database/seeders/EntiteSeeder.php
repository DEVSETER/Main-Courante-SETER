<?php

namespace Database\Seeders;

use App\Models\Entite;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class EntiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $entites = [
            ['code' => 'SR COF', 'nom' => 'SUPERVISEUR COF'],
            ['code' => 'CIV', 'nom' => 'COORDINATEUR INFORMATIONS VOYAGEURS'],
            ['code' => 'HC', 'nom' => 'HOTLINE CONDUITE'],
            ['code' => 'CM', 'nom' => 'COORDINATEUR DE MAINTENANCE'],
            ['code' => 'PTP', 'nom' => 'PLANIFICATION TRANSPORT PERSONNEL'],
        ];

        foreach ($entites as $entite) {
            Entite::updateOrCreate(
                ['code' => $entite['code']],
                $entite
            );
        }

        $this->command->info('✅ Entités créées avec succès!');
    }
    }
