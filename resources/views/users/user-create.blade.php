<x-layout.default>



    <div x-data="auth">
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
                    <div x-data="form" class="mx-auto w-full max-w-[440px]">
                        <div class="mb-10">
                            <h1 class="text-3xl font-extrabold uppercase !leading-snug text-primary md:text-4xl" class="btn" style="color: #67152e; border-color: #ebba7d;"> nouvel utilisateur</h1>
                            <p class="text-base font-bold leading-normal text-white-dark">Enter your email and password to register</p>
                        </div>



        <div class="container mx-auto px-4">
        <h1 class="text-2xl font-bold mb-6">Créer un utilisateur</h1>

        <form action="{{ route('users.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Nom -->
            <div>
                <label for="nom" class="block text-sm font-medium">Nom</label>
                <input type="text" id="nom" name="nom" value="{{ old('nom') }}" class="form-input mt-1 block w-full" required>
                @error('nom')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Prénom -->
            <div>
                <label for="prenom" class="block text-sm font-medium">Prénom</label>
                <input type="text" id="prenom" name="prenom" value="{{ old('prenom') }}" class="form-input mt-1 block w-full" required>
                @error('prenom')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-input mt-1 block w-full" required>
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Mot de passe -->
            <div>
                <label for="password" class="block text-sm font-medium">Mot de passe</label>
                <input type="password" id="password" name="password" class="form-input mt-1 block w-full" required>
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Téléphone -->
            <div>
                <label for="telephone" class="block text-sm font-medium">Téléphone</label>
                <input type="text" id="telephone" name="telephone" value="{{ old('telephone') }}" class="form-input mt-1 block w-full">
                @error('telephone')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Fonction -->
            <div>
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
            </div>

            <!-- Direction -->
            <div>
                <label for="direction" class="block text-sm font-medium">Direction</label>
                <select id="direction" name="direction" class="form-select mt-1 block w-full">
                    <option value="">Sélectionner une direction</option>
                    <option value="DSI" {{ old('direction') == 'DSI' ? 'selected' : '' }}>DSI</option>
                    <option value="DEX" {{ old('direction') == 'DEX' ? 'selected' : '' }}>DEX</option>
                    <option value="DMSV" {{ old('direction') == 'DMSV' ? 'selected' : '' }}>DMSV</option>
                    <option value="DMI" {{ old('direction') == 'DMI' ? 'selected' : '' }}>DMI</option>
                    <option value="DSUR" {{ old('direction') == 'DSUR' ? 'selected' : '' }}>DSUR</option>
                </select>
                @error('direction')
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
                <button type="submit" class="btn btn-primary w-full" class="btn" style="background-color: #67152e; border-color: #67152e; color: #fff;">Créer</button>
            </div>
        </form>
    </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("alpine:init", () => {
            Alpine.data("form", () => ({
                form3: {
                    fonction: '',
                    direction: '',
                    role_id: '',
                    nom: '',
                    prenom: '',
                    email: '',
                    telephone: '',
                    password:''
                },
                isSubmitForm3: false,
                submitForm3() {
                    this.isSubmitForm3 = true;

                    const { fonction, direction, telephone, role_id, nom,email, prenom ,password} = this.form3;

                    if (fonction && direction && telephone && role_id && nom && email && prenom&& password) {
                        this.showMessage('L utilisateur a été créé avec succès..');

                        // Soumission vers Laravel
                        document.getElementById('formUtilisateur').submit();
                    } else {
                        this.showMessage('Merci de remplir tous les champs obligatoires.', 'error');
                    }
                },
                showMessage(msg = '', type = 'success') {
                    const toast = window.Swal.mixin({
                        toast: true,
                        position: 'top',
                        showConfirmButton: false,
                        timer: 3000
                    });
                    toast.fire({
                        icon: type,
                        title: msg,
                        padding: '10px 20px'
                    });
                },
            }));
        });
    </script>


</x-layout.default>
