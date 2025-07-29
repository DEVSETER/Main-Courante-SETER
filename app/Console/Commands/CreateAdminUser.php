<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class CreateAdminUser extends Command
{
    protected $signature = 'admin:create';
    protected $description = 'Créer un utilisateur Super Admin';

    public function handle()
    {
        $this->info(' Création d\'un nouvel administrateur...');

        // Collecte des informations
        $matricule = $this->ask('Matricule de l\'administrateur ?');
        $email = $this->ask('Email de l\'administrateur ?');
        $nom = $this->ask('Nom de l\'administrateur ?');
        $prenom = $this->ask('Prénom de l\'administrateur ?');
        $entite = $this->ask('Entité de l\'administrateur ?');
        $password = $this->secret('Mot de passe ? (min 8 caractères)');

        // Validation du mot de passe
        while (strlen($password) < 8) {
            $this->error('Le mot de passe doit contenir au moins 8 caractères.');
            $password = $this->secret('Mot de passe ? (min 8 caractères)');
        }

        try {
            // Création de l'utilisateur
            $user = User::create([
                'matricule' => $matricule,
                'email' => $email,
                'nom' => $nom,
                'prenom' => $prenom,
                'entite' => $entite,
                'password' => Hash::make($password)
            ]);

            // Création du rôle Super Admin s'il n'existe pas
            $adminRole = Role::firstOrCreate(['name' => 'Super Admin']);

            // Attribution du rôle
            $user->assignRole($adminRole);

            // Définition des permissions
            $permissions = [
                'Créer utilisateur', 'Modifier utilisateur', 'Supprimer utilisateur', 'Consulter liste utilisateurs',
                'Créer rôle', 'Modifier rôle', 'Supprimer rôle', 'Consulter liste rôles',
                'Créer privilège', 'Modifier privilège', 'Supprimer privilège', 'Consulter liste privilège',
                'Créer entité', 'Modifier entité', 'Supprimer entité', 'Consulter liste entités',
                'Créer événement', 'Modifier événement', 'Supprimer événement', 'Consulter liste événements',
                'Créer nature événements', 'Modifier nature événements', 'Supprimer nature événements', 'Consulter liste nature événements',
                'Créer lieu', 'Modifier lieu', 'Supprimer lieu', 'Consulter liste lieux',
                'Créer impact', 'Modifier impact', 'Supprimer impact', 'Consulter liste impacts',
                'Créer liste diffusion', 'Modifier liste diffusion', 'Supprimer liste diffusion', 'Consulter liste diffusion', 'Modifier action', 'Supprimer action', 'Modifier commentaire', 'Supprimer commentaire',
                'Consulter tableau de bord',
                'Consulter liste archive', 'Modifier archive'
            ];

            foreach ($permissions as $permission) {
                $adminRole->givePermissionTo($permission);
            }

            Log::info('✅ Super Admin créé avec succès', [
                'email' => $email,
                'nom' => $nom,
                'prenom' => $prenom
            ]);

            $this->info('✅ Super Admin créé avec succès !');
            $this->table(
                ['Email', 'Nom', 'Prénom', 'Rôle', 'Entité'],
                [[$user->email, $user->nom, $user->prenom, 'Super Admin', $user->entite]]
            );

        } catch (\Exception $e) {
            Log::error('❌ Erreur création Super Admin', [
                'error' => $e->getMessage(),
                'email' => $email
            ]);

            $this->error('❌ Erreur lors de la création : ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
