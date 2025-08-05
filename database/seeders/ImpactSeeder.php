<?php

namespace Database\Seeders;

use App\Models\Impact;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ImpactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */    
    public function run(): void
    {
        $impacts = [
            'Temps perdu',
            'Secours',
            'Pas d\'impact',
            'Changement de PDT',
            'Retard trains (arrivée)',
            'Sollicitation CR MNVR',
            'Sollicitation AST-Conduite',
            'Heures supplémentaires',
            'Retard Sortie SMR',
            'Retard rentrée SMR',
            'Croisement Rame',
            'Pas de rame de réserve',
            'JS couverte par d\'autres agents',
            'Sollicitation CR en SV flux',
            'Suppression de trains',
            'Retard RECO',
            'Retard trains (depart)',
            'Gestion opérationnelle KO'
        ];

        foreach ($impacts as $impact) {
            Impact::updateOrCreate(
                ['libelle' => $impact],
                ['libelle' => $impact]
            );
        }

        $this->command->info('✅ Impacts créés avec succès!');
    }
}
