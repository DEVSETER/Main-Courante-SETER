
<x-layout.default>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Vérifier si on doit highlight un événement
        const urlParams = new URLSearchParams(window.location.search);
        const highlightId = urlParams.get('highlight');

        if (highlightId) {
            // Scroll vers l'événement et le mettre en évidence
            const eventRow = document.querySelector(`[data-event-id="${highlightId}"]`);
            if (eventRow) {
                eventRow.scrollIntoView({ behavior: 'smooth' });
                eventRow.style.backgroundColor = '#fff3cd';
                eventRow.style.border = '2px solid #ffc107';

                // Enlever le highlight après 3 secondes
                setTimeout(() => {
                    eventRow.style.backgroundColor = '';
                    eventRow.style.border = '';
                }, 3000);
            }
        }
    });
    </script>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="/assets/js/simple-datatables.js"></script>
    @section('head')
    @push('styles')
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @endpush
    <!-- NOUVEAU : Scripts pour SimpleDatatables -->
    <script src="/assets/js/simple-datatables.js"></script>
    <head>
    <link rel="stylesheet" href="{{ asset('css/maincourante.css') }}">
</head>
<body>
    <div class="container" x-data="mainCouranteApp()">
        <div class="header">
            <h1>📋 Main Courante</h1>
            <p>Système de gestion des événements</p>
        </div>

        <!-- Navigation des types de main courante -->
        <div class="nav-buttons">
    @if(auth()->user()->entite->code === 'PTP' || auth()->user()->entite->code === 'SR COF'  || auth()->user()->entite->code === 'DSI')
        <button class="nav-btn"
                :class="{ 'active': activeTab === 'SRCOF' }"
                @click="switchTab('SRCOF')">
            MAIN COURANTE SRCOF
        </button>


    @endif

    @if(auth()->user()->entite->code === 'CIV' || auth()->user()->entite->code === 'SR COF' || auth()->user()->entite->code === 'DSI')
        <button class="nav-btn"
                :class="{ 'active': activeTab === 'CIV' }"
                @click="switchTab('CIV')">
            MAIN COURANTE CIV
        </button>
    @endif

    @if(auth()->user()->entite->code === 'HC' || auth()->user()->entite->code === 'SR COF' || auth()->user()->entite->code === 'DSI')
        <button class="nav-btn"
                :class="{ 'active': activeTab === 'HOTLINE' }"
                @click="switchTab('HOTLINE')">
            MAIN COURANTE HOTLINE
        </button>
    @endif

    @if(auth()->user()->entite->code === 'CM' || auth()->user()->entite->code === 'SR COF' || auth()->user()->entite->code === 'DSI')
        <button class="nav-btn"
                :class="{ 'active': activeTab === 'CM' }"
                @click="switchTab('CM')">
            MAIN COURANTE CM
        </button>
    @endif

    @if(auth()->user()->entite->code === 'PTP' || auth()->user()->entite->code === 'SR COF' || auth()->user()->entite->code === 'DSI')
        <button class="nav-btn"
                :class="{ 'active': activeTab === 'PTP' }"
                @click="switchTab('PTP')">
            MAIN COURANTE PTP
        </button>
    @endif
</div>
        <!-- Contrôles -->
        <div class="controls">

            <div style="display: flex;flex-direction: column; justify-content: space-between; align-items: center;border: 1px solid #ccc; padding: 3px; border-radius: 5px;">
                @can('Créer événement')

                <button class="btn btn-primary" @click="openCreateModal()" >
                    ➕ Créer un événement
                    @endcan
                </button>
                @can('Modifier événement')

                <button class="bg-yellow-100 text-yellow-800 hover:bg-yellow-200 hover:text-yellow-900 px-3 py-1 rounded-full text-sm soft-yellow-btn" @click="toggleEditMode()"
                :class="{ 'btn-danger': editMode }" style="margin-top: 10px">
                <span x-text="editMode ? '❌ Désactiver édition' : '✏️ Activer mode édition'"></span>
            </button>
            @endcan
            </div>
            <div>
                <span x-text="`${getCurrentData().length} événements`" class="text-muted"></span>
            </div>
        </div>
        <!-- NOUVEAU : Barre de recherche et contrôles -->
<div class="search-and-controls" style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; gap: 15px; flex-wrap: wrap;">

    <!-- Barre de recherche -->
    <div class="search-container" style="flex: 1; min-width: 300px;">
        <div style="position: relative;">
            <input type="text"
                   x-model="searchQuery"
                   @input="onSearchInput()"
                   placeholder="🔍 Rechercher dans les événements..."
                   class="form-control"
                   style="padding-left: 40px; padding-right: 100px;">

            <!-- Bouton effacer recherche -->
            <button x-show="searchQuery !== ''"
                    @click="clearSearch()"
                    style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #999; cursor: pointer;"
                    title="Effacer la recherche">
                ✕
            </button>
        </div>

        <!-- Indicateur de résultats -->
        <div x-show="searchQuery !== ''" class="text-xs text-gray-600" style="margin-top: 5px;">
            <span x-text="totalItems"></span> résultat(s) trouvé(s) sur <span x-text="paginationInfo.totalOriginal"></span> événements
        </div>
    </div>

    <!-- Sélecteur nombre d'éléments par page -->
    <div class="per-page-selector" style="display: flex; align-items: center; gap: 10px;">
        <label class="text-sm">Afficher :</label>
        <select x-model="pagination.itemsPerPage"
                @change="changeItemsPerPage($event.target.value)"
                class="form-control"
                style="width: auto; min-width: 80px;">
            <template x-for="option in pagination.itemsPerPageOptions" :key="option">
                <option :value="option" x-text="option"></option>
            </template>
        </select>
        <span class="text-sm">par page</span>
    </div>
</div>

        <!-- Tableau dynamique -->
        <div class="table-container">
            <table>
                <thead style="color:#888EA8 !important">
                    <tr>
                        <th x-show="editMode">✓</th>
                        <template x-for="column in getCurrentColumns()">
                            <th x-text="column"></th>
                        </template>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="(item, index) in paginatedData" :key="item.id || index">
                <tr :class="{ 'editable-row': editMode && item.editing }"
                    @click="editMode ? toggleRowEdit(index) : openEditModal(index)">
                    <td x-show="editMode">
                        <input type="checkbox" x-model="item.editing" @click.stop>
                    </td>
                            <template x-for="(column, colIndex) in getCurrentColumns()" :key="colIndex">
                                <td>
                                    <div x-show="!editMode || !item.editing">
                                      <span x-show="column === 'Statut'"
                                        :class="`status-badge status-${item.statut?.toLowerCase().replace(' ', '-').replace('é', 'e')}` "
                                        x-text="item[getColumnKey(column)]"></span>
                                        <span x-show="column === 'POST'"
                                              class="badge-outline post-badge"
                                              x-text="item[getColumnKey(column)]"></span>
                                        <span x-show="column === 'Rédacteur'"
                                              class="badge-outline redacteur-badge"
                                              x-text="item[getColumnKey(column)]"></span>
                                        <span x-show="column === 'Opérations'">
                                            <div class="action-buttons">
                                                <button class="btn btn-small btn-primary" @click.stop="openEditModal(index)">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="font-size: 150%">
                                                <path d="M11.4001 18.1612L11.4001 18.1612L18.796 10.7653C17.7894 10.3464 16.5972 9.6582 15.4697 8.53068C14.342 7.40298 13.6537 6.21058 13.2348 5.2039L5.83882 12.5999L5.83879 12.5999C5.26166 13.1771 4.97307 13.4657 4.7249 13.7838C4.43213 14.1592 4.18114 14.5653 3.97634 14.995C3.80273 15.3593 3.67368 15.7465 3.41556 16.5208L2.05445 20.6042C1.92743 20.9852 2.0266 21.4053 2.31063 21.6894C2.59466 21.9734 3.01478 22.0726 3.39584 21.9456L7.47918 20.5844C8.25351 20.3263 8.6407 20.1973 9.00498 20.0237C9.43469 19.8189 9.84082 19.5679 10.2162 19.2751C10.5343 19.0269 10.823 18.7383 11.4001 18.1612Z" fill="currentColor"></path>
                                                <path d="M20.8482 8.71306C22.3839 7.17735 22.3839 4.68748 20.8482 3.15178C19.3125 1.61607 16.8226 1.61607 15.2869 3.15178L14.3999 4.03882C14.4121 4.0755 14.4246 4.11268 14.4377 4.15035C14.7628 5.0875 15.3763 6.31601 16.5303 7.47002C17.6843 8.62403 18.9128 9.23749 19.85 9.56262C19.8875 9.57563 19.9245 9.58817 19.961 9.60026L20.8482 8.71306Z" fill="currentColor"></path>
                                            </svg>
                                                </button>
                                                <button class="btn btn-small btn-danger" @click.stop="deleteItem(index)"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path opacity="0.5" d="M11.5956 22.0001H12.4044C15.1871 22.0001 16.5785 22.0001 17.4831 21.1142C18.3878 20.2283 18.4803 18.7751 18.6654 15.8686L18.9321 11.6807C19.0326 10.1037 19.0828 9.31524 18.6289 8.81558C18.1751 8.31592 17.4087 8.31592 15.876 8.31592H8.12405C6.59127 8.31592 5.82488 8.31592 5.37105 8.81558C4.91722 9.31524 4.96744 10.1037 5.06788 11.6807L5.33459 15.8686C5.5197 18.7751 5.61225 20.2283 6.51689 21.1142C7.42153 22.0001 8.81289 22.0001 11.5956 22.0001Z" fill="currentColor"></path>
                                                <path d="M3 6.38597C3 5.90152 3.34538 5.50879 3.77143 5.50879L6.43567 5.50832C6.96502 5.49306 7.43202 5.11033 7.61214 4.54412C7.61688 4.52923 7.62232 4.51087 7.64185 4.44424L7.75665 4.05256C7.8269 3.81241 7.8881 3.60318 7.97375 3.41617C8.31209 2.67736 8.93808 2.16432 9.66147 2.03297C9.84457 1.99972 10.0385 1.99986 10.2611 2.00002H13.7391C13.9617 1.99986 14.1556 1.99972 14.3387 2.03297C15.0621 2.16432 15.6881 2.67736 16.0264 3.41617C16.1121 3.60318 16.1733 3.81241 16.2435 4.05256L16.3583 4.44424C16.3778 4.51087 16.3833 4.52923 16.388 4.54412C16.5682 5.11033 17.1278 5.49353 17.6571 5.50879H20.2286C20.6546 5.50879 21 5.90152 21 6.38597C21 6.87043 20.6546 7.26316 20.2286 7.26316H3.77143C3.34538 7.26316 3 6.87043 3 6.38597Z" fill="currentColor"></path>
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M9.42543 11.4815C9.83759 11.4381 10.2051 11.7547 10.2463 12.1885L10.7463 17.4517C10.7875 17.8855 10.4868 18.2724 10.0747 18.3158C9.66253 18.3592 9.29499 18.0426 9.25378 17.6088L8.75378 12.3456C8.71256 11.9118 9.01327 11.5249 9.42543 11.4815Z" fill="currentColor"></path>
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M14.5747 11.4815C14.9868 11.5249 15.2875 11.9118 15.2463 12.3456L14.7463 17.6088C14.7051 18.0426 14.3376 18.3592 13.9254 18.3158C13.5133 18.2724 13.2126 17.8855 13.2538 17.4517L13.7538 12.1885C13.795 11.7547 14.1625 11.4381 14.5747 11.4815Z" fill="currentColor"></path>
                                            </svg></button>
                                       <button class="btn btn-sm btn-outline-secondary rounded-circle px-2" style="font-size: 150%"
                                            @click.stop="openDiffusionModal(index)"
                                            title="Diffuser cet événement">
                                        📤
                                    </button>
                                            </div>
                                        </span>
                                     <span x-show="column !== 'Statut' && column !== 'POST' && column !== 'Rédacteur' && column !== 'Opérations' && column !== 'Commentaire' && column !== 'Action' && column !== 'Visa encadrant'"
                                    :class="column === 'Date et heure' ? 'date-cell' : ''"
                                    x-text="item[getColumnKey(column)]"></span>
                                <span x-show="column === 'Visa encadrant'"
                                    class="visa-encadrant-badge"
                                    x-text="item[getColumnKey(column)] ? item[getColumnKey(column)] : 'Non renseigné'"
                                    :class="item[getColumnKey(column)] ? 'text-green-700 bg-green-100' : 'text-gray-500 bg-gray-100'"
                                    style="padding: 2px 8px; border-radius: 12px; font-size: 11px; font-weight: 600; display: inline-block;">
                                </span>


                            <span x-show="column === 'Commentaire'"
                                class="comment-count"
                                x-text="item[getColumnKey(column)]"></span>
                            <span x-show="column === 'Action'"
                                class="action-count"
                                x-text="item[getColumnKey(column)]"></span>
                                <span x-show="column === 'Pièce jointe'">
    <template x-if="item.pieceJointe && item.pieceJointe !== ''">
        <a :href="`/storage/${item.pieceJointe}`"
           target="_blank"
           class="piece-jointe-link"
           :title="`Ouvrir le fichier: ${item.pieceJointe.split('/').pop()}`">
            <span class="file-icon" x-text="getFileIcon(item.pieceJointe)"></span>
            <span x-text="item.pieceJointe.split('/').pop()"></span>
        </a>
    </template>
    <template x-if="!item.pieceJointe || item.pieceJointe === ''">
        <span class="text-gray-400">Aucun fichier</span>
    </template>
</span>
                                                </div>

                        </div>
                                  <input x-show="editMode && item.editing && column !== 'Opérations' &&
                                    column !== 'Impact' &&
                                    column !== 'Nature de l\'événement' &&
                                    column !== 'Localisation' &&
                                    column !== 'Conséquence sur le PDT' &&
                                   column !== 'Confidentialité' &&
                                    column !== 'Statut'"
                        class="editable-cell"
                        @change="saveInlineEdit(index)"
                        @blur="saveInlineEdit(index)"
                        x-model="item[getColumnKey(column)]"
                        @click.stop>
                        <input
    x-show="editMode && item.editing && column === 'Date et heure'"
    class="editable-cell date-cell"
    type="datetime-local"
    x-model="item[getColumnKey(column)]"
    @change="saveInlineEdit(index)"
    @blur="saveInlineEdit(index)"
    @click.stop
>
                      <select x-show="editMode && item.editing && column === 'Statut'"
                            class="editable-cell"
                            x-model="item[getColumnKey(column)]"
                            @change="saveInlineEdit(index)"
                            @blur="saveInlineEdit(index)"
                            @click.stop>
                        <option value="">-- Sélectionner --</option>
                        <option value="En cours">En cours</option>
                        <option value="Terminé">Terminé</option>
                        <option value="Archivé">Archivé</option>
                    </select>


                        <select x-show="editMode && item.editing && column === 'Impact'"
                            class="editable-cell"
                            x-model="item[getColumnKey(column)]"
                            @change="saveInlineEdit(index)"
                            @blur="saveInlineEdit(index)"
                            @click.stop>
                        <option value="">-- Sélectionner --</option>
                        <template x-for="option in (window.impacts && window.impacts.map(i => i.libelle)) || []">
                            <option :value="option" x-text="option"></option>
                        </template>
                    </select>
                           <select x-show="editMode && item.editing && column === 'Nature de l\'événement'"
                                    class="editable-cell"
                                    x-model="item[getColumnKey(column)]"
                                     @change="saveInlineEdit(index)"
                                 @blur="saveInlineEdit(index)"
                                    @click.stop>
                                <option value="">-- Sélectionner --</option>
                                <template x-for="option in window.nature_evenements.map(n => n.libelle)">
                                    <option :value="option" x-text="option"></option>
                                </template>
                            </select>
                            <div x-show="editMode && item.editing && column === 'Nature de l\'événement' && (!window.nature_evenements || window.nature_evenements.length === 0)"
                                class="alert alert-warning">
                                Aucune option disponible pour ce champ.
                            </div>
                            <!-- Select pour Localisation -->
                            <select x-show="editMode && item.editing && column === 'Localisation'"
                                    class="editable-cell"
                                    x-model="item[getColumnKey(column)]"
                                     @change="saveInlineEdit(index)"
                                    @blur="saveInlineEdit(index)"
                                    @click.stop>
                                <option value="">-- Sélectionner --</option>
                                <template x-for="option in window.locations.map(l => l.libelle)">
                                    <option :value="option" x-text="option"></option>
                                </template>
                            </select>
                            <select x-show="editMode && item.editing && column === 'Conséquence sur le PDT'"
                                class="editable-cell"
                                x-model="item[getColumnKey(column)]"
                                 @change="saveInlineEdit(index)"
                                 @blur="saveInlineEdit(index)"
                                @click.stop>
                            <option value="">-- Sélectionner --</option>
                            <option value="OUI">OUI</option>
                            <option value="NON">NON</option>
                        </select>

                        <!-- Select pour Confidentialité -->
                        <select x-show="editMode && item.editing && column === 'Confidentialité'"
                                class="editable-cell"
                                x-model="item[getColumnKey(column)]"
                                 @change="saveInlineEdit(index)"
                                 @blur="saveInlineEdit(index)"
                                @click.stop>
                            <option value="">-- Sélectionner --</option>
                            <option value="Confidentiel">Confidentiel</option>
                            <option value="Non confidentiel">Non confidentiel</option>
                        </select>


                        <!-- Séparateur pour commentaires existants - SEULEMENT en édition -->


                                    <!-- Liste des commentaires existants - SEULEMENT en édition -->

                                    <!-- Commentaires existants - SEULEMENT en édition -->
<!-- Commentaires existants - SEULEMENT en édition -->
<div x-show="field.key === 'commentaires-list' && isEditing" class="existing-items comments-list-container">
    <!-- Debug temporaire pour voir les données -->
    <div style="background: yellow; padding: 5px; margin: 5px 0; font-size: 11px;">
        DEBUG: <span x-text="(currentEvent?.originalData?.commentaires || []).length"></span> commentaires trouvés
        <br>Données: <span x-text="JSON.stringify(currentEvent?.originalData?.commentaires || [])"></span>
    </div>

  <template x-for="(commentaire, commentIndex) in (currentEvent?.originalData?.commentaires || [])" :key="commentaire.id || commentIndex">
    <div class="bg-white border p-2 mb-2 rounded shadow" style="font-size: 12px;" :class="{ 'border-green-500': commentaire.editing }">
        <!-- En-tête compact avec TOUTES les informations -->
        <div class="flex items-center justify-between mb-1">
            <div style="display: flex; align-items: center; gap: 8px;">
                <span class="text-xs" style="color: #059669;">💬</span>

                <!-- Nom du commentateur -->
                <span class="text-xs font-medium" style="color: #374151;"
                      x-text="commentaire.auteur ?
                             (commentaire.auteur.prenom + ' ' + commentaire.auteur.nom) :
                             (commentaire.redacteur == window.user.id ?
                              (window.user.prenom + ' ' + window.user.nom + ' (Vous)') :
                              'Utilisateur #' + commentaire.redacteur)">
                </span>

                <!-- Heure du commentaire -->
                <span class="text-xs" style="color: #6b7280;"
                      x-text="commentaire.created_at ?
                             new Date(commentaire.created_at).toLocaleString('fr-FR', {
                                 day: '2-digit',
                                 month: '2-digit',
                                 year: 'numeric',
                                 hour: '2-digit',
                                 minute: '2-digit'
                             }) : 'Date inconnue'">
                </span>
            </div>

            <!-- Boutons d'action -->
            <div class="flex items-center gap-1">
                <button type="button" class="text-blue-500 hover:text-blue-700 p-1" style="font-size: 11px;"
                        @click="toggleCommentEdit(commentIndex)" title="Modifier">✏️</button>
                <button type="button" x-show="commentaire.editing" class="text-green-500 hover:text-green-700 p-1" style="font-size: 11px;"
                        @click="saveCommentEdit(commentIndex)" title="Sauvegarder">✅</button>
                <button type="button" class="text-red-500 hover:text-red-700 p-1" style="font-size: 11px;"
                        @click="deleteComment(commentIndex)" title="Supprimer">🗑️</button>
            </div>
        </div>

        <!-- Contenu compact -->
        <div x-show="!commentaire.editing" style="margin-bottom: 8px;">
            <p class="text-xs" x-text="commentaire.text || commentaire.commentaire || 'Aucun commentaire'"
               style="margin: 0; line-height: 1.4; color: #374151;"></p>
        </div>

        <!-- Mode édition compact -->
        <div x-show="commentaire.editing" style="margin-bottom: 8px;">
            <textarea class="form-control" rows="2" x-model="commentaire.text"
                      style="font-size: 11px; width: 100%;"></textarea>
        </div>

        <!-- Informations supplémentaires en bas -->
        <div class="text-xs" style="color: #9ca3af; border-top: 1px solid #f3f4f6; padding-top: 4px; margin-top: 4px;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span>Commentaire #<span x-text="commentaire.id || (commentIndex + 1)"></span></span>
                <span x-show="commentaire.updated_at && commentaire.updated_at !== commentaire.created_at"
                      style="font-style: italic;">
                    Modifié le <span x-text="new Date(commentaire.updated_at).toLocaleDateString('fr-FR')"></span>
                </span>
            </div>
        </div>
    </div>
