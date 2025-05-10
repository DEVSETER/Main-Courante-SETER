<form action="{{ route('roles.permissions.update', $role->id) }}" method="POST">
    @csrf
    @method('PUT')

    <h3 class="text-lg font-bold mb-4">Permissions pour le rôle : {{ $role->libelle }}</h3>

    @if($permissions->isEmpty())
        <p class="text-gray-500">Aucune permission disponible.</p>
    @else
        @foreach($permissions as $permission)
            <div class="mb-2">
                <label class="inline-flex items-center">
                    <input
                        type="checkbox"
                        name="permissions[]"
                        value="{{ $permission->id }}"
                        {{ $role->permissions->contains($permission) ? 'checked' : '' }}
                        class="form-checkbox text-primary focus:ring-primary">
                    <span class="ml-2">{{ $permission->name }}</span>
                </label>
            </div>
        @endforeach
    @endif

    <button type="submit" class="btn btn-primary mt-4">Mettre à jour</button>
</form>
