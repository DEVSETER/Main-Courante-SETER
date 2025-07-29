<x-layout.default>
    @content('head')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

    <!-- Alpine.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.13.3/cdn.min.js" defer></script>

    <!-- jQuery et Select2 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    @endcontent
     <style>
        .form-input {
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
        }
        .form-input:focus {
            outline: none;
            border-color: #67152e;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        .text-sm { font-size: 0.875rem; }
        .font-medium { font-weight: 500; }
        .block { display: block; }
        .mt-1 { margin-top: 0.25rem; }
        .w-full { width: 100%; }
        .text-red-500 { color: #ef4444; }
        .container {
            max-width: 600px;
            margin: 2rem auto;
            padding: 1rem;
            font-family: system-ui, -apple-system, sans-serif;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .readonly-field {
            background-color: #f9fafb;
            cursor: not-allowed;
        }
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
         .select2-container {
        width: 100% !important;
    }

    .select2-container--default .select2-selection--single {
        height: 42px !important;
        border: 1px solid #d1d5db !important;
        border-radius: 0.375rem !important;
        padding: 0.5rem 0.75rem !important;
        display: flex !important;
        align-items: center !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        padding: 0 !important;
        line-height: 1.5 !important;
        color: #374151 !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__placeholder {
        color: #9ca3af !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 40px !important;
        right: 8px !important;
    }

    .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: #67152e !important;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
    }

    .select2-dropdown {
        border: 1px solid #d1d5db !important;
        border-radius: 0.375rem !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1) !important;
    }

    .select2-container--default .select2-search--dropdown .select2-search__field {
        border: 1px solid #d1d5db !important;
        border-radius: 0.25rem !important;
        padding: 0.5rem !important;
        margin: 0.5rem !important;
        width: calc(100% - 1rem) !important;
    }

    .select2-container--default .select2-results__option {
        padding: 0.75rem !important;
        border-bottom: 1px solid #f3f4f6 !important;
    }

    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        /* background-color: #67152e !important;
         */
          background-color: rgba(46, 46, 44, 0.05);



        color: white !important;
    }

    .select2-container--default .select2-results__option[aria-selected="true"] {
        background-color: #e5e7eb !important;
        color: #374151 !important;
    }

    /* ✅ Animation pour les résultats */
    .select2-results__option .fw-bold {
        font-weight: 600 !important;
        color: #1f2937 !important;
    }

    .select2-results__option .text-muted {
        color: #6b7280 !important;
        font-size: 0.875rem !important;
    }

    .select2-results__option .badge {
        background-color: #67152e !important;
        color: white !important;
        padding: 0.25rem 0.5rem !important;
        border-radius: 0.25rem !important;
        font-size: 0.75rem !important;
    }

    /* ✅ État de chargement */
    .select2-results__option.loading-results {
        text-align: center !important;
        color: #6b7280 !important;
        font-style: italic !important;
    }

    /* ✅ Message "Aucun résultat" */
    .select2-results__option.select2-results__message {
        color: #6b7280 !important;
        text-align: center !important;
        font-style: italic !important;
    }
    </style>
    <div >
        <div class="absolute inset-0">
            <img src="/assets/images/auth/bg-gradient.png" alt="image" class="h-full w-full object-cover" />
        </div>
        <div class="relative flex min-h-screen items-center justify-center bg-[url(/assets/images/auth/map.png)] bg-cover bg-center bg-no-repeat px-6 py-10 dark:bg-[#060818] sm:px-16">
            <img src="/assets/images/auth/coming-soon-object1.png" alt="image" class="absolute left-0 top-1/2 h-full max-h-[893px] -translate-y-1/2" />
            <img src="/assets/images/auth/coming-soon-object2.png" alt="image" class="absolute left-24 top-0 h-40 md:left-[30%]" />
            <img src="/assets/images/auth/coming-soon-object3.png" alt="image" class="absolute right-0 top-0 h-[300px]" />
            <img src="/assets/images/auth/polygon-object.svg" alt="image" class="absolute bottom-0 end-[28%]" />
            <div
                class="relative w-full max-w-[870px] rounded-md bg-[linear-gradient(45deg,#fff9f9_0%,rgba(255,255,255,0)_25%,rgba(255,255,255,0)_75%,_#fff9f9_100%)] p-2 dark:bg-[linear-gradient(52.22deg,#0E1726_0%,rgba(14,23,38,0)_18.66%,rgba(14,23,38,0)_51.04%,rgba(14,23,38,0)_80.07%,#0E1726_100%)]">
                <div class="relative flex flex-col justify-center rounded-md bg-white/60 backdrop-blur-lg dark:bg-black/50 px-6 lg:min-h-[758px] py-20">
                    <div class="absolute top-6 end-6">
                        <div class="dropdown" x-data="dropdown" @click.outside="open = false">

                        </div>
                    </div>
                    <div x-ref="form" class="mx-auto w-full max-w-[440px]">
                        <div class="mb-10">
                            <h1 class="text-3xl font-extrabold uppercase !leading-snug text-primary md:text-4xl" class="btn" style="color: #67152e; border-color: #ebba7d;"> nouvel utilisateur</h1>
                            <p class="text-base font-bold leading-normal text-white-dark">Enter your email and password to register</p>
                        </div>



        <div class="container mx-auto px-4">
        <h1 class="text-2xl font-bold mb-6">Créer un utilisateur</h1>

        <form action="{{ route('users.store') }}" method="POST" class="space-y-6">
                    @csrf