</template>
</div>
                                    </div>
                                </td>
                            </template>
                        </tr>

                    </template>
                     <tr x-show="paginatedData.length === 0">
                <td :colspan="getCurrentColumns().length + (editMode ? 1 : 0)"
                    style="text-align: center; padding: 40px; color: #666;">
                    <div x-show="searchQuery !== ''">
                        🔍 Aucun événement trouvé pour "<strong x-text="searchQuery"></strong>"
                        <br>
                        <button @click="clearSearch()" class="btn btn-sm btn-secondary" style="margin-top: 10px;">
                            Effacer la recherche
                        </button>
                    </div>
                    <div x-show="searchQuery === ''">
                        📋 Aucun événement disponible
                    </div>
                </td>
            </tr>
                </tbody>
            </table>
                    </div>

        <!-- PAGINATION SIMPLIFIÉE  -->
<div class="pagination-container" x-show="totalItems > 0">

    <!-- Informations de pagination -->
    <div class="pagination-info">
        <div class="text-sm text-gray-600">
            Affichage de <strong x-text="paginationInfo.start"></strong> à <strong x-text="paginationInfo.end"></strong>
            sur <strong x-text="paginationInfo.total"></strong> événement(s)
            <span x-show="searchQuery !== ''" class="text-blue-600">
                (filtré depuis <span x-text="paginationInfo.totalOriginal"></span> événements)
            </span>
        </div>

        <!-- Navigation rapide -->
        <div class="quick-nav" x-show="totalPages > 1">
            <span class="text-xs">Page :</span>
            <input type="number"
                   min="1"
                   :max="totalPages"
                   x-model.number="pagination.currentPage"
                   @keyup.enter="goToPage(pagination.currentPage)"
                   @change="goToPage($event.target.value)">
            <span class="text-xs">/ <span x-text="totalPages"></span></span>
        </div>
    </div>

    <!-- Boutons de navigation principaux -->
    <div class="pagination-controls" x-show="totalPages > 1">

        <!-- Première page -->
        <button @click="goToPage(1)"
                :disabled="pagination.currentPage === 1"
                title="Première page">
            ⟪
        </button>

        <!-- Page précédente -->
        <button @click="prevPage()"
                :disabled="pagination.currentPage === 1"
                title="Page précédente">
            ‹
        </button>

        <!-- Indicateur page courante -->
        <div class="current-page-indicator">
            <span x-text="pagination.currentPage"></span>
        </div>

        <!-- Page suivante -->
        <button @click="nextPage()"
                :disabled="pagination.currentPage === totalPages"
                title="Page suivante">
            ›
        </button>

        <!-- Dernière page -->
        <button @click="goToPage(totalPages)"
                :disabled="pagination.currentPage === totalPages"
                title="Dernière page">
            ⟫
        </button>
    </div>

    <!-- Numéros de pages -->
    <div class="page-numbers-small" x-show="totalPages <= 15 && totalPages > 1">
        <template x-for="p in Array.from({length: totalPages}, (_, i) => i + 1)" :key="p">
            <button @click="goToPage(p)"
                    :class="{
                        'btn-primary': p === pagination.currentPage,
                        'btn-outline-secondary': p !== pagination.currentPage
                    }"
                    x-text="p">
            </button>
        </template>
    </div>

    <!-- Message pour trop de pages -->
    <div x-show="totalPages > 15" class="text-center text-sm text-gray-500" style="margin-top: 10px;">
        <span x-text="totalPages"></span> pages au total - Utilisez la navigation rapide ci-dessus
    </div>
</div>
        <!-- FIN DE LA PAGINATION -->

        <!-- MODAL POUR CRÉATION/MODIFICATION D'ÉVÉNEMENT -->

<div class="modal" x-show="showModal" @click="$event.target === $event.currentTarget && closeModal()">
    <div class="modal-content" style="max-width: 1400px; width: 95%; max-height: 90vh; overflow-y: auto;">
        <div class="modal-header">
            <h2 class="modal-title" x-text="isEditing ? 'Modifier l\'événement' : 'Créer un événement'"></h2>
            <button class="close-btn" @click="closeModal()">×</button>
        </div>

        <form @submit.prevent="saveEvent()">
            <!--  NOUVELLE STRUCTURE EN DEUX COLONNES -->
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px; min-height: 500px;">

                <!-- COLONNE GAUCHE : CHAMPS PRINCIPAUX -->
                <div class="colonne-principale" style="border-right: 1px solid #e5e7eb; padding-right: 20px;">
                    <h3 style="color: #67152e; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #67152e; font-size: 18px;">
                        📋 Informations de l'événement
                    </h3>

                    <!-- Boucle pour les champs principaux SEULEMENT -->
                    <template x-for="field in getCurrentFields()" :key="field.key + '_main'">
                        <div>
                            <!-- Séparateurs pour champs principaux SEULEMENT -->
                            <div x-show="field.type === 'separator' &&
                                        field.key !== 'separator-actions' &&
                                        field.key !== 'separator-comments' &&
                                        field.key !== 'separator-new-action'"
                                 class="form-separator" style="margin: 25px 0 15px 0;">
                                <h4 x-text="field.label" style="color: #67152e; font-size: 16px; margin: 0;"></h4>
                                <hr style="border: none; height: 1px; background: #67152e; margin-top: 5px;">
                            </div>

                            <!-- Champs normaux (EXCLURE les champs de droite) -->
                            <div class="form-group" x-show="field.type !== 'separator' &&
                                                          field.type !== 'actions-list' &&
                                                          field.type !== 'commentaires-list' &&
                                                          field.type !== 'select-searchable' &&
                                                          field.key !== 'new_comment' &&
                                                          field.key !== 'type_action' &&
                                                          field.key !== 'destinataires' &&
                                                          field.key !== 'message_personnalise' &&
                                                          (!field.condition || field.condition(currentEvent))">
                                <label class="form-label" x-text="field.label"></label>

                                <!-- Input text/datetime/date -->
                                <input x-show="field.type === 'text' || field.type === 'datetime-local' || field.type === 'date'"
                                       class="form-control"
                                       :type="field.type"
                                       x-model="currentEvent[field.key]">

                                <!-- Textarea -->
                                <textarea x-show="field.type === 'textarea'"
                                          class="form-control"
                                          rows="3"
                                          x-model="currentEvent[field.key]"
                                          :placeholder="'Saisir ' + field.label.toLowerCase() + '...'"></textarea>

                                <!-- Select simple -->
                                <select x-show="field.type === 'select'"
                                        class="form-control"
                                        x-model="currentEvent[field.key]">
                                    <option value="">-- Sélectionner --</option>
                                    <template x-for="option in field.options">
                                        <option :value="option" x-text="option"></option>
                                    </template>
                                </select>

                                <!-- File input -->
                                <input x-show="field.type === 'file'"
                                       class="form-control"
                                       type="file"
                                       :name="field.key"
                                        accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpeg,.jpg"
                                       @change="handleFileUpload($event, field.key)">
                            </div>

                            <!-- Champs select-searchable dans la colonne principale -->
                            <div class="form-group" x-show="field.type === 'select-searchable'">
                                <label class="form-label" x-text="field.label"></label>

                                <!-- Zone de recherche et sélection (CONSERVE TOUTES LES PROPRIÉTÉS) -->
                                <div x-data="{
                                    isOpen: false,
                                    searchValue: '',

                                    get options() {
                                        if (field.key === 'nature') {
                                            return filteredNatures;
                                        } else if (field.key === 'localisation') {
                                            return filteredLocations;
                                        } else if (field.key === 'impact') {
                                            return filteredImpacts;
                                        }
                                        return [];
                                    },

                                    get filteredOptions() {
                                        if (!this.searchValue) return this.options;
                                        return this.options.filter(option =>
                                            option.toLowerCase().includes(this.searchValue.toLowerCase())
                                        );
                                    },

                                    selectOption(option) {
                                        if (field.key === 'nature') {
                                            currentEvent.nature = option;
                                            searchNatureQuery = '';
                                        } else if (field.key === 'localisation') {
                                            currentEvent.localisation = option;
                                            searchLocationQuery = '';
                                        } else if (field.key === 'impact') {
                                            currentEvent.impact = option;
                                            searchImpactQuery = '';
                                        }
                                        this.searchValue = option;
                                        this.isOpen = false;
                                        console.log('✅ Option sélectionnée:', option, 'pour', field.key);
                                    },

                                    getCurrentValue() {
                                        if (field.key === 'nature') return currentEvent.nature || '';
                                        if (field.key === 'localisation') return currentEvent.localisation || '';
                                        if (field.key === 'impact') return currentEvent.impact || '';
                                        return '';
                                    },

                                    clearSelection() {
                                        if (field.key === 'nature') {
                                            currentEvent.nature = '';
                                            searchNatureQuery = '';
                                        } else if (field.key === 'localisation') {
                                            currentEvent.localisation = '';
                                            searchLocationQuery = '';
                                        } else if (field.key === 'impact') {
                                            currentEvent.impact = '';
                                            searchImpactQuery = '';
                                        }
                                        this.searchValue = '';
                                        this.isOpen = false;
                                    },

                                    updateSearch() {
                                        if (field.key === 'nature') {
                                            searchNatureQuery = this.searchValue;
                                        } else if (field.key === 'localisation') {
                                            searchLocationQuery = this.searchValue;
                                        } else if (field.key === 'impact') {
                                            searchImpactQuery = this.searchValue;
                                        }
                                    }
                                }"
                                x-init="searchValue = getCurrentValue()"
                                style="position: relative;">

                                    <!-- Champ de saisie avec recherche -->
                                    <input type="text"
                                           class="form-control"
                                           :placeholder="'🔍 Rechercher ' + field.label.toLowerCase() + '...'"
                                           x-model="searchValue"
                                           @focus="isOpen = true; updateSearch()"
                                           @input="isOpen = true; updateSearch()"
                                           @click="isOpen = !isOpen"
                                           @keydown.escape="isOpen = false"
                                           @keydown.enter.prevent="if (filteredOptions.length === 1) selectOption(filteredOptions[0])"
                                           autocomplete="off">

                                    <!-- Bouton clear -->
                                    <button type="button"
                                            x-show="getCurrentValue() !== ''"
                                            @click.stop="clearSelection()"
                                            style="position: absolute; right: 30px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #6b7280; cursor: pointer; padding: 4px;"
                                            title="Effacer la sélection">
                                        ✕
                                    </button>

                                    <!-- Indicateur dropdown -->
                                    <button type="button"
                                            @click.stop="isOpen = !isOpen"
                                            style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #6b7280; cursor: pointer; padding: 4px;"
                                            title="Ouvrir/Fermer la liste">
                                        <span x-show="!isOpen">▼</span>
                                        <span x-show="isOpen">▲</span>
                                    </button>

                                    <!-- Liste des options -->
                                    <div x-show="isOpen"
                                         @click.away="isOpen = false"
                                         style="position: absolute; top: 100%; left: 0; right: 0; z-index: 1000; background: white; border: 1px solid #ddd; border-radius: 6px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); max-height: 200px; overflow-y: auto;">

                                        <!-- Options filtrées -->
                                        <template x-for="option in filteredOptions" :key="option">
                                            <div @click="selectOption(option)"
                                                 @mouseover="$el.style.backgroundColor = '#f0f8ff'"
                                                 @mouseout="$el.style.backgroundColor = 'white'"
                                                 style="padding: 12px; cursor: pointer; border-bottom: 1px solid #f0f0f0; transition: background-color 0.2s;"
                                                 x-text="option">
                                            </div>
                                        </template>

                                        <!-- Message si aucun résultat -->
                                        <div x-show="filteredOptions.length === 0"
                                             style="padding: 15px; text-align: center; color: #666; font-style: italic;">
                                            🔍 Aucune option trouvée
                                            <template x-if="searchValue !== ''">
                                                <div style="margin-top: 5px; font-size: 12px;">
                                                    pour "<strong x-text="searchValue"></strong>"
                                                </div>
                                            </template>
                                        </div>

                                        <!-- Bouton pour effacer la recherche si pas de résultats -->
                                        <div x-show="filteredOptions.length === 0 && searchValue !== ''"
                                             style="padding: 8px; border-top: 1px solid #f0f0f0;">
                                            <button type="button"
                                                    @click="searchValue = ''; updateSearch()"
                                                    style="width: 100%; padding: 6px; background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 4px; cursor: pointer; font-size: 12px;">
                                                ✕ Effacer la recherche
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Affichage de la valeur sélectionnée -->
                                    <div x-show="getCurrentValue() !== '' && !isOpen"
                                         style="margin-top: 8px; padding: 8px; background: linear-gradient(135deg, #f0f8ff 0%, #e6f3ff 100%); border-radius: 4px; font-size: 14px; border: 1px solid #b3d9ff;">
                                        <div style="display: flex; justify-content: space-between; align-items: center;">
                                            <div>
                                                <strong style="color: #1976d2;">✅ Sélectionné :</strong>
                                                <span x-text="getCurrentValue()" style="color: #2e7d32; font-weight: 500;"></span>
                                            </div>
                                            <button type="button"
                                                    @click="clearSelection()"
                                                    style="background: none; border: none; color: #f44336; cursor: pointer; padding: 2px; font-size: 16px;"
                                                    title="Supprimer la sélection">
                                                ✕
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <!--  COLONNE DROITE : ACTIONS ET COMMENTAIRES -->
                <div class="colonne-actions" style="background: #f8f9fa; padding: 20px; border-radius: 8px; position: sticky; top: 20px; max-height: 70vh; overflow-y: auto;">
                    <h3 style="color: #67152e; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #67152e; font-size: 16px;">
                        ⚡ Actions & Commentaires
                    </h3>

                    <!-- Actions et commentaires existants - SEULEMENT en édition -->
                    <template x-for="field in getCurrentFields()" :key="field.key + '_right'">
                        <div>
                            <!-- Séparateur actions existantes -->
                            <div x-show="field.key === 'separator-actions' && isEditing" class="form-separator" style="margin: 20px 0 15px 0;">
                                <h4 style="color: #2563eb; margin: 0; font-size: 14px;">
                                    ⚡ <span x-text="field.label"></span>
                                </h4>
                                <hr style="border: none; height: 1px; background: #2563eb; margin-top: 5px;">
                            </div>

                            <!-- Liste des actions existantes -->
                            <div x-show="field.key === 'actions-list' && isEditing" class="existing-items actions-list-container" style="margin-bottom: 20px;">
                                <!-- CONSERVER EXACTEMENT le code existant pour les actions -->
                                <div class="info-banner actions" style="font-size: 11px; padding: 6px 8px;">
                                    <span class="icon">⚡</span>
                                    <span class="text">Actions existantes</span>
                                    <span class="count" x-text="(currentEvent?.originalData?.actions || []).length"></span>
                                </div>

                                <!-- Message si aucune action -->
                                <div x-show="!currentEvent?.originalData?.actions || currentEvent.originalData.actions.length === 0"
                                     style="text-align: center; padding: 15px; color: #6c757d; font-style: italic; font-size: 12px;">
                                    <span class="icon" style="font-size: 20px; opacity: 0.5;">📝</span>
                                    <p style="margin: 5px 0 0 0;">Aucune action</p>
                                </div>

                                <!-- Template actions existant (version compacte) -->
<template x-for="(action, actionIndex) in (currentEvent?.originalData?.actions || [])" :key="action.id || actionIndex">
    <div class="bg-white border p-2 mb-2 rounded shadow" style="font-size: 12px;" :class="{ 'border-blue-500': action.editing }">
        <!-- En-tête compact avec TOUTES les informations CORRIGÉES -->
        <div class="flex items-center justify-between mb-1">
            <div style="display: flex; align-items: center; gap: 8px;">
                <span class="inline-block px-2 py-1 rounded text-xs font-medium"
                      :class="{
                        'bg-blue-100 text-blue-800': action.type === 'demande_validation',
                        'bg-yellow-100 text-yellow-800': action.type === 'aviser',
                        'bg-green-100 text-green-800': action.type === 'informer',
                        'bg-gray-100 text-gray-800': action.type === 'texte_libre'
                      }"
                      x-text="action.type === 'demande_validation' ? '📋 Validation' :
                             action.type === 'aviser' ? '⚠️ Avertir' :
                             action.type === 'informer' ? 'ℹ️ Informer' : '💭 Libre'">
                </span>

                <!-- Auteur de l'action CORRIGÉ -->
                <span class="text-xs font-medium" style="color: #374151;"
                      x-text="(() => {
                          // Vérifier si on a un auteur avec nom et prénom
                          if (action.auteur && action.auteur.prenom && action.auteur.nom) {
                              return action.auteur.prenom + ' ' + action.auteur.nom;
                          }
                          // Vérifier si on a un utilisateur via auteur_id
                          if (action.auteur_id && window.users) {
                              const user = window.users.find(u => u.id == action.auteur_id);
                              if (user && user.prenom && user.nom) {
                                  return user.prenom + ' ' + user.nom + (action.auteur_id == window.user.id ? ' (Vous)' : '');
                              }
                          }
                          // Vérifier si on a un utilisateur via user_id (alternative)
                          if (action.user_id && window.users) {
                              const user = window.users.find(u => u.id == action.user_id);
                              if (user && user.prenom && user.nom) {
                                  return user.prenom + ' ' + user.nom + (action.user_id == window.user.id ? ' (Vous)' : '');
                              }
                          }
                          // Fallback si c'est l'utilisateur actuel
                          if (action.auteur_id == window.user.id || action.user_id == window.user.id) {
                              return 'Vous';
                          }
                          // Dernier fallback
                          return 'Utilisateur #' + (action.auteur_id || action.user_id || 'inconnu');
                      })()">
                </span>

                <!-- Heure de l'action CORRIGÉE -->
                <span class="text-xs" style="color: #6b7280;"
                      x-text="(() => {
                          const dateStr = action.created_at || action.date;
                          if (!dateStr) return 'Date inconnue';

                          try {
                              const date = new Date(dateStr);
                              if (isNaN(date.getTime())) return 'Date invalide';

                              return date.toLocaleString('fr-FR', {
                                  day: '2-digit',
                                  month: '2-digit',
                                  year: 'numeric',
                                  hour: '2-digit',
                                  minute: '2-digit'
                              });
                          } catch (e) {
                              return 'Date invalide';
                          }
                      })()">
                </span>
            </div>

            <!-- Boutons compacts -->
            <div class="flex items-center gap-1">
                <button type="button" class="text-blue-500 hover:text-blue-700 p-1" style="font-size: 11px;"
                        @click="toggleActionEdit(actionIndex)" title="Modifier">✏️</button>
                <button type="button" x-show="action.editing" class="text-green-500 hover:text-green-700 p-1" style="font-size: 11px;"
                        @click="saveActionEdit(actionIndex)" title="Sauvegarder">✅</button>
                <button type="button" class="text-red-500 hover:text-red-700 p-1" style="font-size: 11px;"
                        @click="deleteAction(actionIndex)" title="Supprimer">🗑️</button>
            </div>
        </div>

        <!-- Contenu compact -->
        <div x-show="!action.editing" style="margin-bottom: 8px;">
            <p class="text-xs" x-text="action.commentaire || action.description || 'Aucun commentaire'"
               style="margin: 0; line-height: 1.4; color: #374151;"></p>

            <!-- Destinataires si présents -->
            <div x-show="action.destinataires_metadata && action.destinataires_metadata.length > 0"
                 style="margin-top: 4px; padding: 4px; background: #f8fafc; border-radius: 4px;">
                <span class="text-xs" style="color: #6b7280;">
                    🎯 Destinataires :
                    <span x-text="getDestinataireNamesFromIds(action.destinataires_metadata).join(', ')"></span>
                </span>
            </div>
        </div>

        <!-- Mode édition compact -->
        <div x-show="action.editing" style="font-size: 11px; margin-bottom: 8px;">
            <select class="form-control mb-1" x-model="action.type" style="font-size: 11px; padding: 2px;">
                <option value="texte_libre">💭 Commentaire</option>
                <option value="demande_validation">📋 Validation</option>
                <option value="aviser">⚠️ Aviser</option>
                <option value="informer">ℹ️ Informer</option>
            </select>
            <textarea class="form-control" rows="2" x-model="action.commentaire"
                      style="font-size: 11px; width: 100%;"></textarea>
        </div>

        <!-- Informations supplémentaires en bas -->
        <div class="text-xs" style="color: #9ca3af; border-top: 1px solid #f3f4f6; padding-top: 4px; margin-top: 4px;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span>Action #<span x-text="action.id || (actionIndex + 1)"></span></span>
                <span x-show="action.updated_at && action.updated_at !== action.created_at"
                      style="font-style: italic;">
                    Modifiée le <span x-text="(() => {
                        try {
                            return new Date(action.updated_at).toLocaleDateString('fr-FR');
                        } catch (e) {
                            return 'Date invalide';
                        }
                    })()"></span>
                </span>
            </div>
        </div>
    </div>
