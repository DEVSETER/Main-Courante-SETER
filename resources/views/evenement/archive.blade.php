<x-layout.default>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="/assets/js/simple-datatables.js"></script>
    @section('head')
    <!-- NOUVEAU : Scripts pour SimpleDatatables -->
    <script src="/assets/js/simple-datatables.js"></script>
    <style>
        /* Styles personnalisés pour SimpleDatatables */
        /* Dans votre section <style>, ajouter : */

/* Empêcher les doublons visuels */
.page-numbers button {
    min-width: 36px;
    height: 36px;
    margin: 0 1px;
}

.page-numbers button:disabled {
    opacity: 1;
    cursor: default;
}

/* Style spécial pour la page courante */
.btn-primary:disabled {
    background-color: #007bff !important;
    border-color: #007bff !important;
    color: white !important;
}

/* Éviter les répétitions de boutons */
.pagination-controls button {
    flex-shrink: 0;
}

.quick-nav input {
    border: 1px solid #007bff;
}

.quick-nav input:focus {
    outline: none;
    box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.25);
}
        .dataTable-wrapper {
            margin-top: 20px;
        }
        .dataTable-top, .dataTable-bottom {
            padding: 10px 0;
        }
        .dataTable-search {
            margin-bottom: 15px;
        }
        .dataTable-pagination {
            display: flex;
            justify-content: center;
            gap: 5px;
        }
        .dataTable-pagination a, .dataTable-pagination span {
            padding: 8px 12px;
            border: 1px solid #ddd;
            text-decoration: none;
            color: #333;
        }
        .dataTable-pagination .active {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }

        /* Classes utilitaires pour corriger l'affichage */
.text-center {
    text-align: center;
}

.text-sm {
    font-size: 14px;
}

.text-xs {
    font-size: 12px;
}

.text-gray-600 {
    color: #6b7280;
}

.text-gray-500 {
    color: #9ca3af;
}

.text-blue-600 {
    color: #2563eb;
}

.btn-xs {
    padding: 2px 6px;
    font-size: 10px;
    line-height: 1.2;
}

.btn-outline-secondary {
    background: transparent;
    /* border: 1px solid #6c757d; */
    /* color: #6c757d; */
}

.btn-primary {
    background: #007bff;
    border: 1px solid #007bff;
    color: white;
}

/* Correction pour éviter les conflits */
.pagination-container * {
    box-sizing: border-box;
}
    </style>
@endsection

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #D3D3D3 0%, #D3D3D3 100%);
            min-height: 100vh;
            padding: 20px;
            width: 100%;
        }

        .container {
            max-width: 95%;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
        }

        .header h1 {
            color: #2c3e50;
            font-size: 2.5rem;
            margin-bottom: 10px;
            font-weight: 700;
        }

        .header p {
            color: #7f8c8d;
            font-size: 1.1rem;
        }

        .nav-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: center;
            margin-bottom: 30px;
        }

        /* .nav-btn {
            background: linear-gradient(45deg, #ebba7d, #ebba7d);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 25px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
        } */

        /* .nav-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(52, 152, 219, 0.4);
        }

        .nav-btn.active {
            background: linear-gradient(45deg, #67152e, #67152e);
            box-shadow: 0 4px 15px rgba(231, 76, 60, 0.3);
        } */
         /* AJOUTER CES STYLES dans la section <style> existante */

/* Styles pour le loader */
/* REMPLACER tous les styles de pagination par ceux-ci : */

/* Styles pour la pagination - VERSION CORRIGÉE */
.pagination-container {
    margin-top: 20px;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 8px;
    border: 1px solid #e5e5e5;
}

.pagination-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    flex-wrap: wrap;
    gap: 10px;
}

.pagination-controls {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
    margin-bottom: 10px;
}

.pagination-controls button {
    min-width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    background: white;
    color: #333;
    font-weight: 500;
    transition: all 0.2s ease;
    cursor: pointer;
}

.pagination-controls button:hover:not(:disabled) {
    background: #007bff;
    color: white;
    border-color: #007bff;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,123,255,0.3);
}

.pagination-controls button:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    background: #f8f9fa;
    /* color: #6c757d; */
}

/* Style spécial pour l'indicateur de page courante */
.current-page-indicator {
    padding: 8px 16px !important;
    background: #007bff !important;
    color: white !important;
    border: 2px solid #007bff !important;
    border-radius: 6px !important;
    font-weight: bold !important;
    min-width: 50px !important;
}

/* Styles pour les petits boutons de pages */
.page-numbers-small {
    display: flex;
    justify-content: center;
    gap: 3px;
    margin-top: 10px;
    flex-wrap: wrap;
}

.page-numbers-small button {
    min-width: 32px !important;
    height: 32px !important;
    padding: 4px 8px !important;
    font-size: 12px !important;
    border-radius: 4px !important;
}

.page-numbers-small button.btn-primary {
    background: #007bff !important;
    border-color: #007bff !important;
    color: white !important;
}

.page-numbers-small button.btn-outline-secondary {
    background: white !important;
    border-color: #ddd !important;
    color: #666 !important;
}

.page-numbers-small button.btn-outline-secondary:hover {
    background: #e9ecef !important;
    border-color: #007bff !important;
    color: #007bff !important;
}

/* Navigation rapide */
.quick-nav {
    display: flex;
    align-items: center;
    gap: 8px;
}

.quick-nav input {
    width: 60px;
    padding: 6px 8px;
    border: 1px solid #007bff;
    border-radius: 4px;
    text-align: center;
    font-size: 14px;
}

.quick-nav input:focus {
    outline: none;
    box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.25);
    border-color: #0056b3;
}

/* Responsive */
@media (max-width: 768px) {
    .pagination-info {
        flex-direction: column;
        text-align: center;
        gap: 10px;
    }

    .pagination-controls {
        gap: 5px;
    }

    .pagination-controls button {
        min-width: 36px;
        height: 36px;
        font-size: 14px;
    }

    .page-numbers-small {
        gap: 2px;
    }

    .page-numbers-small button {
        min-width: 28px !important;
        height: 28px !important;
        font-size: 11px !important;
    }
}
.loader-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

.loader {
    width: 50px;
    height: 50px;
    border: 5px solid #f3f3f3;
    border-top: 5px solid #3498db;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Styles pour les notifications */
.notification {
    position: fixed;
    top: 20px;
    right: 20px;
    max-width: 400px;
    padding: 16px 20px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    z-index: 9998;
    animation: slideInRight 0.3s ease-out;
}

@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}
/* CORRIGER - Remplacer les classes existantes par : */
.notification {
    position: fixed;
    top: 20px;
    right: 20px;
    max-width: 400px;
    padding: 16px 20px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    z-index: 9998;
}

/* Types de notifications - NOMS CORRECTS */
.notification.success {
    background: #d4edda;
    border-left: 4px solid #28a745;
    color: #155724;
}

.notification.error {
    background: #f8d7da;
    border-left: 4px solid #dc3545;
    color: #721c24;
}

.notification.warning {
    background: #fff3cd;
    border-left: 4px solid #ffc107;
    color: #856404;
}

.notification.info {
    background: #d1ecf1;
    border-left: 4px solid #17a2b8;
    color: #0c5460;
}
.nav-btn {
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        background-color: #ebba7d;
        color: #67152e;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
    }

    .nav-btn:hover {
        background-color: #d4a76f;
    }

    .nav-btn.active {
        background-color: #67152e;
        color: #fff;
        box-shadow: 0 0 0 3px #ebba7d;
    }

    .nav-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }
        .controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary {
               background-color: #dbeafe;  /* bleu très clair */
    color: #1e40af;             /* bleu doux */
    border: none;
    padding: 6px 12px;
    border-radius: 999px;
    font-size: 14px;
    cursor: pointer;
    transition: background-color 0.2s ease;
        }
        .btn-primary:hover {
    background-color: #93c5fd;  /* bleu un peu plus vif au hover */
}
        .btn-secondary {
                border-color: rgb(226 160 63 / var(--tw-border-opacity));
                    background-color: rgb(226 160 63 / var(--tw-bg-opacity));
            color: white;
        }

        .btn-danger {
             background-color: #fde8e8;  /* rouge très clair */
            color: #b91c1c;             /* rouge doux */
            border: none;
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.2s ease;
                }
        .btn-danger:hover {
    background-color: #fca5a5;  /* rouge un peu plus vif au hover */
}
        .btn:hover {
            transform: translateY(-1px);
        }

        .table-container {
            overflow-x: auto;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

       /* Tableau avec largeur minimale et retour à la ligne autorisé */
table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    table-layout: auto; /* Largeurs automatiques au lieu de fixed */
}

th, td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ecf0f1;
    min-width: 200px; /* Largeur minimale de 200px */
    max-width: 400px; /* Largeur maximale pour éviter des colonnes trop larges */
    word-wrap: break-word; /* Permet de couper les mots longs */
    white-space: normal; /* Permet le retour à la ligne */
    vertical-align: top; /* Aligne le contenu en haut des cellules */
}

/* Exceptions pour certaines colonnes spécifiques */
th:nth-child(1), td:nth-child(1) { /* Numéro/ID */
    min-width: 80px;
    max-width: 120px;
}

th:nth-child(2), td:nth-child(2) { /* Date et heure */
    min-width: 140px;
    max-width: 180px;
}

/* Colonne Description - peut être plus large */
th:nth-child(5), td:nth-child(5) { /* Description */
    min-width: 200px;
    max-width: 500px; /* Plus large pour la description */
    white-space: normal; /* Permet explicitement le retour à la ligne */
    word-break: break-word; /* Coupe les mots longs si nécessaire */
}

/* Pour les badges, garder une taille raisonnable */
.status-badge {
    padding: 4px 8px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    display: inline-block;
    white-space: nowrap; /* Les badges restent sur une ligne */
    max-width: 100px;
    overflow: hidden;
    text-overflow: ellipsis;
}

.badge-outline {
    display: inline-block;
    padding: 3px 8px;
    border-radius: 15px;
    font-size: 11px;
    font-weight: 600;
    background: transparent;
    white-space: nowrap; /* Les badges restent sur une ligne */
    max-width: 100px;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Pour les boutons d'action - plus compact */
th:last-child, td:last-child { /* Colonne Opérations */
    min-width: 120px;
    max-width: 150px;
    text-align: center;
}

.action-buttons {
    display: flex;
    gap: 3px;
    white-space: nowrap;
    justify-content: center;
}

.btn-small {
    padding: 4px 6px;
    font-size: 11px;
    border-radius: 4px;
    min-width: 28px;
}

/* Ajuster la largeur du conteneur de table */
/* .table-container {
    overflow-x: auto;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    max-width: 100%;
} */

/* Style pour les en-têtes */
/* th {
    border-top: 1px solid #e5e7eb !important;
    border-bottom: 1px solid #e5e7eb !important;
    border-left: none !important;
    border-right: none !important;
    background: #e5e7eb;
    padding: 8px 12px;
    font-weight: 600;
} */

/* Améliorer l'affichage au survol */
/* Styles pour le bouton désarchiver */
/* Style pour les champs en lecture seule */
.form-control[readonly] {
    background-color: #f8f9fa !important;
    cursor: not-allowed !important;
    opacity: 0.8;
    border-color: #e9ecef !important;
}

.form-control:disabled {
    background-color: #f8f9fa !important;
    cursor: not-allowed !important;
    opacity: 0.8;
    border-color: #e9ecef !important;
}

/* Style pour l'alerte d'information */
.alert-info {
    background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
    border-left: 4px solid #17a2b8;
    font-size: 14px;
    line-height: 1.5;
}

/* Style pour le bouton de désarchivage */
.btn-success {
    background-color: #28a745;
    border: 1px solid #28a745;
    color: white;
    transition: all 0.3s ease;
}

.btn-success:hover {
    background-color: #218838;
    border-color: #1e7e34;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(40, 167, 69, 0.3);
}

/* Styles pour les éléments en lecture seule */
.readonly-section {
    opacity: 0.8;
    background: #f8f9fa;
    border-radius: 8px;
    padding: 10px;
    margin: 5px 0;
}
.btn-secondary {
    background-color: #fff7ed; /* Orange très clair, élégant */
    color: #c2410c;             /* Orange doux pour le texte */
    border: 1px solid #fed7aa;  /* Bordure orange claire */
    transition: all 0.3s ease;
}

.btn-secondary:hover {
    background-color: #ffedd5; /* Orange un peu plus prononcé au hover */
    border-color: #fb923c;      /* Bordure plus vive au hover */
    color: #ffc107;             /* Texte plus foncé au hover */
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(194, 65, 12, 0.2);
}
/* Icône spéciale pour désarchiver */
.btn-secondary svg {
    color: #ffc107; /* Couleur dorée pour l'icône */
}

.btn-secondary svg {
    color: currentColor; /* Utilise la couleur du texte du bouton */
    transition: all 0.3s ease;
}

.btn-secondary:hover svg {
    color: currentColor; /* Garde la cohérence avec le texte */
    transform: scale(1.1);  /* Léger agrandissement au hover */
}
tr:hover {
    background: #ffc107;
}

/* Pour les cellules éditables */
.editable-row {
    background: #fff3cd !important;
    border: 2px solid #ffc107;
}

.editable-cell {
    background: white;
    border: 1px solid #007bff;
    padding: 8px;
    border-radius: 4px;
    width: 100%;
    min-height: 34px;
}
table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    table-layout: auto; /* Largeurs automatiques au lieu de fixed */
}

th, td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ecf0f1;
    min-width: 250px; /* Largeur minimale de 200px */
    max-width: 400px; /* Largeur maximale pour éviter des colonnes trop larges */
    word-wrap: break-word; /* Permet de couper les mots longs */
    white-space: normal; /* Permet le retour à la ligne */
    vertical-align: top; /* Aligne le contenu en haut des cellules */
}

/* Exceptions pour certaines colonnes spécifiques */
th:nth-child(1), td:nth-child(1) { /* Numéro/ID */
    min-width: 180px;
    max-width: 120px;
}

th:nth-child(2), td:nth-child(2) { /* Date et heure */
    min-width: 240px;
    max-width: 250px;
}

/* Colonne Description - peut être plus large */
th:nth-child(5), td:nth-child(5) { /* Description */
    min-width: 200px;
    max-width: 500px; /* Plus large pour la description */
    white-space: normal; /* Permet explicitement le retour à la ligne */
    word-break: break-word; /* Coupe les mots longs si nécessaire */
}

