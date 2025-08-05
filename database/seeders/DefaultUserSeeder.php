<?php
// filepath: c:\Projet\MainCourante\database\seeders\SuperAdminSeeder.php

namespace Database\Seeders;

use App\Models\Entite;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DefaultUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('👑 Création du Super Admin avec toutes les permissions typées...');

        // Réinitialiser le cache des permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. CRÉER TOUTES LES PERMISSIONS TYPÉES
        $this->createTypedPermissions();

        // 2. CRÉER LE RÔLE SUPER ADMIN
        $superAdminRole = $this->createSuperAdminRole();

        // 3. CRÉER L'UTILISATEUR SUPER ADMIN
        $this->createSuperAdminUser($superAdminRole);

        $this->command->info('✅ Super Admin créé avec succès!');
        $this->showSummary();
    }

    private function createTypedPermissions(): void
    {
        $this->command->info('📋 Création des permissions typées...');

        $permissionsByType = [
            'administration' => [
                'Créer utilisateur',
                'Consulter liste utilisateurs',
                'Modifier utilisateur',
                'Supprimer utilisateur',
                'Créer rôle',
                'Modifier rôle',
                'Supprimer rôle',
                'Consulter liste rôles',
                'Créer privilège',
                'Modifier privilège',
                'Supprimer privilège',
                'Consulter liste privilège',

            ],
            'organisation' => [
                'Créer entité',
                'Modifier entité',
                'Supprimer entité',
                'Consulter liste entités',
                'Créer nature événement',
                'Modifier nature événement',
                'Supprimer nature événement',
                'Consulter liste nature événements',
                'Créer lieu',
                'Modifier lieu',
                'Supprimer lieu',
                'Consulter liste lieux',
                'Créer impact',
                'Modifier impact',
                'Supprimer impact',
                'Consulter liste impacts',
            ],
            'exploitation' => [
                'Créer événement',
                'Consulter liste événements',
                'Afficher détails événement',
                'Modifier événement',
                'Supprimer événement',
                'Archiver événement',
                'Clôturer événement',
                'Consulter événements archivés',
                'Ajouter commentaire événement',
                'Ajouter action événement',
                'Modifier action événement',
                'Diffuser événement',
                'Taguer entité sur événement',
                'Gérer main courante',
                'Supprimer action',
                'Modifier action',
                'Modifier commentaire',
                'Supprimer commentaire',
                'Consulter liste archive',
                'Modifier archive',

            ],
            'reporting' => [
                '   ',
                'Consulter rapports',
                'Exporter données',
                'Générer un rapport',
            ],
            'communication' => [
                'Créer liste diffusion',
                'Modifier liste diffusion',
                'Supprimer liste diffusion',
                'Consulter liste diffusion',
                'Gérer diffusion événement',
                'Diffuser évènement',
            ],
        ];

        $totalCreated = 0;

        foreach ($permissionsByType as $type => $permissions) {
            $this->command->info("   🔹 Type: {$type}");

            foreach ($permissions as $permissionName) {
                Permission::firstOrCreate(
                    ['name' => $permissionName, 'guard_name' => 'web'],
                    ['type' => $type]
                );
                $this->command->line("     ✓ {$permissionName}");
                $totalCreated++;
            }
        }

        $this->command->info("✅ {$totalCreated} permissions créées et typées");
    }

    private function createSuperAdminRole(): Role
    {
        $this->command->info('👑 Création du rôle Super Admin...');

        // Créer le rôle Super Admin
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);

        // Assigner TOUTES les permissions au Super Admin
        $allPermissions = Permission::all();
        $superAdminRole->syncPermissions($allPermissions);

        $this->command->info("✅ Rôle 'Super Admin' créé avec {$allPermissions->count()} permissions");

        return $superAdminRole;
    }

    private function createSuperAdminUser(Role $superAdminRole): void
    {
        $this->command->info('👤 Création de l\'utilisateur Super Admin...');

        // Récupérer une entité par défaut (SR COF)
        $entite = Entite::where('code', 'SR COF')->first();

        if (!$entite) {
            $this->command->error("❌ Entité 'SR COF' non trouvée. Veuillez d'abord exécuter EntiteSeeder");
            return;
        }

        // Créer l'utilisateur Super Admin AVEC role_id
        $user = User::updateOrCreate(
            ['email' => 'mouhamed.faye@seter.sn'],
            [
                'matricule' => 1409,
                'nom' => 'Faye',
                'prenom' => 'Mouhamed',
                'email' => 'mouhamed.faye@seter.sn',
                'entite_id' => $entite->id,
                'role_id' => $superAdminRole->id, // ✅ AJOUTÉ: satisfaire la contrainte role_id
                'email_verified_at' => now(),
                // Pas de password - authentification par token email
            ]
        );

        // Assigner le rôle Super Admin via Spatie Permission aussi
        $user->assignRole($superAdminRole);

        $this->command->info("✅ Utilisateur Super Admin créé:");
        $this->command->info("   📧 Email: {$user->email}");
        $this->command->info("   👤 Nom: {$user->prenom} {$user->nom}");
        $this->command->info("   🏢 Entité: {$entite->libelle}");
        $this->command->info("   🔑 Matricule: {$user->matricule}");
        $this->command->info("   👑 Rôle ID: {$superAdminRole->id}");
        $this->command->info("   🌟 Permissions: {$user->getAllPermissions()->count()}");
    }

    private function showSummary(): void
    {
        $this->command->info('');
        $this->command->info('📊 RÉSUMÉ FINAL :');
        $this->command->line('╔══════════════════╦═══════════════╗');
        $this->command->line('║ Type Permission  ║ Nombre        ║');
        $this->command->line('╠══════════════════╬═══════════════╣');

        $types = ['administration', 'organisation', 'exploitation', 'reporting', 'communication'];
        $total = 0;

        foreach ($types as $type) {
            $count = Permission::where('type', $type)->count();
            $total += $count;
            $this->command->line(sprintf('║ %-16s ║ %13d ║', ucfirst($type), $count));
        }

        $this->command->line('╠══════════════════╬═══════════════╣');
        $this->command->line(sprintf('║ %-16s ║ %13d ║', 'TOTAL', $total));
        $this->command->line('╚══════════════════╩═══════════════╝');

        $this->command->info('');
        $this->command->info('🎉 SUPER ADMIN CONFIGURÉ :');
        $this->command->info('   📧 Email: mouhamed.faye@seter.sn');
        $this->command->info('   🔐 Connexion: Token par email (pas de mot de passe)');
        $this->command->info('   👑 Permissions: TOUTES (' . $total . ' permissions)');
        $this->command->info('   🌟 Accès: Complet à toutes les fonctionnalités');
    }
}