</template>
                            </div>

                            <!-- Séparateur commentaires existants -->
                            <div x-show="field.key === 'separator-comments' && isEditing" class="form-separator" style="margin: 20px 0 15px 0;">
                                <h4 style="color: #059669; margin: 0; font-size: 14px;">
                                    💬 <span x-text="field.label"></span>
                                </h4>
                                <hr style="border: none; height: 1px; background: #059669; margin-top: 5px;">
                            </div>

                            <!-- Liste des commentaires existants (version compacte) -->
                            <div x-show="field.key === 'commentaires-list' && isEditing" class="existing-items comments-list-container" style="margin-bottom: 20px;">
                                <div class="info-banner comments" style="font-size: 11px; padding: 6px 8px;">
                                    <span class="icon">💬</span>
                                    <span class="text">Commentaires existants</span>
                                    <span class="count" x-text="(currentEvent?.originalData?.commentaires || []).length"></span>
                                </div>

                                <!-- Message si aucun commentaire -->
                                <div x-show="!currentEvent?.originalData?.commentaires || currentEvent.originalData.commentaires.length === 0"
                                     style="text-align: center; padding: 15px; color: #6c757d; font-style: italic; font-size: 12px;">
                                    <span class="icon" style="font-size: 20px; opacity: 0.5;">💭</span>
                                    <p style="margin: 5px 0 0 0;">Aucun commentaire</p>
                                </div>

                                <!-- Template commentaires compact -->

                                <template x-for="(commentaire, commentIndex) in (currentEvent?.originalData?.commentaires || [])" :key="commentaire.id || commentIndex">
    <div class="bg-white border p-2 mb-2 rounded shadow" style="font-size: 12px;" :class="{ 'border-green-500': commentaire.editing }">
        <!-- En-tête compact avec TOUTES les informations CORRIGÉES -->
        <div class="flex items-center justify-between mb-1">
            <div style="display: flex; align-items: center; gap: 8px;">
                <span class="text-xs" style="color: #059669;">💬</span>

                <!-- Nom du commentateur CORRIGÉ -->
                <span class="text-xs font-medium" style="color: #374151;"
                      x-text="(() => {
                          // Vérifier si on a un auteur avec nom et prénom
                          if (commentaire.auteur && commentaire.auteur.prenom && commentaire.auteur.nom) {
                              return commentaire.auteur.prenom + ' ' + commentaire.auteur.nom;
                          }
                          // Vérifier si on a un utilisateur via redacteur
                          if (commentaire.redacteur && window.users) {
                              const user = window.users.find(u => u.id == commentaire.redacteur);
                              if (user && user.prenom && user.nom) {
                                  return user.prenom + ' ' + user.nom + (commentaire.redacteur == window.user.id ? ' (Vous)' : '');
                              }
                          }
                          // Fallback si c'est l'utilisateur actuel
                          if (commentaire.redacteur == window.user.id) {
                              return 'Vous';
                          }
                          // Dernier fallback
                          return 'Utilisateur #' + (commentaire.redacteur || 'inconnu');
                      })()">
                </span>

                <!-- Heure du commentaire CORRIGÉE -->
                <span class="text-xs" style="color: #6b7280;"
                      x-text="(() => {
                          const dateStr = commentaire.created_at || commentaire.date;
                          if (!dateStr) return 'Date inconnue';

                          try {
                              const date = new Date(dateStr);
                              if (isNaN(date.getTime())) return 'Date invalide';

                              return date.toLocaleString('fr-FR', {
                                  day: '2-digit',
                                  month: '2-digit',
                                  year: 'numeric',
                                  hour: '2-digit',
                                  minute: '2-digit'
                              });
                          } catch (e) {
                              return 'Date invalide';
                          }
                      })()">
                </span>
            </div>

            <!-- Boutons d'action -->
            <div class="flex items-center gap-1">
                <button type="button" class="text-blue-500 hover:text-blue-700 p-1" style="font-size: 11px;"
                        @click="toggleCommentEdit(commentIndex)" title="Modifier">✏️</button>
                <button type="button" x-show="commentaire.editing" class="text-green-500 hover:text-green-700 p-1" style="font-size: 11px;"
                        @click="saveCommentEdit(commentIndex)" title="Sauvegarder">✅</button>
                <button type="button" class="text-red-500 hover:text-red-700 p-1" style="font-size: 11px;"
                        @click="deleteComment(commentIndex)" title="Supprimer">🗑️</button>
            </div>
        </div>

        <!-- Contenu compact -->
        <div x-show="!commentaire.editing" style="margin-bottom: 8px;">
            <p class="text-xs" x-text="commentaire.text || commentaire.commentaire || 'Aucun commentaire'"
               style="margin: 0; line-height: 1.4; color: #374151;"></p>
        </div>

        <!-- Mode édition compact -->
        <div x-show="commentaire.editing" style="margin-bottom: 8px;">
            <textarea class="form-control" rows="2" x-model="commentaire.text"
                      style="font-size: 11px; width: 100%;"></textarea>
        </div>

        <!-- Informations supplémentaires en bas -->
        <div class="text-xs" style="color: #9ca3af; border-top: 1px solid #f3f4f6; padding-top: 4px; margin-top: 4px;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span>Commentaire #<span x-text="commentaire.id || (commentIndex + 1)"></span></span>
                <span x-show="commentaire.updated_at && commentaire.updated_at !== commentaire.created_at"
                      style="font-style: italic;">
                    Modifié le <span x-text="(() => {
                        try {
                            return new Date(commentaire.updated_at).toLocaleDateString('fr-FR');
                        } catch (e) {
                            return 'Date invalide';
                        }
                    })()"></span>
                </span>
            </div>
        </div>
    </div>
</template>
                            </div>

                            <!-- Séparateur nouvelle action -->
                            <div x-show="field.key === 'separator-new-action'" class="form-separator" style="margin: 25px 0 15px 0;">
                                <h4 style="color: #dc2626; margin: 0; font-size: 14px;">
                                    ➕ <span x-text="field.label"></span>
                                </h4>
                                <hr style="border: none; height: 1px; background: #dc2626; margin-top: 5px;">
                            </div>

                            <!-- Nouveau commentaire -->
                            <div x-show="field.key === 'new_comment' && isEditing" class="form-group" style="margin-bottom: 15px;">
                                <label class="form-label" style="font-size: 13px; color: #059669;">
                                    💬 <span x-text="field.label"></span>
                                </label>
                                <textarea class="form-control" style="font-size: 12px;"
                                          rows="3"
                                          x-model="currentEvent.new_comment"
                                          placeholder="Ajouter un commentaire..."></textarea>
                            </div>

                            <!-- Type d'action -->
                            <div x-show="field.key === 'type_action'" class="form-group" style="margin-bottom: 15px;">
                                <label class="form-label" style="font-size: 13px; color: #dc2626;">
                                    🎯 <span x-text="field.label"></span>
                                </label>
                                <select class="form-control" style="font-size: 12px;" x-model="currentEvent.type_action">
                                    <option value="">-- Sélectionner --</option>
                                    <option value="texte_libre">💭 Commentaire libre</option>
                                    <option value="demande_validation">📋 Demande de validation</option>
                                    <option value="aviser">⚠️ Aviser</option>
                                    <option value="informer">ℹ️ Informer</option>
                                </select>
                            </div>

                            <!-- Destinataires (version compacte) -->
                            <div x-show="field.key === 'destinataires' && (!field.condition || field.condition(currentEvent))" class="form-group" style="margin-bottom: 15px;">
                                <label class="form-label" style="font-size: 13px; color: #dc2626;">
                                    🎯 <span x-text="field.label"></span>
                                </label>

                                <!-- Champ de recherche compact -->
                                <input type="text" class="form-control" style="font-size: 11px; margin-bottom: 8px;"
                                       placeholder="🔍 Rechercher..." x-model="searchDestinataireQuery"
                                       @input="filterDestinataires()">

                                <!-- Zone de sélection compacte -->
                                <div style="max-height: 120px; overflow-y: auto; border: 1px solid #ddd; border-radius: 4px; font-size: 11px;">
                                    <!-- Listes de diffusion compactes -->
                                    <div style="background: #67152e; color: white; padding: 6px; font-weight: bold; font-size: 10px;">
                                        📋 Listes
                                    </div>
                                    <template x-for="liste in filteredListesDiffusion.slice(0, 3)" :key="'liste_' + liste.id">
                                        <div style="padding: 6px; border-bottom: 1px solid #f0f0f0; cursor: pointer; font-size: 10px;"
                                             :style="currentEvent.destinataires && currentEvent.destinataires.includes('liste_' + liste.id) ? 'background: #e3f2fd;' : 'background: white;'"
                                             @click="manualToggleDestinataire('liste_' + liste.id)">
                                            📋 <span x-text="liste.nom"></span>
                                        </div>
                                    </template>

                                    <!-- Utilisateurs individuels compacts -->
                                    <div style="background: #67152e; color: white; padding: 6px; font-weight: bold; font-size: 10px;">
                                        👤 Utilisateurs
                                    </div>
                                    <template x-for="user in filteredUsers.slice(0, 3)" :key="'user_' + user.id">
                                        <div style="padding: 6px; border-bottom: 1px solid #f0f0f0; cursor: pointer; font-size: 10px;"
                                             :style="currentEvent.destinataires && currentEvent.destinataires.includes('user_' + user.id) ? 'background: #e8f5e8;' : 'background: white;'"
                                             @click="manualToggleDestinataire('user_' + user.id)">
                                            👤 <span x-text="user.prenom + ' ' + user.nom"></span>
                                        </div>
                                    </template>
                                </div>

                                <!-- Aperçu compact des sélections -->
                                <div x-show="currentEvent.destinataires && currentEvent.destinataires.length > 0"
                                     style="margin-top: 8px; font-size: 10px; padding: 6px; background: #f0f8ff; border-radius: 4px;">
                                    <strong x-text="currentEvent.destinataires.length + ' sélectionné(s)'"></strong>
                                </div>
                            </div>

                            <!-- Message personnalisé -->
                            <div x-show="field.key === 'message_personnalise' && (!field.condition || field.condition(currentEvent))" class="form-group">
                                <label class="form-label" style="font-size: 13px; color: #dc2626;">
                                    💬 <span x-text="field.label"></span>
                                </label>
                                <textarea class="form-control" style="font-size: 12px;"
                                          rows="2"
                                          x-model="currentEvent.message_personnalise"
                                          placeholder="Message personnalisé..."></textarea>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- BOUTONS DU FORMULAIRE (pleine largeur en bas) -->
            <div class="form-group" style="display: flex; justify-content: space-between; align-items: center; margin-top: 30px; padding-top: 20px; border-top: 2px solid #e5e7eb;">
                <button type="button"
                        class="soft-yellow-btn"
                        @click="closeModal()">
                    ❌ Annuler
                </button>
                <button type="submit"
                        class="btn btn-primary px-6 py-2">
                    <span x-text="isEditing ? '✏️ Modifier l\'événement' : '➕ Créer l\'événement'"></span>
                </button>
            </div>
        </form>
    </div>
</div>

        <!-- Modal de création/édition -->

            <div x-show="isLoading" class="loader-overlay" x-transition>
        <div class="loader"></div>
    </div>

  <!-- Modal de diffusion d'événement avec recherche -->
<div class="modal"
     x-show="showDiffusionModal"
     @click="if ($event.target === $event.currentTarget) closeDiffusionModal()"
     style="display: none;"
     x-transition>
    <div class="modal-content" style="max-width: 800px;" @click.stop>
        <div class="modal-header">
            <h2 class="modal-title">📤 Diffuser l'événement</h2>
            <button class="close-btn" @click="closeDiffusionModal()">×</button>
        </div>

        <form @submit.prevent="diffuserEvenement()" @click.stop>
            <!-- Informations de l'événement -->
            <div class="form-group" style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                <h4 style="margin: 0 0 10px 0; color: #67152e;">📋 Événement à diffuser</h4>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 10px; font-size: 14px;">
                    <div><strong>ID :</strong> <span x-text="diffusionEvent && diffusionEvent.numero ? diffusionEvent.numero : 'N/A'"></span></div>
                    <div><strong>Nature :</strong> <span x-text="diffusionEvent && diffusionEvent.nature ? diffusionEvent.nature : 'N/A'"></span></div>
                    <div><strong>Date :</strong> <span x-text="diffusionEvent && diffusionEvent.dateHeure ? diffusionEvent.dateHeure : 'N/A'"></span></div>
                    <div><strong>Statut :</strong> <span x-text="diffusionEvent && diffusionEvent.statut ? diffusionEvent.statut : 'N/A'"></span></div>
                </div>
            </div>

            <!--  NOUVEAU : Destinataires avec recherche -->
            <div class="form-group">
                <label class="form-label">🎯 Destinataires *</label>

                <!-- Champ de recherche avec icônes -->
                <div class="mb-3" style="position: relative;">
                    <input type="text"
                           class="form-control"
                           placeholder="🔍 Rechercher un utilisateur, une liste ou une entité..."
                           x-model="searchDiffusionQuery"
                           @input="filterDiffusionDestinataires()"
                           style="padding-left: 40px; padding-right: 100px;">

                    <!-- Icône de recherche -->
                    <div style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #6b7280;">

                    </div>

                    <!-- Bouton effacer recherche -->
                    <button type="button"
                            x-show="searchDiffusionQuery !== ''"
                            @click="clearDiffusionSearch()"
                            style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #6b7280; cursor: pointer; padding: 4px;"
                            title="Effacer la recherche">
                        ✕
                    </button>
                </div>

                <!-- Indicateur de résultats de recherche -->
                <div x-show="searchDiffusionQuery !== ''" class="mb-2" style="font-size: 12px; color: #6b7280; padding: 5px 10px; background: #f0f9ff; border-radius: 4px;">
                    <span x-text="(filteredDiffusionListes.length + filteredDiffusionUsers.length)"></span> résultat(s) trouvé(s) •
                    <span x-text="filteredDiffusionListes.length"></span> liste(s) •
                    <span x-text="filteredDiffusionUsers.length"></span> utilisateur(s)
                </div>

                <!-- Zone de sélection avec recherche améliorée -->
                <div class="destinataires-selection" style="max-height: 350px; overflow-y: auto; border: 1px solid #ddd; border-radius: 6px; background: #fafafa;">

                    <!-- Section Listes de diffusion -->
                    <div x-show="filteredDiffusionListes.length > 0 || searchDiffusionQuery === ''">
                        <div class="destinataire-header" style="background: #67152e; color: white; padding: 12px; font-weight: bold; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center;">
                            <span>📋 Listes de diffusion</span>
                            <span class="count" x-text="filteredDiffusionListes.length + ' disponible' + (filteredDiffusionListes.length > 1 ? 's' : '')" style="font-size: 11px; background: rgba(255,255,255,0.2); padding: 2px 8px; border-radius: 10px;"></span>
                        </div>
<template x-for="liste in filteredDiffusionListes" :key="'diffusion_liste_' + liste.id">
    <div class="destinataire-item"
         :class="{ 'selected': diffusionData.destinataires.includes('liste_' + liste.id) }"
         @click="toggleDiffusionDestinataire('liste_' + liste.id)"
         style="padding: 12px; border-bottom: 1px solid #f0f0f0; cursor: pointer; transition: all 0.2s ease; background: white;">

        <div class="destinataire-name" style="font-weight: 600; margin-bottom: 4px; color: #1976d2;">
            📋 <span x-text="liste.nom || 'Liste sans nom'"></span>
            <span x-show="diffusionData.destinataires.includes('liste_' + liste.id)" style="float: right; color: #22c55e; font-size: 16px;">✓</span>
        </div>

        <div class="destinataire-details" style="font-size: 12px; color: #666;">
            <div style="margin-bottom: 2px;">
                👥 <span x-text="(liste.users ? liste.users.length : 0) + ' utilisateur' + ((liste.users && liste.users.length > 1) ? 's' : '')"></span>
            </div>

            <!-- CORRECTION : Aperçu sécurisé des utilisateurs -->
            <template x-if="liste.users && liste.users.length > 0">
                <div style="font-style: italic; color: #888; margin-top: 4px;">
                    <template x-for="(user, userIndex) in liste.users.slice(0, 3)" :key="user.id">
                        <span>
                            <span x-text="(user.prenom || '') + ' ' + (user.nom || '')"></span><span x-show="userIndex < Math.min(liste.users.length, 3) - 1">, </span>
                        </span>
                    </template>
                    <span x-show="liste.users.length > 3" x-text="' et ' + (liste.users.length - 3) + ' autres...'"></span>
                </div>
            </template>
        </div>
    </div>
</template>