/* Pour les badges, garder une taille raisonnable */
.status-badge {
    padding: 4px 8px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    display: inline-block;
    white-space: nowrap; /* Les badges restent sur une ligne */
    max-width: 100px;
    overflow: hidden;
    text-overflow: ellipsis;
}

.badge-outline {
    display: inline-block;
    padding: 3px 8px;
    border-radius: 15px;
    font-size: 11px;
    font-weight: 600;
    background: transparent;
    white-space: nowrap; /* Les badges restent sur une ligne */
    max-width: 100px;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Pour les boutons d'action - plus compact */
th:last-child, td:last-child { /* Colonne Opérations */
    min-width: 120px;
    max-width: 150px;
    text-align: center;
}

.action-buttons {
    display: flex;
    gap: 3px;
    white-space: nowrap;
    justify-content: center;
}

.btn-small {
    padding: 4px 6px;
    font-size: 11px;
    border-radius: 4px;
    min-width: 28px;
}

/* Ajuster la largeur du conteneur de table */
.table-container {
    overflow-x: auto;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    max-width: 100%;
}

/* Style pour les en-têtes */
th {
    border-top: 1px solid #e5e7eb !important;
    border-bottom: 1px solid #e5e7eb !important;
    border-left: none !important;
    border-right: none !important;
    background: #e5e7eb;
    padding: 8px 12px;
    font-weight: 600;
}

/* Améliorer l'affichage au survol */
tr:hover {
    background: #f8f9fa;
}

/* Pour les cellules éditables */
.editable-row {
    background: #fff3cd !important;
    border: 2px solid #ffc107;
}

.editable-cell {
    background: white;
    border: 1px solid #007bff;
    padding: 8px;
    border-radius: 4px;
    width: 100%;
    min-height: 34px;
}

/* Responsive pour petits écrans */
@media (max-width: 1024px) {
    table {
        min-width: 1000px; /* Force le scroll horizontal sur petits écrans */
    }

    th, td {
        min-width: 150px; /* Réduire légèrement sur petits écrans */
        padding: 8px;
    }
}

        .editable-row {
            background: #fff3cd !important;
            border: 2px solid #ffc107;
        }

        .editable-cell {
            background: white;
            border: 1px solid #007bff;
            padding: 8px;
            border-radius: 4px;
            width: 100%;
        }

     /* Styles pour le modal - VERSION CORRIGÉE */
.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
    padding: 20px;
    box-sizing: border-box;
}

.modal-content {
    background: white;
    border-radius: 15px;
    width: 100%;
    max-width: 800px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
    position: relative;
    margin: auto;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 30px;
    border-bottom: 2px solid #ecf0f1;
    background: #f8f9fa;
    border-radius: 15px 15px 0 0;
    position: sticky;
    top: 0;
    z-index: 1001;
}

.modal-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2c3e50;
    margin: 0;
}

.close-btn {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    color: #7f8c8d;
    padding: 5px;
    border-radius: 50%;
    width: 35px;
    height: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}

.close-btn:hover {
    background: #e74c3c;
    color: white;
    transform: scale(1.1);
}

/* Contenu du formulaire */
.modal-content form {
    padding: 30px;
}

.form-group {
    margin-bottom: 20px;
}

.form-label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #34495e;
    font-size: 14px;
}

.form-control {
    width: 100%;
    padding: 12px;
    border: 2px solid #ecf0f1;
    border-radius: 8px;
    font-size: 14px;
    transition: all 0.3s ease;
    box-sizing: border-box;
}

.form-control:focus {
    border-color: #3498db;
    outline: none;
    box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
}

/* Styles pour les séparateurs */
.form-separator {
    margin: 30px 0 20px;
    padding-bottom: 10px;
    border-bottom: 3px solid #67152e;
}

.form-separator h4 {
    color: #67152e;
    font-size: 18px;
    font-weight: 600;
    margin: 0;
}

/* Styles pour les éléments existants */
.existing-items {
    max-height: 300px;
    overflow-y: auto;
    background: #f8f9fa;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 20px;
    border: 1px solid #e9ecef;
}

