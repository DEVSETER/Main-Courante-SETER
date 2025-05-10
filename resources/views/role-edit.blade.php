<x-layout.default>
    <div class="container mx-auto px-4">
        <h1 class="text-2xl font-bold mb-6">Éditer le rôle</h1>

        <form action="{{ route('roles.update', $role->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Libellé -->
            <div>
                <label for="libelle" class="block text-sm font-medium">Libellé</label>
                <input
                    type="text"
                    id="libelle"
                    name="libelle"
                    value="{{ old('libelle', $role->libelle) }}"
                    class="form-input mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary"
                    required>
                @error('libelle')
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