<!-- CORRECTION : Template utilisateurs avec vérifications -->
<template x-for="user in filteredDiffusionUsers" :key="'diffusion_user_' + user.id">
    <div class="destinataire-item user"
         :class="{ 'selected user': diffusionData.destinataires.includes('user_' + user.id) }"
         @click="toggleDiffusionDestinataire('user_' + user.id)"
         style="padding: 12px; border-bottom: 1px solid #f0f0f0; cursor: pointer; transition: all 0.2s ease; background: white;">

        <div class="destinataire-name user" style="font-weight: 600; margin-bottom: 4px; color: #2e7d32;">
            👤 <span x-text="(user.prenom || '') + ' ' + (user.nom || '') || 'Utilisateur sans nom'"></span>
            <span x-show="diffusionData.destinataires.includes('user_' + user.id)" style="float: right; color: #22c55e; font-size: 16px;">✓</span>
        </div>

        <div class="destinataire-details" style="font-size: 12px; color: #666;">
            <div>📧 <span x-text="user.email || 'Email non défini'"></span></div>
            <template x-if="user.entite && user.entite.nom">
                <div style="margin-top: 2px;">🏢 <span x-text="user.entite.nom"></span></div>
            </template>
        </div>
    </div>
</template>
                    </div>

                    <!-- Section Utilisateurs individuels -->
                    <div x-show="filteredDiffusionUsers.length > 0 || searchDiffusionQuery === ''">
                        <div class="destinataire-header" style="background: #67152e; color: white; padding: 12px; font-weight: bold; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center;">
                            <span>👤 Utilisateurs individuels</span>
                            <span class="count" x-text="filteredDiffusionUsers.length + ' disponible' + (filteredDiffusionUsers.length > 1 ? 's' : '')" style="font-size: 11px; background: rgba(255,255,255,0.2); padding: 2px 8px; border-radius: 10px;"></span>
                        </div>

                        <template x-for="user in filteredDiffusionUsers" :key="'diffusion_user_' + user.id">
                            <div class="destinataire-item user"
                                 :class="{ 'selected user': diffusionData.destinataires.includes('user_' + user.id) }"
                                 @click="toggleDiffusionDestinataire('user_' + user.id)"
                                 style="padding: 12px; border-bottom: 1px solid #f0f0f0; cursor: pointer; transition: all 0.2s ease; background: white;">

                                <div class="destinataire-name user" style="font-weight: 600; margin-bottom: 4px; color: #2e7d32;">
                                    👤 <span x-text="user.prenom + ' ' + user.nom"></span>
                                    <span x-show="diffusionData.destinataires.includes('user_' + user.id)" style="float: right; color: #22c55e; font-size: 16px;">✓</span>
                                </div>

                                <div class="destinataire-details" style="font-size: 12px; color: #666;">
                                    <div>📧 <span x-text="user.email"></span></div>
                                    <template x-if="user.entite && user.entite.nom">
                                        <div style="margin-top: 2px;">🏢 <span x-text="user.entite.nom"></span></div>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Message si aucun résultat -->
                    <div x-show="filteredDiffusionListes.length === 0 && filteredDiffusionUsers.length === 0 && searchDiffusionQuery !== ''"
                         style="padding: 30px; text-align: center; color: #666;">
                        <div style="font-size: 48px; opacity: 0.3; margin-bottom: 10px;">🔍</div>
                        <p style="margin: 0; font-weight: 500;">Aucun résultat trouvé</p>
                        <p style="margin: 5px 0 0 0; font-size: 12px;">
                            Essayez de rechercher par nom, prénom, email ou entité
                        </p>
                        <button type="button"
                                @click="clearDiffusionSearch()"
                                style="margin-top: 10px; padding: 5px 15px; background: #f3f4f6; border: 1px solid #d1d5db; border-radius: 4px; cursor: pointer; font-size: 12px;">
                            ✕ Effacer la recherche
                        </button>
                    </div>

                    <!-- Message si aucune donnée disponible -->
                    <div x-show="(window.listesDiffusion?.length || 0) === 0 && (window.users?.length || 0) === 0"
                         style="padding: 30px; text-align: center; color: #666;">
                        <div style="font-size: 48px; opacity: 0.3; margin-bottom: 10px;">📭</div>
                        <p style="margin: 0; font-weight: 500;">Aucun destinataire disponible</p>
                        <p style="margin: 5px 0 0 0; font-size: 12px;">
                            Vérifiez que des utilisateurs et des listes de diffusion sont configurés
                        </p>
                    </div>
                </div>

                <!-- Aperçu des sélections avec indicateur visuel amélioré -->
                <div x-show="diffusionData.destinataires && diffusionData.destinataires.length > 0"
                     class="selection-preview"
                     style="margin-top: 15px; padding: 15px; background: linear-gradient(135deg, #f0f8ff 0%, #e6f3ff 100%); border-radius: 8px; border: 1px solid #b3d9ff;">

                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                        <div style="font-size: 14px; color: #1976d2; font-weight: 600;">
                            ✅ <span x-text="diffusionData.destinataires.length"></span> destinataire(s) sélectionné(s)
                        </div>
                        <div style="font-size: 12px; color: #666;">
                            📊 Total : <strong x-text="getDestinataireCount()"></strong> utilisateur(s) final(aux)
                        </div>
                    </div>

                    <div style="display: flex; flex-wrap: wrap; gap: 6px;">
                        <template x-for="destId in (diffusionData.destinataires || [])" :key="'preview_' + destId">
                            <span class="selected-badge"
                                  style="font-size: 11px; padding: 4px 8px; background: white; border: 1px solid #2196F3; border-radius: 15px; color: #1976d2; display: inline-flex; align-items: center; gap: 4px;">
                                <span x-text="getDiffusionDestinataireNameById(destId)"></span>
                                <button type="button"
                                        class="remove-destinataire"
                                        @click.stop="toggleDiffusionDestinataire(destId)"
                                        style="background: none; border: none; color: #f44336; cursor: pointer; padding: 0; margin-left: 4px; font-size: 12px; line-height: 1;"
                                        title="Retirer ce destinataire">
                                    ✕
                                </button>
                            </span>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Message personnalisé -->
            <div class="form-group">
                <label class="form-label">💬 Message personnalisé (optionnel)</label>
                <textarea class="form-control"
                          rows="4"
                          x-model="diffusionData.message_personnalise"
                          @click.stop
                          placeholder="Ajoutez un message personnel qui apparaîtra en haut de l'email..."></textarea>
            </div>

            <!-- Options d'inclusion -->
            <div class="form-group">
                <label class="form-label">📋 Contenu à inclure</label>
                <div style="display: flex; gap: 20px; margin-top: 10px; padding: 10px; background: #f8f9fa; border-radius: 6px;">
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                        <input type="checkbox" x-model="diffusionData.inclure_actions" style="margin: 0;" @click.stop>
                        <span>🎯 Inclure les actions (<span x-text="diffusionEvent?.originalData?.actions?.length || 0"></span>)</span>
                    </label>
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                        <input type="checkbox" x-model="diffusionData.inclure_commentaires" style="margin: 0;" @click.stop>
                        <span>💬 Inclure les commentaires (<span x-text="diffusionEvent?.originalData?.commentaires?.length || 0"></span>)</span>
                    </label>
                </div>
            </div>

            <!-- Boutons -->
            <div class="form-group" style="display: flex; justify-content: space-between; align-items: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee;">
                <button type="button" class="soft-yellow-btn" @click.stop="closeDiffusionModal()">
                    ❌ Annuler
                </button>
                <button type="submit"
                        class="btn btn-primary px-6 py-2"
                        :disabled="diffusionData.destinataires.length === 0"
                        @click.stop>
                    📤 Diffuser à <span x-text="getDestinataireCount()"></span> utilisateur<span x-show="getDestinataireCount() > 1">s</span>
                </button>
            </div>
        </form>
    </div>
</div>

    <!-- Notification -->
    <div x-show="notification.show"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="transform translate-x-full opacity-0"
         x-transition:enter-end="transform translate-x-0 opacity-100"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="transform translate-x-0 opacity-100"
         x-transition:leave-end="transform translate-x-full opacity-0"
         class="notification"
         :class="notification.type">
        <div class="notification-header">
            <span class="notification-title" x-text="notification.title"></span>
            <button class="notification-close" @click="hideNotification()">×</button>
        </div>
        <div class="notification-message" x-text="notification.message"></div>
    </div>

    </div>





<script>
        window.user = {
        name: "{{ auth()->user()->prenom }} {{ auth()->user()->nom }}",
        nom_complet: "{{ auth()->user()->prenom }} {{ auth()->user()->nom }}",
        entiteCode: "{{ auth()->user()->entite ? auth()->user()->entite->code : '' }}",
        id: {{ auth()->user()->id }}
    };
        function nullishCoalescing(value, defaultValue) {
         return (value !== null && value !== undefined) ? value : defaultValue;
}

window.entites = @json(isset($entites) ? $entites : []);
window.utilisateurs = @json(isset($utilisateurs) ? $utilisateurs : []);
window.nature_evenements = @json(isset($nature_evenements) ? $nature_evenements : []);
window.evenements = @json(isset($evenements) ? $evenements : []);
window.evenementsSRCOF = @json(isset($evenementsSRCOF) ? $evenementsSRCOF : []);
window.evenementsCIV = @json(isset($evenementsCIV) ? $evenementsCIV : []);
window.evenementsHOTLINE = @json(isset($evenementsHOTLINE) ? $evenementsHOTLINE : []);
window.evenementsCM = @json(isset($evenementsCM) ? $evenementsCM : []);
window.evenementsPTP = @json(isset($evenementsPTP) ? $evenementsPTP : []);
window.locations = @json(isset($locations) ? $locations : []);
window.impacts = @json(isset($impacts) ? $impacts : []);
window.listesDiffusion = @json(isset($listesDiffusion) ? $listesDiffusion : []);
window.users = @json(isset($users) ? $users : []);
// Dans ton script, après le chargement des données window
console.log('=== DEBUG DIFFUSION ===');
console.log('window.users:', window.users?.length || 0, 'utilisateurs');
console.log('window.listesDiffusion:', window.listesDiffusion?.length || 0, 'listes');
console.log('Premier utilisateur:', window.users?.[0]);
console.log('Première liste:', window.listesDiffusion?.[0]);


// Fonction pour transformer les données du contrôleur en format adapté à notre interface
function transformEventsForUI(events) {
    return events.map(evt => ({
        numero: evt.id || '',
        dateHeure: evt.date_evenement ?
        new Date(evt.date_evenement).toLocaleString('fr-FR', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        }) : '',
        nature: evt.nature_evenement ? evt.nature_evenement.libelle : (evt.nature_evenement_id || ''),
        localisation: evt.location ? evt.location.libelle : (evt.location_id || ''),
        description: evt.description || '',
        consequence: evt.consequence_sur_pdt == 1 ? 'OUI' : evt.consequence_sur_pdt == 0 ? 'NON' : '',
        redacteur: evt.redacteur || window.user.nom_complet,
       statut: evt.statut === 'en_cours' ? 'En cours' :
                evt.statut === 'cloture' ? 'Terminé' :
                evt.statut === 'archive' ? 'Archivé' : '',
        descriptionLocalisation: evt.location_description || '',
        dateCloture: evt.date_cloture || '',
        confidentialite: evt.confidentialite == 1 ? 'Confidentiel' : evt.confidentialite == 0 ? 'Non confidentiel' : '',
        impact: evt.impact && evt.impact.libelle ? evt.impact.libelle : (evt.impact_id ? window.impacts.find(i => i.id === evt.impact_id)?.libelle : ''),
        heureAppelIntervenant: evt.heure_appel_intervenant || '',
        heureArriveeIntervenant: evt.heure_arrive_intervenant || '',
        commentaire: evt.commentaires && evt.commentaires.length ?
    `${evt.commentaires.length} commentaire${evt.commentaires.length > 1 ? 's' : ''}` : '0 commentaire',
     commentaire_autre_entite: evt.commentaire_autre_entite || '',
        action: evt.actions && evt.actions.length ?
    `${evt.actions.length} action${evt.actions.length > 1 ? 's' : ''}` : '0 action',
        post: evt.entite ? evt.entite.code : '',
        pieceJointe: evt.piece_jointe || '',
        type_piece_jointe: evt.type_piece_jointe || '',
        date: evt.date_evenement ? new Date(evt.date_evenement).toLocaleDateString('fr-FR') : '',
        heure: evt.date_evenement ? new Date(evt.date_evenement).toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' }) : '',
        semaine: evt.date_evenement ? `Semaine ${new Date(evt.date_evenement).getWeek ? new Date(evt.date_evenement).getWeek() : Math.ceil((new Date(evt.date_evenement) - new Date(new Date(evt.date_evenement).getFullYear(), 0, 1)) / 604800000)}` : '',
        title: evt.id || '',
        id: evt.id || '',
        avis_srcof: evt.avis_srcof || '',
        visa_encadrant: evt.visa_encadrant || '',
        editing: false,
        originalData: {
            ...evt,
            // S'assurer que les commentaires ont la bonne structure
            commentaires: evt.commentaires ? evt.commentaires.map(comment => ({
                id: comment.id,
                text: comment.text || '',
                redacteur: comment.redacteur || window.user.nom_complet,
                type: comment.type,
                date: comment.date,
                created_at: comment.created_at,
                updated_at: comment.updated_at,
                auteur: comment.auteur || null, // Relation auteur chargée
                editing: false
            })) : []
        }
    }));
}

