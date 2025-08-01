<?php

namespace Database\Seeders;

use App\Models\Entite;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DefaultUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $entites = Entite::all()->keyBy('code');

        $users = [
            [
                'matricule' => 1409,
                'nom' => 'Faye',
                'prenom' => 'Mouhamed',
                'email' => 'mouhamed.faye@seter/sn',
                'password' => Hash::make('SETER@2025'),
                'entite_code' => 'SR COF',
                'role' => 'admin'
            ],
           
      
        ];

        foreach ($users as $userData) {
            $entite = $entites->get($userData['entite_code']);
            
            if ($entite) {
                User::updateOrCreate(
                    ['email' => $userData['email']],
                    [
                        'matricule' => $userData['matricule'],
                        'nom' => $userData['nom'],
                        'prenom' => $userData['prenom'],
                        'email' => $userData['email'],
                        'password' => $userData['password'],
                        'entite_id' => $entite->id,
                        'email_verified_at' => now(),
                    ]
                );
            }
        }

        $this->command->info('✅ Utilisateurs par défaut créés avec succès!');
            $this->command->info('📧 Email: mouhamed.faye@seter.sn | Password: SETER@2025');
    }
}
