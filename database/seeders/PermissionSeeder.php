<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;



class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.

{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🔐 Création des permissions...');

        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            // === GESTION UTILISATEURS ===
            'Consulter liste utilisateurs',
            'Supprimer utilisateur',
            'Modifier utilisateur',
            
            // === GESTION RÔLES ===
            'Créer rôle',
            'Modifier rôle',
            'Supprimer rôle',
            'Consulter liste rôles',
            
            // === GESTION ENTITÉS ===
            'Créer entité',
            'Modifier entité',
            'Supprimer entité',
            'Consulter liste entités',
            
            // === GESTION PRIVILÈGES ===
            'Créer privilège',
            'Modifier privilège',
            'Supprimer privilège',
            'Consulter liste privilège',
            
            // === GESTION LISTES DIFFUSION ===
            'Créer liste diffusion',
            'Modifier liste diffusion',
            'Supprimer liste diffusion',
            'Consulter liste diffusion',
            
            // === GESTION ÉVÉNEMENTS ===
            'Créer événement',
            'Consulter liste événements',
            'Modifier événement',
            'Supprimer événement',
            'Archiver évènement',
            'Clôcturer évèement',
            'Afficher détails événement',
            'Diffuser évènement',
            'Taguer entité ou utilisateur sur un évènement',
            'consulter évènement archiver',
            
            // === GESTION NATURE ÉVÉNEMENTS ===
            'Créer nature évènement',
            'Créer nature événements',
            'Modifier nature événements',
            'Supprimer nature événements',
            'Consulter liste nature événements',
            
            // === GESTION LIEUX ===
            'Créer lieu',
            'Modifier lieu',
            'Supprimer lieu',
            'Consulter liste lieux',
            
            // === GESTION IMPACTS ===
            'Créer impact',
            'Modifier impact',
            'Supprimer impact',
            'Consulter liste impacts',
            
            // === GESTION COMMENTAIRES ===
            'Ajouter commentaire évènement',
            'Modifier commentaire',
            'Supprimer commentaire',
            
            // === GESTION ACTIONS ===
            'Ajouter action évènement',
            'Modifier action évènement',
            'Modifier action',
            'Supprimer action',
            
            // === GESTION ARCHIVES ===
            'Consulter liste archive',
            'Modifier archive',
            
            // === RAPPORTS ET TABLEAU DE BORD ===
            'Générer un rapport',
            'Consulter rapports',
            'Consulter tableau de bord',
            