/* Responsive */
@media (max-width: 768px) {
    .modal {
        padding: 10px;
    }

    .modal-content {
        max-width: 100%;
        max-height: 95vh;
    }

    .modal-header {
        padding: 15px 20px;
    }

    .modal-content form {
        padding: 20px;
    }

    .modal-title {
        font-size: 1.2rem;
    }
}

        .close-btn {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #7f8c8d;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #34495e;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            border: 2px solid #ecf0f1;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }

        .form-control:focus {
            border-color: #3498db;
            outline: none;
        }
        .flex {
    display: flex;
}

.items-center {
    align-items: center;
}

.justify-between {
    justify-content: space-between;
}

.gap-2 {
    gap: 0.5rem;
}

.mb-2 {
    margin-bottom: 0.5rem;
}

.text-xs {
    font-size: 0.75rem;
}

.text-sm {
    font-size: 0.875rem;
}

.p-1 {
    padding: 0.25rem;
}

.p-3 {
    padding: 0.75rem;
}

.rounded {
    border-radius: 0.25rem;
}

.border {
    border-width: 1px;
}

.bg-white {
    background-color: white;
}

.text-gray-500 {
    color: #6b7280;
}

.text-gray-700 {
    color: #374151;
}

.bg-blue-100 {
    background-color: #dbeafe;
}

.text-blue-800 {
    color: #1e40af;
}

/* Autres couleurs nécessaires */
.bg-yellow-100 { background-color: #fef3c7; }
.text-yellow-800 { color: #92400e; }
.bg-green-100 { background-color: #dcfce7; }
.text-green-800 { color: #166534; }
.bg-gray-100 { background-color: #f3f4f6; }
.text-gray-800 { color: #1f2937; }

        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-en-cours {
            background: #fff3cd;
            color: #856404;
        }

        .status-termine {
            background: #d4edda;
            color: #155724;
        }

        .status-urgent {
            background: #f8d7da;
            color: #721c24;
        }

        .action-buttons {
            display: flex;
            gap: 5px;
        }

        .btn-small {
            padding: 5px 10px;
            font-size: 12px;
            border-radius: 4px;
        }

        @media (max-width: 768px) {
            .nav-buttons {
                flex-direction: column;
                align-items: center;
            }

            .nav-btn {
                width: 100%;
                max-width: 300px;
            }

            .controls {
                flex-direction: column;
                align-items: stretch;
            }
        }
        // ...existing code...
.status-urgent {
    background: #f8d7da;
    color: #721c24;
}

/* Badges pour POST et Rédacteur */
.badge-outline {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 15px;
    font-size: 12px;
    font-weight: 600;
    background: transparent;
}

.post-badge {
    border: 1px solid #67152e;
    color: #67152e;
}

.redacteur-badge {
    border: 1px solid #3498db;
    color: #3498db;
}

/* Styles pour les actions existantes */
.form-separator {
    margin: 20px 0 10px;
    padding-bottom: 5px;
    border-bottom: 2px solid #67152e;
}

.form-separator h4 {
    color: #67152e;
    font-size: 16px;
    font-weight: 600;
}

.existing-items {
    max-height: 200px;
    overflow-y: auto;
    background: #f8f9fa;
    border-radius: 8px;
    padding: 10px;
    margin-bottom: 15px;
}

.item {
    margin-bottom: 10px;
    padding: 10px;
    border-radius: 5px;
    background: white;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.action-item {
    border-left: 3px solid #67152e;
}

.item-header {
    display: flex;
    justify-content: space-between;
    font-size: 12px;
    color: #666;
    margin-bottom: 5px;
}

.item-type {
    font-weight: bold;
    color: #67152e;
}

.item-text {
    margin-bottom: 5px;
}

.item-author {
    font-size: 12px;
    font-style: italic;
    text-align: right;
    color: #666;
}

/* Symbole de séparation entre les items */
.item:not(:last-child)::after {
    content: "• • •";
    display: block;
    text-align: center;
    color: #ccc;
    margin: 10px 0;
}
.form-group[x-show="field.key === 'new_comment'"] {
    border: 1px solid #3498db;
    padding: 15px;
    border-radius: 8px;
    margin-top: 20px;
    background-color: #f0f8ff;
}

.form-group[x-show="field.key === 'new_comment'"] .form-label {
    color: #3498db;
    font-weight: bold;
}
.form-group[x-show="field.key === 'new_comment'"] {
    display: block !important;
    border: 2px solid red;
}
/* Pour rendre visible la zone d'actions */
.actions-list-container {
    /* border: 2px dashed blue !important; Bordure bleue en pointillés */
    padding: 10px !important;
    margin: 10px 0;
    background:
}
.status-badge {
    width: 100px !important;

}

/* Pour voir la section même si elle est vide */
.existing-items:empty::before {
    content: "Aucune action trouvée";
    display: block;
    color: red;
    font-style: italic;
    padding: 10px;
}
/* Empêcher le retour à la ligne dans les cellules du tableau */
table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    table-layout: fixed;
}

th, td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ecf0f1;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 150px;
}

th:nth-child(1), td:nth-child(1) {
    width: 80px;
    max-width: 80px;
}

th:nth-child(2), td:nth-child(2) {
    width: 120px;
    max-width: 120px;
}

th:nth-child(3), td:nth-child(3) { /* Nature */
    width: 150px;
    max-width: 150px;
}

th:nth-child(4), td:nth-child(4) { /* Localisation */
    width: 120px;
    max-width: 120px;
}

th:nth-child(5), td:nth-child(5) { /* Description */
    width: 200px;
    max-width: 200px;
}

.status-badge {
    padding: 4px 8px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    display: inline-block;
    white-space: nowrap;
    max-width: 90px;
    overflow: hidden;
    text-overflow: ellipsis;
}

.badge-outline {
    display: inline-block;
    padding: 3px 8px;
    border-radius: 15px;
    font-size: 11px;
    font-weight: 600;
    background: transparent;
    white-space: nowrap;
    max-width: 80px;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Pour les boutons d'action */
.action-buttons {
    display: flex;
    gap: 3px;
    white-space: nowrap;
}

.btn-small {
    padding: 4px 8px;
    font-size: 11px;
    border-radius: 4px;
    min-width: 30px;
}

/* Ajuster la largeur du conteneur de table pour permettre le scroll horizontal si nécessaire */
.table-container {
    overflow-x: auto;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    max-width: 100%;
}

/* Pour les colonnes spécifiques, vous pouvez ajuster selon vos besoins */
.status-badge {
    width: 80px !important;
    max-width: 80px !important;
}
.soft-yellow-btn {
    background-color: #fef9c3;
    color: #92400e;
    border: none;
    padding: 6px 12px;
    border-radius: 999px;
    font-size: 14px;
    cursor: pointer;
    transition: background-color 0.2s ease;
}

.soft-yellow-btn:hover {
    background-color: #fde68a;
}
/* Tooltip pour voir le contenu complet au survol */
td[title] {
    cursor: help;
}
/* Styles pour l'édition d'actions */
.action-editing {
    border-color: #3b82f6 !important;
    background-color: #eff6ff;
}

.action-buttons {
    transition: all 0.2s ease;
}

.action-buttons button {
    transition: all 0.2s ease;
    border-radius: 4px;
    padding: 4px;
}

.action-buttons button:hover {
    transform: scale(1.1);
}

/* Animation pour la suppression */
.action-removing {
    animation: slideOut 0.3s ease-out forwards;
}

@keyframes slideOut {
    0% {
        opacity: 1;
        transform: translateX(0);
    }
    100% {
        opacity: 0;
        transform: translateX(-20px);
    }
}

/* Styles pour les champs d'édition d'action */
.action-editing .form-control {
    font-size: 12px;
    padding: 6px 8px;
    border: 1px solid #d1d5db;
    border-radius: 4px;
}

.action-editing .form-label {
    font-size: 11px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 3px;
}

.action-editing .form-group {
    margin-bottom: 8px;
}
    </style>
</head>
<body>
    <div class="container" x-data="mainCouranteApp()">
        <div class="header">
            <h1>📋 Main Courante</h1>
            <p>liste des événements Archivé</p>
        </div>

        <!-- Navigation des types de main courante -->
        <div class="nav-buttons">
    @if(auth()->user()->entite->code === 'PTP' || auth()->user()->entite->code === 'SR COF')
        <button class="nav-btn"
                :class="{ 'active': activeTab === 'SRCOF' }"
                @click="switchTab('SRCOF')">
            MAIN COURANTE SRCOF
        </button>


    @endif

    @if(auth()->user()->entite->code === 'CIV' || auth()->user()->entite->code === 'SR COF')
        <button class="nav-btn"
                :class="{ 'active': activeTab === 'CIV' }"
                @click="switchTab('CIV')">
            MAIN COURANTE CIV
        </button>
    @endif

    @if(auth()->user()->entite->code === 'HC' || auth()->user()->entite->code === 'SR COF')
        <button class="nav-btn"
                :class="{ 'active': activeTab === 'HOTLINE' }"
                @click="switchTab('HOTLINE')">
            MAIN COURANTE HOTLINE
        </button>
    @endif

    @if(auth()->user()->entite->code === 'CM' || auth()->user()->entite->code === 'SR COF')
        <button class="nav-btn"
                :class="{ 'active': activeTab === 'CM' }"
                @click="switchTab('CM')">
            MAIN COURANTE CM
        </button>
    @endif

    @if(auth()->user()->entite->code === 'PTP' || auth()->user()->entite->code === 'SR COF')
        <button class="nav-btn"
                :class="{ 'active': activeTab === 'PTP' }"
                @click="switchTab('PTP')">
            MAIN COURANTE PTP
        </button>
    @endif
</div>
        <!-- Contrôles -->
        {{-- <div class="controls">
            <div style="display: flex;flex-direction: column; justify-content: space-between; align-items: center;border: 1px solid #ccc; padding: 3px; border-radius: 5px;">
                <button class="btn btn-primary" @click="openCreateModal()" >
                    ➕ Créer un événement
                </button>
                <button class="bg-yellow-100 text-yellow-800 hover:bg-yellow-200 hover:text-yellow-900 px-3 py-1 rounded-full text-sm soft-yellow-btn" @click="toggleEditMode()"
                        :class="{ 'btn-danger': editMode }" style="margin-top: 10px">
                    <span x-text="editMode ? '❌ Désactiver édition' : '✏️ Activer mode édition'"></span>
                </button>
            </div>
            <div>
                <span x-text="`${getCurrentData().length} événements`" class="text-muted"></span>
            </div>
        </div> --}}
        <!-- NOUVEAU : Barre de recherche et contrôles -->
<div class="search-and-controls" style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; gap: 15px; flex-wrap: wrap;">

    <!-- Barre de recherche -->
    <div class="search-container" style="flex: 1; min-width: 300px;">
        <div style="position: relative;">
            <input type="text"
                   x-model="searchQuery"
                   @input="onSearchInput()"
                   placeholder="🔍 Rechercher dans les événements..."
                   class="form-control"
                   style="padding-left: 40px; padding-right: 100px;">

            <!-- Bouton effacer recherche -->
            <button x-show="searchQuery !== ''"
                    @click="clearSearch()"
                    style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #999; cursor: pointer;"
                    title="Effacer la recherche">
                ✕
            </button>
        </div>

        <!-- Indicateur de résultats -->
        <div x-show="searchQuery !== ''" class="text-xs text-gray-600" style="margin-top: 5px;">
            <span x-text="totalItems"></span> résultat(s) trouvé(s) sur <span x-text="paginationInfo.totalOriginal"></span> événements
        </div>
    </div>

    <!-- Sélecteur nombre d'éléments par page -->
    <div class="per-page-selector" style="display: flex; align-items: center; gap: 10px;">
        <label class="text-sm">Afficher :</label>
        <select x-model="pagination.itemsPerPage"
                @change="changeItemsPerPage($event.target.value)"
                class="form-control"
                style="width: auto; min-width: 80px;">
            <template x-for="option in pagination.itemsPerPageOptions" :key="option">
                <option :value="option" x-text="option"></option>
            </template>
        </select>
        <span class="text-sm">par page</span>
    </div>
</div>

        <!-- Tableau dynamique -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th x-show="editMode">✓</th>
                        <template x-for="column in getCurrentColumns()">
                            <th x-text="column"></th>
                        </template>
                    </tr>
                </thead>
                <tbody>
        <template x-for="(item, index) in getPaginatedData()" :key="item.id || index">
                <tr :class="{ 'editable-row': editMode && item.editing }"
                    @click="editMode ? toggleRowEdit(index) : openEditModal(index)">
                    <td x-show="editMode">
                        <input type="checkbox" x-model="item.editing" @click.stop>
                    </td>
                            <template x-for="(column, colIndex) in getCurrentColumns()" :key="colIndex">
                                <td>
                                    <div x-show="!editMode || !item.editing">
                                        <span x-show="column === 'Statut'"
                                              :class="`status-badge status-${item.statut?.toLowerCase().replace(' ', '-')}` "
                                              x-text="item[getColumnKey(column)]"></span>
                                        <span x-show="column === 'POST'"
                                              class="badge-outline post-badge"
                                              x-text="item[getColumnKey(column)]"></span>
                                        <span x-show="column === 'Rédacteur'"
                                              class="badge-outline redacteur-badge"
                                              x-text="item[getColumnKey(column)]"></span>
                                        <span x-show="column === 'Opérations'">
    <div class="action-buttons">
        <!-- NOUVEAU : Bouton désarchiver -->
        <button class="btn btn-small btn-secondary "
                @click.stop="unarchiveEventFromTable(index)"
                title="Désarchiver cet événement">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12ZM5.46056 11.0833C5.83331 7.79988 8.62404 5.25 12.0096 5.25C14.148 5.25 16.0489 6.26793 17.2521 7.84246C17.5036 8.17158 17.4406 8.64227 17.1115 8.89376C16.7824 9.14526 16.3117 9.08233 16.0602 8.7532C15.1289 7.53445 13.6613 6.75 12.0096 6.75C9.45213 6.75 7.33639 8.63219 6.9733 11.0833H7.33652C7.63996 11.0833 7.9135 11.2662 8.02953 11.5466C8.14556 11.8269 8.0812 12.1496 7.86649 12.364L6.69823 13.5307C6.40542 13.8231 5.9311 13.8231 5.63829 13.5307L4.47003 12.364C4.25532 12.1496 4.19097 11.8269 4.30699 11.5466C4.42302 11.2662 4.69656 11.0833 5 11.0833H5.46056ZM18.3617 10.4693C18.0689 10.1769 17.5946 10.1769 17.3018 10.4693L16.1335 11.636C15.9188 11.8504 15.8545 12.1731 15.9705 12.4534C16.0865 12.7338 16.3601 12.9167 16.6635 12.9167H17.0267C16.6636 15.3678 14.5479 17.25 11.9905 17.25C10.3464 17.25 8.88484 16.4729 7.9529 15.2638C7.70002 14.9358 7.22908 14.8748 6.90101 15.1277C6.57295 15.3806 6.512 15.8515 6.76487 16.1796C7.96886 17.7416 9.86205 18.75 11.9905 18.75C15.376 18.75 18.1667 16.2001 18.5395 12.9167H19C19.3035 12.9167 19.577 12.7338 19.693 12.4534C19.8091 12.1731 19.7447 11.8504 19.53 11.636L18.3617 10.4693Z" fill="currentColor"></path>
                                            </svg>
        </button>

        <!-- Garder le bouton supprimer -->
        <button class="btn btn-small btn-danger" @click.stop="deleteItem(index)">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path opacity="0.5" d="M11.5956 22.0001H12.4044C15.1871 22.0001 16.5785 22.0001 17.4831 21.1142C18.3878 20.2283 18.4803 18.7751 18.6654 15.8686L18.9321 11.6807C19.0326 10.1037 19.0828 9.31524 18.6289 8.81558C18.1751 8.31592 17.4087 8.31592 15.876 8.31592H8.12405C6.59127 8.31592 5.82488 8.31592 5.37105 8.81558C4.91722 9.31524 4.96744 10.1037 5.06788 11.6807L5.33459 15.8686C5.5197 18.7751 5.61225 20.2283 6.51689 21.1142C7.42153 22.0001 8.81289 22.0001 11.5956 22.0001Z" fill="currentColor"></path>
                <path d="M3 6.38597C3 5.90152 3.34538 5.50879 3.77143 5.50879L6.43567 5.50832C6.96502 5.49306 7.43202 5.11033 7.61214 4.54412C7.61688 4.52923 7.62232 4.51087 7.64185 4.44424L7.75665 4.05256C7.8269 3.81241 7.8881 3.60318 7.97375 3.41617C8.31209 2.67736 8.93808 2.16432 9.66147 2.03297C9.84457 1.99972 10.0385 1.99986 10.2611 2.00002H13.7391C13.9617 1.99986 14.1556 1.99972 14.3387 2.03297C15.0621 2.16432 15.6881 2.67736 16.0264 3.41617C16.1121 3.60318 16.1733 3.81241 16.2435 4.05256L16.3583 4.44424C16.3778 4.51087 16.3833 4.52923 16.388 4.54412C16.5682 5.11033 17.1278 5.49353 17.6571 5.50879H20.2286C20.6546 5.50879 21 5.90152 21 6.38597C21 6.87043 20.6546 7.26316 20.2286 7.26316H3.77143C3.34538 7.26316 3 6.87043 3 6.38597Z" fill="currentColor"></path>
                <path fill-rule="evenodd" clip-rule="evenodd" d="M9.42543 11.4815C9.83759 11.4381 10.2051 11.7547 10.2463 12.1885L10.7463 17.4517C10.7875 17.8855 10.4868 18.2724 10.0747 18.3158C9.66253 18.3592 9.29499 18.0426 9.25378 17.6088L8.75378 12.3456C8.71256 11.9118 9.01327 11.5249 9.42543 11.4815Z" fill="currentColor"></path>
                <path fill-rule="evenodd" clip-rule="evenodd" d="M14.5747 11.4815C14.9868 11.5249 15.2875 11.9118 15.2463 12.3456L14.7463 17.6088C14.7051 18.0426 14.3376 18.3592 13.9254 18.3158C13.5133 18.2724 13.2126 17.8855 13.2538 17.4517L13.7538 12.1885C13.795 11.7547 14.1625 11.4381 14.5747 11.4815Z" fill="currentColor"></path>
            </svg>
        </button>
    </div>
</span>
                                        <span x-show="column !== 'Statut' && column !== 'POST' && column !== 'Rédacteur' && column !== 'Opérations'"
                                                      x-text="item[getColumnKey(column)]"></span>
                                                </div>

                        </div>
                                  <input x-show="editMode && item.editing && column !== 'Opérations' &&
                                    column !== 'Impact' &&
                                    column !== 'Nature de l\'événement' &&
                                    column !== 'Localisation' &&
                                    column !== 'Conséquence sur le PDT' &&
                                   column !== 'Confidentialité' &&
                                    column !== 'Statut'"
                        class="editable-cell"
                        x-model="item[getColumnKey(column)]"
                        @click.stop>
                      <select x-show="editMode && item.editing && column === 'Statut'"
                            class="editable-cell"
                            x-model="item[getColumnKey(column)]"
                            @click.stop>
                        <option value="">-- Sélectionner --</option>
                        <option value="En cours">En cours</option>
                        <option value="Terminé">Terminé</option>
                        <option value="Archivé">Archivé</option>
                    </select>

                        <select x-show="editMode && item.editing && column === 'Impact'"
                                class="editable-cell"
                                x-model="item[getColumnKey(column)]"
                                @click.stop>
                            <option value="">-- Sélectionner --</option>
                            <template x-for="option in window.impacts.map(i => i.libelle)">
                                <option :value="option" x-text="option"></option>
                            </template>
                            </select>
                           <select x-show="editMode && item.editing && column === 'Nature de l\'événement'"
                                    class="editable-cell"
                                    x-model="item[getColumnKey(column)]"
                                    @click.stop>
                                <option value="">-- Sélectionner --</option>
                                <template x-for="option in window.nature_evenements.map(n => n.libelle)">
                                    <option :value="option" x-text="option"></option>
                                </template>
                            </select>
                            <div x-show="editMode && item.editing && column === 'Nature de l\'événement' && (!window.nature_evenements || window.nature_evenements.length === 0)"
                                class="alert alert-warning">
                                Aucune option disponible pour ce champ.
                            </div>
                            <!-- Select pour Localisation -->
                            <select x-show="editMode && item.editing && column === 'Localisation'"
                                    class="editable-cell"
                                    x-model="item[getColumnKey(column)]"
                                    @click.stop>
                                <option value="">-- Sélectionner --</option>
                                <template x-for="option in window.locations.map(l => l.libelle)">
                                    <option :value="option" x-text="option"></option>
                                </template>
                            </select>
                            <select x-show="editMode && item.editing && column === 'Conséquence sur le PDT'"
                                class="editable-cell"
                                x-model="item[getColumnKey(column)]"
                                @click.stop>
                            <option value="">-- Sélectionner --</option>
                            <option value="OUI">OUI</option>
                            <option value="NON">NON</option>
                        </select>
                        <!-- Select pour Confidentialité -->
                        <select x-show="editMode && item.editing && column === 'Confidentialité'"
                                class="editable-cell"
                                x-model="item[getColumnKey(column)]"
                                @click.stop>
                            <option value="">-- Sélectionner --</option>
                            <option value="Confidentiel">Confidentiel</option>
                            <option value="Non confidentiel">Non confidentiel</option>
                        </select>
                        <!-- Séparateur pour commentaires existants - SEULEMENT en édition -->


                                    <!-- Liste des commentaires existants - SEULEMENT en édition -->

                                    <!-- Commentaires existants - SEULEMENT en édition -->
<!-- Commentaires existants - SEULEMENT en édition -->
<div x-show="field.key === 'commentaires-list' && isEditing" class="existing-items comments-list-container">
    <!-- Debug temporaire pour voir les données -->
    <div style="background: yellow; padding: 5px; margin: 5px 0; font-size: 11px;">
        DEBUG: <span x-text="(currentEvent?.originalData?.commentaires || []).length"></span> commentaires trouvés
        <br>Données: <span x-text="JSON.stringify(currentEvent?.originalData?.commentaires || [])"></span>
    </div>

    <template x-for="(commentaire, commentIndex) in (currentEvent?.originalData?.commentaires || [])" :key="commentaire.id || commentIndex">
        <div class="bg-white border p-3 mb-2 rounded shadow" :class="{ 'border-green-500': commentaire.editing }">
            <!-- En-tête du commentaire avec boutons -->
            <div class="flex items-center justify-between mb-2">
                <span class="inline-block px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-800">
                    💬 Commentaire
                    <span x-show="commentaire.redacteur == window.user.id" class="ml-1 text-green-600">
                    (Votre commentaire)
                </span>
                <span x-show="commentaire.redacteur != window.user.id" class="ml-1 text-orange-600">
                    (Commentaire d'un autre utilisateur)
                </span>
                </span>

                <!-- Boutons d'action -->
                <div class="flex items-center gap-2">
                    <span class="text-xs text-gray-500" x-text="commentaire.created_at ? new Date(commentaire.created_at).toLocaleString() : 'Date inconnue'"></span>

                    <!-- Bouton modifier -->
                    <button type="button"
                            class="text-blue-500 hover:text-blue-700 hover:bg-blue-50 p-1 rounded"
                            @click="toggleCommentEdit(commentIndex)"
                            :title="commentaire.editing ? 'Annuler modification' : 'Modifier ce commentaire'">
                        ✏️
                    </button>

                    <!-- Bouton sauvegarder (visible seulement en mode édition) -->
                    <button type="button"
                            x-show="commentaire.editing"
                            class="text-green-500 hover:text-green-700 hover:bg-green-50 p-1 rounded"
                            @click="saveCommentEdit(commentIndex)"
                            title="Sauvegarder les modifications">
                        ✅
                    </button>

                    <!-- Bouton supprimer -->
                    <button type="button"
                            class="text-red-500 hover:text-red-700 hover:bg-red-50 p-1 rounded"
                            @click="deleteComment(commentIndex)"
                            title="Supprimer ce commentaire">
                        🗑️
                    </button>
                </div>
            </div>

            <!-- Contenu du commentaire (mode lecture) -->
            <div x-show="!commentaire.editing" class="mb-2">
                <p class="text-sm font-medium text-gray-700" x-text="commentaire.text || commentaire.commentaire || 'Aucun commentaire'"></p>
            </div>

            <!-- Contenu du commentaire (mode édition) -->
            <div x-show="commentaire.editing" class="mb-2">
                <div class="form-group mb-2">
                    <label class="form-label text-xs">Commentaire</label>
                    <textarea class="form-control text-sm"
                              rows="3"
                              x-model="commentaire.text"
                              placeholder="Modifier le commentaire..."></textarea>
                </div>
            </div>

<!-- Informations supplémentaires -->
<div class="flex items-center justify-between text-xs text-gray-500">
    <span>Commentaire #<span x-text="commentaire.id || commentIndex + 1"></span></span>
    <div class="text-right">
        <div x-text="commentaire.auteur ?
                    ('Par: ' + commentaire.auteur.nom + ' ' + commentaire.auteur.prenom) :
                    commentaire.redacteur == window.user.id ?
                    ('Par: ' + window.user.prenom + ' ' + window.user.nom + ' (Vous)') :
                    ('Par: Utilisateur #' + commentaire.redacteur)"></div>
        <div class="text-xs text-gray-400" x-text="commentaire.created_at ?
            'Créé le ' + new Date(commentaire.created_at).toLocaleDateString('fr-FR') + ' à ' + new Date(commentaire.created_at).toLocaleTimeString('fr-FR', {hour: '2-digit', minute: '2-digit'}) :
            'Date inconnue'"></div>
    </div>
</div>
        </div>
    </template>
</div>
                                    </div>
                                </td>
                            </template>
                        </tr>

                    </template>
                     <tr x-show="paginatedData.length === 0">
                <td :colspan="getCurrentColumns().length + (editMode ? 1 : 0)"
                    style="text-align: center; padding: 40px; color: #666;">
                    <div x-show="searchQuery !== ''">
                        🔍 Aucun événement trouvé pour "<strong x-text="searchQuery"></strong>"
                        <br>
                        <button @click="clearSearch()" class="btn btn-sm btn-secondary" style="margin-top: 10px;">
                            Effacer la recherche
                        </button>
                    </div>
                    <div x-show="searchQuery === ''">
                        📋 Aucun événement disponible
                    </div>
                </td>
            </tr>
                </tbody>
            </table>
                    </div>

        <!-- NOUVEAU : Ajouter la pagination ICI -->
        <!-- PAGINATION SIMPLIFIÉE SANS DOUBLONS -->
     <!-- PAGINATION CORRIGÉE - STRUCTURE PROPRE -->
<div class="pagination-container" x-show="totalItems > 0">

    <!-- Informations de pagination -->
    <div class="pagination-info">
        <div class="text-sm text-gray-600">
            Affichage de <strong x-text="paginationInfo.start"></strong> à <strong x-text="paginationInfo.end"></strong>
            sur <strong x-text="paginationInfo.total"></strong> événement(s)
            <span x-show="searchQuery !== ''" class="text-blue-600">
                (filtré depuis <span x-text="paginationInfo.totalOriginal"></span> événements)
            </span>
        </div>

        <!-- Navigation rapide -->
        <div class="quick-nav" x-show="totalPages > 1">
            <span class="text-xs">Page :</span>
            <input type="number"
                   min="1"
                   :max="totalPages"
                   x-model.number="pagination.currentPage"
                   @keyup.enter="goToPage(pagination.currentPage)"
                   @change="goToPage($event.target.value)">
            <span class="text-xs">/ <span x-text="totalPages"></span></span>
        </div>
    </div>

    <!-- Boutons de navigation principaux -->
    <div class="pagination-controls" x-show="totalPages > 1">

        <!-- Première page -->
        <button @click="goToPage(1)"
                :disabled="pagination.currentPage === 1"
                title="Première page">
            ⟪
        </button>

        <!-- Page précédente -->
        <button @click="prevPage()"
                :disabled="pagination.currentPage === 1"
                title="Page précédente">
            ‹
        </button>

        <!-- Indicateur page courante -->
        <div class="current-page-indicator">
            <span x-text="pagination.currentPage"></span>
        </div>

        <!-- Page suivante -->
        <button @click="nextPage()"
                :disabled="pagination.currentPage === totalPages"
                title="Page suivante">
            ›
        </button>

        <!-- Dernière page -->
        <button @click="goToPage(totalPages)"
                :disabled="pagination.currentPage === totalPages"
                title="Dernière page">
            ⟫
        </button>
    </div>

    <!-- Numéros de pages (seulement si pas trop de pages) -->
    <div class="page-numbers-small" x-show="totalPages <= 15 && totalPages > 1">
        <template x-for="p in Array.from({length: totalPages}, (_, i) => i + 1)" :key="p">
            <button @click="goToPage(p)"
                    :class="{
                        'btn-primary': p === pagination.currentPage,
                        'btn-outline-secondary': p !== pagination.currentPage
                    }"
                    x-text="p">
            </button>
        </template>
    </div>

    <!-- Message pour trop de pages -->
    <div x-show="totalPages > 15" class="text-center text-sm text-gray-500" style="margin-top: 10px;">
        <span x-text="totalPages"></span> pages au total - Utilisez la navigation rapide ci-dessus
    </div>
</div>
        <!-- FIN DE LA PAGINATION -->

<div class="modal" x-show="showModal" @click="$event.target === $event.currentTarget && closeModal()">
    <div class="modal-content">
        <!-- En-tête du modal -->
        <div class="modal-header">
            <h2 class="modal-title" x-text="'📦 Événement archivé (Lecture seule)'"></h2>
            <button class="close-btn" @click="closeModal()">×</button>
        </div>

        <!-- Message d'information TOUJOURS AFFICHÉ -->
        <div class="alert alert-info" style="margin-bottom: 20px; padding: 15px; background: #d1ecf1; border: 1px solid #bee5eb; border-radius: 8px; color: #0c5460;">
            <strong>ℹ️ Mode lecture seule :</strong> Cet événement est archivé. Toutes les données sont affichées en lecture seule. Utilisez le bouton "Désarchiver" pour le remettre en cours.
        </div>

        <!-- Formulaire EN LECTURE SEULE UNIQUEMENT -->
        <form @submit.prevent="unarchiveEvent()">
            <!-- Boucle pour générer tous les champs du formulaire -->
            <template x-for="field in getCurrentFields()" :key="field.key">
                <div>
                    <!-- Séparateurs normaux -->
                    <div x-show="field.type === 'separator' && field.key !== 'separator-actions' && field.key !== 'separator-comments'"
                         class="form-separator">
                        <h4 x-text="field.label"></h4>
                    </div>

                    <!-- Séparateur pour actions existantes -->
                    <div x-show="field.key === 'separator-actions' && (isEditing || isUnarchiving)"
                         class="form-separator">
                        <h4 x-text="field.label"></h4>
                    </div>

                    <!-- Séparateur pour commentaires existants -->
                    <div x-show="field.key === 'separator-comments' && (isEditing || isUnarchiving)"
                         class="form-separator">
                        <h4 x-text="field.label"></h4>
                    </div>

                    <!-- Champs de formulaire normaux - TOUJOURS EN LECTURE SEULE -->
                    <div class="form-group"
                         x-show="field.type !== 'separator' &&
                                 field.type !== 'actions-list' &&
                                 field.type !== 'commentaires-list' &&
                                 field.key !== 'new_comment' &&
                                 field.key !== 'type_action' &&
                                 field.key !== 'destinataires' &&
                                 field.key !== 'message_personnalise' &&
                                 (!field.condition || field.condition(currentEvent))">

                        <label class="form-label" x-text="field.label"></label>

                        <!-- Input text/datetime/date - LECTURE SEULE -->
                        <input x-show="field.type === 'text' || field.type === 'datetime-local' || field.type === 'date'"
                               class="form-control"
                               :type="field.type"
                               x-model="currentEvent[field.key]"
                               readonly
                               style="background-color: #f8f9fa; cursor: not-allowed; border-color: #e9ecef;">

                        <!-- Textarea - LECTURE SEULE -->
                        <textarea x-show="field.type === 'textarea'"
                                  class="form-control"
                                  rows="3"
                                  x-model="currentEvent[field.key]"
                                  readonly
                                  style="background-color: #f8f9fa; cursor: not-allowed; border-color: #e9ecef;"></textarea>

                        <!-- Select - DÉSACTIVÉ -->
                        <select x-show="field.type === 'select'"
                                class="form-control"
                                x-model="currentEvent[field.key]"
                                disabled
                                style="background-color: #f8f9fa; cursor: not-allowed; border-color: #e9ecef;">
                            <option value="">-- Sélectionner --</option>
                            <template x-for="option in field.options">
                                <option :value="option" x-text="option"></option>
                            </template>
                        </select>
                    </div>

                    <!-- Actions existantes - LECTURE SEULE -->
                    <div x-show="field.key === 'actions-list' && (isEditing || isUnarchiving)"
                         class="existing-items actions-list-container">

                        <!-- Message info pour lecture seule -->
                        <div style="background: #fff3cd; padding: 10px; margin-bottom: 15px; border-radius: 5px; color: #856404; font-size: 12px;">
                            📋 Actions en lecture seule - Modification impossible en mode archivé
                        </div>

                        <!-- Liste des actions -->
                        <template x-for="(action, actionIndex) in (currentEvent?.originalData?.actions || [])" :key="action.id || actionIndex">
                            <div class="bg-white border p-3 mb-2 rounded shadow"
                                 style="opacity: 0.8; background: #f8f9fa;">
                                <!-- Contenu de l'action -->
                                <div class="flex items-center justify-between mb-2">
                                    <span class="inline-block px-2 py-1 rounded text-xs font-medium"
                                          :class="{
                                            'bg-blue-100 text-blue-800': action.type === 'demande_validation',
                                            'bg-yellow-100 text-yellow-800': action.type === 'aviser',
                                            'bg-green-100 text-green-800': action.type === 'informer',
                                            'bg-gray-100 text-gray-800': action.type === 'texte_libre'
                                          }"
                                          x-text="action.type === 'demande_validation' ? '🔍 Demande de validation' :
                                                 action.type === 'aviser' ? '⚠️ Avis' :
                                                 action.type === 'informer' ? '📢 Information' :
                                                 action.type === 'texte_libre' ? '💬 Commentaire' :
                                                 (action.type || 'Action')">
                                    </span>
                                    <span class="text-xs text-gray-500"
                                          x-text="action.created_at ? new Date(action.created_at).toLocaleString() : 'Date inconnue'"></span>
                                </div>

                                <div class="mb-2">
                                    <p class="text-sm font-medium text-gray-700"
                                       x-text="action.commentaire || 'Aucun commentaire'"></p>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Commentaires existants - LECTURE SEULE -->
                    <div x-show="field.key === 'commentaires-list' && (isEditing || isUnarchiving)"
                         class="existing-items comments-list-container">

                        <!-- Message info pour lecture seule -->
                        <div style="background: #d4edda; padding: 10px; margin-bottom: 15px; border-radius: 5px; color: #155724; font-size: 12px;">
                            💬 Commentaires en lecture seule - Modification impossible en mode archivé
                        </div>

                        <!-- Liste des commentaires -->
                        <template x-for="(commentaire, commentIndex) in (currentEvent?.originalData?.commentaires || [])" :key="commentaire.id || commentIndex">
                            <div class="bg-white border p-3 mb-2 rounded shadow"
                                 style="opacity: 0.8; background: #f8f9fa;">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="inline-block px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                        💬 Commentaire
                                    </span>
                                    <span class="text-xs text-gray-500"
                                          x-text="commentaire.created_at ? new Date(commentaire.created_at).toLocaleString() : 'Date inconnue'"></span>
                                </div>

                                <div class="mb-2">
                                    <p class="text-sm font-medium text-gray-700"
                                       x-text="commentaire.text || commentaire.commentaire || 'Aucun commentaire'"></p>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Nouveau commentaire - MASQUÉ COMPLÈTEMENT -->
                    <!-- RIEN ICI - PAS DE CHAMPS DE CRÉATION EN MODE ARCHIVÉ -->

                    <!-- Type d'action - MASQUÉ COMPLÈTEMENT -->
                    <!-- RIEN ICI - PAS DE CHAMPS DE CRÉATION EN MODE ARCHIVÉ -->

                    <!-- Destinataires - MASQUÉS COMPLÈTEMENT -->
                    <!-- RIEN ICI - PAS DE CHAMPS DE CRÉATION EN MODE ARCHIVÉ -->

                    <!-- Message personnalisé - MASQUÉ COMPLÈTEMENT -->
                    <!-- RIEN ICI - PAS DE CHAMPS DE CRÉATION EN MODE ARCHIVÉ -->
                </div>
            </template>

            <!-- Boutons du formulaire - SEULEMENT FERMER ET DÉSARCHIVER -->
            <div class="form-group" style="display: flex; justify-content: space-between; align-items: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee;">
                <button type="button"
                        class="soft-yellow-btn"
                        @click="closeModal()">
                    ❌ Fermer
                </button>

                <button type="submit"
                        class="btn btn-success px-6 py-2">
                    📦➡️ Désarchiver l'événement
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Loader et notifications APRÈS le modal -->
<div x-show="isLoading" class="loader-overlay" x-transition>
    <div class="loader"></div>
</div>

<!-- Notification -->
<div x-show="notification.show"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="transform translate-x-full opacity-0"
     x-transition:enter-end="transform translate-x-0 opacity-100"
     x-transition:leave="transition ease-in duration-300"
     x-transition:leave-start="transform translate-x-0 opacity-100"
     x-transition:leave-end="transform translate-x-full opacity-0"
     class="notification"
     :class="notification.type">
    <div class="notification-header">
        <span class="notification-title" x-text="notification.title"></span>
        <button class="notification-close" @click="hideNotification()">×</button>
    </div>
    <div class="notification-message" x-text="notification.message"></div>
</div>




    <!-- Notification -->







<script>
        window.user = {
            name: "{{ auth()->user()->nom }}",
            nom_complet: "{{ auth()->user()->prenom }} {{ auth()->user()->nom }}",
            entiteCode: "{{ auth()->user()->entite ? auth()->user()->entite->code : '' }}",
            id: {{ auth()->user()->id }}
        };

window.entites = @json($entites ?? []);
window.utilisateurs = @json($utilisateurs ?? []);
window.nature_evenements = @json($nature_evenements ?? []);
window.evenements = @json($evenements ?? []);
window.evenementsSRCOF = @json($evenementsSRCOF ?? []);
window.evenementsCIV = @json($evenementsCIV ?? []);
window.evenementsHOTLINE = @json($evenementsHOTLINE ?? []);
window.evenementsCM = @json($evenementsCM ?? []);
window.evenementsPTP = @json($evenementsPTP ?? []);
window.locations = @json($locations ?? []);
window.impacts = @json($impacts ?? []);
window.listesDiffusion = @json($listesDiffusion ?? []);
window.users = @json($users ?? []);

// Fonction pour transformer les données du contrôleur en format adapté à notre interface
function transformEventsForUI(events) {
    return events.map(evt => ({
        numero: evt.id || '',
        dateHeure: evt.date_evenement || '',
        nature: evt.nature_evenement ? evt.nature_evenement.libelle : (evt.nature_evenement_id || ''),
        localisation: evt.location ? evt.location.libelle : (evt.location_id || ''),
        description: evt.description || '',
        consequence: evt.consequence_sur_pdt == 1 ? 'OUI' : evt.consequence_sur_pdt == 0 ? 'NON' : '',
        redacteur: evt.redacteur || '',
       statut: evt.statut === 'en_cours' ? 'En cours' :
                evt.statut === 'cloture' ? 'Terminé' :
                evt.statut === 'archive' ? 'Archivé' : '',
        descriptionLocalisation: evt.location_description || '',
        dateCloture: evt.date_cloture || '',
        confidentialite: evt.confidentialite == 1 ? 'Confidentiel' : evt.confidentialite == 0 ? 'Non confidentiel' : '',
        impact: evt.impact && evt.impact.libelle ? evt.impact.libelle : (evt.impact_id ? window.impacts.find(i => i.id === evt.impact_id)?.libelle : ''),
        heureAppelIntervenant: evt.heure_appel_intervenant || '',
        heureArriveeIntervenant: evt.heure_arrive_intervenant || '',
        commentaire: evt.commentaires && evt.commentaires.length ?
        evt.commentaires.map(c => c.text || 'Commentaire vide').join(', ') : '',

        action: evt.actions && evt.actions.length ? evt.actions.map(a => a.commentaire).join(', ') : '',
        post: evt.entite ? evt.entite.code : '',
        pieceJointe: evt.piece_jointe || '',
        date: evt.date_evenement ? new Date(evt.date_evenement).toLocaleDateString('fr-FR') : '',
        heure: evt.date_evenement ? new Date(evt.date_evenement).toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' }) : '',
        semaine: evt.date_evenement ? `Semaine ${new Date(evt.date_evenement).getWeek ? new Date(evt.date_evenement).getWeek() : Math.ceil((new Date(evt.date_evenement) - new Date(new Date(evt.date_evenement).getFullYear(), 0, 1)) / 604800000)}` : '',
        title: evt.id || '',
        id: evt.id || '',
        editing: false,
        originalData: {
            ...evt,
            // S'assurer que les commentaires ont la bonne structure
            commentaires: evt.commentaires ? evt.commentaires.map(comment => ({
                id: comment.id,
                text: comment.text || '',
                redacteur: comment.redacteur,
                type: comment.type,
                date: comment.date,
                created_at: comment.created_at,
                updated_at: comment.updated_at,
                auteur: comment.auteur || null, // Relation auteur chargée
                editing: false
            })) : []
        }
    }));
}

function mainCouranteApp() {
    return {
        activeTab: '{{ $defaultTab ?? "SRCOF" }}',
        editMode: false,
        showModal: false,
        isEditing: false,
        isUnarchiving: false,
        currentEvent: {},
        currentEditIndex: null,
         params: {},
           isLoading: false,
        notification: {
            show: false,
            type: 'success',
            title: '',
            message: '',
            duration: 5000
        },
        pagination: {
                    currentPage: 1,
                    itemsPerPage: 10,
                    itemsPerPageOptions: [5, 10, 20, 50, 100]
                },
                searchQuery: '',

        // Configuration des colonnes pour chaque type
        columns: {
            SRCOF: [
                'Numéro', 'Date et heure', 'Nature de l\'événement', 'Localisation', 'Description',
                'Conséquence sur le PDT', 'Rédacteur', 'Statut',
                'Commentaire', 'Action', 'POST', 'Opérations'
            ],
            CIV: [
                'ID', 'Date et heure', 'Nature de l\'événement', 'Localisation', 'Description',
                'Conséquence sur le PDT', 'Rédacteur', 'Statut',
                'Confidentialité', 'Commentaire', 'Action', 'POST', 'Opérations'
            ],
            HOTLINE: [
                'Numéro', 'Date et heure', 'Nature de l\'événement', 'Localisation', 'Description',
                'Conséquence sur le PDT', 'Statut', 'Date clôture',
                'Confidentialité', 'Impact', 'Commentaire', 'Action', 'POST', 'Opérations'
            ],
            CM: [
                'Title', 'Date et heure', 'Nature de l\'événement', 'Localisation', 'Description',
                'Conséquence sur le PDT', 'Rédacteur', 'Statut', 'Date clôture',
                'Confidentialité', 'Impact', 'Heure appel intervenant', 'Heure arrivée intervenant',
                'Commentaire', 'Action', 'POST', 'Opérations'
            ],
            PTP: [
                'Numéro', 'Date', 'Heure', 'Semaine', 'Nature de l\'événement', 'Description',
                'Rédacteur', 'Statut', 'Date clôture',
                'Commentaire', 'Action', 'POST', 'Opérations'
            ]
        },

        // Données dynamiques pour chaque type
        data: {
            SRCOF: transformEventsForUI(window.evenementsSRCOF || []),
            CIV: transformEventsForUI(window.evenementsCIV || []),
            HOTLINE: transformEventsForUI(window.evenementsHOTLINE || []),
            CM: transformEventsForUI(window.evenementsCM || []),
            PTP: transformEventsForUI(window.evenementsPTP || [])
        },
init() {
    console.log('=== DONNÉES CHARGÉES ===');
    console.log('SRCOF:', this.data.SRCOF?.length || 0, 'événements');
    console.log('CIV:', this.data.CIV?.length || 0, 'événements');
    console.log('HOTLINE:', this.data.HOTLINE?.length || 0, 'événements');
    console.log('CM:', this.data.CM?.length || 0, 'événements');
    console.log('PTP:', this.data.PTP?.length || 0, 'événements');
    console.log('=========================');
},
        // Votre configuration des fields

       fields: {
    SRCOF: [
        { key: 'dateHeure', label: 'Date et heure', type: 'datetime-local' },
        { key: 'nature', label: 'Nature de l\'événement', type: 'select', options: window.nature_evenements?.map(n => n.libelle) || [] },
        { key: 'localisation', label: 'Localisation', type: 'select', options: window.locations?.map(l => l.libelle) || [] },
        { key: 'description', label: 'Description', type: 'textarea' },
        { key: 'consequence', label: 'Conséquence sur le PDT', type: 'select', options: ['OUI', 'NON'] },
        { key: 'redacteur', label: 'Rédacteur', type: 'text' },
        { key: 'statut', label: 'Statut', type: 'select', options: ['En cours', 'Terminé', 'Archivé'] },
        { key: 'commentaire', label: 'Commentaire', type: 'textarea' },

        // Séparateur pour les actions existantes
        // { type: 'separator', label: 'Actions existantes' },
        // { key: 'actions-list', type: 'actions-list', label: 'Liste des actions' },

        // // Nouveau commentaire
        // { key: 'new_comment', label: 'Ajouter un nouveau commentaire', type: 'textarea' },

        // // Séparateur pour ajouter une action
        // { type: 'separator', label: 'Ajouter une action' },
        //  { type: 'separator', label: 'Actions existantes' },
        { key: 'separator-actions', type: 'separator', label: 'Actions existantes' },
        { key: 'actions-list', type: 'actions-list', label: 'Liste des actions' },
        { key: 'separator-comments', type: 'separator', label: 'Commentaires existants' },
        { key: 'commentaires-list', type: 'commentaires-list', label: 'Liste des commentaires' },
        { key: 'new_comment', label: 'Ajouter un nouveau commentaire', type: 'textarea' },
        { key: 'separator-new-action', type: 'separator', label: 'Ajouter une action' },

        {
            key: 'type_action',
            label: 'Type d\'action',
            type: 'select',
            options: ['texte_libre', 'demande_validation', 'aviser', 'informer']
        },
        {
            key: 'destinataires',
            label: 'Destinataires',
            type: 'multiselect',
            options: [
        // Listes de diffusion
        ...(window.listesDiffusion?.map(liste => ({
            value: `liste_${liste.id}`,
            label: `📋 ${liste.nom} (${liste.users.length} utilisateurs)`,
            type: 'liste',
            users: liste.users
        })) || []),

        // Utilisateurs individuels
        ...(window.users?.map(u => ({
            value: `user_${u.id}`,
            label: `👤 ${u.prenom} ${u.nom} (${u.entite?.nom || 'Sans entité'})`,
            type: 'utilisateur',
            email: u.email
        })) || [])
    ],
    condition: (currentEvent) => ['demande_validation', 'aviser', 'informer'].includes(currentEvent.type_action)
        },
        {
            key: 'message_personnalise',
            label: 'Message personnalisé',
            type: 'textarea',
            condition: (currentEvent) => currentEvent.type_action && currentEvent.type_action !== ''
        }
    ],

    CIV: [
        { key: 'dateHeure', label: 'Date et heure', type: 'datetime-local' },
        { key: 'nature', label: 'Nature de l\'événement', type: 'select', options: window.nature_evenements?.map(n => n.libelle) || [] },
        { key: 'localisation', label: 'Localisation', type: 'select', options: window.locations?.map(l => l.libelle) || [] },
        { key: 'description', label: 'Description', type: 'textarea' },
        { key: 'consequence', label: 'Conséquence sur le PDT', type: 'select', options: ['OUI', 'NON'] },
        { key: 'redacteur', label: 'Rédacteur', type: 'text' },
        { key: 'statut', label: 'Statut', type: 'select', options: ['En cours', 'Terminé', 'Archivé'] },
        { key: 'confidentialite', label: 'Confidentialité', type: 'select', options: ['Confidentiel', 'Non confidentiel'] },
        { key: 'commentaire', label: 'Commentaire', type: 'textarea' },

        // { type: 'separator', label: 'Actions existantes' },
        // { key: 'actions-list', type: 'actions-list', label: 'Liste des actions' },
        // { key: 'new_comment', label: 'Ajouter un nouveau commentaire', type: 'textarea' },
        // { type: 'separator', label: 'Ajouter une action' },

        { key: 'separator-actions', type: 'separator', label: 'Actions existantes' },
        { key: 'actions-list', type: 'actions-list', label: 'Liste des actions' },
        { key: 'separator-comments', type: 'separator', label: 'Commentaires existants' },
        { key: 'commentaires-list', type: 'commentaires-list', label: 'Liste des commentaires' },
        { key: 'new_comment', label: 'Ajouter un nouveau commentaire', type: 'textarea' },
        { key: 'separator-new-action', type: 'separator', label: 'Ajouter une action' },


        { key: 'type_action', label: 'Type d\'action', type: 'select', options: ['texte_libre', 'demande_validation', 'aviser', 'informer'] },
         {
            key: 'destinataires',
            label: 'Destinataires',
            type: 'multiselect',
            options: [
        // Listes de diffusion
        ...(window.listesDiffusion?.map(liste => ({
            value: `liste_${liste.id}`,
            label: `📋 ${liste.nom} (${liste.users.length} utilisateurs)`,
            type: 'liste',
            users: liste.users
        })) || []),

        // Utilisateurs individuels
        ...(window.users?.map(u => ({
            value: `user_${u.id}`,
            label: `👤 ${u.prenom} ${u.nom} (${u.entite?.nom || 'Sans entité'})`,
            type: 'utilisateur',
            email: u.email
        })) || [])
     ],
    condition: (currentEvent) => ['demande_validation', 'aviser', 'informer'].includes(currentEvent.type_action)
        },
        {
            key: 'message_personnalise',
            label: 'Message personnalisé',
            type: 'textarea',
            condition: (currentEvent) => currentEvent.type_action && currentEvent.type_action !== ''
        }
    ],

    HOTLINE: [
        { key: 'dateHeure', label: 'Date et heure', type: 'datetime-local' },
        { key: 'nature', label: 'Nature de l\'événement', type: 'select', options: window.nature_evenements?.map(n => n.libelle) || [] },
        { key: 'localisation', label: 'Localisation', type: 'select', options: window.locations?.map(l => l.libelle) || [] },
        { key: 'description', label: 'Description', type: 'textarea' },
        { key: 'consequence', label: 'Conséquence sur le PDT', type: 'select', options: ['OUI', 'NON'] },
        { key: 'statut', label: 'Statut', type: 'select', options: ['En cours', 'Terminé', 'Archivé'] },
        { key: 'dateCloture', label: 'Date clôture', type: 'date' },
        { key: 'confidentialite', label: 'Confidentialité', type: 'select', options: ['Confidentiel', 'Non confidentiel'] },
        { key: 'impact', label: 'Impact', type: 'select', options: window.impacts?.map(i => i.libelle) || [] },
        { key: 'commentaire', label: 'Commentaire', type: 'textarea' },

        // { type: 'separator', label: 'Actions existantes' },
        // { key: 'actions-list', type: 'actions-list', label: 'Liste des actions' },
        // { key: 'new_comment', label: 'Ajouter un nouveau commentaire', type: 'textarea' },
        // { type: 'separator', label: 'Ajouter une action' },
            { type: 'separator', label: 'Actions existantes' },
            { key: 'actions-list', type: 'actions-list', label: 'Liste des actions' },
            { key: 'separator-comments', type: 'separator', label: 'Commentaires existants' },
            { key: 'commentaires-list', type: 'commentaires-list', label: 'Liste des commentaires' },
            { key: 'new_comment', label: 'Ajouter un nouveau commentaire', type: 'textarea' },
            { key: 'separator-new-action', type: 'separator', label: 'Ajouter une action' },

        {
            key: 'type_action',
            label: 'Type d\'action',
            type: 'select',
            options: ['texte_libre', 'demande_validation', 'aviser', 'informer']
        },
        {
            key: 'destinataires',
            label: 'Destinataires',
            type: 'multiselect',
            options: [
        // Listes de diffusion
        ...(window.listesDiffusion?.map(liste => ({
            value: `liste_${liste.id}`,
            label: `📋 ${liste.nom} (${liste.users.length} utilisateurs)`,
            type: 'liste',
            users: liste.users
        })) || []),

        // Utilisateurs individuels
        ...(window.users?.map(u => ({
            value: `user_${u.id}`,
            label: `👤 ${u.prenom} ${u.nom} (${u.entite?.nom || 'Sans entité'})`,
            type: 'utilisateur',
            email: u.email
        })) || [])
    ],
    condition: (currentEvent) => ['demande_validation', 'aviser', 'informer'].includes(currentEvent.type_action)
        },
        {
            key: 'message_personnalise',
            label: 'Message personnalisé',
            type: 'textarea',
            condition: (currentEvent) => currentEvent.type_action && currentEvent.type_action !== ''
        }
    ],

    CM: [
        { key: 'dateHeure', label: 'Date et heure', type: 'datetime-local' },
        { key: 'nature', label: 'Nature de l\'événement', type: 'select', options: window.nature_evenements?.map(n => n.libelle) || [] },
        { key: 'localisation', label: 'Localisation', type: 'select', options: window.locations?.map(l => l.libelle) || [] },
        { key: 'description', label: 'Description', type: 'textarea' },
        { key: 'consequence', label: 'Conséquence sur le PDT', type: 'select', options: ['OUI', 'NON'] },
        { key: 'redacteur', label: 'Rédacteur', type: 'text' },
        { key: 'statut', label: 'Statut', type: 'select', options: ['En cours', 'Terminé', 'Archivé'] },
        { key: 'dateCloture', label: 'Date clôture', type: 'date' },
        { key: 'confidentialite', label: 'Confidentialité', type: 'select', options: ['Confidentiel', 'Non confidentiel'] },
        { key: 'impact', label: 'Impact', type: 'select', options: window.impacts?.map(i => i.libelle) || [] },
        { key: 'heureAppelIntervenant', label: 'Heure appel intervenant', type: 'datetime-local' },
        { key: 'heureArriveeIntervenant', label: 'Heure arrivée intervenant', type: 'datetime-local' },
        { key: 'commentaire', label: 'Commentaire', type: 'textarea' },

        // { type: 'separator', label: 'Actions existantes' },
        // { key: 'actions-list', type: 'actions-list', label: 'Liste des actions' },
        // { key: 'new_comment', label: 'Ajouter un nouveau commentaire', type: 'textarea' },
        // { type: 'separator', label: 'Ajouter une action' },
        { key: 'separator-actions', type: 'separator', label: 'Actions existantes' },
        { key: 'actions-list', type: 'actions-list', label: 'Liste des actions' },
        { key: 'separator-comments', type: 'separator', label: 'Commentaires existants' },
        { key: 'commentaires-list', type: 'commentaires-list', label: 'Liste des commentaires' },
        { key: 'new_comment', label: 'Ajouter un nouveau commentaire', type: 'textarea'  },
        { key: 'separator-new-action', type: 'separator', label: 'Ajouter une action' },

        {
            key: 'type_action',
            label: 'Type d\'action',
            type: 'select',
            options: ['texte_libre', 'demande_validation', 'aviser', 'informer']
        },
        {
            key: 'destinataires',
            label: 'Destinataires',
            type: 'multiselect',
            options: [
        // Listes de diffusion
        ...(window.listesDiffusion?.map(liste => ({
            value: `liste_${liste.id}`,
            label: `📋 ${liste.nom} (${liste.users.length} utilisateurs)`,
            type: 'liste',
            users: liste.users
        })) || []),

        // Utilisateurs individuels
        ...(window.users?.map(u => ({
            value: `user_${u.id}`,
            label: `👤 ${u.prenom} ${u.nom} (${u.entite?.nom || 'Sans entité'})`,
            type: 'utilisateur',
            email: u.email
        })) || [])
      ],
        condition: (currentEvent) => ['demande_validation', 'aviser', 'informer'].includes(currentEvent.type_action)
        },
        {
            key: 'message_personnalise',
            label: 'Message personnalisé',
            type: 'textarea',
            condition: (currentEvent) => currentEvent.type_action && currentEvent.type_action !== ''
        }
    ],

    PTP: [
        { key: 'dateHeure', label: 'Date et heure', type: 'datetime-local' },
        { key: 'nature', label: 'Nature de l\'événement', type: 'select', options: window.nature_evenements?.map(n => n.libelle) || [] },
        { key: 'description', label: 'Description', type: 'textarea' },
        { key: 'redacteur', label: 'Rédacteur', type: 'text' },
        { key: 'statut', label: 'Statut', type: 'select', options: ['En cours', 'Terminé', 'Archivé'] },
        { key: 'dateCloture', label: 'Date clôture', type: 'date' },
        { key: 'commentaire', label: 'Commentaire', type: 'textarea' },

        // { type: 'separator', label: 'Actions existantes' },
        // { key: 'actions-list', type: 'actions-list', label: 'Liste des actions' },
        // { key: 'new_comment', label: 'Ajouter un nouveau commentaire', type: 'textarea' },
        // { type: 'separator', label: 'Ajouter une action' },
       { key: 'separator-actions', type: 'separator', label: 'Actions existantes' },
        { key: 'actions-list', type: 'actions-list', label: 'Liste des actions' },
        { key: 'separator-comments', type: 'separator', label: 'Commentaires existants' },
        { key: 'commentaires-list', type: 'commentaires-list', label: 'Liste des commentaires' },
        { key: 'new_comment', label: 'Ajouter un nouveau commentaire', type: 'textarea' },
        { key: 'separator-new-action', type: 'separator', label: 'Ajouter une action' },

        {
            key: 'type_action',
            label: 'Type d\'action',
            type: 'select',
            options: ['texte_libre', 'demande_validation', 'aviser', 'informer']
        },
        {
            key: 'destinataires',
            label: 'Destinataires',
            type: 'multiselect',
            options: [
        // Listes de diffusion
        ...(window.listesDiffusion?.map(liste => ({
            value: `liste_${liste.id}`,
            label: `📋 ${liste.nom} (${liste.users.length} utilisateurs)`,
            type: 'liste',
            users: liste.users
        })) || []),

        // Utilisateurs individuels
        ...(window.users?.map(u => ({
            value: `user_${u.id}`,
            label: `👤 ${u.prenom} ${u.nom} (${u.entite?.nom || 'Sans entité'})`,
            type: 'utilisateur',
            email: u.email
        })) || [])
    ],
    condition: (currentEvent) => ['demande_validation', 'aviser', 'informer'].includes(currentEvent.type_action)
        },
        {
            key: 'message_personnalise',
            label: 'Message personnalisé',
            type: 'textarea',
            condition: (currentEvent) => currentEvent.type_action && currentEvent.type_action !== ''
        }
    ]
},
 get filteredData() {
    // Utiliser les données complètes de l'onglet actif
    const data = this.data[this.activeTab] || [];

    console.log('Données filtrées pour', this.activeTab, ':', data.length, 'éléments');

    if (!this.searchQuery) return data;

    const query = this.searchQuery.toLowerCase();
    const filtered = data.filter(item => {
        return Object.values(item).some(value => {
            if (value === null || value === undefined) return false;
            return String(value).toLowerCase().includes(query);
        });
    });

    console.log('Après recherche:', filtered.length, 'éléments trouvés');
    return filtered;
},

        get totalItems() {
            return this.filteredData.length;
        },

        get totalPages() {
            return Math.ceil(this.totalItems / this.pagination.itemsPerPage);
        },

        get paginatedData() {
            const start = (this.pagination.currentPage - 1) * this.pagination.itemsPerPage;
            const end = start + this.pagination.itemsPerPage;
            return this.filteredData.slice(start, end);
        },

        get paginationInfo() {
            const start = this.totalItems === 0 ? 0 : (this.pagination.currentPage - 1) * this.pagination.itemsPerPage + 1;
            const end = Math.min(this.pagination.currentPage * this.pagination.itemsPerPage, this.totalItems);
            return {
                start: start,
                end: end,
                total: this.totalItems,
                totalOriginal: this.data[this.activeTab]?.length || 0
            };
        },

        // NOUVEAU : Méthodes de pagination
        goToPage(page) {
        // Convertir en nombre et valider
        const pageNum = parseInt(page);

        if (isNaN(pageNum)) {
            console.warn('Numéro de page invalide:', page);
            return;
        }

        if (pageNum < 1) {
            this.pagination.currentPage = 1;
            return;
        }

        if (pageNum > this.totalPages) {
            this.pagination.currentPage = this.totalPages;
            return;
        }

        this.pagination.currentPage = pageNum;
        console.log('Navigation vers la page:', pageNum);
    },

        nextPage() {
            if (this.pagination.currentPage < this.totalPages) {
                this.pagination.currentPage++;
            }
        },

        prevPage() {
            if (this.pagination.currentPage > 1) {
                this.pagination.currentPage--;
            }
        },

        changeItemsPerPage(newCount) {
            this.pagination.itemsPerPage = parseInt(newCount);
            this.pagination.currentPage = 1;
        },

        // NOUVEAU : Méthodes de recherche
        onSearchInput() {
            this.pagination.currentPage = 1; // Retour à la première page lors d'une recherche
        },

        clearSearch() {
            this.searchQuery = '';
            this.pagination.currentPage = 1;
        },

        // MODIFIER : Méthode getCurrentData pour utiliser les données paginées
       getCurrentData() {
            return this.paginatedData;
        },


        // MODIFIER : Méthode switchTab pour réinitialiser pagination et recherche
        switchTab(tab) {
            this.activeTab = tab;
            this.editMode = false;
            this.searchQuery = '';
            this.pagination.currentPage = 1;
            const currentData = this.data[this.activeTab];
        },
   showNotification(type, title, message, duration = 5000) {
    this.notification = {
        show: true,
        type: type,
        title: title,
        message: message,
        duration: duration
    };

            setTimeout(() => {
                this.hideNotification();
            }, duration);
        },

        hideNotification() {
            this.notification.show = false;
        },
        showSuccess(title, message) {
            this.showNotification('success', title, message);
        },

            showError(title, message) {
                this.showNotification('error', title, message, 7000);
            },

            showWarning(title, message) {
                this.showNotification('warning', title, message);
            },

            showInfo(title, message) {
                this.showNotification('info', title, message);
            },

        showLoader() {
            this.isLoading = true;
        },

        hideLoader() {
            this.isLoading = false;
        },

// MÉTHODES (continuent après la virgule)
switchTab(tab) {
    this.activeTab = tab;
    this.editMode = false;
},

        toggleEditMode() {
            this.editMode = !this.editMode;
            if (!this.editMode) {
                this.getCurrentData().forEach(item => {
                    item.editing = false;
                });
            }
        },

        toggleRowEdit(index) {
            if (this.editMode) {
                this.data[this.activeTab][index].editing = !this.data[this.activeTab][index].editing;
            }
        },

        getCurrentColumns() {
            return this.columns[this.activeTab];
        },

        getCurrentData() {
            return this.data[this.activeTab];
        },
getPaginatedData() {
    return this.paginatedData;
},
        getCurrentFields() {
            return this.fields[this.activeTab];
        },

        getColumnKey(column) {
            const keyMap = {
                'Numéro': 'numero',
                'Date et heure': 'dateHeure',
                'Nature de l\'événement': 'nature',
                'Localisation': 'localisation',
                'Description': 'description',
                'Conséquence sur le PDT': 'consequence',
                'Rédacteur': 'redacteur',
                'Statut': 'statut',
                'Commentaire': 'commentaire',
                'Action': 'action',
                'POST': 'post',
                'Pièce jointe': 'pieceJointe',
                'ID': 'id',
                'Description localisation': 'descriptionLocalisation',
                'Confidentialité': 'confidentialite',
                'Date': 'date',
                'Heure': 'heure',
                'Semaine': 'semaine',
                'Impact': 'impact',
                'Title': 'title',
                'Date clôture': 'dateCloture',
                'Heure appel intervenant': 'heureAppelIntervenant',
                'Heure arrivée intervenant': 'heureArriveeIntervenant',
            };
            return keyMap[column] || column.toLowerCase().replace(/ /g, '');
        },


        openCreateModal() {
            const nomUtilisateur = window.user.nom_complet;
            this.isEditing = false;
            this.currentEvent = {
                redacteur: nomUtilisateur,
                post: window.user.entiteCode,
                statut: 'En cours',
                type_action: '',
                destinataires: [],
                message_personnalise: ''
            };
            this.showModal = true;
        },

       openEditModal(index) {
    this.isEditing = true;
    this.currentEditIndex = index;
    const eventData = this.getCurrentData()[index];

    // Créer une copie profonde des données de l'événement
    const eventCopy = JSON.parse(JSON.stringify(eventData));

    // S'assurer que l'originalData existe
    if (!eventCopy.originalData) {
        eventCopy.originalData = {};
    }

    // S'assurer que les actions sont un tableau même si c'est vide
    if (!eventCopy.originalData.actions) {
        eventCopy.originalData.actions = [];
    }

    // NOUVEAU : S'assurer que les commentaires sont un tableau même si c'est vide
    if (!eventCopy.originalData.commentaires) {
        eventCopy.originalData.commentaires = [];
    }

    // NOUVEAU : Si les commentaires sont dans un autre format, les convertir
    if (eventCopy.originalData.commentaires && Array.isArray(eventCopy.originalData.commentaires)) {
        eventCopy.originalData.commentaires = eventCopy.originalData.commentaires.map(comment => {
            // S'assurer que chaque commentaire a le bon format
            if (typeof comment === 'string') {
                return {
                    id: Date.now() + Math.random(),
                    text: comment,
                    created_at: new Date().toISOString(),
                    auteur_id: window.user.id
                };
            }
            // S'assurer que le champ 'text' existe
            if (!comment.text && comment.commentaire) {
                comment.text = comment.commentaire;
            }
            return comment;
        });
    }

    // Assigner l'objet complet en une seule fois pour une meilleure réactivité
    this.currentEvent = eventCopy;

    // Initialiser les champs pour les nouvelles actions
    this.currentEvent.new_comment = '';
    this.currentEvent.type_action = '';
    this.currentEvent.destinataires = [];
    this.currentEvent.message_personnalise = '';

    // Afficher le modal
    this.showModal = true;

    // Loguer les données pour débogage
    console.log("DEBUG FINAL - currentEvent:", this.currentEvent);
    console.log("DEBUG FINAL - actions:", this.currentEvent.originalData?.actions);
    console.log("DEBUG FINAL - commentaires:", this.currentEvent.originalData?.commentaires);
},
        EditEvent(evenement = null) {
    this.resetParams();
    if (!evenement || typeof evenement !== 'object') {
        console.warn('Aucun événement à éditer ou type invalide');
        return;
    }

    console.log('Édition de l\'événement:', evenement);

    try {
        const evt = JSON.parse(JSON.stringify(evenement));

        // Assignation des données de base
        this.params = {
            id: evt.id || '',
            dateHeure: evt.date_evenement || '',
            nature: evt.nature_evenement ? evt.nature_evenement.libelle : '',
            localisation: evt.location ? evt.location.libelle : '',
            description: evt.description || '',
            consequence: evt.consequence_sur_pdt == 1 ? 'OUI' : evt.consequence_sur_pdt == 0 ? 'NON' : '',
            redacteur: evt.redacteur || '',
            statut: evt.statut === 'en_cours' ? 'En cours' : evt.statut === 'cloture' ? 'Terminé' : '',
            confidentialite: evt.confidentialite == 1 ? 'Confidentiel' : evt.confidentialite == 0 ? 'Non confidentiel' : '',
            impact: evt.impact && evt.impact.libelle ? evt.impact.libelle : '',
            commentaire: evt.commentaires && evt.commentaires.length ? evt.commentaires.map(c => c.text).join(', ') : '',

            // Gestion spéciale des actions
            actions: this.uniqueActions(evt.actions || []),

            // Champs pour nouvelles actions
            type_action: '',
            destinataires: [],
            message_personnalise: '',
            new_comment: ''
        };

        this.isEditing = true;
        this.showModal = true;

    } catch (error) {
        console.error('Erreur lors de l\'édition de l\'événement:', error);
    }
},
uniqueActions(actions) {
    if (!Array.isArray(actions)) {
        console.warn('Actions n\'est pas un tableau:', actions);
        return [];
    }

    const seen = new Set();
    return actions
        .filter(action => {
            if (!action || !action.id) return false;
            if (seen.has(action.id)) return false;
            seen.add(action.id);
            return true;
        })
        .sort((a, b) => new Date(b.created_at) - new Date(a.created_at)); // Tri par date décroissante
},
        closeModal() {
    this.showModal = false;
    this.currentEvent = {};
    this.currentEditIndex = null;
   this.isUnarchiving = false;
    this.isEditing = false;
},
resetParams() {
    this.params = {
        id: '',
        dateHeure: '',
        nature: '',
        localisation: '',
        description: '',
        consequence: '',
        redacteur: '',
        statut: '',
        confidentialite: '',
        impact: '',
        commentaire: '',
        actions: [],
        type_action: '',
        destinataires: [],
        message_personnalise: '',
        new_comment: ''
    };
},
      saveEvent() {
    if (this.isEditing) {
        this.showInfo('🔄 Traitement', 'Mise à jour en cours...');
         Object.assign(this.getCurrentData()[this.currentEditIndex], this.currentEvent);

        // Traitement du type d'action
        if (this.currentEvent.type_action && this.currentEvent.type_action !== '') {
            console.log('Traitement du type d\'action:', this.currentEvent.type_action);

            let commentaire = '';

            if (this.currentEvent.type_action === 'texte_libre') {
                commentaire = this.currentEvent.message_personnalise || 'Action libre';
            } else if (this.currentEvent.type_action === 'demande_validation') {
                const destinataires = this.getDestinataireNames(this.currentEvent.destinataires);
                commentaire = `Demande de validation à ${destinataires}`;
            } else if (this.currentEvent.type_action === 'aviser') {
                const destinataires = this.getDestinataireNames(this.currentEvent.destinataires);
                commentaire = `${destinataires} avisé`;
            } else if (this.currentEvent.type_action === 'informer') {
                const destinataires = this.getDestinataireNames(this.currentEvent.destinataires);
                commentaire = `${destinataires} informé`;
            }

            const newAction = {
                id: Date.now(), // ID temporaire
                commentaire: commentaire,
                type: this.currentEvent.type_action,
                message: this.currentEvent.message_personnalise || '',
                created_at: new Date().toISOString(),
                evenement_id: this.currentEvent.id || this.currentEvent.originalData.id,
                auteur_id: window.user.id,
                destinataires: this.currentEvent.destinataires || [] // IMPORTANT : Inclure les destinataires
            };

            console.log('Nouvelle action créée avec destinataires:', newAction);

            // S'assurer que la structure existe
            if (!this.getCurrentData()[this.currentEditIndex].originalData) {
                this.getCurrentData()[this.currentEditIndex].originalData = {};
            }
            if (!this.getCurrentData()[this.currentEditIndex].originalData.actions) {
                this.getCurrentData()[this.currentEditIndex].originalData.actions = [];
            }

            // Ajouter l'action
            this.getCurrentData()[this.currentEditIndex].originalData.actions.push(newAction);

            console.log('Action ajoutée, total actions:', this.getCurrentData()[this.currentEditIndex].originalData.actions.length);

            // Réinitialiser les champs
            this.currentEvent.type_action = '';
            this.currentEvent.message_personnalise = '';
            this.currentEvent.destinataires = [];
        }

        this.updateEventInDatabase(this.currentEvent);
    } else {
         this.showInfo('🔄 Traitement', 'Création en cours...');
        this.createEventInDatabase(this.currentEvent);
    }

    this.closeModal();
},

        getDestinataireNames(destinataires) {
    if (!destinataires || destinataires.length === 0) {
        return 'destinataire inconnu';
    }

    const noms = destinataires.map(destinataireId => {
        if (destinataireId.startsWith('liste_')) {
            // C'est une liste de diffusion
            const listeId = destinataireId.replace('liste_', '');
            const liste = window.listesDiffusion?.find(l => l.id == listeId);
            if (liste) {
                return `${liste.nom} (Liste: ${liste.utilisateurs?.length || 0} utilisateurs)`;
            }
        } else if (destinataireId.startsWith('user_')) {
            // C'est un utilisateur individuel
            const userId = destinataireId.replace('user_', '');
            const utilisateur = window.utilisateurs?.find(u => u.id == userId);
            if (utilisateur) {
                return `${utilisateur.prenom} ${utilisateur.nom}`;
            }
        }
        return destinataireId; // Fallback
    });

    return noms.join(', ');
},


      getDestinataireEmails(destinataires) {
    if (!destinataires || !Array.isArray(destinataires)) {
        return [];
    }

    const emails = [];
    destinataires.forEach(destinataireId => {
        try {
            if (typeof destinataireId === 'string' && destinataireId.startsWith('liste_')) {
                // C'est une liste de diffusion - ajouter tous les emails des utilisateurs
                const listeId = destinataireId.replace('liste_', '');
                const liste = window.listesDiffusion?.find(l => l.id == listeId);
                if (liste && liste.utilisateurs) {
                    liste.utilisateurs.forEach(user => {
                        if (user.email) {
                            emails.push(user.email);
                        }
                    });
                }
            } else if (typeof destinataireId === 'string' && destinataireId.startsWith('user_')) {
                // C'est un utilisateur individuel
                const userId = destinataireId.replace('user_', '');
                const utilisateur = window.utilisateurs?.find(u => u.id == userId);
                if (utilisateur && utilisateur.email) {
                    emails.push(utilisateur.email);
                }
            }
        } catch (error) {
            console.error('Erreur lors du traitement du destinataire:', destinataireId, error);
        }
    });

    return emails;
},
// Activer/désactiver le mode édition d'une action
toggleActionEdit(actionIndex) {
    if (!this.currentEvent?.originalData?.actions || actionIndex < 0 || actionIndex >= this.currentEvent.originalData.actions.length) {
        this.showError('❌ Erreur', 'Action introuvable');
        return;
    }

    const action = this.currentEvent.originalData.actions[actionIndex];

    // Basculer le mode édition
    action.editing = !action.editing;

    // Si on entre en mode édition, sauvegarder l'état original
    if (action.editing && !action.originalState) {
        action.originalState = {
            type: action.type,
            commentaire: action.commentaire,
            message: action.message
        };
    }

    // Si on sort du mode édition sans sauvegarder, restaurer l'état original
    if (!action.editing && action.originalState) {
        action.type = action.originalState.type;
        action.commentaire = action.originalState.commentaire;
        action.message = action.originalState.message;
        delete action.originalState;
    }

    console.log('Mode édition action:', action.editing ? 'activé' : 'désactivé');
},

// Sauvegarder les modifications d'une action
saveActionEdit(actionIndex) {
    if (!this.currentEvent?.originalData?.actions || actionIndex < 0 || actionIndex >= this.currentEvent.originalData.actions.length) {
        this.showError('❌ Erreur', 'Action introuvable');
        return;
    }

    const action = this.currentEvent.originalData.actions[actionIndex];

    // Validation
    if (!action.commentaire || action.commentaire.trim() === '') {
        this.showError('❌ Erreur', 'Le commentaire est obligatoire');
        return;
    }

    // Confirmer la modification
    if (!confirm('Êtes-vous sûr de vouloir modifier cette action ?')) {
        return;
    }

    this.showLoader();

    // Si l'action a un ID réel (pas temporaire), la modifier en base
    if (action.id && action.id < 1000000000000) {
        this.updateActionInDatabase(action, actionIndex);
    } else {
        // Action temporaire, juste désactiver le mode édition
        action.editing = false;
        delete action.originalState;
        this.showSuccess('✅ Modifié', 'Action modifiée (sera effective à la sauvegarde)');
    }
},
// Activer/désactiver le mode édition d'un commentaire
// Activer/désactiver le mode édition d'un commentaire
    toggleCommentEdit(commentIndex) {
        if (!this.currentEvent?.originalData?.commentaires || commentIndex < 0 || commentIndex >= this.currentEvent.originalData.commentaires.length) {
            this.showError('❌ Erreur', 'Commentaire introuvable');
            return;
        }

        const commentaire = this.currentEvent.originalData.commentaires[commentIndex];

        // Vérifier que l'utilisateur peut modifier ce commentaire
        // if (commentaire.redacteur != window.user.id) {
        //     this.showError('❌ Erreur', 'Vous ne pouvez modifier que vos propres commentaires');
        //     return;
        // }

        // Basculer le mode édition
        commentaire.editing = !commentaire.editing;

        // Si on entre en mode édition, sauvegarder l'état original
        if (commentaire.editing && !commentaire.originalState) {
            commentaire.originalState = {
                text: commentaire.text || ''
            };
            console.log('État original sauvegardé:', commentaire.originalState);
        }

        // Si on sort du mode édition sans sauvegarder, restaurer l'état original
        if (!commentaire.editing && commentaire.originalState) {
            commentaire.text = commentaire.originalState.text;
            delete commentaire.originalState;
        }

        console.log('Mode édition commentaire:', commentaire.editing ? 'activé' : 'désactivé', commentaire);
    },

// Sauvegarder les modifications d'un commentaire
saveCommentEdit(commentIndex) {
    if (!this.currentEvent?.originalData?.commentaires || commentIndex < 0 || commentIndex >= this.currentEvent.originalData.commentaires.length) {
        this.showError('❌ Erreur', 'Commentaire introuvable');
        return;
    }

    const commentaire = this.currentEvent.originalData.commentaires[commentIndex];

    // Validation
    if (!commentaire.text || commentaire.text.trim() === '') {
        this.showError('❌ Erreur', 'Le commentaire ne peut pas être vide');
        return;
    }

    // Confirmer la modification
    if (!confirm('Êtes-vous sûr de vouloir modifier ce commentaire ?')) {
        return;
    }

    this.showLoader();

    // Si le commentaire a un ID réel (pas temporaire), le modifier en base
    if (commentaire.id && commentaire.id < 1000000000000) {
        this.updateCommentInDatabase(commentaire, commentIndex);
    } else {
        // Commentaire temporaire, juste désactiver le mode édition
        commentaire.editing = false;
        delete commentaire.originalState;
        this.showSuccess('✅ Modifié', 'Commentaire modifié (sera effectif à la sauvegarde)');
    }
},

// Modifier un commentaire en base de données
updateCommentInDatabase(commentaire, commentIndex) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    const data = {
        text: commentaire.text.trim()
    };

    fetch(`/commentaires/${commentaire.id}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`Erreur ${response.status}: Impossible de modifier le commentaire`);
        }
        return response.json();
    })
    .then(data => {
        this.hideLoader();
        this.showSuccess('✅ Modifié', 'Commentaire modifié avec succès');

        // Désactiver le mode édition et nettoyer
        commentaire.editing = false;
        delete commentaire.originalState;

        console.log('Commentaire modifié:', data);
    })
    .catch(error => {
        this.hideLoader();
        this.showError('❌ Erreur', `Erreur lors de la modification: ${error.message}`);
        console.error('Erreur modification commentaire:', error);
    });
},

// Supprimer un commentaire
// Supprimer un commentaire
deleteComment(commentIndex) {
    if (!this.currentEvent?.originalData?.commentaires || commentIndex < 0 || commentIndex >= this.currentEvent.originalData.commentaires.length) {
        this.showError('❌ Erreur', 'Commentaire introuvable');
        return;
    }

    const commentaire = this.currentEvent.originalData.commentaires[commentIndex];

    // Vérifier que l'utilisateur peut supprimer ce commentaire
    if (commentaire.redacteur != window.user.id) {
        this.showError('❌ Erreur', 'Vous ne pouvez supprimer que vos propres commentaires');
        return;
    }

    // Confirmer la suppression
    if (!confirm(`Êtes-vous sûr de vouloir supprimer ce commentaire ?\n\n"${commentaire.text || 'Commentaire sans texte'}"`)) {
        return;
    }

    // Animation avant suppression
    const commentElement = document.querySelectorAll('.comments-list-container .bg-white.border.p-3.mb-2.rounded.shadow')[commentIndex];
    if (commentElement) {
        commentElement.style.transition = 'all 0.3s ease';
        commentElement.style.opacity = '0.5';
        commentElement.style.transform = 'translateX(-20px)';
    }

    // Supprimer après un délai pour l'animation
    setTimeout(() => {
        // Supprimer le commentaire du tableau local
        this.currentEvent.originalData.commentaires.splice(commentIndex, 1);

        // Si le commentaire a un ID réel, le supprimer de la base
        if (commentaire.id && commentaire.id < 1000000000000) {
            this.deleteCommentFromDatabase(commentaire.id);
        } else {
            this.showSuccess('✅ Supprimé', 'Commentaire supprimé (sera effectif à la sauvegarde)');
        }

        console.log('Commentaire supprimé:', commentaire);
        console.log('Commentaires restants:', this.currentEvent.originalData.commentaires.length);
    }, 300);
},

// Supprimer un commentaire en base de données
deleteCommentFromDatabase(commentaireId) {
    this.showWarning('🗑️ Suppression', 'Suppression en cours...');

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    fetch(`/commentaires/${commentaireId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`Erreur ${response.status}: Impossible de supprimer le commentaire`);
        }
        return response.json();
    })
    .then(data => {
        this.showSuccess('✅ Supprimé', 'Commentaire supprimé avec succès');
        console.log('Commentaire supprimé de la base:', data);
    })
    .catch(error => {
        this.showError('❌ Erreur', `Erreur lors de la suppression: ${error.message}`);
        console.error('Erreur suppression commentaire:', error);
    });
},

// Modifier une action en base de données
updateActionInDatabase(action, actionIndex) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    const data = {
        type: action.type,
        commentaire: action.commentaire,
        message: action.message || ''
    };

    fetch(`/actions/${action.id}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`Erreur ${response.status}: Impossible de modifier l'action`);
        }
        return response.json();
    })
    .then(data => {
        this.hideLoader();
        this.showSuccess('✅ Modifié', 'Action modifiée avec succès');

        // Désactiver le mode édition et nettoyer
        action.editing = false;
        delete action.originalState;

        console.log('Action modifiée:', data);
    })
    .catch(error => {
        this.hideLoader();
        this.showError('❌ Erreur', `Erreur lors de la modification: ${error.message}`);
        console.error('Erreur modification action:', error);
    });
},

// Supprimer une action
// deleteAction(actionIndex) {
//     if (!this.currentEvent?.originalData?.actions || actionIndex < 0 || actionIndex >= this.currentEvent.originalData.actions.length) {
//         this.showError('❌ Erreur', 'Action introuvable');
//         return;
//     }

//     const action = this.currentEvent.originalData.actions[actionIndex];

//     // Confirmer la suppression
//     if (!confirm(`Êtes-vous sûr de vouloir supprimer cette action ?\n\n"${action.commentaire || 'Action sans commentaire'}"`)) {
//         return;
//     }

//     // Animation avant suppression (optionnel)
//     const actionElement = document.querySelectorAll('.bg-white.border.p-3.mb-2.rounded.shadow')[actionIndex];
//     if (actionElement) {
//         actionElement.style.transition = 'all 0.3s ease';
//         actionElement.style.opacity = '0.5';
//         actionElement.style.transform = 'translateX(-20px)';
//     }

//     // Supprimer après un délai pour l'animation
//     setTimeout(() => {
//         // Supprimer l'action du tableau local
//         this.currentEvent.originalData.actions.splice(actionIndex, 1);

//         // Si l'action a un ID réel (pas temporaire), la supprimer de la base
//         if (action.id && action.id < 1000000000000) {
//             this.deleteActionFromDatabase(action.id);
//         } else {
//             this.showSuccess('✅ Supprimé', 'Action supprimée (sera effective à la sauvegarde)');
//         }

//         console.log('Action supprimée:', action);
//         console.log('Actions restantes:', this.currentEvent.originalData.actions.length);
//     }, 300);
// },

// Supprimer une action en base de données
// deleteActionFromDatabase(actionId) {
//     this.showWarning('🗑️ Suppression', 'Suppression en cours...');

//     const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

//     fetch(`/actions/${actionId}`, {
//         method: 'DELETE',
//         headers: {
//             'X-CSRF-TOKEN': csrfToken,
//             'Accept': 'application/json',
//             'X-Requested-With': 'XMLHttpRequest'
//         }
//     })
//     .then(response => {
//         if (!response.ok) {
//             throw new Error(`Erreur ${response.status}: Impossible de supprimer l'action`);
//         }
//         return response.json();
//     })
//     .then(data => {
//         this.showSuccess('✅ Supprimé', 'Action supprimée avec succès');
//         console.log('Action supprimée de la base:', data);
//     })
//     .catch(error => {
//         this.showError('❌ Erreur', `Erreur lors de la suppression: ${error.message}`);
//         console.error('Erreur suppression action:', error);
//     });
// },

        deleteItem(index) {
            const itemToDelete = this.getCurrentData()[index];
            if (confirm('Êtes-vous sûr de vouloir supprimer cet événement ?')) {
                this.showWarning('🗑️ Suppression', 'Suppression en cours...');
                this.getCurrentData().splice(index, 1);
                if (itemToDelete.id) {
                    this.deleteEventFromDatabase(itemToDelete.id);
                }
            }
        },

        refreshEvents() {
            window.location.reload();
        },

        renderActions() {
            if (!this.currentEvent?.originalData?.actions) {
                return 'Aucune action disponible';
            }

            const actions = Array.from(this.currentEvent.originalData.actions).map(action => ({
                ...action
            }));

            let html = '';
            for (let i = 0; i < actions.length; i++) {
                const action = actions[i];
                const date = action.created_at ? new Date(action.created_at).toLocaleString() : 'Date inconnue';
                const commentaire = action.commentaire || 'Aucun commentaire';
                const id = action.id || '';

                html += `
                    <div class="item action-item">
                        <div class="item-header">
                            <span class="item-date">${date}</span>
                        </div>
                        <p class="item-text">${commentaire}</p>
                        <p class="item-author">Action #${id}</p>
                    </div>
                `;
            }
            return html;
        },
                updateEventInDatabase(event) {
            if (event.id || (event.originalData && event.originalData.id)) {
                 this.showLoader();
                const id = event.id || event.originalData.id;
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                const data = this.prepareEventData(event);

                console.log('Données envoyées pour mise à jour:', data);

                fetch(`/evenements/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(data)
                })
                .then(response => {
                    return response.json().catch(() => {
                        return response.text().then(text => {
                            return { error: text };
                        });
                    }).then(data => {
                        if (!response.ok) {
                            if (response.status === 422 && data.errors) {
                                const errorMessages = Object.entries(data.errors)
                                    .map(([field, messages]) => `${field}: ${messages.join(', ')}`)
                                    .join('\n');
                                throw new Error(`Erreur de validation:\n${errorMessages}`);
                            } else {
                                throw new Error(data.message || `Erreur ${response.status}`);
                            }
                        }
                        return data;
                    });
                })
                .then((data) => {
                    console.log('Succès de la mise à jour:', data);
                    this.hideLoader();
                     this.showSuccess('✅ Succès !', 'Événement mis à jour avec succès !');
                     setTimeout(() => {
                      this.refreshEvents();
                     }, 1500);
                })
                .catch(error => {
                    console.error('Erreur complète:', error);
                     this.hideLoader();
                    this.showError('❌ Erreur', `Erreur lors de la mise à jour: ${error.message}`);
                });
            }
        },

        createEventInDatabase(event) {
            this.showLoader();
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

            fetch('/evenements', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(this.prepareEventData(event))
            })
            .then(response => {
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    return response.text().then(text => {
                        console.error('Réponse non-JSON reçue:', text);
                        throw new Error('Réponse serveur inattendue (HTML au lieu de JSON)');
                    });
                }

                if (!response.ok) {
                    return response.json().then(errorData => {
                        console.error('Erreur serveur:', errorData);
                        throw new Error(`Erreur ${response.status}: ${errorData.message || 'Erreur inconnue'}`);
                    });
                }

                return response.json();
            })
            .then((data) => {
                console.log('Succès:', data);
                this.hideLoader();
                this.showSuccess('✅ Succès !', 'Événement créé avec succès !');
                setTimeout(() => {
                    this.refreshEvents();
                }, 1500);
            })
            .catch(error => {
                console.error('Erreur complète:', error);
                this.hideLoader();
                this.showError('❌ Erreur', `Erreur lors de la création: ${error.message}`);
            });
        },

        deleteEventFromDatabase(id) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            fetch(`/evenements/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur lors de la suppression');
                }
                return response.json();
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Erreur lors de la suppression de l\'événement');
            });
        },
           prepareEventData(event) {
    const data = {
        id: event.id || event.numero || null,
        date_evenement: event.dateHeure || null,
        nature_evenement_id: this.getNatureIdByLibelle(event.nature),
        location_id: this.getLocationIdByLibelle(event.localisation),
        description: event.description || '',
        consequence_sur_pdt: event.consequence === 'OUI' ? 1 : event.consequence === 'NON' ? 0 : null,
        redacteur: event.redacteur || '',
       statut: event.statut === 'En cours' ? 'en_cours' :
            event.statut === 'Terminé' ? 'cloture' :
            event.statut === 'Archivé' ? 'archive' : '',
        location_description: event.descriptionLocalisation || '',
        date_cloture: event.dateCloture || null,
        impact_id: this.getImpactIdByLibelle(event.impact),
        heure_appel_intervenant: event.heureAppelIntervenant || null,
        heure_arrive_intervenant: event.heureArriveeIntervenant || null,
        commentaire: event.commentaire || ''
    };

    // Gestion de la confidentialité
    if (event.confidentialite === 'Confidentiel') {
        data.confidentialite = true;
    } else if (event.confidentialite === 'Non confidentiel') {
        data.confidentialite = false;
    }

    // IMPORTANT : Inclure les actions (nouvelles et existantes)
    if (event.originalData?.actions && Array.isArray(event.originalData.actions)) {
        // Filtrer seulement les nouvelles actions (celles avec des IDs temporaires)
        const nouvelles_actions = event.originalData.actions.filter(action =>
            action.id > 1000000000000 // IDs temporaires générés par Date.now()
        );

        if (nouvelles_actions.length > 0) {
            data.actions = nouvelles_actions;
            console.log('Nouvelles actions à envoyer:', nouvelles_actions);
        }
    }

    // Gestion du nouveau commentaire
    if (event.new_comment && event.new_comment.trim() !== '') {
        data.new_comment = event.new_comment.trim();
    }

    // Gestion du type d'action individuel
    if (event.type_action && event.type_action !== '') {
        data.type_action = event.type_action;
        data.message_personnalise = event.message_personnalise || '';
        data.destinataires_metadata = event.destinataires || [];
    }

    console.log("Données préparées pour l'événement:", data);
    return data;
},
// NOUVELLE méthode pour ouvrir le modal de désarchivage
// CORRIGER la méthode unarchiveEvent dans le JavaScript
unarchiveEvent() {
    if (!this.currentEvent?.originalData?.id) {
        this.showError('❌ Erreur', 'Impossible de désarchiver : ID manquant');
        return;
    }

    if (!confirm('Êtes-vous sûr de vouloir désarchiver cet événement ?\n\nIl sera remis en statut "En cours".')) {
        return;
    }

    this.showLoader();

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    const eventId = this.currentEvent.originalData.id;

    // Envoyer seulement une requête PUT simple
    fetch(`/evenements/${eventId}/unarchive`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(errorData => {
                throw new Error(errorData.message || `Erreur ${response.status}`);
            });
        }
        return response.json();
    })
    .then(data => {
        this.hideLoader();
        this.showSuccess('✅ Désarchivé !', 'Événement désarchivé avec succès !');

        // Supprimer l'événement de la liste des archives
        if (this.currentEditIndex !== null) {
            this.getCurrentData().splice(this.currentEditIndex, 1);
        }

        // Fermer le modal
        this.closeModal();

        console.log('Événement désarchivé:', data);
    })
    .catch(error => {
        this.hideLoader();
        this.showError('❌ Erreur', `Erreur lors du désarchivage: ${error.message}`);
        console.error('Erreur désarchivage:', error);
    });
},

        getNatureIdByLibelle(libelle) {
            const nature = window.nature_evenements?.find(n => n.libelle === libelle);
            return nature ? nature.id : null;
        },

        getLocationIdByLibelle(libelle) {
            const location = window.locations?.find(l => l.libelle === libelle);
            return location ? location.id : null;
        },

        getImpactIdByLibelle(libelle) {
            const impact = window.impacts?.find(i => i.libelle === libelle);
            return impact ? impact.id : null;
        },
        unarchiveEventFromTable(index) {
    const eventData = this.getPaginatedData()[index];

    if (!eventData || !eventData.id) {
        this.showError('❌ Erreur', 'Impossible de désarchiver : événement introuvable');
        return;
    }

    console.log('Désarchivage depuis tableau:', eventData);

    if (!confirm(`Êtes-vous sûr de vouloir désarchiver l'événement #${eventData.id} ?\n\nIl sera remis en statut "En cours".`)) {
        return;
    }

    this.performUnarchive(eventData.id, index);
},

