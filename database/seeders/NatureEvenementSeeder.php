<?php

namespace Database\Seeders;
use App\Models\Nature_evenement;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NatureEvenementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $natures = [
            // SRCOF
            'Incident technique',
            'Maintenance préventive',
            'PDT',
            'Audit sécurité',
            'Railcube',
            
            // CIV
            'Gestion du personnel',
            'Information',
            'Signalement',
            'Transport du personnel',
            'Gestion PDT',
            'Croisement rames',
            'Planification JS',
            'Matériel Roulant',
            'Autres',

            // HOTLINE
            'Anomalie MR',
            'Demande assistance',
            'Information technique',
            'Réclamation client',
            'Signalement incident',
            
            // CM
            'Présence corps étranger engageant le gabarit du panto',
            'Maintenance corrective',
            'Remplacement pièce',
            'Panne billettique',
            
            // PTP
            'Info Hotline',
            'Appel chauffeur',
            'Reclamation Agent',
            'Reclamation Chauffeur',
            'Changement de planning',
            'Planification erronnée',
            'Panne/Problème navette',
            'Info retard',
            'Sollicitation navette',
            'Info ramassage',
            'Info demenagement',
            'Annulation ramassage',
            'Ramassage raté',
            'Horaire ramassage',
            'Agent non planifié',
            'Injoignabilité chauffeur',
            'Appel SR_COF',
            'Changement JS',
            'Changement en opérationnel',
            'Alerte',
            'Info chauffeur',
            'Chauffeur injoignable',
            'Appel CIV',
            'Agent injoignable',
            'TTX',
            'Incident & accident',
            'Crevaison',
            'Team Building',
            'Injoignabilité chauffeur',
            'Bouclage avec Chauffeur',
            'Arrêt maladie Chauff',
            'Info Transp du Personnel',
            'Incident Véhicule interne',
            'Absence navette',
            'Situation perturbée',
            'Binômage',
            'GESTION SITUATION de CRISE',
            'Application DIEULSIMA',
            'Vidange pour véhicule interne',
            'Incident Meteo',
            'Planning Hebdo extraction RAILCUBE',
            'Cartes Carburant',
            'Bouclage Hotline conduite',
            'Gestion carburant',
            'Application DIEULSIMA',
            'Appel CIV',
            'Adresse incorrecte',

            // Communs
            'Réunion équipe',
            'Communication interne',
            'Problème informatique',
            'Question administrative',
            
        ];

        foreach ($natures as $libelle) {
            Nature_evenement::updateOrCreate(
                ['libelle' => $libelle],
                ['libelle' => $libelle]
            );
        }

        $this->command->info('✅ Natures d\'événements créées avec succès!');
    }
    }

