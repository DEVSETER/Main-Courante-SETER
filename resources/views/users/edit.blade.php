<x-layout.default>
    <div class="container mx-auto px-4">
        <h1 class="text-2xl font-bold mb-6">Éditer l'utilisateur</h1>

        <form action="{{ route('users.update', $user->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Nom -->
            <div>
                <label for="nom" class="block text-sm font-medium">Nom</label>
                <input type="text" id="nom" name="nom" value="{{ old('nom', $user->nom) }}" class="form-input mt-1 block w-full" required>
                @error('nom')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Prénom -->
            <div>
                <label for="prenom" class="block text-sm font-medium">Prénom</label>
                <input type="text" id="prenom" name="prenom" value="{{ old('prenom', $user->prenom) }}" class="form-input mt-1 block w-full" required>
                @error('prenom')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" class="form-input mt-1 block w-full" required>
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Mot de passe -->
            <div>
                <label for="password" class="block text-sm font-medium">Mot de passe</label>
                <input type="password" id="password" name="password" class="form-input mt-1 block w-full">
                <p class="text-sm text-gray-500 mt-1">Laissez vide si vous ne souhaitez pas changer le mot de passe.</p>
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Téléphone -->
            <div>
                <label for="telephone" class="block text-sm font-medium">Téléphone</label>
                <input type="text" id="telephone" name="telephone" value="{{ old('telephone', $user->telephone) }}" class="form-input mt-1 block w-full">
                @error('telephone')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Fonction -->
            <div>
                <label for="fonction" class="block text-sm font-medium">Fonction</label>
                <select id="fonction" name="fonction" class="form-select mt-1 block w-full">
                    <option value="">Sélectionner une fonction</option>
                    <option value="CIV" {{ old('fonction', $user->fonction) == 'CIV' ? 'selected' : '' }}>CIV</option>
                    <option value="HOTLINE" {{ old('fonction', $user->fonction) == 'HOTLINE' ? 'selected' : '' }}>HOTLINE</option>
                    <option value="SUPERVISEUR COF" {{ old('fonction', $user->fonction) == 'SUPERVISEUR COF' ? 'selected' : '' }}>SUPERVISEUR COF</option>
                    <option value="CM" {{ old('fonction', $user->fonction) == 'CM' ? 'selected' : '' }}>CM</option>
                    <option value="PLANIFICATEUR" {{ old('fonction', $user->fonction) == 'PLANIFICATEUR' ? 'selected' : '' }}>PLANIFICATEUR</option>
                    <option value="CHEF CIRCULATION" {{ old('fonction', $user->fonction) == 'CHEF CIRCULATION' ? 'selected' : '' }}>CHEF CIRCULATION</option>
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
                    <option value="DSI" {{ old('direction', $user->direction) == 'DSI' ? 'selected' : '' }}>DSI</option>
                    <option value="DEX" {{ old('direction', $user->direction) == 'DEX' ? 'selected' : '' }}>DEX</option>
                    <option value="DMSV" {{ old('direction', $user->direction) == 'DMSV' ? 'selected' : '' }}>DMSV</option>
                    <option value="DMI" {{ old('direction', $user->direction) == 'DMI' ? 'selected' : '' }}>DMI</option>
                    <option value="DSUR" {{ old('direction', $user->direction) == 'DSUR' ? 'selected' : '' }}>DSUR</option>
                </select>
                @error('direction')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Rôle -->
            <div>
                <label for="role_id" class="block text-sm font-medium">Rôle</label>
                <select id="role_id" name="role_id" class="form-select mt-1 block w-full">
                    <option value="">Sélectionner un rôle</option>
                    <option value="1" {{ old('role_id', $user->role_id) == 1 ? 'selected' : '' }}>Utilisateur simple</option>
                    <option value="2" {{ old('role_id', $user->role_id) == 2 ? 'selected' : '' }}>Admin</option>
                    <option value="3" {{ old('role_id', $user->role_id) == 3 ? 'selected' : '' }}>Super Admin</option>
                </select>
                @error('role_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Bouton de soumission -->
            <div>
                <button type="submit" class="btn btn-primary w-full">Mettre à jour</button>
            </div>
        </form>
    </div>
</x-layout.default>