function mainCouranteApp() {
    return {
        activeTab: '{{ $defaultTab ?? "SRCOF" }}',
        searchDestinataireQuery: '',
        searchDiffusionQuery: '',
        searchNatureQuery: '',
        searchLocationQuery: '',
        searchImpactQuery: '',
        editMode: false,
        showModal: false,
        isEditing: false,
        currentEvent: {},
        currentEditIndex: null,
         params: {},
           isLoading: false,
        notification: {
            show: false,
            type: 'success',
            title: '',
            message: '',
            duration: 5000
        },
        showDiffusionModal: false,
        diffusionEvent: {},
        diffusionData: {
            destinataires: [],
            message_personnalise: '',
            inclure_actions: true,
            inclure_commentaires: true
        },
                pagination: {
                    currentPage: 1,
                    itemsPerPage: 10,
                    itemsPerPageOptions: [5, 10, 20, 50, 100]
                },
                searchQuery: '',

        // Configuration des colonnes pour chaque type
        columns: {
            SRCOF: [
                'Numéro', 'Date et heure', 'Nature de l\'événement', 'Localisation', 'Description',
                'Conséquence sur le PDT', 'Rédacteur', 'Statut',
                'Commentaire', 'Action', 'POST','Visa encadrant','Pièce jointe','Type d\'élément', 'Opérations'
            ],
            CIV: [
                'ID', 'Date et heure', 'Nature de l\'événement', 'Localisation', 'Description',
                'Conséquence sur le PDT', 'Rédacteur', 'Statut',
                'Confidentialité', 'Commentaire', 'Action', 'POST', 'Pièce jointe', 'Type d\'élément', 'Opérations'
            ],
            HOTLINE: [
                'Numéro', 'Date et heure', 'Nature de l\'événement', 'Localisation', 'Description',
                'Conséquence sur le PDT', 'Rédacteur','Statut', 'Date clôture',
                'Confidentialité', 'Impact', 'Commentaire','Commentaire autre entité', 'Action', 'POST', 'Pièce jointe', 'Type d\'élément', 'Opérations'
            ],
            CM: [
                'Title', 'Date et heure', 'Nature de l\'événement', 'Localisation', 'Description',
                'Conséquence sur le PDT', 'Rédacteur', 'Statut', 'Date clôture',
                'Confidentialité', 'Impact', 'Heure appel intervenant', 'Heure arrivée intervenant',
                'Commentaire','Avis SRCOF', 'Action', 'POST', 'Pièce jointe', 'Type d\'élément', 'Opérations'
            ],
            PTP: [
                'Numéro', 'Date', 'Heure', 'Semaine', 'Nature de l\'événement', 'Description',
                'Rédacteur', 'Statut', 'Date clôture',
                'Commentaire','Commentaire Planificateur', 'Action', 'POST', 'Opérations'
            ]
        },

        // Données dynamiques pour chaque type
        data: {
            SRCOF: transformEventsForUI(window.evenementsSRCOF || []),
            CIV: transformEventsForUI(window.evenementsCIV || []),
            HOTLINE: transformEventsForUI(window.evenementsHOTLINE || []),
            CM: transformEventsForUI(window.evenementsCM || []),
            PTP: transformEventsForUI(window.evenementsPTP || [])
        },

        // Votre configuration des fields

       fields: {
    SRCOF: [
        { key: 'dateHeure', label: 'Date et heure', type: 'datetime-local' },
       { key: 'nature', label: 'Nature de l\'événement', type: 'select-searchable', options: this.filteredNatures },
        { key: 'localisation', label: 'Localisation', type: 'select-searchable', options: this.filteredLocations },
        { key: 'description', label: 'Description', type: 'textarea' },
        { key: 'consequence', label: 'Conséquence sur le PDT', type: 'select', options: ['OUI', 'NON'] },
        { key: 'redacteur', label: 'Rédacteur', type: 'text',readonly: true },
        { key: 'statut', label: 'Statut', type: 'select', options: ['En cours', 'Terminé', 'Archivé'] },
        { key: 'commentaire', label: 'Commentaire', type: 'textarea' },
        { key: 'visa_encadrant', label: 'Visa encadrant', type: 'textarea' },


        // Séparateur pour les actions existantes
        // { type: 'separator', label: 'Actions existantes' },
        // { key: 'actions-list', type: 'actions-list', label: 'Liste des actions' },

        // // Nouveau commentaire
        // { key: 'new_comment', label: 'Ajouter un nouveau commentaire', type: 'textarea' },

        // // Séparateur pour ajouter une action
        // { type: 'separator', label: 'Ajouter une action' },
        //  { type: 'separator', label: 'Actions existantes' },
        { key: 'separator-actions', type: 'separator', label: 'Actions existantes' },
        { key: 'actions-list', type: 'actions-list', label: 'Liste des actions' },
        { key: 'separator-comments', type: 'separator', label: 'Commentaires existants' },
        { key: 'commentaires-list', type: 'commentaires-list', label: 'Liste des commentaires' },
        { key: 'new_comment', label: 'Ajouter un nouveau commentaire', type: 'textarea' },
        { key: 'separator-new-action', type: 'separator', label: 'Ajouter une action' },

        {
            key: 'type_action',
            label: 'Type d\'action',
            type: 'select',
            options: ['texte_libre', 'demande_validation', 'aviser', 'informer']
        },
        {
        key: 'destinataires',
        label: 'Destinataires',
        type: 'select-multiple-searchable',
        condition: (currentEvent) => ['demande_validation', 'aviser', 'informer'].includes(currentEvent.type_action)
         },
            {
            key: 'message_personnalise',
            label: 'Message personnalisé',
            type: 'textarea',
            condition: (currentEvent) => currentEvent.type_action && currentEvent.type_action !== ''
        },
        {
            key: 'piece_jointe',
            label: 'Pièce jointe',
            type: 'file'
        }
    ],

    CIV: [
        { key: 'dateHeure', label: 'Date et heure', type: 'datetime-local' },
      { key: 'nature', label: 'Nature de l\'événement', type: 'select-searchable' },
        { key: 'localisation', label: 'Localisation', type: 'select-searchable' },
        { key: 'description', label: 'Description', type: 'textarea' },
        { key: 'consequence', label: 'Conséquence sur le PDT', type: 'select', options: ['OUI', 'NON'] },
        { key: 'redacteur', label: 'Rédacteur', type: 'text', readonly: true },
        { key: 'statut', label: 'Statut', type: 'select', options: ['En cours', 'Terminé', 'Archivé'] },
        { key: 'confidentialite', label: 'Confidentialité', type: 'select', options: ['Confidentiel', 'Non confidentiel'] },
        { key: 'commentaire', label: 'Commentaire', type: 'textarea' },

        // { type: 'separator', label: 'Actions existantes' },
        // { key: 'actions-list', type: 'actions-list', label: 'Liste des actions' },
        // { key: 'new_comment', label: 'Ajouter un nouveau commentaire', type: 'textarea' },
        // { type: 'separator', label: 'Ajouter une action' },

        { key: 'separator-actions', type: 'separator', label: 'Actions existantes' },
        { key: 'actions-list', type: 'actions-list', label: 'Liste des actions' },
        { key: 'separator-comments', type: 'separator', label: 'Commentaires existants' },
        { key: 'commentaires-list', type: 'commentaires-list', label: 'Liste des commentaires' },
        { key: 'new_comment', label: 'Ajouter un nouveau commentaire', type: 'textarea' },
        { key: 'separator-new-action', type: 'separator', label: 'Ajouter une action' },


        { key: 'type_action', label: 'Type d\'action', type: 'select', options: ['texte_libre', 'demande_validation', 'aviser', 'informer'] },
         {
        key: 'destinataires',
        label: 'Destinataires',
        type: 'select-multiple-searchable',
        condition: (currentEvent) => ['demande_validation', 'aviser', 'informer'].includes(currentEvent.type_action)
         },
        {
            key: 'message_personnalise',
            label: 'Message personnalisé',
            type: 'textarea',
            condition: (currentEvent) => currentEvent.type_action && currentEvent.type_action !== ''
        },
       {
            key: 'piece_jointe',
            label: 'Pièce jointe',
            type: 'file'
        }
    ],

    HOTLINE: [
        { key: 'dateHeure', label: 'Date et heure', type: 'datetime-local' },
       { key: 'nature', label: 'Nature de l\'événement', type: 'select-searchable' },
        { key: 'localisation', label: 'Localisation', type: 'select-searchable' },
        { key: 'description', label: 'Description', type: 'textarea' },
        { key: 'consequence', label: 'Conséquence sur le PDT', type: 'select', options: ['OUI', 'NON'] },
        { key: 'redacteur', label: 'Rédacteur', type: 'text', readonly: true },
        { key: 'statut', label: 'Statut', type: 'select', options: ['En cours', 'Terminé', 'Archivé'] },
        { key: 'dateCloture', label: 'Date clôture', type: 'date' },
        { key: 'confidentialite', label: 'Confidentialité', type: 'select', options: ['Confidentiel', 'Non confidentiel'] },
        { key: 'impact', label: 'Impact', type: 'select-searchable' },
        { key: 'commentaire', label: 'Commentaire', type: 'textarea' },
        { key: 'commentaire_autre_entite', label: 'Commentaire autre entité', type: 'textarea' },

        // { type: 'separator', label: 'Actions existantes' },
        // { key: 'actions-list', type: 'actions-list', label: 'Liste des actions' },
        // { key: 'new_comment', label: 'Ajouter un nouveau commentaire', type: 'textarea' },
        // { type: 'separator', label: 'Ajouter une action' },
            { type: 'separator', label: 'Actions existantes' },
            { key: 'actions-list', type: 'actions-list', label: 'Liste des actions' },
            { key: 'separator-comments', type: 'separator', label: 'Commentaires existants' },
            { key: 'commentaires-list', type: 'commentaires-list', label: 'Liste des commentaires' },
            { key: 'new_comment', label: 'Ajouter un nouveau commentaire', type: 'textarea' },
            { key: 'separator-new-action', type: 'separator', label: 'Ajouter une action' },

        {
            key: 'type_action',
            label: 'Type d\'action',
            type: 'select',
            options: ['texte_libre', 'demande_validation', 'aviser', 'informer']
        },
        {
        key: 'destinataires',
        label: 'Destinataires',
        type: 'select-multiple-searchable',
        condition: (currentEvent) => ['demande_validation', 'aviser', 'informer'].includes(currentEvent.type_action)
         },
        {
            key: 'message_personnalise',
            label: 'Message personnalisé',
            type: 'textarea',
            condition: (currentEvent) => currentEvent.type_action && currentEvent.type_action !== ''
        },
       {
            key: 'piece_jointe',
            label: 'Pièce jointe',
            type: 'file'
        }
    ],

    CM: [
        { key: 'dateHeure', label: 'Date et heure', type: 'datetime-local' },
       { key: 'nature', label: 'Nature de l\'événement', type: 'select-searchable' },
        { key: 'localisation', label: 'Localisation', type: 'select-searchable' },
        { key: 'description', label: 'Description', type: 'textarea' },
        { key: 'consequence', label: 'Conséquence sur le PDT', type: 'select', options: ['OUI', 'NON'] },
        { key: 'redacteur', label: 'Rédacteur', type: 'text', readonly: true },
        { key: 'statut', label: 'Statut', type: 'select', options: ['En cours', 'Terminé', 'Archivé'] },
        { key: 'dateCloture', label: 'Date clôture', type: 'date' },
        { key: 'confidentialite', label: 'Confidentialité', type: 'select', options: ['Confidentiel', 'Non confidentiel'] },
        { key: 'impact', label: 'Impact', type: 'select-searchable' },
        { key: 'heureAppelIntervenant', label: 'Heure appel intervenant', type: 'datetime-local' },
        { key: 'heureArriveeIntervenant', label: 'Heure arrivée intervenant', type: 'datetime-local' },
        { key: 'commentaire', label: 'Commentaire', type: 'textarea' },
        { key: 'avis_srcof', label: 'Avis SRCOF', type: 'textarea' },


        // { type: 'separator', label: 'Actions existantes' },
        // { key: 'actions-list', type: 'actions-list', label: 'Liste des actions' },
        // { key: 'new_comment', label: 'Ajouter un nouveau commentaire', type: 'textarea' },
        // { type: 'separator', label: 'Ajouter une action' },
        { key: 'separator-actions', type: 'separator', label: 'Actions existantes' },
        { key: 'actions-list', type: 'actions-list', label: 'Liste des actions' },
        { key: 'separator-comments', type: 'separator', label: 'Commentaires existants' },
        { key: 'commentaires-list', type: 'commentaires-list', label: 'Liste des commentaires' },
        { key: 'new_comment', label: 'Ajouter un nouveau commentaire', type: 'textarea'  },
        { key: 'separator-new-action', type: 'separator', label: 'Ajouter une action' },

        {
            key: 'type_action',
            label: 'Type d\'action',
            type: 'select',
            options: ['texte_libre', 'demande_validation', 'aviser', 'informer']
        },
      {
        key: 'destinataires',
        label: 'Destinataires',
        type: 'select-multiple-searchable',
        condition: (currentEvent) => ['demande_validation', 'aviser', 'informer'].includes(currentEvent.type_action)
         },
        {
            key: 'message_personnalise',
            label: 'Message personnalisé',
            type: 'textarea',
            condition: (currentEvent) => currentEvent.type_action && currentEvent.type_action !== ''
        },
        {
            key: 'piece_jointe',
            label: 'Pièce jointe',
            type: 'file'
        }
    ],

    PTP: [
        { key: 'dateHeure', label: 'Date et heure', type: 'datetime-local' },
        { key: 'nature', label: 'Nature de l\'événement', type: 'select-searchable' },
        { key: 'description', label: 'Description', type: 'textarea' },
        { key: 'redacteur', label: 'Rédacteur', type: 'text', readonly: true },
        { key: 'statut', label: 'Statut', type: 'select', options: ['En cours', 'Terminé', 'Archivé'] },
        { key: 'dateCloture', label: 'Date clôture', type: 'date' },
        { key: 'commentaire', label: 'Commentaire', type: 'textarea' },
        { key: 'commentaire_autre_entite', label: 'Commentaire Planificateur', type: 'textarea' },

        // { type: 'separator', label: 'Actions existantes' },
        // { key: 'actions-list', type: 'actions-list', label: 'Liste des actions' },
        // { key: 'new_comment', label: 'Ajouter un nouveau commentaire', type: 'textarea' },
        // { type: 'separator', label: 'Ajouter une action' },
       { key: 'separator-actions', type: 'separator', label: 'Actions existantes' },
        { key: 'actions-list', type: 'actions-list', label: 'Liste des actions' },
        { key: 'separator-comments', type: 'separator', label: 'Commentaires existants' },
        { key: 'commentaires-list', type: 'commentaires-list', label: 'Liste des commentaires' },
        { key: 'new_comment', label: 'Ajouter un nouveau commentaire', type: 'textarea' },
        { key: 'separator-new-action', type: 'separator', label: 'Ajouter une action' },

        {
            key: 'type_action',
            label: 'Type d\'action',
            type: 'select',
            options: ['texte_libre', 'demande_validation', 'aviser', 'informer']
        },
        {
        key: 'destinataires',
        label: 'Destinataires',
        type: 'select-multiple-searchable',
        condition: (currentEvent) => ['demande_validation', 'aviser', 'informer'].includes(currentEvent.type_action)
         },
        {
            key: 'message_personnalise',
            label: 'Message personnalisé',
            type: 'textarea',
            condition: (currentEvent) => currentEvent.type_action && currentEvent.type_action !== ''
        },
    ]
},
 get filteredData() {
            const data = this.data[this.activeTab] || [];
            if (!this.searchQuery) return data;

            const query = this.searchQuery.toLowerCase();
            return data.filter(item => {
                return Object.values(item).some(value => {
                    if (value === null || value === undefined) return false;
                    return String(value).toLowerCase().includes(query);
                });
            });
        },

        get totalItems() {
            return this.filteredData.length;
        },

        get totalPages() {
            return Math.ceil(this.totalItems / this.pagination.itemsPerPage);
        },

        get paginatedData() {
            const start = (this.pagination.currentPage - 1) * this.pagination.itemsPerPage;
            const end = start + this.pagination.itemsPerPage;
            return this.filteredData.slice(start, end);
        },

        get paginationInfo() {
            const start = this.totalItems === 0 ? 0 : (this.pagination.currentPage - 1) * this.pagination.itemsPerPage + 1;
            const end = Math.min(this.pagination.currentPage * this.pagination.itemsPerPage, this.totalItems);
            return {
                start: start,
                end: end,
                total: this.totalItems,
                totalOriginal: this.data[this.activeTab]?.length || 0
            };
        },
        get filteredNatures() {
            const natures = window.nature_evenements || [];
            if (!this.searchNatureQuery || this.searchNatureQuery.trim() === '') {
                return natures.map(n => n.libelle);
            }
            const query = this.searchNatureQuery.toLowerCase().trim();
            return natures.filter(nature =>
                nature.libelle.toLowerCase().includes(query)
            ).map(n => n.libelle);
        },

        get filteredLocations() {
            const locations = window.locations || [];
            if (!this.searchLocationQuery || this.searchLocationQuery.trim() === '') {
                return locations.map(l => l.libelle);
            }
            const query = this.searchLocationQuery.toLowerCase().trim();
            return locations.filter(location =>
                location.libelle.toLowerCase().includes(query)
            ).map(l => l.libelle);
        },

        get filteredImpacts() {
            const impacts = window.impacts || [];
            if (!this.searchImpactQuery || this.searchImpactQuery.trim() === '') {
                return impacts.map(i => i.libelle);
            }
            const query = this.searchImpactQuery.toLowerCase().trim();
            return impacts.filter(impact =>
                impact.libelle.toLowerCase().includes(query)
            ).map(i => i.libelle);
        },


        // NOUVEAU : Méthodes de pagination
        goToPage(page) {
            if (page >= 1 && page <= this.totalPages) {
                this.pagination.currentPage = page;
            }
        },

        nextPage() {
            if (this.pagination.currentPage < this.totalPages) {
                this.pagination.currentPage++;
            }
        },

        prevPage() {
            if (this.pagination.currentPage > 1) {
                this.pagination.currentPage--;
            }
        },

        changeItemsPerPage(newCount) {
            this.pagination.itemsPerPage = parseInt(newCount);
            this.pagination.currentPage = 1;
        },

        // NOUVEAU : Méthodes de recherche
        onSearchInput() {
            this.pagination.currentPage = 1; // Retour à la première page lors d'une recherche
        },

        clearSearch() {
            this.searchQuery = '';
            this.pagination.currentPage = 1;
        },

        // MODIFIER : Méthode getCurrentData pour utiliser les données paginées
        getCurrentData() {
            return this.data[this.activeTab] || [];
        },
        getDisplayedData() {
                return this.paginatedData;
            },

        // MODIFIER : Méthode switchTab pour réinitialiser pagination et recherche
        switchTab(tab) {
            this.activeTab = tab;
            this.editMode = false;
            this.searchQuery = '';
            this.pagination.currentPage = 1;
        },
   showNotification(type, title, message, duration = 5000) {
    this.notification = {
        show: true,
        type: type,
        title: title,
        message: message,
        duration: duration
    };

            setTimeout(() => {
                this.hideNotification();
            }, duration);
        },
        get filteredDiffusionListes() {
    const listes = window.listesDiffusion || [];
    if (!this.searchDiffusionQuery || this.searchDiffusionQuery.trim() === '') {
        return listes;
    }

    const query = this.searchDiffusionQuery.toLowerCase().trim();
    return listes.filter(liste => {
        // Recherche dans le nom de la liste
        if (liste.nom.toLowerCase().includes(query)) {
            return true;
        }

        // Recherche dans les utilisateurs de la liste
        if (liste.users && Array.isArray(liste.users)) {
            return liste.users.some(user =>
                `${user.prenom} ${user.nom}`.toLowerCase().includes(query) ||
                (user.email && user.email.toLowerCase().includes(query)) ||
                (user.entite && user.entite.nom && user.entite.nom.toLowerCase().includes(query))
            );
        }

        return false;
    });
},

get filteredDiffusionUsers() {
    const users = window.users || [];
    if (!this.searchDiffusionQuery || this.searchDiffusionQuery.trim() === '') {
        return users;
    }

    const query = this.searchDiffusionQuery.toLowerCase().trim();
    return users.filter(user => {
        return `${user.prenom} ${user.nom}`.toLowerCase().includes(query) ||
               (user.email && user.email.toLowerCase().includes(query)) ||
               (user.entite && user.entite.nom && user.entite.nom.toLowerCase().includes(query));
    });
},
selectNature(nature) {
    console.log('🎯 Sélection nature:', nature);
    this.currentEvent.nature = nature;
    this.searchNatureQuery = '';

    this.$nextTick(() => {
        console.log('Nature sélectionnée:', this.currentEvent.nature);
    });
},

selectLocation(location) {
    console.log('🎯 Sélection localisation:', location);
    this.currentEvent.localisation = location;
    this.searchLocationQuery = '';

    this.$nextTick(() => {
        console.log(' Localisation sélectionnée:', this.currentEvent.localisation);
    });
},

selectImpact(impact) {
    console.log('🎯 Sélection impact:', impact);
    this.currentEvent.impact = impact;
    this.searchImpactQuery = '';

    this.$nextTick(() => {
        console.log(' Impact sélectionné:', this.currentEvent.impact);
    });
},

//   Méthodes pour effacer les recherches
clearNatureSearch() {
    console.log('🧹 Effacement recherche nature');
    this.searchNatureQuery = '';
},

clearLocationSearch() {
    console.log('🧹 Effacement recherche localisation');
    this.searchLocationQuery = '';
},

clearImpactSearch() {
    console.log('🧹 Effacement recherche impact');
    this.searchImpactQuery = '';
},
// Méthode pour filtrer les destinataires de diffusion
filterDiffusionDestinataires() {
    console.log('🔍 Filtrage diffusion avec:', this.searchDiffusionQuery);
    // La réactivité d'Alpine.js se charge automatiquement du filtrage
    // grâce aux getters filteredDiffusionListes et filteredDiffusionUsers
},

// Effacer la recherche de diffusion
clearDiffusionSearch() {
    this.searchDiffusionQuery = '';
    console.log('🧹 Recherche de diffusion effacée');

    // Force la réactivité
    this.$nextTick(() => {
        console.log(' Recherche réinitialisée');
    });
},
// Amélioration de la méthode openDiffusionModal
openDiffusionModal(index) {
    const eventData = this.getCurrentData()[index];
    this.diffusionEvent = JSON.parse(JSON.stringify(eventData));

    //  Réinitialiser la recherche
    this.searchDiffusionQuery = '';

    this.diffusionData = {
        destinataires: [],
        message_personnalise: '',
        inclure_actions: true,
        inclure_commentaires: true
    };

    this.showDiffusionModal = true;

    console.log('📤 Modal de diffusion ouvert pour:', this.diffusionEvent);
    console.log('📊 Listes disponibles:', window.listesDiffusion?.length || 0);
    console.log('👥 Utilisateurs disponibles:', window.users?.length || 0);
},

// Amélioration de la méthode closeDiffusionModal
closeDiffusionModal() {
    this.showDiffusionModal = false;
    this.diffusionEvent = {};
    this.searchDiffusionQuery = '';
    this.diffusionData = {
        destinataires: [],
        message_personnalise: '',
        inclure_actions: true,
        inclure_commentaires: true
    };
    console.log('📤 Modal de diffusion fermée');
},

// Méthode pour basculer un destinataire de diffusion
toggleDiffusionDestinataire(destinataireId) {
    console.log('🔧 toggleDiffusionDestinataire appelé avec:', destinataireId);

    if (!Array.isArray(this.diffusionData.destinataires)) {
        this.diffusionData.destinataires = [];
    }

    const index = this.diffusionData.destinataires.indexOf(destinataireId);

    if (index > -1) {
        // Retirer si déjà sélectionné
        this.diffusionData.destinataires.splice(index, 1);
        console.log('🔄 Destinataire de diffusion retiré:', destinataireId);
    } else {
        // Ajouter si pas sélectionné
        this.diffusionData.destinataires.push(destinataireId);
        console.log('✅ Destinataire de diffusion ajouté:', destinataireId);
    }

    console.log('📋 Destinataires de diffusion actuels:', this.diffusionData.destinataires);
},

// Vérifier si un destinataire de diffusion est sélectionné
isDiffusionDestinataireSelected(destinataireId) {
    return Array.isArray(this.diffusionData.destinataires) &&
           this.diffusionData.destinataires.includes(destinataireId);
},

        hideNotification() {
            this.notification.show = false;
        },
        showSuccess(title, message) {
            this.showNotification('success', title, message);
        },

            showError(title, message) {
                this.showNotification('error', title, message, 7000);
            },

            showWarning(title, message) {
                this.showNotification('warning', title, message);
            },

            showInfo(title, message) {
                this.showNotification('info', title, message);
            },

        showLoader() {
            this.isLoading = true;
        },

        hideLoader() {
            this.isLoading = false;
        },

        getFileIcon(filename) {
    if (!filename) return '📎';

    const extension = filename.split('.').pop().toLowerCase();

    switch (extension) {
        case 'pdf':
            return '📄';
        case 'doc':
        case 'docx':
            return '📝';
        case 'xls':
        case 'xlsx':
            return '📊';
        case 'jpg':
        case 'jpeg':
        case 'png':
        case 'gif':
            return '🖼️';
        case 'zip':
        case 'rar':
            return '🗜️';
        case 'mp4':
        case 'avi':
        case 'mov':
            return '🎥';
        default:
            return '📎';
    }
},
//Methode de gestion des filtre de destinataires dans actions
get filteredListesDiffusion() {
    if (!this.searchDestinataireQuery) {
        return window.listesDiffusion || [];
    }
    const query = this.searchDestinataireQuery.toLowerCase();
    return (window.listesDiffusion || []).filter(liste =>
        liste.nom.toLowerCase().includes(query)
    );
},

get filteredUsers() {
    if (!this.searchDestinataireQuery) {
        return window.users || [];
    }
    const query = this.searchDestinataireQuery.toLowerCase();
    return (window.users || []).filter(user =>
        `${user.prenom} ${user.nom}`.toLowerCase().includes(query) ||
        (user.entite && user.entite.nom && user.entite.nom.toLowerCase().includes(query))
    );
},
filterDestinataires() {
    console.log('Filtrage avec:', this.searchDestinataireQuery);
},
getDestinataireNameById(destId) {
    if (destId.startsWith('liste_')) {
        const listeId = destId.replace('liste_', '');
        const liste = window.listesDiffusion && window.listesDiffusion.find(l => l.id == listeId);
        return liste ? `📋 ${liste.nom}` : destId;
    } else if (destId.startsWith('user_')) {
        const userId = destId.replace('user_', '');
        const user = window.users && window.users.find(u => u.id == userId);
        return user ? `👤 ${user.prenom} ${user.nom}` : destId;
    }
    return destId;
},

// MÉTHODES (continuent après la virgule)
switchTab(tab) {
    this.activeTab = tab;
    this.editMode = false;
},

        toggleEditMode() {
            this.editMode = !this.editMode;
            if (!this.editMode) {
                this.getCurrentData().forEach(item => {
                    item.editing = false;
                });
            }
        },

        toggleRowEdit(index) {
            if (this.editMode) {
                this.data[this.activeTab][index].editing = !this.data[this.activeTab][index].editing;
            }
        },

        getCurrentColumns() {
            return this.columns[this.activeTab];
        },

        getCurrentData() {
            return this.data[this.activeTab];
        },

        getCurrentFields() {
            return this.fields[this.activeTab];
        },

        getColumnKey(column) {
            const keyMap = {
                'Numéro': 'numero',
                'Date et heure': 'dateHeure',
                'Nature de l\'événement': 'nature',
                'Localisation': 'localisation',
                'Description': 'description',
                'Conséquence sur le PDT': 'consequence',
                'Rédacteur': 'redacteur',
                'Statut': 'statut',
                'Commentaire': 'commentaire',
                'Commentaire autre entité': 'commentaire_autre_entite', // VÉRIFIEZ CETTE LIGNE
                'Commentaire Planificateur': 'commentaire_autre_entite', // POUR PTP                'Action': 'action',
                'POST': 'post',
                'Pièce jointe': 'pieceJointe',
                'Type d\'élément': 'type_piece_jointe',
                'ID': 'id',
                'Description localisation': 'descriptionLocalisation',
                'Confidentialité': 'confidentialite',
                'Date': 'date',
                'Heure': 'heure',
                'Semaine': 'semaine',
                'Impact': 'impact',
                'Title': 'title',
                'Date clôture': 'dateCloture',
                'Heure appel intervenant': 'heureAppelIntervenant',
                'Heure arrivée intervenant': 'heureArriveeIntervenant',
                'Avis SRCOF': 'avis_srcof',
                'Visa encadrant': 'visa_encadrant',
            };
            return keyMap[column] || column.toLowerCase().replace(/ /g, '');
        },


        openCreateModal() {

    const nomUtilisateur = "{{ auth()->user()->prenom }} {{ auth()->user()->nom }}";
    console.log('🔍 DEBUG Rédacteur:');
console.log('- nomUtilisateur:', nomUtilisateur);
console.log('- window.user.name:', window.user.name);
console.log('- window.user.nom_complet:', window.user.nom_complet);
console.log('- auth()->user()->prenom:', "{{ auth()->user()->prenom }}");
console.log('- auth()->user()->nom:', "{{ auth()->user()->nom }}");
    this.isEditing = false;
    this.searchDestinataireQuery = '';
    this.searchNatureQuery = '';
    this.searchLocationQuery = '';
    this.searchImpactQuery = '';
    this.currentEvent = {
        redacteur: nomUtilisateur,
        post: window.user.entiteCode,
        statut: 'En cours',
        type_action: '',
        destinataires: [],
        message_personnalise: '',
        originalData: {
            actions: [],
            commentaires: []
        }
    };
    this.showModal = true;

    // Force la réactivité
    this.$nextTick(() => {
        if (!Array.isArray(this.currentEvent.destinataires)) {
            this.currentEvent.destinataires = [];
        }
    });
},

     openEditModal(index) {
    this.isEditing = true;
    this.searchDestinataireQuery = '';
    this.currentEditIndex = index;
      this.searchNatureQuery = '';
    this.searchLocationQuery = '';
    this.searchImpactQuery = '';
    const eventData = this.getCurrentData()[index];

    // Créer une copie profonde
    const eventCopy = JSON.parse(JSON.stringify(eventData));

    // NOUVEAU - Formatter les dates pour les champs HTML
    if (eventCopy.originalData) {
        // Format pour datetime-local: YYYY-MM-DDThh:mm
        if (eventCopy.originalData.date_evenement) {
            const date = new Date(eventCopy.originalData.date_evenement);
            if (!isNaN(date)) {
                // Format YYYY-MM-DDThh:mm
                eventCopy.dateHeure = date.toISOString().substring(0, 16);
                console.log('Date formatée:', eventCopy.dateHeure);
            }
        }

        // Pour dateCloture (format date: YYYY-MM-DD)
        if (eventCopy.originalData.date_cloture) {
            const date = new Date(eventCopy.originalData.date_cloture);
            if (!isNaN(date)) {
                // Format YYYY-MM-DD
                eventCopy.dateCloture = date.toISOString().split('T')[0];
            }
        }

        // Pour heureAppelIntervenant
        if (eventCopy.originalData.heure_appel_intervenant) {
            const date = new Date(eventCopy.originalData.heure_appel_intervenant);
            if (!isNaN(date)) {
                eventCopy.heureAppelIntervenant = date.toISOString().substring(0, 16);
            }
        }

        // Pour heureArriveeIntervenant
        if (eventCopy.originalData.heure_arrive_intervenant) {
            const date = new Date(eventCopy.originalData.heure_arrive_intervenant);
            if (!isNaN(date)) {
                eventCopy.heureArriveeIntervenant = date.toISOString().substring(0, 16);
            }
        }
    }

    // S'assurer que l'originalData existe
    if (!eventCopy.originalData) {
        eventCopy.originalData = {};
    }
    // S'assurer que les actions sont un tableau même si c'est vide
    if (!eventCopy.originalData.actions) {
        eventCopy.originalData.actions = [];
    }

    // NOUVEAU : S'assurer que les commentaires sont un tableau même si c'est vide
    if (!eventCopy.originalData.commentaires) {
        eventCopy.originalData.commentaires = [];
    }

    // NOUVEAU : Si les commentaires sont dans un autre format, les convertir
    if (eventCopy.originalData.commentaires && Array.isArray(eventCopy.originalData.commentaires)) {
        eventCopy.originalData.commentaires = eventCopy.originalData.commentaires.map(comment => {
            // S'assurer que chaque commentaire a le bon format
            if (typeof comment === 'string') {
                return {
                    id: Date.now() + Math.random(),
                    text: comment,
                    created_at: new Date().toISOString(),
                    auteur_id: window.user.id
                };
            }
            // S'assurer que le champ 'text' existe
            if (!comment.text && comment.commentaire) {
                comment.text = comment.commentaire;
            }
            return comment;
        });
    }

    // Assigner l'objet complet en une seule fois pour une meilleure réactivité
    this.currentEvent = eventCopy;


    // Debug pour vérifier les dates formatées
    console.log('DEBUG DATES:', {
        dateHeure: this.currentEvent.dateHeure,
        dateCloture: this.currentEvent.dateCloture,
        heureAppelIntervenant: this.currentEvent.heureAppelIntervenant,
        heureArriveeIntervenant: this.currentEvent.heureArriveeIntervenant
    });

    this.showModal = true;
},
        EditEvent(evenement = null) {
    this.resetParams();
    if (!evenement || typeof evenement !== 'object') {
        console.warn('Aucun événement à éditer ou type invalide');
        return;
    }

    console.log('Édition de l\'événement:', evenement);

    try {
        const evt = JSON.parse(JSON.stringify(evenement));

        // Assignation des données de base
        this.params = {
            id: evt.id || '',
            dateHeure: evt.date_evenement || '',
            nature: evt.nature_evenement ? evt.nature_evenement.libelle : '',
            localisation: evt.location ? evt.location.libelle : '',
            description: evt.description || '',
            consequence: evt.consequence_sur_pdt == 1 ? 'OUI' : evt.consequence_sur_pdt == 0 ? 'NON' : '',
            redacteur: evt.redacteur || '',
            statut: evt.statut === 'en_cours' ? 'En cours' : evt.statut === 'cloture' ? 'Terminé' : '',
            confidentialite: evt.confidentialite == 1 ? 'Confidentiel' : evt.confidentialite == 0 ? 'Non confidentiel' : '',
            impact: evt.impact && evt.impact.libelle ? evt.impact.libelle : '',
            commentaire: evt.commentaires && evt.commentaires.length ? evt.commentaires.map(c => c.text).join(', ') : '',
            commentaire_autre_entite: evt.commentaire_autre_entite || '', // AJOUTÉ
            piece_jointe: evt.piece_jointe || '',

            // Gestion spéciale des actions
            actions: this.uniqueActions(evt.actions || []),

            // Champs pour nouvelles actions
            type_action: '',
            destinataires: [],
            message_personnalise: '',
            new_comment: ''
        };

        this.isEditing = true;
        this.showModal = true;

    } catch (error) {
        console.error('Erreur lors de l\'édition de l\'événement:', error);
    }
},
uniqueActions(actions) {
    if (!Array.isArray(actions)) {
        console.warn('Actions n\'est pas un tableau:', actions);
        return [];
    }

    const seen = new Set();
    return actions
        .filter(action => {
            if (!action || !action.id) return false;
            if (seen.has(action.id)) return false;
            seen.add(action.id);
            return true;
        })
        .sort((a, b) => new Date(b.created_at) - new Date(a.created_at)); // Tri par date décroissante
},
        closeModal() {
            this.showModal = false;
            this.currentEvent = {};
            this.currentEditIndex = null;
        },
resetParams() {
    this.params = {
        id: '',
        dateHeure: '',
        nature: '',
        localisation: '',
        description: '',
        consequence: '',
        redacteur: '',
        statut: '',
        confidentialite: '',
        impact: '',
        commentaire: '',
        actions: [],
        type_action: '',
        destinataires: [],
        message_personnalise: '',
        new_comment: ''
    };
},
saveEvent() {
     console.log('🔍 DEBUG - currentEvent.destinataires:', this.currentEvent.destinataires);
    console.log('🔍 DEBUG - type_action:', this.currentEvent.type_action);
     if (!this.currentEvent.description || this.currentEvent.description.trim() === '') {
        this.showError('❌ Erreur', 'La description ne peut pas être vide');
        return; // Bloquer la soumission
    }
  if (!this.currentEvent.dateHeure || this.currentEvent.dateHeure.trim() === '') {
        this.showError('❌ Erreur', 'La date ne peut pas être vide');
        return; // Bloquer la soumission
    }
     if (!this.currentEvent.nature || this.currentEvent.nature.trim() === '') {
        this.showError('❌ Erreur', 'Choisissez une nature pour cet évè,ement');
        return; // Bloquer la soumission
    }
    this.showInfo('🔄 Traitement', this.isEditing ? 'Mise à jour en cours...' : 'Création en cours...');

    if (this.currentEvent.type_action && ['demande_validation', 'aviser', 'informer'].includes(this.currentEvent.type_action)) {
        if (!this.currentEvent.destinataires || this.currentEvent.destinataires.length === 0) {
            this.showError('❌ Erreur', 'Veuillez sélectionner au moins un destinataire pour cette action');
            return;
        }
        console.log('✅ Destinataires validés pour l\'action:', this.currentEvent.destinataires);
    }
    if (this.isEditing) {
        // Ajout d'une nouvelle action si demandée
        if (this.currentEvent.type_action && this.currentEvent.type_action !== '') {
    let commentaire = '';
    const destinataires = this.getDestinataireNamesFromIds(this.currentEvent.destinataires).join(', ');

    switch (this.currentEvent.type_action) {
        case 'texte_libre':
            commentaire = this.currentEvent.message_personnalise || 'Action libre';
            break;
        case 'demande_validation':
            commentaire = `Demande de validation à ${destinataires}`;
            break;
        case 'aviser':
            commentaire = `${destinataires} avisé`;
            break;
        case 'informer':
            commentaire = `${destinataires} informé`;
            break;
    }

            const newAction = {
                id: Date.now(),
                commentaire,
                type: this.currentEvent.type_action,
                message: this.currentEvent.message_personnalise || '',
                created_at: new Date().toISOString(),
                evenement_id: this.currentEvent.id || this.currentEvent.originalData?.id,
                auteur_id: window.user.id,
                 destinataires_metadata: this.currentEvent.destinataires || []
            };

            if (!this.currentEvent.originalData) {
                this.currentEvent.originalData = {};
            }
            if (!this.currentEvent.originalData.actions) {
                this.currentEvent.originalData.actions = [];
            }
            this.currentEvent.originalData.actions.push(newAction);

            // Réinitialisation des champs d'action
            this.currentEvent.type_action = '';
            this.currentEvent.message_personnalise = '';
            this.currentEvent.destinataires = [];
        }

        // Ajout d'un nouveau commentaire si présent
        if (this.currentEvent.new_comment && this.currentEvent.new_comment.trim() !== '') {
            const newComment = {
                id: Date.now(),
                text: this.currentEvent.new_comment.trim(),
                redacteur: window.user.id,
                auteur: { nom: window.user.name, prenom: window.user.name },
                created_at: new Date().toISOString(),
                editing: false
            };
            if (!this.currentEvent.originalData.commentaires) {
                this.currentEvent.originalData.commentaires = [];
            }
            this.currentEvent.originalData.commentaires.push(newComment);
            this.currentEvent.new_comment = '';
        }

        // Mise à jour en base
        this.updateEventInDatabase(this.currentEvent, this.currentEditIndex);
    } else {
        // Création d'un nouvel événement
        this.createEventInDatabase(this.currentEvent);
    }

    this.closeModal();
},

handleFileUpload(event, key) {
    const file = event.target.files[0];
    if (file) {
        this.currentEvent[key] = file; // ✅ Tu stockes bien le fichier dans ton objet
    }
},

updateEventInDatabase(event, index) {
    // Validation des paramètres
    if (!event) {
        this.showError('❌ Erreur', 'Données d\'événement manquantes');
        return;
    }

    const eventId = event.id || (event.originalData && event.originalData.id);

    if (!eventId) {
        this.showError('❌ Erreur', 'ID d\'événement manquant');
        console.error('Événement sans ID:', event);
        return;
    }

    console.log('🔧 updateEventInDatabase - ID:', eventId, 'Index:', index);

    this.showLoader();

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    if (!csrfToken) {
        this.hideLoader();
        this.showError('❌ Erreur', 'Token CSRF manquant');
        return;
    }

    const data = this.prepareEventData(event);
    console.log('🔍 DEBUG impact reçu:', event.impact);

    // Debug des données préparées
    console.log('📦 Données à envoyer:', data);

    // Préparer FormData
    const formData = new FormData();

    for (const key in data) {
        if (key === 'destinataires') {
            // Toujours sérialiser les destinataires
            formData.append('destinataires', JSON.stringify(data[key]));
        } else if (Array.isArray(data[key])) {
            formData.append(key, JSON.stringify(data[key]));
        } else if (data[key] instanceof File) {
            formData.append(key, data[key]);
        } else if (data[key] !== null && typeof data[key] === 'object') {
            formData.append(key, JSON.stringify(data[key]));
        } else if (data[key] !== undefined && data[key] !== null) {
            formData.append(key, data[key]);
        }
    }

    // Debug FormData
    console.log('📋 FormData préparé:');
    for (let [key, value] of formData.entries()) {
        console.log(`  ${key}:`, value);
    }

    fetch(`/evenements/${eventId}?_method=PUT`, {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => {
        console.log('📡 Réponse reçue:', response.status, response.statusText);

        // Gérer les réponses non-JSON
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            return response.text().then(text => {
                console.error('❌ Réponse non-JSON:', text.substring(0, 500));
                throw new Error('Le serveur a retourné une réponse HTML au lieu de JSON. Vérifiez les logs du serveur.');
            });
        }

        return response.json().then(jsonData => {
            if (!response.ok) {
                console.error('❌ Erreur serveur:', jsonData);
                throw new Error(jsonData.message || `Erreur ${response.status}: ${response.statusText}`);
            }
            return jsonData;
        });
    })
    .then((data) => {
        console.log('✅ Succès mise à jour:', data);
        this.hideLoader();
        this.showSuccess('✅ Modifié !', 'Événement mis à jour avec succès !');

        // Mettre à jour les données locales
        if (typeof index === 'number' && index >= 0) {
            this.updateLocalEventData(data.evenement || data, index);
        }
    })
    .catch(error => {
        console.error('❌ Erreur complète:', error);
        this.hideLoader();
        this.showError('❌ Erreur', `Erreur lors de la mise à jour: ${error.message}`);
    });
},

      getDestinataireNames() {
    const noms = [];
    if (!this.diffusionData.destinataires) return noms;

    this.diffusionData.destinataires.forEach(destinataireId => {
        console.log('Processing destinataire:', destinataireId); // DEBUG

        if (destinataireId.startsWith('liste_')) {
            const listeId = destinataireId.replace('liste_', '');
            const liste = window.listesDiffusion && window.listesDiffusion.find(l => l.id == listeId);
            if (liste) {
                noms.push(`📋 ${liste.nom}`);
            }
        } else if (destinataireId.startsWith('user_')) {
            const userId = destinataireId.replace('user_', '');
            const user = window.users && window.users.find(u => u.id == userId);
            if (user) {
                noms.push(`👤 ${user.prenom} ${user.nom}`);
            }
        }
    });

    console.log('Noms générés:', noms); // DEBUG
    return noms;
},
getDestinataireNamesFromIds(destinataireIds) {
    const noms = [];
    if (!destinataireIds || !Array.isArray(destinataireIds)) return noms;

    destinataireIds.forEach(destinataireId => {
        if (destinataireId.startsWith('liste_')) {
            const listeId = destinataireId.replace('liste_', '');
            const liste = window.listesDiffusion && window.listesDiffusion.find(l => l.id == listeId);
            if (liste) {
                noms.push(`📋 ${liste.nom}`);
            }
        } else if (destinataireId.startsWith('user_')) {
            const userId = destinataireId.replace('user_', '');
            const user = window.users && window.users.find(u => u.id == userId);
            if (user) {
                noms.push(`👤 ${user.prenom} ${user.nom}`);
            }
        }
    });
    return noms;
},

      getDestinataireEmails(destinataires) {
    if (!destinataires || !Array.isArray(destinataires)) {
        return [];
    }

    const emails = [];
    destinataires.forEach(destinataireId => {
        try {
            if (typeof destinataireId === 'string' && destinataireId.startsWith('liste_')) {
                // C'est une liste de diffusion - ajouter tous les emails des utilisateurs
                const listeId = destinataireId.replace('liste_', '');
                const liste = window.listesDiffusion && window.listesDiffusion.find(l => l.id == listeId) || null;

                if (liste && liste.utilisateurs) {
                    liste.utilisateurs.forEach(user => {
                        if (user.email) {
                            emails.push(user.email);
                        }
                    });
                }
            } else if (typeof destinataireId === 'string' && destinataireId.startsWith('user_')) {
                // C'est un utilisateur individuel
                const userId = destinataireId.replace('user_', '');
                const utilisateur = window.utilisateurs?.find(u => u.id == userId);
                if (utilisateur && utilisateur.email) {
                    emails.push(utilisateur.email);
                }
            }
        } catch (error) {
            console.error('Erreur lors du traitement du destinataire:', destinataireId, error);
        }
    });

    return emails;
},
// Activer/désactiver le mode édition d'une action
toggleActionEdit(actionIndex) {
    if (!this.currentEvent?.originalData?.actions || actionIndex < 0 || actionIndex >= this.currentEvent.originalData.actions.length) {
        this.showError('❌ Erreur', 'Action introuvable');
        return;
    }

    const action = this.currentEvent.originalData.actions[actionIndex];

    // Basculer le mode édition
    action.editing = !action.editing;

    // Si on entre en mode édition, sauvegarder l'état original
    if (action.editing && !action.originalState) {
        action.originalState = {
            type: action.type,
            commentaire: action.commentaire,
            message: action.message
        };
    }

    // Si on sort du mode édition sans sauvegarder, restaurer l'état original
    if (!action.editing && action.originalState) {
        action.type = action.originalState.type;
        action.commentaire = action.originalState.commentaire;
        action.message = action.originalState.message;
        delete action.originalState;
    }

    console.log('Mode édition action:', action.editing ? 'activé' : 'désactivé');
},
toggleDestinataire(destinataireId) {
    console.log('🔧 toggleDestinataire appelé avec:', destinataireId);

    // S'assurer que destinataires est un array
    if (!Array.isArray(this.currentEvent.destinataires)) {
        console.log('⚠️ destinataires n\'est pas un array, initialisation...');
        this.currentEvent.destinataires = [];
    }

    const index = this.currentEvent.destinataires.indexOf(destinataireId);

    if (index > -1) {
        // Si déjà sélectionné, le retirer
        this.currentEvent.destinataires.splice(index, 1);
        console.log('🔄 Destinataire retiré:', destinataireId);
    } else {
        // Si pas sélectionné, l'ajouter
        this.currentEvent.destinataires.push(destinataireId);
        console.log('✅ Destinataire ajouté:', destinataireId);
    }

    console.log('📋 Destinataires actuels:', this.currentEvent.destinataires);

    // Force la réactivité d'Alpine.js
    this.$nextTick(() => {
        console.log('🔄 Après nextTick, destinataires:', this.currentEvent.destinataires);
    });
},

// Méthode pour vérifier si un destinataire est sélectionné
isDestinataireSelected(destinataireId) {
    return Array.isArray(this.currentEvent.destinataires) &&
           this.currentEvent.destinataires.includes(destinataireId);
},
// Sauvegarder les modifications d'une action
saveActionEdit(actionIndex) {
    if (!this.currentEvent?.originalData?.actions || actionIndex < 0 || actionIndex >= this.currentEvent.originalData.actions.length) {
        this.showError('❌ Erreur', 'Action introuvable');
        return;
    }

    const action = this.currentEvent.originalData.actions[actionIndex];

    // Validation
    if (!action.commentaire || action.commentaire.trim() === '') {
        this.showError('❌ Erreur', 'Le commentaire est obligatoire');
        return;
    }

    // Confirmer la modification
    if (!confirm('Êtes-vous sûr de vouloir modifier cette action ?')) {
        return;
    }

    this.showLoader();

    // Si l'action a un ID réel (pas temporaire), la modifier en base
    if (action.id && action.id < 1000000000000) {
        this.updateActionInDatabase(action, actionIndex);
    } else {
        // Action temporaire, juste désactiver le mode édition
        action.editing = false;
        delete action.originalState;
        this.showSuccess('✅ Modifié', 'Action modifiée (sera effective à la sauvegarde)');
    }
},

// Activer/désactiver le mode édition d'un commentaire
    toggleCommentEdit(commentIndex) {
        if (!this.currentEvent?.originalData?.commentaires || commentIndex < 0 || commentIndex >= this.currentEvent.originalData.commentaires.length) {
            this.showError('❌ Erreur', 'Commentaire introuvable');
            return;
        }

        const commentaire = this.currentEvent.originalData.commentaires[commentIndex];

        // Vérifier que l'utilisateur peut modifier ce commentaire
        // if (commentaire.redacteur != window.user.id) {
        //     this.showError('❌ Erreur', 'Vous ne pouvez modifier que vos propres commentaires');
        //     return;
        // }

        // Basculer le mode édition
        commentaire.editing = !commentaire.editing;

        // Si on entre en mode édition, sauvegarder l'état original
        if (commentaire.editing && !commentaire.originalState) {
            commentaire.originalState = {
                text: commentaire.text || ''
            };
            console.log('État original sauvegardé:', commentaire.originalState);
        }

        // Si on sort du mode édition sans sauvegarder, restaurer l'état original
        if (!commentaire.editing && commentaire.originalState) {
            commentaire.text = commentaire.originalState.text;
            delete commentaire.originalState;
        }

        console.log('Mode édition commentaire:', commentaire.editing ? 'activé' : 'désactivé', commentaire);
    },

// Sauvegarder les modifications d'un commentaire
saveCommentEdit(commentIndex) {
    if (!this.currentEvent?.originalData?.commentaires || commentIndex < 0 || commentIndex >= this.currentEvent.originalData.commentaires.length) {
        this.showError('❌ Erreur', 'Commentaire introuvable');
        return;
    }

    const commentaire = this.currentEvent.originalData.commentaires[commentIndex];

    // Validation
    if (!commentaire.text || commentaire.text.trim() === '') {
        this.showError('❌ Erreur', 'Le commentaire ne peut pas être vide');
        return;
    }

    // Confirmer la modification
    if (!confirm('Êtes-vous sûr de vouloir modifier ce commentaire ?')) {
        return;
    }

    this.showLoader();

    // Si le commentaire a un ID réel (pas temporaire), le modifier en base
    if (commentaire.id && commentaire.id < 1000000000000) {
        this.updateCommentInDatabase(commentaire, commentIndex);
    } else {
        // Commentaire temporaire, juste désactiver le mode édition
        commentaire.editing = false;
        delete commentaire.originalState;
        this.showSuccess('✅ Modifié', 'Commentaire modifié (sera effectif à la sauvegarde)');
    }
},

// Modifier un commentaire en base de données
updateCommentInDatabase(commentaire, commentIndex) {
 const csrfMeta = document.querySelector('meta[name="csrf-token"]');
 const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';

    const data = {
        text: commentaire.text.trim()
    };

    fetch(`/commentaires/${commentaire.id}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`Erreur ${response.status}: Impossible de modifier le commentaire`);
        }
        return response.json();
    })
    .then(data => {
        this.hideLoader();
        this.showSuccess('✅ Modifié', 'Commentaire modifié avec succès');

        // Désactiver le mode édition et nettoyer
        commentaire.editing = false;
        delete commentaire.originalState;

        console.log('Commentaire modifié:', data);
    })
    .catch(error => {
        this.hideLoader();
        this.showError('❌ Erreur', `Erreur lors de la modification: ${error.message}`);
        console.error('Erreur modification commentaire:', error);
    });
},

// Supprimer un commentaire
// Supprimer un commentaire
deleteComment(commentIndex) {
    if (!this.currentEvent?.originalData?.commentaires || commentIndex < 0 || commentIndex >= this.currentEvent.originalData.commentaires.length) {
        this.showError('❌ Erreur', 'Commentaire introuvable');
        return;
    }

    const commentaire = this.currentEvent.originalData.commentaires[commentIndex];

    // Confirmer la suppression
    if (!confirm(`Êtes-vous sûr de vouloir supprimer ce commentaire ?\n\n"${commentaire.text || 'Commentaire sans texte'}"`)) {
        return;
    }

    // Animation avant suppression
    const commentElement = document.querySelectorAll('.comments-list-container .bg-white.border.p-3.mb-2.rounded.shadow')[commentIndex];
    if (commentElement) {
        commentElement.style.transition = 'all 0.3s ease';
        commentElement.style.opacity = '0.5';
        commentElement.style.transform = 'translateX(-20px)';
    }

    // Supprimer après un délai pour l'animation
    setTimeout(() => {
        // Supprimer le commentaire du tableau local
        this.currentEvent.originalData.commentaires.splice(commentIndex, 1);

        // NOUVEAU : Mettre à jour le compteur de commentaires dans le tableau principal
        this.updateCommentCountInMainTable();

        // Si le commentaire a un ID réel, le supprimer de la base
        if (commentaire.id && commentaire.id < 1000000000000) {
            this.deleteCommentFromDatabase(commentaire.id);
        } else {
            this.showSuccess('✅ Supprimé', 'Commentaire supprimé (sera effectif à la sauvegarde)');
        }

        console.log('Commentaire supprimé:', commentaire);
        console.log('Commentaires restants:', this.currentEvent.originalData.commentaires.length);
    }, 300);
},
manualToggleDestinataire(destinataireId) {
    console.log('🔧 manualToggleDestinataire appelé avec:', destinataireId);

    // S'assurer que destinataires est un array
    if (!Array.isArray(this.currentEvent.destinataires)) {
        console.log('⚠️ Initialisation destinataires...');
        this.currentEvent.destinataires = [];
    }

    const index = this.currentEvent.destinataires.indexOf(destinataireId);

    if (index > -1) {
        // Retirer si déjà sélectionné
        this.currentEvent.destinataires.splice(index, 1);
        console.log('🔄 Destinataire retiré:', destinataireId);
    } else {
        // Ajouter si pas sélectionné
        this.currentEvent.destinataires.push(destinataireId);
        console.log('✅ Destinataire ajouté:', destinataireId);
    }

    console.log('📋 Destinataires actuels:', JSON.stringify(this.currentEvent.destinataires));

    // NOUVEAU : Force la réactivité Alpine.js
    this.$nextTick(() => {
        console.log('🔄 Après nextTick, destinataires:', JSON.stringify(this.currentEvent.destinataires));
    });
},
// Mettre à jour le compteur de commentaires dans le tableau principal
updateCommentCountInMainTable() {
    if (this.currentEditIndex !== null && this.currentEvent?.originalData?.commentaires) {
        const commentsCount = this.currentEvent.originalData.commentaires.length;
        const commentText = commentsCount > 0 ?
            `${commentsCount} commentaire${commentsCount > 1 ? 's' : ''}` :
            '0 commentaire';

        // Mettre à jour dans le tableau principal
        if (this.data[this.activeTab] && this.data[this.activeTab][this.currentEditIndex]) {
            this.data[this.activeTab][this.currentEditIndex].commentaire = commentText;

            // Mettre à jour aussi dans originalData pour la cohérence
            if (this.data[this.activeTab][this.currentEditIndex].originalData) {
                this.data[this.activeTab][this.currentEditIndex].originalData.commentaires = [...this.currentEvent.originalData.commentaires];
            }
        }

        console.log('Compteur de commentaires mis à jour:', commentText);
    }
},
// Supprimer un commentaire en base de données
deleteCommentFromDatabase(commentaireId) {
    this.showWarning('🗑️ Suppression', 'Suppression en cours...');

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    fetch(`/commentaires/${commentaireId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`Erreur ${response.status}: Impossible de supprimer le commentaire`);
        }
        return response.json();
    })
    .then(data => {
        this.showSuccess('✅ Supprimé', 'Commentaire supprimé avec succès');
        console.log('Commentaire supprimé de la base:', data);
    })
    .catch(error => {
        this.showError('❌ Erreur', `Erreur lors de la suppression: ${error.message}`);
        console.error('Erreur suppression commentaire:', error);
    });
},

