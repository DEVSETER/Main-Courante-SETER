{{-- filepath: c:\Projet\MainCourante\MainCourante\MainCourante\resources\views\listeDiffusion\listeDiffusion-create.blade.php --}}

<x-layout.default>
    <!-- ✅ CORRECTION : Même structure exacte que user-create -->
    <div class="main-background bg-[url(/assets/images/auth/map.png)] bg-cover bg-center">
        <div x-data="userSelection" class="container">
            <!-- ✅ En-tête harmonisé -->
            <div class="mb-8 text-center">
                <h1 class="text-3xl font-bold mb-2" style="color: #67152e;">
                    📝 Créer une nouvelle liste de diffusion
                </h1>
                <p class="text-gray-600">
                    Créez une liste de diffusion en sélectionnant les utilisateurs
                </p>
            </div>

            <!-- ✅ Formulaire avec le même style -->
            <form class="space-y-6" id="formUtilisateur" action="{{ route('liste_diffusions.store') }}" method="POST">
                @csrf

                <!-- ✅ Champ Nom avec le même style -->
                <div class="form-group">
                    <label for="nom" class="block text-sm font-medium text-gray-700 mb-2">
                        📋 Nom de la liste
                    </label>
                    <input
                        id="nom"
                        name="nom"
                        type="text"
                        placeholder="Entrez le nom de la liste de diffusion"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        required
                    />
                    @error('nom')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- ✅ Recherche d'utilisateurs avec le même style -->
                <div class="form-group">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                        🔍 Rechercher des utilisateurs
                    </label>
                    <input
                        type="text"
                        id="search"
                        x-model="search"
                        placeholder="Rechercher par nom, prénom ou email"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    />
                    <small class="text-gray-500 mt-1 block">
                        Tapez pour filtrer les utilisateurs disponibles
                    </small>
                </div>

                <!-- ✅ Liste des utilisateurs disponibles -->
                <div class="form-group">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        👥 Utilisateurs disponibles
                        <span x-show="filteredUsers.length > 0"
                              x-text="`(${filteredUsers.length} trouvé${filteredUsers.length > 1 ? 's' : ''})`"
                              class="text-gray-500 font-normal">
                        </span>
                    </label>

                    <div class="border border-gray-300 rounded-md max-h-64 overflow-y-auto bg-gray-50">
                        <template x-for="user in filteredUsers" :key="user.id">
                            <div class="flex items-center p-3 border-b border-gray-200 hover:bg-blue-50 transition-colors">
                                <label class="flex items-center cursor-pointer w-full">
                                    <input
                                        type="checkbox"
                                        :id="'user-' + user.id"
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded mr-3"
                                        name="users[]"
                                        :value="user.id"
                                        @change="toggleUser(user)"
                                        :checked="selectedUsers.some(selected => selected.id === user.id)"
                                    />
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-2">
                                            <span class="font-medium text-gray-900">
                                                <span x-text="user.nom"></span>
                                                <span x-text="user.prenom"></span>
                                            </span>
                                            <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">
                                                ID: <span x-text="user.id"></span>
                                            </span>
                                        </div>
                                        <div class="text-sm text-gray-600 mt-1">
                                            📧 <span x-text="user.email"></span>
                                        </div>
                                        <div x-show="user.fonction" class="text-xs text-gray-500 mt-1">
                                            🏢 <span x-text="user.fonction || 'Fonction non précisée'"></span>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </template>

                        <!-- Message si aucun utilisateur -->
                        <div x-show="filteredUsers.length === 0" class="p-4 text-center text-gray-500">
                            <div x-show="search === ''">
                                <p>Tous les utilisateurs sont déjà sélectionnés</p>
                            </div>
                            <div x-show="search !== ''">
                                <p>Aucun utilisateur trouvé pour "<span x-text="search"></span>"</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ✅ Utilisateurs sélectionnés -->
                <div class="form-group">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        ✅ Utilisateurs sélectionnés
                        <span x-show="selectedUsers.length > 0"
                              x-text="`(${selectedUsers.length} sélectionné${selectedUsers.length > 1 ? 's' : ''})`"
                              class="text-green-600 font-normal">
                        </span>
                    </label>

                    <div class="border border-gray-300 rounded-md min-h-[100px] bg-green-50">
                        <template x-for="user in selectedUsers" :key="user.id">
                            <div class="flex items-center justify-between p-3 border-b border-green-200 bg-white mx-2 my-2 rounded shadow-sm">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                        <span class="text-green-600 text-sm font-medium" x-text="user.nom.charAt(0) + user.prenom.charAt(0)"></span>
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900">
                                            <span x-text="user.nom"></span>
                                            <span x-text="user.prenom"></span>
                                        </div>
                                        <div class="text-sm text-gray-600">
                                            <span x-text="user.email"></span>
                                        </div>
                                    </div>
                                </div>
                                <button
                                    type="button"
                                    @click="removeUser(user)"
                                    class="text-red-500 hover:text-red-700 font-medium text-sm px-3 py-1 rounded hover:bg-red-50 transition-colors"
                                >
                                    ❌ Retirer
                                </button>
                            </div>
                        </template>

                        <div x-show="selectedUsers.length === 0" class="p-4 text-center text-gray-500">
                            <p>Aucun utilisateur sélectionné</p>
                            <small>Cochez les utilisateurs ci-dessus pour les ajouter à la liste</small>
                        </div>
                    </div>
                </div>

                <!-- ✅ Champs cachés pour les utilisateurs sélectionnés -->
                <template x-for="user in selectedUsers" :key="'hidden-'+user.id">
                    <input type="hidden" name="users[]" :value="user.id">
                </template>

                <!-- ✅ Résumé avant soumission -->
                <div x-show="selectedUsers.length > 0" class="form-group bg-blue-50 border border-blue-200 rounded-md p-4">
                    <h3 class="font-medium text-blue-900 mb-2">📊 Résumé de la liste</h3>
                    <ul class="text-sm text-blue-800 space-y-1">
                        <li>
                            <strong>Nom :</strong>
                            <span x-text="document.getElementById('nom').value || 'Non défini'"></span>
                        </li>
                        <li>
                            <strong>Nombre d'utilisateurs :</strong>
                            <span x-text="selectedUsers.length"></span>
                        </li>
                    </ul>
                </div>

                <!-- ✅ Boutons d'action avec le même style -->
                <div class="form-group flex space-x-4">
                    <button
                        type="submit"
                        class="flex-1 text-white font-semibold py-3 px-6 rounded-md transition-all duration-300 hover:shadow-lg disabled:opacity-50"
                        style="background-color: #67152e;"
                        :disabled="selectedUsers.length === 0 || !document.getElementById('nom').value"
                    >
                        <span x-show="selectedUsers.length > 0">
                            ✅ Créer la liste (<span x-text="selectedUsers.length"></span> utilisateur<span x-text="selectedUsers.length > 1 ? 's' : ''"></span>)
                        </span>
                        <span x-show="selectedUsers.length === 0">
                            📝 Sélectionnez des utilisateurs
                        </span>
                    </button>

                    <a
                        href="{{ route('liste_diffusions.index') }}"
                        class="px-6 py-3 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors font-medium"
                    >
                        ❌ Annuler
                    </a>
                </div>

                <!-- ✅ Aide -->
                <div class="form-group bg-yellow-50 border border-yellow-200 rounded-md p-4">
                    <h4 class="font-medium text-yellow-800 mb-2">💡 Aide</h4>
                    <ul class="text-sm text-yellow-700 space-y-1">
                        <li>• Donnez un nom descriptif à votre liste</li>
                        <li>• Utilisez la recherche pour trouver rapidement des utilisateurs</li>
                        <li>• Vous pouvez sélectionner/désélectionner des utilisateurs à tout moment</li>
                        <li>• Au moins un utilisateur doit être sélectionné</li>
                    </ul>
                </div>
            </form>
        </div>
    </div>

    <!-- ✅ CORRECTION : Styles exactement comme user-create -->
    <style>
        /* ✅ Arrière-plan avec image map.png - IDENTIQUE à user-create */
        .main-background {
            min-height: 100vh;
            background: #f8fafc;
            padding: 2rem 0;
            /* ✅ L'image map.png est ajoutée via la classe Tailwind bg-[url(/assets/images/auth/map.png)] */
        }

        /* ✅ IDENTIQUE à user-create.blade.php */
        .text-red-500 {
            color: #ef4444;
        }

        /* ✅ CORRECTION : Conteneur avec fond transparent blur EXACTEMENT comme user-create */
        .container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
            font-family: system-ui, -apple-system, sans-serif;

            /* ✅ TRANSPARENT BLUR - IDENTIQUE à user-create */
            background: rgba(255, 255, 255, 0.9); /* Fond blanc semi-transparent */
            backdrop-filter: blur(10px); /* Effet blur */
            -webkit-backdrop-filter: blur(10px); /* Support Safari */

            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2); /* Bordure subtile */
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group:last-child {
            margin-bottom: 0;
        }

        /* Focus states - identiques */
        input:focus, select:focus {
            outline: none;
            ring: 2px;
            ring-color: #3b82f6;
            border-color: transparent;
        }

        /* Checkbox styles - identiques */
        input[type="checkbox"]:checked {
            background-color: #67152e;
            border-color: #67152e;
        }

        /* Hover effects - identiques */
        .hover\:bg-blue-50:hover {
            background-color: #eff6ff;
        }

        .hover\:bg-red-50:hover {
            background-color: #fef2f2;
        }

        /* Scrollbar styling - identiques */
        .overflow-y-auto::-webkit-scrollbar {
            width: 6px;
        }

        .overflow-y-auto::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        .overflow-y-auto::-webkit-scrollbar-thumb {
            background: #67152e;
            border-radius: 3px;
        }

        .overflow-y-auto::-webkit-scrollbar-thumb:hover {
            background: #8b2042;
        }

        /* Animation pour les utilisateurs sélectionnés */
        .bg-white {
            transition: all 0.2s ease-in-out;
        }

        /* ✅ Amélioration du blur pour différents navigateurs */
        @supports (backdrop-filter: blur(10px)) {
            .container {
                background: rgba(255, 255, 255, 0.85);
                backdrop-filter: blur(10px);
            }
        }

        @supports not (backdrop-filter: blur(10px)) {
            .container {
                background: rgba(255, 255, 255, 0.95);
            }
        }

        /* Responsive - identiques */
        @media (max-width: 768px) {
            .main-background {
                padding: 1rem 0;
            }

            .container {
                margin: 1rem;
                padding: 1rem;
                max-width: calc(100% - 2rem);
                /* ✅ Maintenir l'effet blur sur mobile */
                background: rgba(255, 255, 255, 0.9);
                backdrop-filter: blur(8px);
                -webkit-backdrop-filter: blur(8px);
            }

            .flex.space-x-4 {
                flex-direction: column;
                gap: 1rem;
            }

            .flex.space-x-4 > * {
                margin-left: 0 !important;
            }
        }

        /* ✅ Loading spinner - identique à user-create */
        .loading {
            border: 2px solid #f3f3f3;
            border-top: 2px solid #67152e;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            animation: spin 1s linear infinite;
            display: inline-block;
            margin-left: 10px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* ✅ Améliorer la lisibilité sur le fond avec image */
        h1, p, label {
            text-shadow: 0 1px 2px rgba(255, 255, 255, 0.8);
        }

        /* ✅ S'assurer que les éléments de formulaire sont bien visibles */
        input, select, textarea {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(5px);
        }

        .form-group .bg-yellow-50,
        .form-group .bg-blue-50,
        .form-group .bg-green-50 {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(5px);
        }
    </style>

    <!-- Script Alpine.js identique -->
    <script>
        document.addEventListener("alpine:init", () => {
            Alpine.data("userSelection", () => ({
                users: @json($users),
                search: '',
                selectedUsers: [],

                get filteredUsers() {
                    const searchTerm = this.search.toLowerCase().trim();

                    return this.users.filter(user => {
                        if (this.selectedUsers.some(selected => selected.id === user.id)) {
                            return false;
                        }

                        if (!searchTerm) {
                            return true;
                        }

                        const nom = (user.nom || '').toLowerCase();
                        const prenom = (user.prenom || '').toLowerCase();
                        const email = (user.email || '').toLowerCase();
                        const fonction = (user.fonction || '').toLowerCase();

                        return nom.includes(searchTerm) ||
                               prenom.includes(searchTerm) ||
                               email.includes(searchTerm) ||
                               fonction.includes(searchTerm) ||
                               `${nom} ${prenom}`.includes(searchTerm);
                    });
                },

                toggleUser(user) {
                    const isSelected = this.selectedUsers.some(selected => selected.id === user.id);

                    if (isSelected) {
                        this.removeUser(user);
                    } else {
                        this.selectedUsers.push(user);
                        console.log('✅ Utilisateur ajouté:', user.nom, user.prenom);
                    }

                    this.updateSubmitButton();
                },

                removeUser(user) {
                    this.selectedUsers = this.selectedUsers.filter(selected => selected.id !== user.id);
                    console.log('❌ Utilisateur retiré:', user.nom, user.prenom);
                    this.updateSubmitButton();
                },

                updateSubmitButton() {
                    const submitBtn = document.querySelector('button[type="submit"]');
                    const nomInput = document.getElementById('nom');

                    if (submitBtn) {
                        const canSubmit = this.selectedUsers.length > 0 && nomInput.value.trim() !== '';
                        submitBtn.disabled = !canSubmit;
                    }
                },

                init() {
                    console.log('🚀 Liste de diffusion - Initialisation');
                    console.log('👥 Utilisateurs disponibles:', this.users.length);

                    this.$watch('selectedUsers', () => {
                        this.updateSubmitButton();
                    });

                    const nomInput = document.getElementById('nom');
                    if (nomInput) {
                        nomInput.addEventListener('input', () => {
                            this.updateSubmitButton();
                        });
                    }
                }
            }));
        });

        // ✅ Validation avant soumission - identique à user-create
        document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formUtilisateur');
    if (form) {
        form.addEventListener('submit', function(e) {
            const nom = document.getElementById('nom').value.trim();

            // ✅ CORRECTION : Compter les champs cachés au lieu des checkboxes
            const selectedCount = document.querySelectorAll('input[type="hidden"][name="users[]"]').length;

            if (!nom) {
                e.preventDefault();
                alert('❌ Veuillez saisir un nom pour la liste');
                document.getElementById('nom').focus();
                return false;
            }

            if (selectedCount === 0) {
                e.preventDefault();
                alert('❌ Veuillez sélectionner au moins un utilisateur');
                return false;
            }

            const confirmMessage = `Créer la liste "${nom}" avec ${selectedCount} utilisateur${selectedCount > 1 ? 's' : ''} ?`;
            if (!confirm(confirmMessage)) {
                e.preventDefault();
                return false;
            }

            console.log('✅ Création de la liste en cours...');
            console.log('📋 Utilisateurs sélectionnés:', selectedCount);

            // Debug : afficher les IDs sélectionnés
            const userIds = Array.from(document.querySelectorAll('input[type="hidden"][name="users[]"]')).map(input => input.value);
            console.log('👥 IDs utilisateurs:', userIds);

            return true;
        });
    }
});
    </script>
</x-layout.default>