// ✅ MÉTHODE COMMUNE : Effectuer le désarchivage
performUnarchive(eventId, itemIndex = null) {
    this.showLoader();

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    fetch(`/evenements/${eventId}/unarchive`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            statut: 'en_cours',
            note_desarchivage: `Désarchivé le ${new Date().toLocaleDateString('fr-FR')} par ${window.user.nom_complet}`
        })
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(errorData => {
                throw new Error(errorData.message || `Erreur ${response.status}`);
            });
        }
        return response.json();
    })
    .then(data => {
        this.hideLoader();
        this.showSuccess('✅ Désarchivé !', 'Événement désarchivé avec succès !');

        // Supprimer l'événement de la liste des archives
        if (itemIndex !== null) {
            // Trouver l'index dans les données complètes (pas seulement paginées)
            const fullDataIndex = this.data[this.activeTab].findIndex(item => item.id == eventId);
            if (fullDataIndex !== -1) {
                this.data[this.activeTab].splice(fullDataIndex, 1);
            }
        }

        // Fermer le modal si ouvert
        if (this.showModal) {
            this.closeModal();
        }

        console.log('Événement désarchivé:', data);
    })
    .catch(error => {
        this.hideLoader();
        this.showError('❌ Erreur', `Erreur lors du désarchivage: ${error.message}`);
        console.error('Erreur désarchivage:', error);
    });
},
        // NOUVELLE méthode pour désarchiver un événement