<div class="form-group" id="userForm">
    <label for="employe-select" class="block text-sm font-medium">
        🔍 Rechercher un employé
    </label>
    <select id="employe-select"
            class="select2"
            name="matricule"
            required
            data-placeholder="Tapez pour rechercher un employé..."
            data-allow-clear="true">
        <option value="">-- Sélectionner un employé --</option>
        <!-- Les options seront ajoutées en JS -->
    </select>
    <span id="loading" class="loading" style="display: none;"></span>
    <p id="matricule-error" class="text-red-500 text-sm mt-1" style="display: none;"></p>

    <!-- ✅ NOUVEAU : Indicateur de nombre d'employés -->
    <div id="employes-count" class="text-xs text-gray-500 mt-1" style="display: none;">
        <span id="total-employes">0</span> employés disponibles
    </div>
</div>
            <!-- Champs qui apparaissent après validation du matricule -->
            <div id="userFields" style="display: none;">
                <!-- Nom (automatiquement rempli) -->
                <div class="form-group">
                    <label for="nom" class="block text-sm font-medium">Nom</label>
                    <input type="text" id="nom" name="nom" class="form-input mt-1 block w-full readonly-field" readonly>
                </div>

                <!-- Prénom (automatiquement rempli) -->
                <div class="form-group">
                    <label for="prenom" class="block text-sm font-medium">Prénom</label>
                    <input type="text" id="prenom" name="prenom" class="form-input mt-1 block w-full readonly-field" readonly>
                </div>

                <!-- Email (automatiquement rempli) -->
                <div class="form-group">
                    <label for="email" class="block text-sm font-medium">Email</label>
                    <input type="email" id="email" name="email" class="form-input mt-1 block w-full readonly-field" readonly>
                </div>



                <!-- Téléphone (automatiquement rempli) -->
                <div class="form-group">
                    <label for="telephone" class="block text-sm font-medium">Téléphone</label>
                    <input type="text" id="telephone" name="telephone" class="form-input mt-1 block w-full readonly-field" readonly>
                </div>
                <!-- Direction (automatiquement rempli) -->

                   <div class="form-group">
                    <label for="direction" class="block text-sm font-medium">Direction</label>
                    <input type="text" id="direction" name="direction" class="form-input mt-1 block w-full readonly-field" readonly>
                </div>
                <!-- Fonction (automatiquement rempli) -->

                   <div class="form-group">
                    <label for="fonction" class="block text-sm font-medium">Fonction</label>
                    <input type="text" id="fonction" name="fonction" class="form-input mt-1 block w-full readonly-field" readonly>
                </div>

            </div>

            <!-- Fonction -->
            {{-- <div>
                <label for="fonction" class="block text-sm font-medium">Fonction</label>
                <select id="fonction" name="fonction" class="form-select mt-1 block w-full">
                    <option value="">Sélectionner une fonction</option>
                    <option value="CIV" {{ old('fonction') == 'CIV' ? 'selected' : '' }}>CIV</option>
                    <option value="HOTLINE" {{ old('fonction') == 'HOTLINE' ? 'selected' : '' }}>HOTLINE</option>
                    <option value="SUPERVISEUR COF" {{ old('fonction') == 'SUPERVISEUR COF' ? 'selected' : '' }}>SUPERVISEUR COF</option>
                    <option value="CM" {{ old('fonction') == 'CM' ? 'selected' : '' }}>CM</option>
                    <option value="PLANIFICATEUR" {{ old('fonction') == 'PLANIFICATEUR' ? 'selected' : '' }}>PLANIFICATEUR</option>
                    <option value="CHEF CIRCULATION" {{ old('fonction') == 'CHEF CIRCULATION' ? 'selected' : '' }}>CHEF CIRCULATION</option>
                </select>
                @error('fonction')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div> --}}

            <!-- Direction -->
              <div>
                @if ($entites->isNotEmpty())
                    <label for="entite_id" class="block text-sm font-medium">Entité</label>
                    <select id="entite_id" name="entite_id" class="form-select mt-1 block w-full">
                        <option value="">Sélectionner une entité</option>
                        @foreach ($entites as $item)
                            <option value="{{ $item->id }}" {{ old('entite_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->nom }}
                            </option>
                        @endforeach
                    </select>
                @else
                    <p class="text-gray-500 text-sm mt-1">Aucune fonction disponible.</p>
                @endif

                @error('entite_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>


            <!-- Rôle -->
            <div>
                @if ($roles->isNotEmpty())
                    <label for="role_id" class="block text-sm font-medium">Rôle</label>
                    <select id="role_id" name="role_id" class="form-select mt-1 block w-full">
                        <option value="">Sélectionner un rôle</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                @else
                    <p class="text-gray-500 text-sm mt-1">Aucun rôle disponible.</p>
                @endif

                @error('role_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Bouton de soumission -->
            <div>
                <button  @click="$refs.form.submit()"  class="btn btn-primary w-full" class="btn" style="background-color: #67152e; border-color: #67152e; color: #fff;">Créer</button>
            </div>
        </form>
    </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
document.addEventListener('DOMContentLoaded', async () => {
    // ✅ DÉFINIR TOUTES LES VARIABLES GLOBALES EN PREMIER
    const select = document.getElementById('employe-select');
    const userFields = document.getElementById('userFields');
    const nomInput = document.getElementById('nom');
    const prenomInput = document.getElementById('prenom');
    const emailInput = document.getElementById('email');
    const telephoneInput = document.getElementById('telephone');
    const directionInput = document.getElementById('direction');
    const fonctionInput = document.getElementById('fonction');
    const loadingSpinner = document.getElementById('loading');
    const errorMessage = document.getElementById('matricule-error');

    let employes = JSON.parse(localStorage.getItem('employes_annuaire') || '[]');

    // ✅ DÉFINIR TOUTES LES FONCTIONS EN PREMIER

    // Fonction pour cacher les champs et vider leurs valeurs
    function hideAndClearFields() {
        userFields.style.display = 'none';
        nomInput.value = '';
        prenomInput.value = '';
        emailInput.value = '';
        telephoneInput.value = '';
        directionInput.value = '';
        fonctionInput.value = '';
    }

    // Fonction pour afficher et remplir les champs avec les données trouvées
    function showAndFillFields(user) {
        nomInput.value = user.nom || '';
        prenomInput.value = user.prenom || '';
        emailInput.value = user.email || '';
        telephoneInput.value = user.telephone || '';
        directionInput.value = user.direction || '';
        fonctionInput.value = user.fonction || '';

        userFields.style.display = 'block';
        userFields.style.opacity = '0';
        setTimeout(() => {
            userFields.style.transition = 'opacity 0.3s ease-in-out';
            userFields.style.opacity = '1';
        }, 10);
    }

    // ✅ Format d'affichage personnalisé pour les résultats
    function formatEmploye(employe) {
        if (employe.loading) return employe.text;
        if (!employe.id) return employe.text;

        // Récupérer les données de l'employé
        const empData = employes.find(emp => emp.matricule == employe.id);
        if (!empData) return employe.text;

        return $(`
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <div class="fw-bold">${empData.nom || 'N/A'} ${empData.prenom || 'N/A'}</div>
                    <small class="text-muted">
                        📧 ${empData.email || 'Email non disponible'} |
                        🏢 ${empData.direction || 'Direction non précisée'}
                    </small>
                </div>
                <span class="badge bg-primary ms-2">${empData.matricule}</span>
            </div>
        `);
    }

    // ✅ Format pour la sélection
    function formatEmployeSelection(employe) {
        if (!employe.id) return employe.text;

        const empData = employes.find(emp => emp.matricule == employe.id);
        if (!empData) return employe.text;

        return `${empData.nom || ''} ${empData.prenom || ''} (${empData.matricule})`;
    }

    // ✅ Matcher personnalisé pour recherche avancée
    function customMatcher(params, data) {
        // Si pas de terme de recherche, afficher tout
        if ($.trim(params.term) === '') {
            return data;
        }

        // Si pas d'enfants (pas un groupe), vérifier si ça correspond
        if (typeof data.text === 'undefined') {
            return null;
        }

        const term = params.term.toLowerCase();
        const empData = employes.find(emp => emp.matricule == data.id);

        if (!empData) {
            return null;
        }

        // Recherche dans plusieurs champs
        const searchFields = [
            empData.nom || '',
            empData.prenom || '',
            empData.email || '',
            empData.matricule || '',
            empData.direction || '',
            empData.fonction || '',
            empData.telephone || ''
        ];

        const found = searchFields.some(field =>
            field.toString().toLowerCase().indexOf(term) > -1
        );

        return found ? data : null;
    }

    // ✅ Fonction pour gérer la sélection d'employé
    function handleEmployeSelection(matricule) {
        hideAndClearFields();
        errorMessage.style.display = 'none';

        if (!matricule) return;

        // Cherche l'employé dans le cache
        const user = employes.find(emp => emp.matricule == matricule);

        if (user) {
            showAndFillFields(user);
            console.log('✅ Employé sélectionné:', user);
        } else {
            errorMessage.textContent = 'Aucun utilisateur trouvé avec ce matricule';
            errorMessage.style.display = 'block';
        }
    }

    // ✅ Fonction d'initialisation Select2
    function initializeSelect2() {
        // Détruire Select2 s'il existe déjà
        if ($(select).hasClass('select2-hidden-accessible')) {
            $(select).select2('destroy');
        }

        $(select).select2({
            placeholder: '🔍 Rechercher un employé...',
            allowClear: true,
            width: '100%',
            theme: 'default',
            language: {
                noResults: function() {
                    return "Aucun employé trouvé";
                },
                searching: function() {
                    return "Recherche en cours...";
                },
                inputTooShort: function() {
                    return "Tapez au moins 2 caractères";
                }
            },
            minimumInputLength: 0,
            templateResult: formatEmploye,
            templateSelection: formatEmployeSelection,
            matcher: customMatcher
        });

        // Gestionnaire d'événement Select2
        $(select).on('select2:select', function(e) {
            const matricule = e.params.data.id;
            handleEmployeSelection(matricule);
        });

        $(select).on('select2:unselect select2:clear', function() {
            hideAndClearFields();
            errorMessage.style.display = 'none';
        });

        console.log('✅ Select2 initialisé avec succès');
    }

    // ✅ Ajouter un compteur d'employés
    function updateEmployeCount(count) {
        const countElement = document.getElementById('employes-count');
        const totalElement = document.getElementById('total-employes');

        if (countElement && totalElement) {
            totalElement.textContent = count;
            countElement.style.display = count > 0 ? 'block' : 'none';
        }
    }

    // ✅ FONCTIONS UTILITAIRES (déplacées ici)
    const token = 'wB?iNyUiB1kI-aCo9O/wYi!8ejBbID?oDQCXB?QzvVh9cEIx2IGo3LYf2v300JHlv/=2lLvqHRqHb47/oktUEM8xZaAbzuW8hGfoUYDYg!cey!YaACNtJPsI5F==EGRVKV7EemK80/V/c?0tQziJl!S5CvzrYu-UHB6y0=BE8PbBLKFs4Tw8/Ts50-B-L2L4lYCd8EAfxkGIggcboh56Fox7ODQRxZYIVqvtm9ikomm9V!4Kc2nW?7GGF?d3tXM6';

    function delay(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }

    async function fetchWithRetry(url, options, maxRetries = 3, baseDelay = 2000) {
        for (let attempt = 1; attempt <= maxRetries; attempt++) {
            try {
                console.log(`Tentative ${attempt}/${maxRetries}...`);
                const response = await fetch(url, options);

                if (!response.ok) {
                    const errorText = await response.text();
                    console.log('Réponse d\'erreur:', errorText);

                    if (errorText.includes('doo doug mbeuleee') || response.status === 429) {
                        console.warn('API bloquée - Attente avant nouvelle tentative...');
                        if (attempt < maxRetries) {
                            const waitTime = baseDelay * Math.pow(2, attempt - 1);
                            console.log(`Attente de ${waitTime/1000} secondes...`);
                            await delay(waitTime);
                            continue;
                        }
                    }
                    throw new Error(`HTTP ${response.status}: ${errorText}`);
                }
                return response;
            } catch (error) {
                console.error(`Tentative ${attempt} échouée:`, error.message);
                if (attempt === maxRetries) {
                    throw error;
                }
                const waitTime = baseDelay * attempt;
                console.log(`Attente de ${waitTime/1000} secondes avant nouvelle tentative...`);
                await delay(waitTime);
            }
        }
    }

    // ✅ VÉRIFICATION DES ÉLÉMENTS DOM
    if (!select) {
        console.error('❌ Élément select non trouvé');
        return;
    }

    // ✅ CHARGEMENT DES DONNÉES
    const cacheTimestamp = localStorage.getItem('employes_cache_timestamp');
    const cacheAge = cacheTimestamp ? Date.now() - parseInt(cacheTimestamp) : Infinity;
    const cacheMaxAge = 60 * 60 * 1000; // 1 heure

    if (employes.length === 0 || cacheAge > cacheMaxAge) {
        try {
            console.log('🔄 Chargement des données depuis l\'API...');
            loadingSpinner.style.display = 'inline-block';

            const response = await fetchWithRetry('https://annuaire.seter.sn/api/annuaire', {
                method: 'GET',
                headers: {
                    'token': token,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            });

            const responseText = await response.text();
            console.log('📥 Réponse brute de l\'API:', responseText.substring(0, 200) + '...');

            if (responseText.includes('doo doug mbeuleee')) {
                console.warn('⚠️ API retourne un message de blocage');
                throw new Error('Accès refusé par l\'API');
            }

            try {
                employes = JSON.parse(responseText);

                if (typeof employes === 'string' && employes === 'doo doug mbeuleee') {
                    throw new Error('API retourne un message d\'erreur');
                }

                if (!Array.isArray(employes)) {
                    if (employes.data && Array.isArray(employes.data)) {
                        employes = employes.data;
                    } else if (employes.employees && Array.isArray(employes.employees)) {
                        employes = employes.employees;
                    } else {
                        throw new Error('Format de données invalide');
                    }
                }

                if (employes.length > 0) {
                    localStorage.setItem('employes_annuaire', JSON.stringify(employes));
                    localStorage.setItem('employes_cache_timestamp', Date.now().toString());
                    console.log(`✅ ${employes.length} employés chargés et sauvegardés`);
                }

            } catch (parseError) {
                console.error('❌ Erreur parsing JSON:', parseError);
                throw new Error('Réponse API invalide');
            }

        } catch (error) {
            console.error('❌ Erreur chargement API:', error);

            if (error.message.includes('doo doug mbeuleee')) {
                alert('Accès temporairement bloqué. Veuillez patienter quelques minutes.');
            } else {
                console.log('📦 Utilisation des données en cache si disponibles');
            }
        } finally {
            loadingSpinner.style.display = 'none';
        }
    } else {
        console.log(`📦 Utilisation du cache (${employes.length} employés)`);
    }

    // ✅ PEUPLEMENT DU SELECT
    console.log('🔧 Peuplement du select...');
    select.innerHTML = '<option value="">-- Sélectionner un employé --</option>';

    if (employes && employes.length > 0) {
        let validEmployees = 0;

        employes.forEach((emp, index) => {
            // Vérifier si l'employé contient le message d'erreur
            if (typeof emp === 'string' && emp.includes('doo doug mbeuleee')) {
                console.warn(`⚠️ Employé ${index + 1} contient un message d'erreur`);
                return;
            }

            // Vérifier la structure attendue
            if (emp && typeof emp === 'object' && emp.matricule) {
                const option = document.createElement('option');
                option.value = emp.matricule;
                option.textContent = `${emp.nom || 'N/A'} ${emp.prenom || 'N/A'} (${emp.matricule})`;
                select.appendChild(option);
                validEmployees++;
            } else {
                console.warn(`⚠️ Employé ${index + 1} avec données incomplètes:`, emp);
            }
        });

        console.log(`✅ ${validEmployees} employés valides ajoutés au select`);
        updateEmployeCount(validEmployees);

    } else {
        console.log('❌ Aucun employé disponible');
        select.innerHTML = '<option value="">Aucun employé disponible</option>';
        updateEmployeCount(0);
    }

    // ✅ INITIALISATION FINALE DE SELECT2
    console.log('🎯 Initialisation de Select2...');
    initializeSelect2();

    // ✅ GESTIONNAIRE D'ÉVÉNEMENT DE FALLBACK
    select.addEventListener('change', function() {
        if (!$(this).hasClass('select2-hidden-accessible')) {
            // Fallback si Select2 n'est pas initialisé
            console.log('🔄 Fallback: utilisation de l\'événement change natif');
            const matricule = this.value;
            handleEmployeSelection(matricule);
        }
    });

    console.log('🏁 Initialisation terminée');
});

// ✅ FONCTIONS GLOBALES POUR LE DEBUG
function testSearch() {
    const select = $('#employe-select');
    console.log('🔍 État actuel de Select2:', {
        isInitialized: select.hasClass('select2-hidden-accessible'),
        optionsCount: select.find('option').length,
        selectedValue: select.val(),
        selectedText: select.find('option:selected').text()
    });
}

function forceReload() {
    localStorage.removeItem('employes_annuaire');
    localStorage.removeItem('employes_cache_timestamp');
    location.reload();
}
</script>


</x-layout.default>