// Modifier une action en base de données
updateActionInDatabase(action, actionIndex) {
const csrfMeta = document.querySelector('meta[name="csrf-token"]');
const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';
    const data = {
        type: action.type,
        commentaire: action.commentaire,
        message: action.message || ''
    };

    fetch(`/actions/${action.id}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`Erreur ${response.status}: Impossible de modifier l'action`);
        }
        return response.json();
    })
    .then(data => {
        this.hideLoader();
        this.showSuccess('✅ Modifié', 'Action modifiée avec succès');

        // Désactiver le mode édition et nettoyer
        action.editing = false;
        delete action.originalState;

        console.log('Action modifiée:', data);
    })
    .catch(error => {
        this.hideLoader();
        this.showError('❌ Erreur', `Erreur lors de la modification: ${error.message}`);
        console.error('Erreur modification action:', error);
    });
},

// Supprimer une action
deleteAction(actionIndex) {
    if (!this.currentEvent?.originalData?.actions || actionIndex < 0 || actionIndex >= this.currentEvent.originalData.actions.length) {
        this.showError('❌ Erreur', 'Action introuvable');
        return;
    }

    const action = this.currentEvent.originalData.actions[actionIndex];

    // Confirmer la suppression
    if (!confirm(`Êtes-vous sûr de vouloir supprimer cette action ?\n\n"${action.commentaire || 'Action sans commentaire'}"`)) {
        return;
    }

    // Animation avant suppression (optionnel)
    const actionElement = document.querySelectorAll('.actions-list-container .bg-white.border.p-3.mb-2.rounded.shadow')[actionIndex];
    if (actionElement) {
        actionElement.style.transition = 'all 0.3s ease';
        actionElement.style.opacity = '0.5';
        actionElement.style.transform = 'translateX(-20px)';
    }

    // Supprimer après un délai pour l'animation
    setTimeout(() => {
        // Supprimer l'action du tableau local
        this.currentEvent.originalData.actions.splice(actionIndex, 1);

        // NOUVEAU : Mettre à jour le compteur d'actions dans le tableau principal
        this.updateActionCountInMainTable();

        // Si l'action a un ID réel (pas temporaire), la supprimer de la base
        if (action.id && action.id < 1000000000000) {
            this.deleteActionFromDatabase(action.id);
        } else {
            this.showSuccess('✅ Supprimé', 'Action supprimée (sera effective à la sauvegarde)');
        }

        console.log('Action supprimée:', action);
        console.log('Actions restantes:', this.currentEvent.originalData.actions.length);
    }, 300);
},
updateActionCountInMainTable() {
    if (this.currentEditIndex !== null && this.currentEvent?.originalData?.actions) {
        const actionsCount = this.currentEvent.originalData.actions.length;
        const actionText = actionsCount > 0 ?
            `${actionsCount} action${actionsCount > 1 ? 's' : ''}` :
            '0 action';

        // Mettre à jour dans le tableau principal
        if (this.data[this.activeTab] && this.data[this.activeTab][this.currentEditIndex]) {
            this.data[this.activeTab][this.currentEditIndex].action = actionText;

            // Mettre à jour aussi dans originalData pour la cohérence
            if (this.data[this.activeTab][this.currentEditIndex].originalData) {
                this.data[this.activeTab][this.currentEditIndex].originalData.actions = [...this.currentEvent.originalData.actions];
            }
        }

        console.log('Compteur d\'actions mis à jour:', actionText);
    }
},
// Supprimer une action en base de données
deleteActionFromDatabase(actionId) {
    this.showWarning('🗑️ Suppression', 'Suppression en cours...');

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    fetch(`/actions/${actionId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`Erreur ${response.status}: Impossible de supprimer l'action`);
        }
        return response.json();
    })
    .then(data => {
        this.showSuccess('✅ Supprimé', 'Action supprimée avec succès');
        console.log('Action supprimée de la base:', data);
    })
    .catch(error => {
        this.showError('❌ Erreur', `Erreur lors de la suppression: ${error.message}`);
        console.error('Erreur suppression action:', error);
    });
},

        deleteItem(index) {
            if (!confirm('Êtes-vous sûr de vouloir supprimer cet événement ?')) {
                return;
            }

            // Calculer l'index réel dans la liste complète
            const realIndex = (this.pagination.currentPage - 1) * this.pagination.itemsPerPage + index;
            const eventData = this.data[this.activeTab][realIndex];

            if (!eventData?.originalData?.id && !eventData?.id) {
                this.showError('❌ Erreur', 'Impossible de supprimer : ID manquant');
                return;
            }

            // Supprimer immédiatement de la liste (optimiste)
            this.data[this.activeTab].splice(realIndex, 1);

            // Puis supprimer de la base de données
            const eventId = eventData.originalData?.id || eventData.id;
            this.deleteEventFromDatabase(eventId);
        },

      refreshEvents(force = false) {
    if (force) {
        window.location.reload();
    } else {
        console.log('Actualisation évitée - données mises à jour localement');
    }
},

        renderActions() {
            if (!this.currentEvent?.originalData?.actions) {
                return 'Aucune action disponible';
            }

            const actions = Array.from(this.currentEvent.originalData.actions).map(action => ({
                ...action
            }));

            let html = '';
            for (let i = 0; i < actions.length; i++) {
                const action = actions[i];
                const date = action.created_at ? new Date(action.created_at).toLocaleString() : 'Date inconnue';
                const commentaire = action.commentaire || 'Aucun commentaire';
                const id = action.id || '';

                html += `
                    <div class="item action-item">
                        <div class="item-header">
                            <span class="item-date">${date}</span>
                        </div>
                        <p class="item-text">${commentaire}</p>
                        <p class="item-author">Action #${id}</p>
                    </div>
                `;
            }
            return html;
        },

