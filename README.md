# Main Courante - Système de Gestion d'Événements

Application Laravel pour la gestion de la main courante des événements avec authentification SSO Wallix et système de permissions basé sur Spatie.

## 🚀 Fonctionnalités

## 🚀 Fonctionnalités

- **Authentification hybride** : SSO Wallix + Email Token
- **Gestion des permissions** : Système basé sur Spatie Laravel Permission
- **Gestion des événements** : CRUD complet avec workflow
- **Multi-entités** : Support de plusieurs organisations
- **API REST** : Endpoints pour intégrations
- **Interface moderne** : UI responsive avec TailwindCSS

## 🛠 Technologies

- **Backend** : Laravel 10+
- **Frontend** : Blade Templates + Alpine.js + TailwindCSS
- **Base de données** : MySQL/PostgreSQL
- **Authentification** : Laravel Sanctum + Spatie Permission
- **SSO** : Wallix OIDC
- **Email** : Laravel Mail

## 📋 Prérequis

- PHP 8.1+
- Composer
- Node.js & NPM
- MySQL/PostgreSQL
- Serveur SMTP pour les emails

## ⚡ Installation

1. **Cloner le projet**
```bash
git clone https://github.com/votre-username/MainCourante.git
cd MainCourante
```

2. **Installer les dépendances**
```bash
composer install
npm install
```

3. **Configuration**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Base de données**
```bash
php artisan migrate
php artisan db:seed
```

5. **Assets**
```bash
npm run build
```

6. **Serveur de développement**
```bash
php artisan serve
```

## 🔧 Configuration

### Variables d'environnement principales

```env
# Application
APP_NAME="Main Courante"
APP_URL=http://localhost:8000

# Base de données
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=main_courante
DB_USERNAME=root
DB_PASSWORD=

# Email
MAIL_MAILER=smtp
MAIL_HOST=smtp.example.com
MAIL_PORT=587
MAIL_USERNAME=your-email@example.com
MAIL_PASSWORD=your-password

# Session
SESSION_LIFETIME=480
EMAIL_TOKEN_EXPIRY=3600
```

### Configuration Wallix SSO

La configuration Wallix se trouve dans `config/services.php` :

```php
'wallix' => [
    'oidc_issuer' => 'https://your-wallix-server/sso',
    'client_id' => 'your-client-id',
    'client_secret' => 'your-client-secret',
    'redirect_uri' => config('app.url') . '/auth/wallix/callback',
    'timeout' => 30,
    'verify_ssl' => true,
],
```

## 👥 Utilisateurs par défaut

Après les seeders, vous aurez ces comptes de test :

- **Super Admin** : `admin@maincourante.sn` | `password123`
- **Superviseur** : `moussa.diop@srcof.sn` | `password123`
- **Operateur** : `fatou.ndiaye@civ.sn` | `password123`
- **Demo** : `demo@maincourante.sn` | `password123`

## 🔐 Système d'authentification

### Workflow de connexion

1. **SSO Wallix** (méthode principale)
   - Redirection vers Wallix
   - Récupération de l'email utilisateur
   - Envoi automatique d'un token par email
   - Connexion via le lien email

2. **Email direct** (méthode de fallback)
   - Saisie directe de l'email
   - Envoi d'un token de connexion
   - Connexion via le lien email

### Permissions et rôles

- **Super Admin** : Toutes les permissions
- **Admin** : Gestion complète sauf suppressions critiques
- **Superviseur** : Gestion opérationnelle
- **Operateur** : Opérations courantes
- **Technicien** : Interventions techniques
- **Planificateur** : Planification et rapports
- **Invite** : Lecture seule

## 📁 Structure du projet

```
app/
├── Http/Controllers/
│   ├── AuthenticationController.php  # Gestion auth
│   ├── EvenementController.php       # CRUD événements
│   └── ...
├── Models/
│   ├── User.php                      # Utilisateur avec permissions
│   ├── Evenement.php                # Événement
│   ├── EmailToken.php               # Tokens d'auth
│   └── ...
├── Services/
│   └── SSOAuthService.php           # Service auth SSO
└── ...

config/
├── services.php                     # Config Wallix
├── permission.php                   # Config Spatie
└── ...

resources/views/
├── auth/                            # Vues d'authentification
├── evenements/                      # Vues événements
└── ...
```

## 🔄 Workflow des événements

1. **Création** → **En cours** → **Clôturé** → **Archivé**
2. Ajout de commentaires et actions à chaque étape
3. Notifications et diffusion selon les permissions
4. Génération de rapports

## 📊 API Endpoints

```
GET    /api/evenements              # Liste des événements
POST   /api/evenements              # Créer un événement
GET    /api/evenements/{id}         # Détails d'un événement
PUT    /api/evenements/{id}         # Modifier un événement
DELETE /api/evenements/{id}         # Supprimer un événement
```

## 🧪 Tests

```bash
# Tests unitaires
php artisan test

# Tests avec coverage
php artisan test --coverage
```

## 📝 Logs

Les logs sont stockés dans `storage/logs/` avec les niveaux :
- `🚀` Démarrage d'action
- `✅` Succès
- `❌` Erreur
- `🔄` Traitement en cours
- `📧` Envoi d'email

## 🤝 Contribution

1. Fork le projet
2. Créer une branche feature (`git checkout -b feature/AmazingFeature`)
3. Commit vos changements (`git commit -m 'Add AmazingFeature'`)
4. Push vers la branche (`git push origin feature/AmazingFeature`)
5. Ouvrir une Pull Request

## 📄 Licence

Ce projet est sous licence [MIT](LICENSE).

## 👨‍💻 Équipe de développement

- **Backend** : Laravel + Spatie Permission
- **Frontend** : Blade + Alpine.js + TailwindCSS
- **SSO** : Wallix OIDC Integration
- **Database** : MySQL avec migrations complètes
