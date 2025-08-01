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
            ['libelle' => 'Temps perdu', 'niveau' => 4, 'couleur' => '#dc2626'],
            ['libelle' => 'Secours', 'niveau' => 3, 'couleur' => '#ea580c'],
            ['libelle' => 'Pas d\'impact', 'niveau' => 2, 'couleur' => '#d97706'],
            ['libelle' => 'Changement de PDT', 'niveau' => 1, 'couleur' => '#16a34a'],
            ['libelle' => 'Retard trains (arrivée)', 'niveau' => 0, 'couleur' => '#6b7280'],
            ['libelle' => 'Sollicitation CR MNVR', 'niveau' => 0, 'couleur' => '#6b7280'],
            ['libelle' => 'Sollicitation AST-Conduite', 'niveau' => 0, 'couleur' => '#6b7280'],
            ['libelle' => 'Heures supplémentaires', 'niveau' => 0, 'couleur' => '#6b7280'],
            ['libelle' => 'Retard Sortie SMR', 'niveau' => 0, 'couleur' => '#6b7280'],
            ['libelle' => 'Retard rentrée SMR', 'niveau' => 0, 'couleur' => '#6b7280'],
            ['libelle' => 'Croisement Rame', 'niveau' => 0, 'couleur' => '#6b7280'],
            ['libelle' => 'Pas de rame de réserve', 'niveau' => 0, 'couleur' => '#6b7280'],
            ['libelle' => 'JS couverte par d\' autres agents', 'niveau' => 0, 'couleur' => '#6b7280'],
            ['libelle' => 'Sollicitation CR en SV flux', 'niveau' => 0, 'couleur' => '#6b7280'],
            ['libelle' => 'Suppression de trains', 'niveau' => 0, 'couleur' => '#6b7280'],
            ['libelle' => 'Retard RECO', 'niveau' => 0, 'couleur' => '#6b7280'],
            ['libelle' => 'Retard trains (depart)', 'niveau' => 0, 'couleur' => '#6b7280'],
            ['libelle' => 'Gestion opérationnelle KO', 'niveau' => 0, 'couleur' => '#6b7280'],

           
        ];

        foreach ($impacts as $impact) {
            Impact::updateOrCreate(
                ['libelle' => $impact['libelle']],
                $impact
            );
        }

        $this->command->info('✅ Impacts créés avec succès!');
    }
}