updateLocalEventData(updatedEvent, index) {
    if (index === null || index < 0) {
        console.error('Index invalide pour la mise à jour locale');
        return;
    }

    // Transformer les données mises à jour au format UI
    const transformedEvent = this.transformSingleEventForUI(updatedEvent);

    // Mettre à jour l'événement dans la liste courante
    if (this.data[this.activeTab] && this.data[this.activeTab][index]) {
        // Conserver l'état d'édition et autres propriétés UI
        const currentEditingState = this.data[this.activeTab][index].editing;

        // Remplacer les données
        this.data[this.activeTab][index] = {
            ...transformedEvent,
            editing: currentEditingState
        };

        console.log('Données locales mises à jour:', this.data[this.activeTab][index]);
    }
},
transformSingleEventForUI(evt) {
    return {
        numero: evt.id || '',
        dateHeure: evt.date_evenement ?
        new Date(evt.date_evenement).toLocaleString('fr-FR', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        }) : '',
        nature: evt.nature_evenement ? evt.nature_evenement.libelle : (evt.nature_evenement_id || ''),
        localisation: evt.location ? evt.location.libelle : (evt.location_id || ''),
        description: evt.description || '',
        consequence: evt.consequence_sur_pdt == 1 ? 'OUI' : evt.consequence_sur_pdt == 0 ? 'NON' : '',
        redacteur: evt.redacteur || '',
        statut: evt.statut === 'en_cours' ? 'En cours' :
                evt.statut === 'cloture' ? 'Terminé' :
                evt.statut === 'archive' ? 'Archivé' : '',
        descriptionLocalisation: evt.location_description || '',
        dateCloture: evt.date_cloture || '',
        confidentialite: evt.confidentialite == 1 ? 'Confidentiel' : evt.confidentialite == 0 ? 'Non confidentiel' : '',
        impact: evt.impact && evt.impact.libelle ? evt.impact.libelle : (evt.impact_id ? window.impacts.find(i => i.id === evt.impact_id)?.libelle : ''),
        heureAppelIntervenant: evt.heure_appel_intervenant || '',
        heureArriveeIntervenant: evt.heure_arrive_intervenant || '',
       commentaire: evt.commentaires && evt.commentaires.length ?
         `${evt.commentaires.length} commentaire${evt.commentaires.length > 1 ? 's' : ''}` : '0 commentaire',
         commentaire_autre_entite: evt.commentaire_autre_entite || '',
        action: evt.actions && evt.actions.length ?
         `${evt.actions.length} action${evt.actions.length > 1 ? 's' : ''}` : '0 action',
        post: evt.entite ? evt.entite.code : '',
        pieceJointe: evt.piece_jointe || '',
        date: evt.date_evenement ? new Date(evt.date_evenement).toLocaleDateString('fr-FR') : '',
        heure: evt.date_evenement ? new Date(evt.date_evenement).toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' }) : '',
        semaine: evt.date_evenement ? `Semaine ${new Date(evt.date_evenement).getWeek ? new Date(evt.date_evenement).getWeek() : Math.ceil((new Date(evt.date_evenement) - new Date(new Date(evt.date_evenement).getFullYear(), 0, 1)) / 604800000)}` : '',
        title: evt.id || '',
        id: evt.id || '',
        avis_srcof: evt.avis_srcof || '',
        visa_encadrant: evt.visa_encadrant || '',

        editing: false,
        originalData: {
            ...evt,
            commentaire_autre_entite: evt.commentaire_autre_entite || '',
            commentaires: evt.commentaires ? evt.commentaires.map(comment => ({
                id: comment.id,
                text: comment.text || '',
                redacteur: comment.redacteur,
                type: comment.type,
                date: comment.date,
                created_at: comment.created_at,
                updated_at: comment.updated_at,
                auteur: comment.auteur || null,
                editing: false
            })) : [],
            actions: evt.actions ? evt.actions.map(action => ({
                ...action,
                editing: false
            })) : []
        }
    };
},createEventInDatabase(event) {
    this.showLoader();
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    const formData = new FormData();

    const eventData = this.prepareEventData(event);

    // Ajouter chaque champ à formData
    for (const key in eventData) {
        if (Array.isArray(eventData[key])) {
            eventData[key].forEach((item, index) => {
                // Si c'est un fichier
                if (item instanceof File) {
                    formData.append(`${key}[]`, item);
                } else {
                    formData.append(`${key}[${index}]`, item);
                }
            });
        } else if (eventData[key] instanceof File) {
            formData.append(key, eventData[key]);
        } else if (eventData[key] !== null && typeof eventData[key] === 'object') {
            // Pour les objets imbriqués, tu peux faire un traitement personnalisé si besoin
            formData.append(key, JSON.stringify(eventData[key]));
        } else {
            formData.append(key, eventData[key]);
        }
    }

    fetch('/evenements', {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => {
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            return response.text().then(text => {
                console.error('Réponse non-JSON reçue:', text);
                throw new Error('Réponse serveur inattendue (HTML au lieu de JSON)');
            });
        }

        if (!response.ok) {
            return response.json().then(errorData => {
                console.error('Erreur serveur:', errorData);
                throw new Error(`Erreur ${response.status}: ${errorData.message || 'Erreur inconnue'}`);
            });
        }

        return response.json();
    })
    .then((data) => {
        console.log('Succès:', data);
        this.hideLoader();
        this.showSuccess('✅ Créé !', 'Événement créé avec succès !');

        const newEvent = this.transformSingleEventForUI(data.evenement || data);
        this.data[this.activeTab].unshift(newEvent);
    })
    .catch(error => {
        console.error('Erreur complète:', error);
        this.hideLoader();
        this.showError('❌ Erreur', `Erreur lors de la création: ${error.message}`);
    });
},

        deleteEventFromDatabase(eventId) {
    this.showLoader();

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    // Requête DELETE avec les bons headers
    fetch(`/evenements/${eventId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(errorData => {
                throw new Error(errorData.message || `Erreur ${response.status}`);
            });
        }
        return response.json();
    })
    .then(data => {
        this.hideLoader();

        // CORRECTION : Toujours afficher la notification de succès
        this.showSuccess('✅ Supprimé !', 'Événement supprimé avec succès !');

        console.log('Événement supprimé:', data);
    })
    .catch(error => {
        this.hideLoader();
        this.showError('❌ Erreur', `Erreur lors de la suppression: ${error.message}`);
        console.error('Erreur suppression:', error);
    });
},
        prepareEventData(event) {
    console.log('🔍 DEBUG prepareEventData - event complet:', event);


    const data = {
        id: event.id || event.numero || null,
        description: event.description || '',
        redacteur: "{{ auth()->user()->prenom }} {{ auth()->user()->nom }}", // Force la valeur
        location_description: event.descriptionLocalisation || '',
        commentaire: event.commentaire || '',
        avis_srcof: event.avis_srcof || '',
        visa_encadrant: event.visa_encadrant,
        commentaire_autre_entite: event.commentaire_autre_entite || ''

    };

    //   Gestion sécurisée des dates
    if (event.dateHeure && event.dateHeure !== '') {
        try {
            // Vérifier si c'est déjà au bon format
            if (event.dateHeure.includes('T')) {
                // Format datetime-local (YYYY-MM-DDTHH:mm)
                data.date_evenement = event.dateHeure;
            } else {
                // Tenter de parser et reformater
                const date = new Date(event.dateHeure);
                if (!isNaN(date.getTime())) {
                    data.date_evenement = date.toISOString().slice(0, 16);
                } else {
                    console.warn('⚠️ Date invalide:', event.dateHeure);
                    data.date_evenement = null;
                }
            }
        } catch (error) {
            console.error('❌ Erreur parsing date:', error);
            data.date_evenement = null;
        }
    } else {
        data.date_evenement = null;
    }

    // Autres dates avec validation
    if (event.dateCloture && event.dateCloture !== '') {
        try {
            const date = new Date(event.dateCloture);
            if (!isNaN(date.getTime())) {
                data.date_cloture = event.dateCloture;
            }
        } catch (error) {
            console.warn('Date clôture invalide:', event.dateCloture);
        }
    }

    if (event.heureAppelIntervenant && event.heureAppelIntervenant !== '') {
        try {
            data.heure_appel_intervenant = event.heureAppelIntervenant;
        } catch (error) {
            console.warn('Heure appel invalide:', event.heureAppelIntervenant);
        }
    }

    if (event.heureArriveeIntervenant && event.heureArriveeIntervenant !== '') {
        try {
            data.heure_arrive_intervenant = event.heureArriveeIntervenant;
        } catch (error) {
            console.warn('Heure arrivée invalide:', event.heureArriveeIntervenant);
        }
    }

    //  Validation des IDs
    const natureId = this.getNatureIdByLibelle(event.nature);
    if (natureId) {
        data.nature_evenement_id = natureId;
    }

    const locationId = this.getLocationIdByLibelle(event.localisation);
    if (locationId) {
        data.location_id = locationId;
    }

    //  Gestion sécurisée du statut
    if (event.statut && event.statut !== '') {
        switch (event.statut) {
            case 'En cours':
                data.statut = 'en_cours';
                break;
            case 'Terminé':
                data.statut = 'cloture';
                break;
            case 'Archivé':
                data.statut = 'archive';
                break;
            default:
                console.warn('Statut inconnu:', event.statut);
        }
    }

    //  Gestion sécurisée de la conséquence
    if (typeof event.consequence !== 'undefined' && event.consequence !== '') {
        const val = event.consequence.toString().trim().toUpperCase();
        if (val === 'OUI') {
            data.consequence_sur_pdt = 1;
        } else if (val === 'NON') {
            data.consequence_sur_pdt = 0;
        }
    }

    //  Gestion sécurisée de la confidentialité
    if (event.confidentialite && event.confidentialite !== '') {
        if (event.confidentialite === 'Confidentiel') {
            data.confidentialite = 1;
        } else if (event.confidentialite === 'Non confidentiel') {
            data.confidentialite = 0;
        }
    }

    //  Gestion sécurisée de l'impact
    if (event.impact && event.impact !== '') {
        const impactId = this.getImpactIdByLibelle(event.impact);
        if (impactId) {
            data.impact_id = impactId;
        }
    }

    //  Gestion des fichiers
    if (event.piece_jointe instanceof File) {
        data.piece_jointe = event.piece_jointe;
    }

    //  Avis SRCOF
    if (event.avis_srcof !== undefined && event.avis_srcof !== '') {
        data.avis_srcof = event.avis_srcof;
    }

    //  Actions et commentaires (code existant)
    data.actions = [];

    if (event.type_action && event.type_action !== '') {
        let commentaire = '';
        const destinataires = event.destinataires && event.destinataires.length > 0 ?
            this.getDestinataireNamesFromIds(event.destinataires).join(', ') : '';

        switch (event.type_action) {
            case 'texte_libre':
                commentaire = event.message_personnalise || 'Action libre';
                break;
            case 'demande_validation':
                commentaire = `Demande de validation à ${destinataires}`;
                break;
            case 'aviser':
                commentaire = `${destinataires} avisé`;
                break;
            case 'informer':
                commentaire = `${destinataires} informé`;
                break;
        }

        const newAction = {
            id: Date.now(),
            commentaire: commentaire,
            type: event.type_action,
            message: event.message_personnalise || '',
            created_at: new Date().toISOString(),
            auteur_id: window.user.id,
            destinataires_metadata: event.destinataires || []
        };

        data.actions.push(newAction);
    }

    if (event.originalData?.actions && Array.isArray(event.originalData.actions)) {
        const nouvelles_actions = event.originalData.actions.filter(action =>
            action.id > 1000000000000
        );
        if (nouvelles_actions.length > 0) {
            data.actions.push(...nouvelles_actions);
        }
    }

    if (data.actions.length === 0) {
        delete data.actions;
    }

    if (event.type_action && event.type_action !== '') {
        data.type_action = event.type_action;
        data.message_personnalise = event.message_personnalise || '';
        data.destinataires = JSON.stringify(event.destinataires || []);
    }

    data.commentaires = [];

    // Ajouter le nouveau commentaire simple si présent
    if (event.new_comment && event.new_comment.trim() !== '') {
        data.new_comment = event.new_comment.trim();
        console.log('✅ Nouveau commentaire ajouté:', data.new_comment);
    }

    // ✅ NOUVEAU : Ajouter tous les commentaires temporaires (nouveaux) de originalData
    if (event.originalData?.commentaires && Array.isArray(event.originalData.commentaires)) {
        const nouveaux_commentaires = event.originalData.commentaires.filter(comment =>
            comment.id > 1000000000000 // IDs temporaires créés dans saveEvent()
        );

        if (nouveaux_commentaires.length > 0) {
            data.commentaires = nouveaux_commentaires.map(comment => ({
                text: comment.text || '',
                redacteur: comment.redacteur || window.user.id,
                type: 'Simple',
                date: comment.created_at || new Date().toISOString()
            }));
            console.log('✅ Commentaires temporaires ajoutés:', data.commentaires);
        }
    }

    // Si aucun commentaire, supprimer la propriété
    if (data.commentaires.length === 0) {
        delete data.commentaires;
    }

     console.log('🔍 DEBUG - Données finales à envoyer:');
    console.log('  - event.redacteur:', event.redacteur);
    console.log('  - window.user.nom_complet:', window.user.nom_complet);
    console.log('  - data.redacteur final:', data.redacteur);
    console.log('📦 Données préparées pour envoi:', data);
    return data;
},

openDiffusionModal(index) {
    const eventData = this.getCurrentData()[index];
    this.diffusionEvent = JSON.parse(JSON.stringify(eventData));

    this.searchDiffusionQuery = '';
    this.diffusionData = {
        destinataires: [], // ← Force un array vide
        message_personnalise: '',
        inclure_actions: true,
        inclure_commentaires: true
    };

    this.showDiffusionModal = true;
    console.log('Modal de diffusion ouvert pour:', this.diffusionEvent);
},

// Ajouter la méthode pour la diffusion
toggleDiffusionDestinataire(destinataireId) {
    if (!Array.isArray(this.diffusionData.destinataires)) {
        this.diffusionData.destinataires = [];
    }

    const index = this.diffusionData.destinataires.indexOf(destinataireId);

    if (index > -1) {
        this.diffusionData.destinataires.splice(index, 1);
    } else {
        this.diffusionData.destinataires.push(destinataireId);
    }
},

isDiffusionDestinataireSelected(destinataireId) {
    return Array.isArray(this.diffusionData.destinataires) &&
           this.diffusionData.destinataires.includes(destinataireId);
},

closeDiffusionModal() {
    this.showDiffusionModal = false;
    this.diffusionEvent = {};
    this.diffusionData = {
        destinataires: [],
        message_personnalise: '',
        inclure_actions: true,
        inclure_commentaires: true
    };
},

getDestinataireCount() {
    let count = 0;
    if (!this.diffusionData.destinataires) return 0;

    this.diffusionData.destinataires.forEach(destinataireId => {
        if (destinataireId.startsWith('liste_')) {
            const listeId = destinataireId.replace('liste_', '');
            // CORRECTION ICI
            const liste = window.listesDiffusion && window.listesDiffusion.find(l => l.id == listeId);
            if (liste && liste.users) {
                count += liste.users.length;
            }
        } else if (destinataireId.startsWith('user_')) {
            count += 1;
        }
    });
    return count;
},

// CORRIGER la méthode getDestinataireNames
getDestinataireNames() {
    const noms = [];
    if (!this.diffusionData.destinataires) return noms;

    this.diffusionData.destinataires.forEach(destinataireId => {
        if (destinataireId.startsWith('liste_')) {
            const listeId = destinataireId.replace('liste_', '');
            // CORRECTION ICI
            const liste = window.listesDiffusion && window.listesDiffusion.find(l => l.id == listeId);
            if (liste) {
                noms.push(`📋 ${liste.nom}`);
            }
        } else if (destinataireId.startsWith('user_')) {
            const userId = destinataireId.replace('user_', '');
            // CORRECTION ICI
            const user = window.users && window.users.find(u => u.id == userId);
            if (user) {
                noms.push(`👤 ${user.prenom} ${user.nom}`);
            }
        }
    });
    return noms;
},
get filteredDiffusionListes() {
    const listes = window.listesDiffusion || [];
    if (!this.searchDiffusionQuery || this.searchDiffusionQuery.trim() === '') {
        return listes;
    }

    const query = this.searchDiffusionQuery.toLowerCase().trim();
    return listes.filter(liste => {
        // Vérifier que la liste a bien un nom
        if (!liste.nom) return false;

        // Recherche dans le nom de la liste
        if (liste.nom.toLowerCase().includes(query)) {
            return true;
        }

        // Recherche dans les utilisateurs de la liste
        if (liste.users && Array.isArray(liste.users)) {
            return liste.users.some(user => {
                // Vérifier que l'utilisateur a les propriétés nécessaires
                const prenom = user.prenom || '';
                const nom = user.nom || '';
                const email = user.email || '';
                const entiteNom = (user.entite && user.entite.nom) ? user.entite.nom : '';

                return `${prenom} ${nom}`.toLowerCase().includes(query) ||
                       email.toLowerCase().includes(query) ||
                       entiteNom.toLowerCase().includes(query);
            });
        }

        return false;
    });
},

get filteredDiffusionUsers() {
    const users = window.users || [];
    if (!this.searchDiffusionQuery || this.searchDiffusionQuery.trim() === '') {
        return users;
    }

    const query = this.searchDiffusionQuery.toLowerCase().trim();
    return users.filter(user => {
        // Vérifier que l'utilisateur a les propriétés nécessaires
        const prenom = user.prenom || '';
        const nom = user.nom || '';
        const email = user.email || '';
        const entiteNom = (user.entite && user.entite.nom) ? user.entite.nom : '';

        return `${prenom} ${nom}`.toLowerCase().includes(query) ||
               email.toLowerCase().includes(query) ||
               entiteNom.toLowerCase().includes(query);
    });
},
filterDiffusionDestinataires() {
    console.log('Filtrage diffusion avec:', this.searchDiffusionQuery);
},
getDiffusionDestinataireNameById(destId) {
    if (!destId) return 'Destinataire inconnu';

    if (destId.startsWith('liste_')) {
        const listeId = destId.replace('liste_', '');
        const liste = window.listesDiffusion && window.listesDiffusion.find(l => l.id == listeId);
        return liste && liste.nom ? `📋 ${liste.nom}` : `Liste #${listeId}`;
    } else if (destId.startsWith('user_')) {
        const userId = destId.replace('user_', '');
        const user = window.users && window.users.find(u => u.id == userId);
        if (user) {
            const prenom = user.prenom || '';
            const nom = user.nom || '';
            return `👤 ${prenom} ${nom}`.trim() || `Utilisateur #${userId}`;
        }
        return `Utilisateur #${userId}`;
    }
    return destId;
},
diffuserEvenement() {
        console.log('🔍 DEBUG - Destinataires sélectionnés:', this.diffusionData.destinataires);
             this.diffusionData.destinataires.forEach(dest => {
        if (!dest.startsWith('liste_') && !dest.startsWith('user_')) {
            console.error('❌ Format incorrect pour destinataire:', dest);
        }
    });
    if (!this.diffusionData.destinataires || this.diffusionData.destinataires.length === 0) {
        this.showError('❌ Erreur', 'Veuillez sélectionner au moins un destinataire');
        return;
    }

    if (!this.diffusionEvent || !this.diffusionEvent.originalData || !this.diffusionEvent.originalData.id) {
        this.showError('❌ Erreur', 'Impossible de diffuser : ID d\'événement manquant');
        return;
    }

    const confirmation = `Êtes-vous sûr de vouloir diffuser cet événement à ${this.getDestinataireCount()} destinataire(s) ?\n\n` +
                        `Destinataires : ${this.getDestinataireNames().join(', ')}`;

    if (!confirm(confirmation)) {
        return;
    }

    this.showLoader();

    // CORRECTION ICI
    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';
    const eventId = this.diffusionEvent.originalData.id;

    fetch(`/evenements/${eventId}/diffuser`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify(this.diffusionData)
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(errorData => {
                throw new Error(errorData.message || `Erreur ${response.status}`);
            });
        }
        return response.json();
    })
    .then(data => {
        this.hideLoader();
        this.showSuccess('📤 Diffusé !', data.message);
        this.closeDiffusionModal();
        console.log('Événement diffusé:', data);
    })
    .catch(error => {
        this.hideLoader();
        this.showError('❌ Erreur', `Erreur lors de la diffusion: ${error.message}`);
        console.error('Erreur diffusion:', error);
    });
},
saveInlineEdit(index) {
    console.log('🔧 saveInlineEdit appelé avec index:', index);

    // Utiliser directement les données paginées au lieu de recalculer
    const eventData = this.paginatedData[index];

    if (!eventData) {
        this.showError('❌ Erreur', 'Événement introuvable dans les données paginées');
        console.error('Index invalide:', index, 'Données paginées:', this.paginatedData.length);
        return;
    }

    // Trouver l'index réel dans la liste complète pour la mise à jour
    const realIndex = this.data[this.activeTab].findIndex(item =>
        (item.id && item.id === eventData.id) ||
        (item.originalData?.id && item.originalData.id === eventData.originalData?.id)
    );

    if (realIndex === -1) {
        this.showError('❌ Erreur', 'Impossible de localiser l\'événement dans les données');
        return;
    }

    console.log('🎯 Index réel trouvé:', realIndex, 'pour événement:', eventData.id);

    this.showInfo('🔄 Traitement', 'Mise à jour en cours...');
    this.updateEventInDatabase(eventData, realIndex);
},
        getNatureIdByLibelle(libelle) {
            const nature = window.nature_evenements  && window.nature_evenements.find(n => n.libelle === libelle);
            return nature ? nature.id : null;
        },

        getLocationIdByLibelle(libelle) {
            const location = window.locations && window.locations.find(l => l.libelle === libelle);
            return location ? location.id : null;
        },

        getImpactIdByLibelle(libelle) {
            const impact = window.impacts && window.impacts.find(i => i.libelle === libelle);
            return impact ? impact.id : null;
        }

    }; // Fin de l'objet retourné par mainCouranteApp

} ;// Fin de la fonction mainCouranteApp

// Ajouter la fonction getWeek à Date
Date.prototype.getWeek = function() {
    const firstDayOfYear = new Date(this.getFullYear(), 0, 1);
    const pastDaysOfYear = (this - firstDayOfYear) / 86400000;
    return Math.ceil((pastDaysOfYear + firstDayOfYear.getDay() + 1) / 7);
};

</script>


</x-layout.default>
