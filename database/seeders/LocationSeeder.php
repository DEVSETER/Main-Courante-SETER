<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locations = [
            // Zones principales
            'SMR1',
            'SMR2',
            'SMR3',
            'SMR4',
            'SMI',    
          // Zones spécifiques
            'Gare de Dakar',
            'Gare de Colobane',
            'Gare de Dalifort',
            'Gare de Hann',
            'Gare de BAM',
            'Gare de Pikine',
            'Gare de Thiaroye',
            'Gare de Yeumbeuml',
            'Gare de KMF',
            'Gare de PNR',
            'Gare de Rufisque',
            'Gare de Bargny',
            'Gare de Diamniadio',

         
            
         
        ];

        foreach ($locations as $libelle) {
            Location::updateOrCreate(
                ['libelle' => $libelle],
                ['libelle' => $libelle]
            );
        }

        $this->command->info('✅ Localisations créées avec succès!');
    }
    }