            // === PERMISSION TEST ===
            'Test',
        ];

        // Créer toutes les permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $this->command->info('✅ ' . count($permissions) . ' permissions créées avec succès!');
        
        // Créer les rôles avec leurs permissions
        $this->createRoles();
    }

    private function createRoles()
    {
        $this->command->info('👥 Création des rôles...');

        // === SUPER ADMIN - Toutes les permissions ===
        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin']);
        $superAdmin->syncPermissions(Permission::all());
        $this->command->info("   → Rôle 'Super Admin' créé avec " . Permission::count() . " permissions");

        // === ADMIN - Gestion complète sauf suppression critique ===
        $admin = Role::firstOrCreate(['name' => 'Admin']);
        $adminPermissions = [
            'Consulter liste utilisateurs', 'Modifier utilisateur',
            'Créer rôle', 'Modifier rôle', 'Consulter liste rôles',
            'Créer entité', 'Modifier entité', 'Consulter liste entités',
            'Créer privilège', 'Modifier privilège', 'Consulter liste privilège',
            'Créer liste diffusion', 'Modifier liste diffusion', 'Consulter liste diffusion',
            'Créer événement', 'Consulter liste événements', 'Modifier événement', 
            'Archiver évènement', 'Clôcturer évèement', 'Afficher détails événement',
            'Diffuser évènement', 'Taguer entité ou utilisateur sur un évènement',
            'Créer nature évènement', 'Modifier nature événements', 'Consulter liste nature événements',
            'Créer lieu', 'Modifier lieu', 'Consulter liste lieux',
            'Créer impact', 'Modifier impact', 'Consulter liste impacts',
            'Ajouter commentaire évènement', 'Modifier commentaire',
            'Ajouter action évènement', 'Modifier action évènement', 'Modifier action',
            'Consulter liste archive', 'Modifier archive',
            'Générer un rapport', 'Consulter rapports', 'Consulter tableau de bord',
            'consulter évènement archiver'
        ];
        $admin->syncPermissions($adminPermissions);
        $this->command->info("   → Rôle 'Admin' créé avec " . count($adminPermissions) . " permissions");

        // === SUPERVISEUR - Gestion opérationnelle ===
        $superviseur = Role::firstOrCreate(['name' => 'Superviseur']);
        $superviseurPermissions = [
            'Consulter liste utilisateurs',
            'Consulter liste rôles', 'Consulter liste entités',
            'Créer liste diffusion', 'Modifier liste diffusion', 'Consulter liste diffusion',
            'Créer événement', 'Consulter liste événements', 'Modifier événement',
            'Clôcturer évèement', 'Afficher détails événement', 'Diffuser évènement',
            'Consulter liste nature événements', 'Consulter liste lieux', 'Consulter liste impacts',
            'Ajouter commentaire évènement', 'Modifier commentaire',
            'Ajouter action évènement', 'Modifier action évènement', 'Modifier action',
            'Générer un rapport', 'Consulter rapports', 'Consulter tableau de bord',
            'consulter évènement archiver'
        ];
        $superviseur->syncPermissions($superviseurPermissions);
        $this->command->info("   → Rôle 'Superviseur' créé avec " . count($superviseurPermissions) . " permissions");

        // === OPERATEUR - Opérations courantes ===
        $operateur = Role::firstOrCreate(['name' => 'Operateur']);
        $operateurPermissions = [
            'Créer événement', 'Consulter liste événements', 'Modifier événement',
            'Afficher détails événement', 'Clôcturer évèement',
            'Consulter liste nature événements', 'Consulter liste lieux', 'Consulter liste impacts',
            'Ajouter commentaire évènement', 'Modifier commentaire',
            'Ajouter action évènement', 'Modifier action évènement',
            'Consulter tableau de bord', 'consulter évènement archiver'
        ];
        $operateur->syncPermissions($operateurPermissions);
        $this->command->info("   → Rôle 'Operateur' créé avec " . count($operateurPermissions) . " permissions");

        // === TECHNICIEN - Interventions techniques ===
        $technicien = Role::firstOrCreate(['name' => 'Technicien']);
        $technicienPermissions = [
            'Créer événement', 'Consulter liste événements', 'Modifier événement',
            'Afficher détails événement', 'Clôcturer évèement',
            'Consulter liste nature événements', 'Consulter liste lieux', 'Consulter liste impacts',
            'Ajouter commentaire évènement', 'Modifier commentaire',
            'Ajouter action évènement', 'Modifier action évènement',
            'Consulter tableau de bord'
        ];
        $technicien->syncPermissions($technicienPermissions);
        $this->command->info("   → Rôle 'Technicien' créé avec " . count($technicienPermissions) . " permissions");

        // === PLANIFICATEUR - Planification et organisation ===
        $planificateur = Role::firstOrCreate(['name' => 'Planificateur']);
        $planificateurPermissions = [
            'Créer événement', 'Consulter liste événements', 'Modifier événement',
            'Afficher détails événement', 'Clôcturer évèement', 'Diffuser évènement',
            'Consulter liste nature événements', 'Consulter liste lieux', 'Consulter liste impacts',
            'Ajouter commentaire évènement', 'Modifier commentaire',
            'Ajouter action évènement', 'Modifier action évènement',
            'Générer un rapport', 'Consulter rapports', 'Consulter tableau de bord'
        ];
        $planificateur->syncPermissions($planificateurPermissions);
        $this->command->info("   → Rôle 'Planificateur' créé avec " . count($planificateurPermissions) . " permissions");

        // === INVITE - Lecture seule ===
        $invite = Role::firstOrCreate(['name' => 'Invite']);
        $invitePermissions = [
            'Consulter liste événements', 'Afficher détails événement',
            'Consulter liste nature événements', 'Consulter liste lieux', 'Consulter liste impacts',
            'Consulter tableau de bord', 'consulter évènement archiver'
        ];
        $invite->syncPermissions($invitePermissions);
        $this->command->info("   → Rôle 'Invite' créé avec " . count($invitePermissions) . " permissions");

        $this->command->info('✅ 7 rôles créés avec leurs permissions!');
    }
    }
