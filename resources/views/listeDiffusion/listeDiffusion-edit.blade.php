<x-layout.default>
    <div x-data="userSelection">
        <div class="absolute inset-0">
            <img src="/assets/images/auth/bg-gradient.png" alt="image" class="h-full w-full object-cover" />
        </div>
        <div class="relative flex min-h-screen items-center justify-center bg-[url(/assets/images/auth/map.png)] bg-cover bg-center bg-no-repeat px-6 py-10 dark:bg-[#060818] sm:px-16">
            <div
                class="relative w-full max-w-[870px] rounded-md bg-[linear-gradient(45deg,#fff9f9_0%,rgba(255,255,255,0)_25%,rgba(255,255,255,0)_75%,_#fff9f9_100%)] p-2 dark:bg-[linear-gradient(52.22deg,#0E1726_0%,rgba(14,23,38,0)_18.66%,rgba(14,23,38,0)_51.04%,rgba(14,23,38,0)_80.07%,#0E1726_100%)]">
                <div class="relative flex flex-col justify-center rounded-md bg-white/60 backdrop-blur-lg dark:bg-black/50 px-6 lg:min-h-[758px] py-20">
                    <div class="absolute top-6 end-6">
                        <div class="dropdown" x-data="dropdown" @click.outside="open = false"></div>
                    </div>
                    <div class="mx-auto w-full max-w-[440px]">
                        <div class="mb-10">
                            <h1 class="text-3xl font-extrabold uppercase !leading-snug text-primary md:text-4xl" style="color: #67152e; border-color: #ebba7d;">Modifier la Liste de Diffusion</h1>
                            <p class="text-base font-bold leading-normal text-white-dark">Modifiez le nom de la liste de diffusion et sélectionnez les utilisateurs, puis enregistrez.</p>
                        </div>
                        <form class="space-y-5 dark:text-white" id="formUtilisateur" action="{{ route('liste_diffusions.update', $liste->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <!-- Champ Nom -->
                            <div>
                                <label for="nom">Nom</label>
                                <div class="relative text-white-dark">
                                    <input id="nom" name="nom" type="text" placeholder="Entrez le nom de la liste" value="{{ old('nom', $liste->nom) }}" class="form-input ps-10 placeholder:text-white-dark" />
                                    @error('nom')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Recherche d'utilisateurs -->
                            <div>
                                <label for="search">Rechercher des utilisateurs</label>
                                <input type="text" id="search" x-model="search" placeholder="Rechercher par email ou nom" class="form-input placeholder:text-white-dark" />
                            </div>

                            <!-- Liste des utilisateurs filtrés -->
                            <div>
                                <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-4">Utilisateurs disponibles</h2>
                                <div class="max-h-40 overflow-y-auto">
                                    <template x-for="user in filteredUsers" :key="user.id">
                                        <div class="flex items-center space-x-4 mb-4">
                                            <label class="inline-flex items-start space-x-2">
                                                <input type="checkbox" :id="'user-' + user.id" class="form-checkbox text-success" name="users[]" :value="user.id" @change="toggleUser(user)" :checked="selectedUsers.some(selected => selected.id === user.id)" />
                                                <div>
                                                    <!-- Nom de l'utilisateur -->
                                                    <span x-text="user.nom" class="text-sm font-medium text-gray-700 dark:text-gray-300"></span> <span x-text="user.prenom" class="text-sm font-medium text-gray-700 dark:text-gray-300"></span>
                                                    <br>
                                                    <!-- Email de l'utilisateur -->
                                                    <span x-text="user.email" class="text-xs font-light text-gray-500 dark:text-gray-400"></span>
                                                </div>
                                            </label>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- Utilisateurs sélectionnés -->
                            <div>
                                <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-4">Utilisateurs de la liste</h2>
                                <div class="p-4 bg-gray-100 dark:bg-gray-800 rounded">
                                    <template x-for="user in selectedUsers" :key="user.id">
                                        <div class="flex items-center justify-between mb-2">
                                            <span x-text="user.email" class="text-sm text-gray-700 dark:text-gray-300"></span>
                                            <button type="button" @click="removeUser(user)" class="text-red-500 text-sm">Retirer</button>
                                        </div>
                                    </template>
                                    <p x-show="selectedUsers.length === 0" class="text-gray-500">Aucun utilisateur sélectionné.</p>
                                </div>
                            </div>

                            <!-- Bouton de soumission -->
                            <div class="mt-10">
                                <button type="submit" class="btn btn-primary flex items-center w-full">
                                    Enregistrer
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("alpine:init", () => {
            Alpine.data("userSelection", () => ({
                users: @json($users), // Injecter les utilisateurs depuis Laravel
                selectedUsers: @json($liste->users), // Injecter les utilisateurs déjà associés à la liste
                search: '',

                get filteredUsers() {
                    if (!this.search) {
                        return this.users.filter(user => !this.selectedUsers.some(selected => selected.id === user.id));
                    }
                    return this.users.filter(user =>
                        (user.email.toLowerCase().includes(this.search.toLowerCase()) ||
                        user.nom.toLowerCase().includes(this.search.toLowerCase())) &&
                        !this.selectedUsers.some(selected => selected.id === user.id)
                    );
                },

                toggleUser(user) {
                    if (this.selectedUsers.some(selected => selected.id === user.id)) {
                        this.removeUser(user);
                    } else {
                        this.selectedUsers.push(user);
                    }
                },

                removeUser(user) {
                    this.selectedUsers = this.selectedUsers.filter(selected => selected.id !== user.id);
                }
            }));
        });
    </script>
</x-layout.default>
