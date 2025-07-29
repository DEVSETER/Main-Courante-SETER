<x-layout.default>
    {{-- @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif --}}
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
        <h1 class="text-3xl font-extrabold uppercase !leading-snug text-primary md:text-4xl" style="color: #67152e; border-color: #ebba7d;">Modifier le Rôle</h1>
        <p class="text-base font-bold leading-normal text-white-dark">Renseignez les informations du rôle et ses permissions, puis enregistrez.</p>
    </div>

   <form action="{{ route('roles.update', $role->id) }}" method="POST" class="space-y-5 dark:text-white">
    @csrf
    @method('PUT')

    <!-- Champ Nom -->
    <div>
        <label for="name">Nom</label>
        <input id="name" name="name" type="text" value="{{ old('name', $role->name) }}" class="form-input ps-10 placeholder:text-white-dark" />
        @error('name')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <!-- Permissions groupées par type avec dropdown -->
                    <div>
                        <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-4">Permissions</h2>
                        @if($permissions->isNotEmpty())
                            <div class="space-y-3">
                                @foreach ($permissions as $type => $perms)
                                    <details class="border rounded bg-white/80 dark:bg-black/30">
                                        <summary class="cursor-pointer px-4 py-2 font-bold text-[#67152e] uppercase select-none">
                                            {{ ucfirst($type) }}
                                        </summary>
                                        <div class="p-4">
                                            @foreach ($perms as $permission)
                                                <div class="flex items-center space-x-4 mb-2">
                                                    <label class="inline-flex items-center space-x-2">
                                                        <input type="checkbox"
                                                            id="permission-{{ $permission->id }}"
                                                            class="form-checkbox"
                                                            name="permissions[]"
                                                            value="{{ $permission->id }}"
                                                            {{ in_array($permission->id, $role->permissions->pluck('id')->toArray()) ? 'checked' : '' }} />
                                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $permission->name }}</span>
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </details>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500">Aucune permission disponible.</p>
                        @endif
                    </div>

                    <div class="mt-10">
                        <button type="submit" class="btn btn-primary flex items-center w-full" style="background-color: #67152e; border-color: #67152e; color: #fff;">
                            Mettre à jour
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
            Alpine.data("form", () => ({
                form3: {
                    name: "{{ $role->name }}"

                },
                isSubmitForm3: false,
                submitForm3() {
                    this.isSubmitForm3 = true;

                    const { name} = this.form3;

                    if (name) {
                        this.showMessage('Le rôle a été modifié avec succès..');

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
