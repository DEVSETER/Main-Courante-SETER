<!-- filepath: resources/views/liste_diffusion/index.blade.php -->
{{-- <x-layout.default>
    <h1>Listes de Diffusion</h1>
    <a href="{{ route('liste_diffusions.create') }}" class="btn btn-primary">Créer une nouvelle liste</a>

    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Utilisateurs</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($listes as $liste)
                <tr>
                    <td>{{ $liste->nom }}</td>
                    <td>{{ $liste->users->pluck('name')->join(', ') }}</td>
                    <td>
                        <a href="{{ route('liste_diffusion.edit', $liste->id) }}">Modifier</a>
                        <form action="{{ route('liste_diffusion.destroy', $liste->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</x-layout.default> --}}
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
    <div class="rounded-full p-1.5 text-white  ltr:mr-3 rtl:ml-3" style="background-color: #67152e;">
        <!-- Icône représentant une liste de diffusion -->
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M4 6H20M4 12H20M4 18H20" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            <circle cx="4" cy="6" r="1.5" fill="currentColor" />
            <circle cx="4" cy="12" r="1.5" fill="currentColor" />
            <circle cx="4" cy="18" r="1.5" fill="currentColor" />
        </svg>
    </div>
    <!-- Texte -->
    <span class="font-semibold text-lg" style="color: #67152e;">LISTE DES DIFFUSIONS</span>
</div>

                <!-- Bouton aligné à droite -->
                <button class="btn btn-primary flex items-center" style="background-color: #67152e; border-color: #67152e; color: #fff;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ltr:mr-2 rtl:ml-2">
                        <circle cx="10" cy="6" r="4" stroke="currentColor" stroke-width="1.5" />
                        <path opacity="0.5"
                            d="M18 17.5C18 19.9853 18 22 10 22C2 22 2 19.9853 2 17.5C2 15.0147 5.58172 13 10 13C14.4183 13 18 15.0147 18 17.5Z"
                            stroke="currentColor" stroke-width="1.5" />
                        <path d="M21 10H19M19 10H17M19 10L19 8M19 10L19 12" stroke="currentColor"
                            stroke-width="1.5" stroke-linecap="round" />
                    </svg>
                    <a href="{{ route('liste_diffusions.create') }}" tasrget="_blank">Ajouter une liste</a>
                </button>
            </div>
            <div class="panel">
                <h5 class="md:absolute md:top-[25px] md:mb-0 mb-5 font-semibold text-lg dark:text-white-light">Listes de Diffusion</h5>
                <div class="relative">
                    <table id="myTable1" class="table-auto w-full border-collapse border border-gray-300"></table>
                </div>
            </div>
        </div>
    </div>
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
                listes: @json($listes), // Injecter les listes depuis Laravel

                init() {
                    // Vérifiez que les listes sont bien injectées
                    console.log(this.listes);

                    // Fonction pour formater la date
                    const formatDate = (dateString) => {
                        const options = { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' };
                        return new Date(dateString).toLocaleString('fr-FR', options);
                    };

                    // Générer les en-têtes de colonnes
                    let headers = this.columns.map((col) => col.name);

                    // Générer les données pour chaque liste de diffusion
                    let data = this.listes.map((liste) => [
                        liste.id,
                        liste.nom,
                        liste.users.map(user => user.nom).join(', '), // Afficher les noms des utilisateurs
                        formatDate(liste.created_at), // Formater la date
                        `
                            <ul class="flex items-center justify-center gap-2">
                                <!-- Icône pour éditer -->
                                <li>
                                    <a href="/liste_diffusions/${liste.id}/edit" x-tooltip="Edit">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 ltr:mr-2 rtl:ml-2">
                                            <path d="M15.2869 3.15178L14.3601 4.07866L5.83882 12.5999C5.26166 13.1771 4.97308 13.4656 4.7249 13.7838C4.43213 14.1592 4.18114 14.5653 3.97634 14.995C3.80273 15.3593 3.67368 15.7465 3.41556 16.5208L2.32181 19.8021L2.05445 20.6042C1.92743 20.9852 2.0266 21.4053 2.31063 21.6894C2.59466 21.9734 3.01478 22.0726 3.39584 21.9456L4.19792 21.6782L7.47918 20.5844C8.25353 20.3263 8.6407 20.1973 9.00498 20.0237C9.43469 19.8189 9.84082 19.5679 10.2162 19.2751C10.5344 19.0269 10.8229 18.7383 11.4001 18.1612L19.9213 9.63993L20.8482 8.71306C22.3839 7.17735 22.3839 4.68748 20.8482 3.15178C19.3125 1.61607 16.8226 1.61607 15.2869 3.15178Z" stroke="currentColor" stroke-width="1.5" />
                                            <path opacity="0.5" d="M14.36 4.07812C14.36 4.07812 14.4759 6.04774 16.2138 7.78564C17.9517 9.52354 19.9213 9.6394 19.9213 9.6394M4.19789 21.6777L2.32178 19.8015" stroke="currentColor" stroke-width="1.5" />
                                        </svg>
                                    </a>
                                </li>
                                <!-- Icône pour supprimer -->
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
                },

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
