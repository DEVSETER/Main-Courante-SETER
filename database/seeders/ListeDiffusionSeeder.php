<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Liste_diffusion;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ListeDiffusionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $listes = [
            [
                'nom' => 'SR COF',
                'entite_codes' => ['SR COF']
            ],
            [
                'nom' => 'CM',
                'entite_codes' => ['CM']
            ],
            [
                'nom' => 'HOTLINE',
                'entite_codes' => ['HC']
            ],
            [
                'nom' => 'CIVs',
                'entite_codes' => ['CIV', 'PTP']
            ],
             [
                'nom' => 'Transport du personnel',
                'entite_codes' => ['PTP']
            ],
            [
                'nom' => 'Tous les Utilisateurs',
                'entite_codes' => ['SR COF', 'CIV', 'HC', 'CM', 'PTP']
            ]
        ];

        foreach ($listes as $listeData) {
            $liste = Liste_diffusion::updateOrCreate(
                ['nom' => $listeData['nom']],
                [
                    'nom' => $listeData['nom'],
                ]
            );

            // Associer les utilisateurs des entités correspondantes
            $users = User::whereHas('entite', function($query) use ($listeData) {
                $query->whereIn('code', $listeData['entite_codes']);
            })->get();

            $liste->users()->sync($users->pluck('id'));
        }

        $this->command->info('✅ Listes de diffusion créées avec succès!');
    }
}