unarchiveEvent() {
    if (!this.currentEvent?.originalData?.id) {
        this.showError('❌ Erreur', 'Impossible de désarchiver : ID manquant');
        return;
    }

    if (!confirm('Êtes-vous sûr de vouloir désarchiver cet événement ?\n\nIl sera remis en statut "En cours".')) {
        return;
    }

    this.showLoader();

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    const eventId = this.currentEvent.originalData.id;

    // Données pour désarchiver (changement de statut seulement)
    const data = {
        statut: 'en_cours', // Remettre en cours
        note_desarchivage: `Désarchivé le ${new Date().toLocaleDateString('fr-FR')} par ${window.user.nom_complet}`
    };

    fetch(`/evenements/${eventId}/unarchive`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(errorData => {
                throw new Error(errorData.message || `Erreur ${response.status}`);
            });
        }
        return response.json();
    })
    .then(data => {
        this.hideLoader();
        this.showSuccess('✅ Désarchivé !', 'Événement désarchivé avec succès !');

        // Supprimer l'événement de la liste des archives
        if (this.currentEditIndex !== null) {
            this.getCurrentData().splice(this.currentEditIndex, 1);
        }

        // Fermer le modal
        this.closeModal();

        console.log('Événement désarchivé:', data);
    })
    .catch(error => {
        this.hideLoader();
        this.showError('❌ Erreur', `Erreur lors du désarchivage: ${error.message}`);
        console.error('Erreur désarchivage:', error);
    });
},

    }; // Fin de l'objet retourné par mainCouranteApp

} ;// Fin de la fonction mainCouranteApp

// Ajouter la fonction getWeek à Date
Date.prototype.getWeek = function() {
    const firstDayOfYear = new Date(this.getFullYear(), 0, 1);
    const pastDaysOfYear = (this - firstDayOfYear) / 86400000;
    return Math.ceil((pastDaysOfYear + firstDayOfYear.getDay() + 1) / 7);
};

</script>


</x-layout.default>
