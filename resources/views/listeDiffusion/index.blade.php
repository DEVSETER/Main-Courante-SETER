<x-layout.default>
    <script src="/assets/js/simple-datatables.js"></script>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div x-data="miscellaneous">
        <div class="space-y-6">
            <div class="panel flex items-center justify-between overflow-x-auto whitespace-nowrap p-3 text-primary">
                <div class="flex items-center">
                    <div class="rounded-full p-1.5 text-white ltr:mr-3 rtl:ml-3" style="background-color: #67152e;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4 6H20M4 12H20M4 18H20" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <circle cx="4" cy="6" r="1.5" fill="currentColor" />
                            <circle cx="4" cy="12" r="1.5" fill="currentColor" />
                            <circle cx="4" cy="18" r="1.5" fill="currentColor" />
                        </svg>
                    </div>
                    <span class="font-semibold text-lg" style="color: #67152e;">LISTE DES DIFFUSIONS</span>
                </div>

                <button class="btn btn-primary flex items-center" style="background-color: #67152e; border-color: #67152e; color: #fff;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ltr:mr-2 rtl:ml-2">
                        <circle cx="10" cy="6" r="4" stroke="currentColor" stroke-width="1.5" />
                        <path opacity="0.5" d="M18 17.5C18 19.9853 18 22 10 22C2 22 2 19.9853 2 17.5C2 15.0147 5.58172 13 10 13C14.4183 13 18 15.0147 18 17.5Z" stroke="currentColor" stroke-width="1.5" />
                        <path d="M21 10H19M19 10H17M19 10L19 8M19 10L19 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                    </svg>
                    <a href="{{ route('liste_diffusions.create') }}" >Ajouter une liste</a>
                </button>
            </div>

            <div class="panel">
                <h5 class="md:absolute md:top-[25px] md:mb-0 mb-5 font-semibold text-lg dark:text-white-light">Listes de Diffusion</h5>
                <div class="relative">
                    <table id="myTable1" class="table-auto w-full border-collapse border border-gray-300"></table>
                </div>
            </div>
        </div>

      <div x-show="showUsersModal"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 scale-95"
     x-transition:enter-end="opacity-100 scale-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 scale-100"
     x-transition:leave-end="opacity-0 scale-95"
     class="fixed inset-0 z-[999] overflow-y-auto"
     style="display: none;">

    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black bg-opacity-50" @click="closeUsersModal()"></div>

    <!-- Modal Content -->
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-hidden">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200 flex-shrink-0" style="background-color: #67152e;">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-white">
                        <i class="fas fa-users mr-2"></i>
                        Utilisateurs de la liste : <span x-text="selectedListName"></span>
                    </h3>
                    <button @click="closeUsersModal()" class="text-white hover:text-gray-200 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- ✅ CORRECTION : Body avec scrollbar et hauteur limitée -->
            <div class="flex flex-col max-h-[calc(90vh-120px)]">
                <!-- Compteur d'utilisateurs (fixe) -->
                <div class="px-6 py-3 bg-gray-50 border-b border-gray-200 flex-shrink-0">
                    <div x-show="selectedUsers.length === 0" class="text-center py-4 text-gray-500">
                        <i class="fas fa-users fa-2x mb-2 text-gray-300"></i>
                        <p class="text-sm">Aucun utilisateur dans cette liste</p>
                    </div>

                    <div x-show="selectedUsers.length > 0" class="flex items-center justify-between">
                        <p class="text-sm text-gray-600 font-medium">
                            <i class="fas fa-users mr-2 text-blue-600"></i>
                            <strong x-text="selectedUsers.length"></strong>
                            utilisateur<span x-show="selectedUsers.length > 1">s</span> dans cette liste
                        </p>
                        <div class="text-xs text-gray-500">
                            <i class="fas fa-info-circle mr-1"></i>
                            Liste scrollable
                        </div>
                    </div>
                </div>

                <!-- ✅ Liste des utilisateurs avec scroll -->
                <div class="flex-1 overflow-y-auto px-6 py-4 scrollbar-custom">
                    <div x-show="selectedUsers.length > 0" class="space-y-3">
                        <template x-for="(user, index) in selectedUsers" :key="user.id">
                            <div class="flex items-center p-3 bg-gray-50 rounded-lg border hover:bg-gray-100 transition-colors">
                                <!-- Numéro d'ordre -->
                                <div class="flex-shrink-0 w-8 h-8 bg-gray-400 rounded-full flex items-center justify-center text-white text-xs font-bold mr-3">
                                    <span x-text="index + 1"></span>
                                </div>

                                <!-- Avatar avec initiales -->
                                <div class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center text-white font-semibold mr-3"
                                     :style="'background-color: ' + getUserColor(user.id)">
                                    <span x-text="getUserInitials(user)"></span>
                                </div>

                                <!-- Informations utilisateur -->
                                <div class="flex-1 min-w-0">
                                    <div class="font-medium text-gray-900 truncate">
                                        <span x-text="user.prenom + ' ' + user.nom"></span>
                                    </div>
                                    <div class="text-sm text-gray-500 truncate">
                                        <i class="fas fa-envelope mr-1"></i>
                                        <span x-text="user.email"></span>
                                    </div>
                                    <div x-show="user.entite" class="text-sm text-gray-500 truncate">
                                        <i class="fas fa-building mr-1"></i>
                                        <span x-text="user.entite ? user.entite.nom : '-'"></span>
                                    </div>
                                </div>

                                <!-- Badge statut -->
                                <div class="flex-shrink-0 ml-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Actif
                                    </span>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Footer (fixe) -->
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex-shrink-0">
                <div class="flex justify-between items-center">
                    <div class="text-xs text-gray-500">
                        <i class="fas fa-clock mr-1"></i>
                        Dernière mise à jour : <span x-text="getCurrentDate()"></span>
                    </div>
                    <button @click="closeUsersModal()"
                            class="px-4 py-2 text-white rounded hover:opacity-90 transition-colors"
                            style="background-color: #67152e;">
                        <i class="fas fa-times mr-2"></i>
                        Fermer
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ✅ Styles CSS pour le scrollbar personnalisé -->
<style>
    /* Scrollbar personnalisé */
    .scrollbar-custom {
        scrollbar-width: thin;
        scrollbar-color: #67152e #f1f5f9;
    }

    .scrollbar-custom::-webkit-scrollbar {
        width: 8px;
    }

    .scrollbar-custom::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 4px;
    }

    .scrollbar-custom::-webkit-scrollbar-thumb {
        background: #67152e;
        border-radius: 4px;
        transition: background 0.3s ease;
    }

    .scrollbar-custom::-webkit-scrollbar-thumb:hover {
        background: #8b2042;
    }

    /* Animation pour les éléments de la liste */
    .space-y-3 > * {
        animation: fadeInUp 0.3s ease-out;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Hauteur maximale pour différentes tailles d'écran */
    @media (max-height: 600px) {
        .scrollbar-custom {
            max-height: 200px;
        }
    }

    @media (min-height: 601px) and (max-height: 800px) {
        .scrollbar-custom {
            max-height: 300px;
        }
    }

    @media (min-height: 801px) {
        .scrollbar-custom {
            max-height: 400px;
        }
    }

    /* Hover effect sur les cartes utilisateur */
    .hover\:bg-gray-100:hover {
        background-color: #f3f4f6 !important;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
</style>
    <script>
        document.addEventListener("alpine:init", () => {
            Alpine.data("miscellaneous", () => ({
                columns: [
                    { name: 'Id', hidden: false },
                    { name: 'Nom', hidden: false },
                    { name: 'Utilisateurs', hidden: false },
                    { name: 'Crée le', hidden: false },
                    { name: 'Action', hidden: false },
                ],
                hideCols: [],
                showCols: [0, 1, 2, 3],
                datatable1: null,
                listes: @json($listes),

                // Propriétés pour le modal des utilisateurs
                showUsersModal: false,
                selectedUsers: [],
                selectedListName: '',

                init() {
                    console.log(this.listes);

                    const formatDate = (dateString) => {
                        const options = { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' };
                        return new Date(dateString).toLocaleString('fr-FR', options);
                    };

                    let headers = this.columns.map((col) => col.name);

                    let data = this.listes.map((liste) => [
                        liste.id,
                        liste.nom,
                        // Afficher le nombre d'utilisateurs avec un bouton cliquable
                      `<div class="flex items-center gap-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            ${liste.users.length} utilisateur(s)
                        </span>
                        ${liste.users.length > 0 ?
                            `<button onclick="window.showUsersList(${liste.id}, '${liste.nom.replace(/'/g, '\\\'')}')"
                                    class="inline-flex items-center justify-center w-8 h-8 bg-blue-100 text-blue-700 rounded-full hover:bg-blue-200 transition-colors"
                                    title="Voir la liste des utilisateurs">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </button>`
                            : ''
                        }
                    </div>`,
                        formatDate(liste.created_at),
                        `
                            <ul class="flex items-center justify-center gap-2">
                                <li>
                                    <a href="/liste_diffusions/${liste.id}/edit" x-tooltip="Edit">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 ltr:mr-2 rtl:ml-2">
                                            <path d="M15.2869 3.15178L14.3601 4.07866L5.83882 12.5999C5.26166 13.1771 4.97308 13.4656 4.7249 13.7838C4.43213 14.1592 4.18114 14.5653 3.97634 14.995C3.80273 15.3593 3.67368 15.7465 3.41556 16.5208L2.32181 19.8021L2.05445 20.6042C1.92743 20.9852 2.0266 21.4053 2.31063 21.6894C2.59466 21.9734 3.01478 22.0726 3.39584 21.9456L4.19792 21.6782L7.47918 20.5844C8.25353 20.3263 8.6407 20.1973 9.00498 20.0237C9.43469 19.8189 9.84082 19.5679 10.2162 19.2751C10.5344 19.0269 10.8229 18.7383 11.4001 18.1612L19.9213 9.63993L20.8482 8.71306C22.3839 7.17735 22.3839 4.68748 20.8482 3.15178C19.3125 1.61607 16.8226 1.61607 15.2869 3.15178Z" stroke="currentColor" stroke-width="1.5" />
                                            <path opacity="0.5" d="M14.36 4.07812C14.36 4.07812 14.4759 6.04774 16.2138 7.78564C17.9517 9.52354 19.9213 9.6394 19.9213 9.6394M4.19789 21.6777L2.32178 19.8015" stroke="currentColor" stroke-width="1.5" />
                                        </svg>
                                    </a>
                                </li>
                                <li>
                                    <form action="/liste_diffusions/${liste.id}" method="POST" style="display:inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette liste ?');">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                                        <button type="submit" x-tooltip="Delete">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-danger">
                                                <circle opacity="0.5" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.5"></circle>
                                                <path d="M14.5 9.50002L9.5 14.5M9.49998 9.5L14.5 14.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        `
                    ]);

                    // Initialiser la table avec simple-datatables
                    this.datatable1 = new simpleDatatables.DataTable('#myTable1', {
                        data: {
                            headings: headers,
                            data: data,
                        },
                        perPage: 10,
                        perPageSelect: [10, 20, 30, 50, 100],
                        columns: [{ select: 0, sort: 'asc' }],
                        layout: {
                            top: "{search}",
                            bottom: "{info}{select}{pager}",
                        },
                    });

                    // Fonction globale pour afficher le modal des utilisateurs
                    window.showUsersList = (listeId, listeName) => {
                        const liste = this.listes.find(l => l.id === listeId);
                        if (liste) {
                            this.selectedUsers = liste.users;
                            this.selectedListName = listeName;
                            this.showUsersModal = true;
                        }
                    };
                },
                 getUserInitials(user) {
            const prenom = user.prenom ? user.prenom.charAt(0).toUpperCase() : '';
            const nom = user.nom ? user.nom.charAt(0).toUpperCase() : '';
            return prenom + nom || '??';
        },
 getUserColor(userId) {
            const colors = [
                '#3b82f6', '#ef4444', '#10b981', '#f59e0b',
                '#8b5cf6', '#06b6d4', '#84cc16', '#f97316',
                '#ec4899', '#6366f1', '#14b8a6', '#fbbf24'
            ];
            return colors[userId % colors.length];
        },
         getCurrentDate() {
            return new Date().toLocaleString('fr-FR', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        },
                // Méthode pour fermer le modal
                closeUsersModal() {
                    this.showUsersModal = false;
                    this.selectedUsers = [];
                    this.selectedListName = '';
                },

                // Méthode pour afficher/masquer les colonnes
                showHideColumns(col, value) {
                    if (value) {
                        this.showCols.push(col);
                        this.hideCols = this.hideCols.filter((d) => d != col);
                    } else {
                        this.hideCols.push(col);
                        this.showCols = this.showCols.filter((d) => d != col);
                    }

                    // Mettre à jour les colonnes dans le tableau
                    let cols = this.datatable1.columns();
                    cols.hide(this.hideCols);
                    cols.show(this.showCols);
                },
            }));
        });
    </script>
</x-layout.default>
