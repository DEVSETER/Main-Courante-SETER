{{--

                            <form action="{{ route('users.update', $user->id) }}" method="POST" class="space-y-6">
                                @csrf
                                @method('PUT') --}}
<x-layout.default>

     {{-- <style>
        .form-input {
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
        }
        .form-input:focus {
            outline: none;
            border-color: #3b82f6;
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
            border-top: 2px solid #3b82f6;
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
    </style> --}}
    <div>
        <div class="absolute inset-0">
            <img src="/assets/images/auth/bg-gradient.png" alt="image" class="h-full w-full object-cover" />
        </div>
        <div class="relative flex min-h-screen items-center justify-center bg-[url(/assets/images/auth/map.png)] bg-cover bg-center bg-no-repeat px-6 py-10 dark:bg-[#060818] sm:px-16">
            <img src="/assets/images/auth/coming-soon-object1.png" alt="image" class="absolute left-0 top-1/2 h-full max-h-[893px] -translate-y-1/2" />
            <img src="/assets/images/auth/coming-soon-object2.png" alt="image" class="absolute left-24 top-0 h-40 md:left-[30%]" />
            <img src="/assets/images/auth/coming-soon-object3.png" alt="image" class="absolute right-0 top-0 h-[300px]" />
            <img src="/assets/images/auth/polygon-object.svg" alt="image" class="absolute bottom-0 end-[28%]" />            <div
                class="relative w-full max-w-[870px] rounded-md bg-[linear-gradient(45deg,#fff9f9_0%,rgba(255,255,255,0)_25%,rgba(255,255,255,0)_75%,_#fff9f9_100%)] p-2 dark:bg-[linear-gradient(52.22deg,#0E1726_0%,rgba(14,23,38,0)_18.66%,rgba(14,23,38,0)_51.04%,rgba(14,23,38,0)_80.07%,#0E1726_100%)]">
                <div class="relative flex flex-col justify-center rounded-md bg-white/60 backdrop-blur-lg dark:bg-black/50 px-6 lg:min-h-[758px] py-20">
                    <div class="absolute top-6 end-6">
                        <div class="dropdown" x-data="dropdown" @click.outside="open = false">

                        </div>
                    </div>
                    <div x-ref="form" class="mx-auto w-full max-w-[440px]">
                        <div class="mb-10">
                            <h1 class="text-2xl font-extrabold uppercase !leading-snug text-primary md:text-4xl" class="btn" style="color: #67152e; border-color: #ebba7d;"> Mettre à jour </h1>
                            <p class="text-base font-bold leading-normal text-white-dark">mise à jour les Informations de l'utilisateur</p>
                        </div>



        <div class="container mx-auto px-4">
        {{-- <h1 class="text-2xl font-bold mb-6">Créer un utilisateur</h1> --}}

        <form action="{{ route('users.update', $user->id) }}" method="POST" class="space-y-6">
                       @csrf
                                @method('PUT')
 <div class="form-group" id="userForm">
                <label for="matricule" class="block text-sm font-medium">Matricule</label>
<input type="number" id="matricule" name="matricule" value="{{ $user->matricule }}" class="form-input mt-1 block w-full readonly-field" readonly>
  {{-- <span id="loading" class="loading"></span> --}}
                <p id="matricule-error" class="text-red-500 text-sm mt-1" style="display: none;"></p>
            </div>

            <!-- Champs qui apparaissent après validation du matricule -->
            <div id="userFields" >
                <!-- Nom (automatiquement rempli) -->
                <div class="form-group">
                    <label for="nom" class="block text-sm font-medium">Nom</label>
                    <input type="text" id="nom" name="nom" value="{{ old('nom', $user->nom) }}" class="form-input mt-1 block w-full readonly-field" readonly>
                </div>

                <!-- Prénom (automatiquement rempli) -->
                <div class="form-group">
                    <label for="prenom" class="block text-sm font-medium">Prénom</label>
                    <input type="text" id="prenom" name="prenom" value="{{ old('prenom', $user->prenom) }}" class="form-input mt-1 block w-full readonly-field" readonly>
                </div>

                <!-- Email (automatiquement rempli) -->
                <div class="form-group">
                    <label for="email" class="block text-sm font-medium">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" class="form-input mt-1 block w-full readonly-field" readonly>
                </div>



                <!-- Téléphone (automatiquement rempli) -->
                <div class="form-group">
                    <label for="telephone" class="block text-sm font-medium">Téléphone</label>
                    <input type="text" id="telephone" name="telephone" value="{{ old('telephone', $user->telephone) }}" class="form-input mt-1 block w-full readonly-field" readonly>
                </div>
                <!-- Direction (automatiquement rempli) -->

                   <div class="form-group">
                    <label for="direction" class="block text-sm font-medium">Direction</label>
                    <input type="text" id="direction" name="direction" value="{{ old('direction', $user->direction) }}" class="form-input mt-1 block w-full readonly-field" readonly>
                </div>
                <!-- Fonction (automatiquement rempli) -->

                   <div class="form-group">
                    <label for="fonction" class="block text-sm font-medium">Fonction</label>
                    <input type="text" id="fonction" name="fonction" value="{{ old('fonction', $user->fonction) }}" class="form-input mt-1 block w-full readonly-field" readonly>
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
                        <option value="{{ $item->id }}"
                    {{ old('entite_id', $user->entite_id) == $item->id ? 'selected' : '' }}>
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
                           <option value="{{ $role->id }}"
                            {{ old('role_id', $user->roles->first()->id ?? '') == $role->id ? 'selected' : '' }}>
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
            <button type="submit" class="btn btn-primary w-full" style="background-color: #67152e; border-color: #67152e; color: #fff;">
                Mettre à jour
            </button>
             </div>
        </form>
        @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
    </div>
            </div>
                </div>
            </div>
        </div>
    </div>
<script>
    // Remplace les données simulées par un appel AJAX à ton backend
    const matriculeInput = document.getElementById('matricule');
    const userFields = document.getElementById('userFields');
    const nomInput = document.getElementById('nom');
    const prenomInput = document.getElementById('prenom');
    const emailInput = document.getElementById('email');
    const telephoneInput = document.getElementById('telephone');
    const directionInput = document.getElementById('direction');
    const fonctionInput = document.getElementById('fonction');
    // const loadingSpinner = document.getElementById('loading');
    const errorMessage = document.getElementById('matricule-error');

    // Fonction pour cacher les champs et vider leurs valeurs
 function hideAndClearFields() {
    nomInput.value = '';
    prenomInput.value = '';
    emailInput.value = '';
    telephoneInput.value = '';
    directionInput.value = '';
    fonctionInput.value = '';
    userFields.style.display = 'block';
    userFields.style.opacity = '1';
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

    // Fonction pour récupérer les données depuis l'API Laravel
    async function fetchUserData(matricule) {
        try {
            const response = await fetch(`/api/employe?matricule=${encodeURIComponent(matricule)}`);
            console.log(response);

            if (!response.ok) return null;
            return await response.json();
        } catch (error) {
            return null;
        }
    }

    // Gestionnaire d'événement pour le champ matricule
    matriculeInput.addEventListener('input', async function() {
        const matricule = this.value.trim();

        hideAndClearFields();
        errorMessage.style.display = 'none';

        if (matricule === '') {
            return;
        }

        try {
            // loadingSpinner.style.display = 'inline-block';

        const user = await fetchUserData(matricule);
            console.log('Données employé:', user); // Ajoute ce log pour voir le contenu
            if (user && !user.error) {
                showAndFillFields(user);
            }else {
                errorMessage.textContent = 'Aucun utilisateur trouvé avec ce matricule';
                errorMessage.style.display = 'block';
            }
        } catch (error) {
            errorMessage.textContent = 'Erreur lors de la recherche des données';
            errorMessage.style.display = 'block';
            console.error('Erreur:', error);
        } finally {
            // loadingSpinner.style.display = 'none';
        }
    });



</script>


</x-layout.default>
