<script>
document.addEventListener("alpine:init", () => {
    // Initialiser d'abord l'objet miscellaneous pour qu'il soit disponible globalement
    window.miscellaneous = {
        tab: '{{ $defaultTab }}',
        editMode: false,
        attachEventListeners(tab) {
            // Événements sur les champs éditables
            document.querySelectorAll('#myTable' + tab + ' [contenteditable="true"]').forEach(el => {
                el.onblur = null;
                el.onblur = function() {
                    const field = el.getAttribute('data-field');
                    const id = el.getAttribute('data-id');
                    const value = el.innerText.trim();
                    fetch(`/evenements/${id}/update-field`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ field, value })
                    });
                };
            });

            // Événements sur les selects
            document.querySelectorAll('#myTable' + tab + ' select').forEach(el => {
                el.onchange = null;
                el.onchange = function() {
                    const field = el.getAttribute('data-field');
                    const id = el.getAttribute('data-id');
                    const value = el.value;
                    fetch(`/evenements/${id}/update-field`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ field, value })
                    });
                };
            });

            // Événements sur le bouton de changement de statut
            document.querySelectorAll('#myTable' + tab + ' .btn-toggle-statut').forEach(btn => {
                btn.onclick = null;
                btn.onclick = async (e) => {
                    e.stopPropagation();
                    const id = btn.getAttribute('data-id');
                    const evt = window['evenements' + tab]?.find(ev => ev.id == id);
                    if (!evt) return;
                    const newStatut = evt.statut === 'en_cours' ? 'cloture' : 'en_cours';
                    await fetch(`/evenements/${id}/update-field`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ field: 'statut', value: newStatut })
                    });
                    // Recharger la page après modification
                    location.reload();
                };
            });

            // Événement sur les lignes du tableau (édition)
            if (!this.editMode) {
                document.querySelectorAll('#myTable' + tab + ' tbody tr').forEach(tr => {
                    const id = tr.querySelector('td')?.innerText;
                    if (id) {
                        tr.style.cursor = 'pointer';
                        tr.onclick = (e) => {
                            if (!e.target.closest('a') && !e.target.closest('button') && e.target.tagName !== 'INPUT') {
                                // Mettre à jour pour utiliser le tableau correspondant à l'onglet actuel
                                const targetEvt = window['evenements' + tab]?.find(ev => ev.id == id);
                                if (targetEvt && window.evenementForm) {
                                    window.evenementForm.EditEvent(targetEvt);
                                } else {
                                    window.dispatchEvent(new CustomEvent('edit-evenement', {
                                        detail: {
                                            id: id,
                                            tab: tab
                                        }
                                    }));
                                }
                            }
                        };
                    }
                });
            }
        }
    };

    // Définir ensuite les comportements Alpine
    Alpine.data("evenementForm", () => ({
    addContactModal: false,
    isSubmitting: false,
    uniqueActions(actions) {
        if (!Array.isArray(actions)) return [];
        const seen = new Set();
        return actions.filter(a => {
            if (!a || !a.id) return false;
            if (seen.has(a.id)) return false;
            seen.add(a.id);
            return true;
        });
    },
    params: {
        id: null,
        statut: 'en_cours',
        type_action: '',
        message_personnalise: '',
        destinataires: [],
        commentaire: '',
        actions: [],
        date_evenement: '',
        nature_evenement_id: '',
        location_id: '',
        description: '',
        consequence_sur_pdt: '',
        impact_id: '',
        heure_appel_intervenant: '',
        heure_arrive_intervenant: '',
        post: '{{ auth()->user()->entite->nom }}',
        confidentialite: '',
        piece_jointe: ''
    },
    defaultParams() {
        return {
            id: null,
            statut: 'en_cours',
            type_action: '',
            message_personnalise: '',
            destinataires: [],
            commentaire: '',
            actions: [],
            date_evenement: '',
            nature_evenement_id: '',
            location_id: '',
            description: '',
            consequence_sur_pdt: '',
            impact_id: '',
            heure_appel_intervenant: '',
            heure_arrive_intervenant: '',
            post: '{{ auth()->user()->entite->nom }}',
            confidentialite: '',
            piece_jointe: ''
        };
    },
    init() {
        const defaults = this.defaultParams();
        Object.keys(defaults).forEach(key => {
            this.params[key] = defaults[key];
        });
        this.resetParams();

        // Rendre l'objet accessible globalement pour les interactions depuis d'autres composants
        window.evenementForm = this;

        // Gérer l'événement d'édition
        window.addEventListener('edit-evenement', (e) => {
            console.log('Événement edit-evenement reçu:', e.detail);

            try {
                if (e.detail && typeof e.detail === 'object' && e.detail.id && e.detail.tab) {
                    const targetEvt = window['evenements' + e.detail.tab]?.find(ev => ev.id == e.detail.id);
                    if (targetEvt) {
                        this.EditEvent(targetEvt);
                    } else {
                        console.warn(`Événement avec ID ${e.detail.id} non trouvé dans ${e.detail.tab}`);
                    }
                } else if (e.detail) {
                    const id = e.detail;
                    const currentTab = window.currentTab || '{{ $defaultTab }}';
                    const targetArray = window['evenements' + currentTab] || [];

                    if (Array.isArray(targetArray)) {
                        const targetEvt = targetArray.find(ev => ev.id == id);
                        if (targetEvt) {
                            this.EditEvent(targetEvt);
                        } else {
                            console.warn(`Événement avec ID ${id} non trouvé dans ${currentTab}`);
                        }
                    } else {
                        console.error(`Array evenements${currentTab} n'est pas défini ou n'est pas un tableau`);
                    }
                }
            } catch (error) {
                console.error('Erreur lors du traitement de l\'événement edit-evenement:', error);
            }
        });
    },
    resetParams() {
        const defaults = this.defaultParams();
        Object.keys(defaults).forEach(key => {
            this.params[key] = Array.isArray(defaults[key]) ? [] : defaults[key];
        });
        this.params.id = null;
        this.$nextTick(() => {
            document.querySelectorAll('input[type="file"]').forEach(input => input.value = '');
        });
        console.log('params après reset', this.params);
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

            if (evt.entite && evt.entite.code) {
                window.currentTab = evt.entite.code === 'SR COF' ? 'SRCOF'
                    : evt.entite.code === 'CIV' ? 'CIV'
                    : evt.entite.code === 'HC' ? 'HOTLINE'
                    : evt.entite.code === 'CM' ? 'CM'
                    : evt.entite.code === 'PTP' ? 'PTP'
                    : window.currentTab;
            }

            const defaults = this.defaultParams();
            Object.keys(defaults).forEach(key => {
                if (evt.hasOwnProperty(key)) {
                    if (key === 'actions' && Array.isArray(evt[key])) {
                        this.params[key] = this.uniqueActions(evt[key]);
                    } else {
                        this.params[key] = Array.isArray(defaults[key]) && Array.isArray(evt[key]) ? [...evt[key]] : evt[key];
                    }
                }
            });

            if (!Array.isArray(this.params.actions)) this.params.actions = [];

            this.addContactModal = true;

            this.$nextTick(() => {
                document.body.classList.add('overflow-hidden');
            });
        } catch (error) {
            console.error('Erreur lors de l\'édition de l\'événement:', error);
        }
    },
    closeModal() {
        this.addContactModal = false;
        document.body.classList.remove('overflow-hidden');

        setTimeout(() => {
            this.resetParams();
        }, 300);
    },
    async saveEvent() {
        if (this.isSubmitting) return;
        this.isSubmitting = true;
        let form = document.getElementById('evenement-form');
        let formData = new FormData(form);
        const submitButton = form.querySelector('button[type=submit]');
        if (submitButton) submitButton.disabled = true;
        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });
            if (response.ok) {
                this.addContactModal = false;
                alert('Événement enregistré avec succès !');
                window.location.reload();
            } else {
                const data = await response.json();
                alert('Erreur lors de la sauvegarde : ' + (data.message || 'Vérifiez les champs.'));
            }
        } catch (e) {
            alert('Erreur réseau ou serveur.');
        } finally {
            if (submitButton) submitButton.disabled = false;
            this.isSubmitting = false;
        }
    },
}));

    Alpine.data("miscellaneous", () => {
        return window.miscellaneous;
    });
});
</script>

<div x-data="evenementForm" x-init="init()" class="container mx-auto p-6">
    <div class="flex items-center justify-between w-full mb-6">

            <button type="button" class="btn btn-outline-primary p-2" :class="{ 'bg-primary text-white': editMode }" @click="editMode = !editMode; $nextTick(() => window.initTable(tab, editMode))">
            <template x-if="!editMode">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-5 h-5"><path d="M4 12h16M12 4v16" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </template>
            <template x-if="editMode">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path opacity="0.5" d="M22 10.5V12C22 16.714 22 19.0711 20.5355 20.5355C19.0711 22 16.714 22 12 22C7.28595 22 4.92893 22 3.46447 20.5355C2 19.0711 2 16.714 2 12C2 7.28595 2 4.92893 3.46447 3.46447C4.92893 2 7.28595 2 12 2H13.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path><path d="M17.3009 2.80624L16.652 3.45506L10.6872 9.41993C10.2832 9.82394 10.0812 10.0259 9.90743 10.2487C9.70249 10.5114 9.52679 10.7957 9.38344 11.0965C9.26191 11.3515 9.17157 11.6225 8.99089 12.1646L8.41242 13.9L8.03811 15.0229C7.9492 15.2897 8.01862 15.5837 8.21744 15.7826C8.41626 15.9814 8.71035 16.0508 8.97709 15.9619L10.1 15.5876L11.8354 15.0091C12.3775 14.8284 12.6485 14.7381 12.9035 14.6166C13.2043 14.4732 13.4886 14.2975 13.7513 14.0926C13.9741 13.9188 14.1761 13.7168 14.5801 13.3128L20.5449 7.34795L21.1938 6.69914C22.2687 5.62415 22.2687 3.88124 21.1938 2.80624C20.1188 1.73125 18.3759 1.73125 17.3009 2.80624Z" stroke="currentColor" stroke-width="1.5"></path><path opacity="0.5" d="M16.6522 3.45508C16.6522 3.45508 16.7333 4.83381 17.9499 6.05034C19.1664 7.26687 20.5451 7.34797 20.5451 7.34797M10.1002 15.5876L8.4126 13.9" stroke="currentColor" stroke-width="1.5"></path>
           </svg>
            </template>
            <span x-text="editMode ? 'Désactiver édition' : 'Activer édition'" class="ml-2"></span>
        </button>
        <div>
            @can('Créer événement')
            {{-- <button class="btn btn-primary ml-auto flex items-center"
            style="background-color: #67152e; border-color: #67152e; color: #fff;"
            @click="resetParams(); addContactModal = true">
            <svg width="24" height="24" ...></svg>
            Ajouter un nouvel événement
        </button> --}}
        <button class="btn btn-primary ml-auto flex items-center"
    style="background-color: #67152e; border-color: #67152e; color: #fff;"
    @click="resetParams(); addContactModal = true">
    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ltr:mr-2 rtl:ml-2">
        <path d="M4 12H20M12 4V20" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
    </svg>
    Ajouter un nouvel événement
</button>
        @endcan
    </div>
</div>


</div>
    <!-- MODAL UNIQUE -->
    <template x-if="addContactModal">
        {{-- <div class="fixed inset-0 bg-[black]/60 z-[999] flex items-center justify-center min-h-screen px-4 overflow-y-auto" @click.self="addContactModal = false">
            <div x-show="addContactModal" x-transition x-transition.duration.300
                :class="(params && typeof params === 'object' && 'id' in params && params.id !== null && params.id !== undefined)
                    ? 'panel border-0 p-10 rounded-lg overflow-hidden w-[99vw] max-w-[85vw] md:max-w-[85vw] my-8 max-h-[90vh] overflow-y-auto'
                    : 'panel border-0 p-10 rounded-lg overflow-hidden md:w-full max-w-lg w-[99%] my-8 max-h-[90vh] overflow-y-auto'">
                <button type="button"
                    class="absolute top-4 ltr:right-4 rtl:left-4 text-white-dark hover:text-dark"
                    @click="addContactModal = false">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                        stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button> --}}
                <div class="fixed inset-0 bg-[black]/60 z-[999] flex items-center justify-center min-h-screen px-4 overflow-y-auto"
         @click.self="closeModal()">
        <div x-show="addContactModal" x-transition x-transition.duration.300
            :class="(params && typeof params === 'object' && 'id' in params && params.id !== null && params.id !== undefined)
                ? 'panel border-0 p-10 rounded-lg overflow-hidden w-[99vw] max-w-[85vw] md:max-w-[85vw] my-8 max-h-[90vh] overflow-y-auto'
                : 'panel border-0 p-10 rounded-lg overflow-hidden md:w-full max-w-lg w-[99%] my-8 max-h-[90vh] overflow-y-auto'">
            <button type="button"
                class="absolute top-4 ltr:right-4 rtl:left-4 text-white-dark hover:text-dark"
                @click="closeModal()">
                <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                    stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
                <h3 class="text-lg font-medium bg-[#fbfbfb] dark:bg-[#121c2c] ltr:pl-5 rtl:pr-5 py-3 ltr:pr-[50px] rtl:pl-[50px]"
                    x-text="params && typeof params === 'object' && 'id' in params && params.id ? 'Mettre à jour l\'evenement' : 'Ajouter evenement'"></h3>
                <div class="p-5">
                  <form @submit.prevent="saveEvent"
                :action="params && typeof params === 'object' && 'id' in params && params.id ? `/evenements/${params.id}` : '/evenements/store'"
                method="POST"
                id="evenement-form">
                @csrf
                        @csrf
                        <template x-if="params && typeof params === 'object' && 'id' in params && params.id">
                        <!-- On est en mode édition -->
                            <input type="hidden" name="_method" value="PUT">
                        </template>
                        <input type="hidden" name="statut" value="en_cours">
                        <!-- EDITION : deux colonnes -->
                        <template x-if="params && typeof params === 'object' && 'id' in params && params.id">
                            <div class="flex flex-col md:flex-row gap-8 items-stretch">
                                <!-- Colonne gauche : tous les champs sauf Action et Commentaire -->
                                <div class="flex-1">
                                    <div class="mb-5">
                                        <label for="date_evenement">Date et Heure PTP</label>
                                        <input id="date_evenement" name="date_evenement" type="datetime-local" class="form-input" x-model="params.date_evenement" />
                                    </div>
                                    <div class="mb-5">
                                        <label for="nature_evenement_id">Nature de l'évènement</label>
                                        <select id="nature_evenement_id" name="nature_evenement_id" x-model="params.nature_evenement_id" class="form-select mt-1 block w-full">
                                            <option value="">Sélectionner une nature d'événement</option>
                                            @foreach ($nature_evenements as $nat)
                                                <option value="{{ $nat->id }}">{{ $nat->libelle }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    {{-- <div class="mb-5">
                                        <select id="location_id" name="location_id" x-model="params.location_id" class="form-select mt-1 block w-full">
                                            <option value="">Sélectionner une localisation</option>
                                            @foreach ($locations as $location)
                                                <option value="{{ $location->id }}">{{ $location->libelle }}</option>
                                            @endforeach
                                        </select>
                                    </div> --}}
                                    <div class="mb-5">
                                        <label for="description">Description de l'évènement</label>
                                        <textarea id="description" name="description" class="form-textarea resize-none min-h-[130px]" x-model="params.description"></textarea>
                                    </div>
                                    <!-- Liste des actions pour cet événement (édition uniquement) -->
                                    <div class="mb-5">
                                        <label class="block font-semibold mb-2">Actions pour cet événement</label>
                                        <template x-if="params && typeof params === 'object' && Array.isArray(params.actions) && params.actions.length">
                                            <ul class="space-y-2">
                                                <template x-for="action in (params && typeof params === 'object' && Array.isArray(params.actions) ? uniqueActions(params.actions) : [])" :key="action.id">
                                                    <li class="flex items-center gap-2">
                                                        <span class="inline-block px-2 py-1 rounded border border-primary bg-primary/10 text-primary text-xs font-medium"
                                                            style="background-color: #f3f6fa; border-color: #67152e; color: #67152e;"
                                                            x-text="action.commentaire"></span>
                                                        <span class="text-gray-500 text-xs" x-text="action.type"></span>
                                                    </li>
                                                </template>
                                            </ul>
                                        </template>
                                        <template x-if="!params || typeof params !== 'object' || !Array.isArray(params.actions) || !params.actions.length">
                                            <div class="text-gray-400 text-xs">Aucune action pour cet événement.</div>
                                        </template>
                                    </div>
                                    {{-- <div class="mb-5">
                                        <label for="consequence_sur_pdt">Conséquence sur le PDT</label>
                                        <select id="consequence_sur_pdt" name="consequence_sur_pdt" x-model="params.consequence_sur_pdt" class="form-select mt-1 block w-full">
                                            <option value="">Sélectionner</option>
                                            <option value="1">OUI</option>
                                            <option value="0">NON</option>
                                        </select>
                                    </div> --}}
                                    {{-- <div class="mb-5">
                                        <label for="impact_id">Impact</label>
                                        <select id="impact_id" name="impact_id" class="form-select mt-1 block w-full" x-model="params.impact_id">
                                            <option value="">Sélectionner un impact</option>
                                            @foreach ($impacts as $impact)
                                                <option value="{{ $impact->id }}" {{ old('impact_id') == $impact->id ? 'selected' : '' }}>
                                                    {{ $impact->libelle }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div> --}}
                                    {{-- <div class="mb-5">
                                        <label for="heure_appel_intervenant">Heure d'appel de l'intervenant</label>
                                        <input id="heure_appel_intervenant" type="datetime-local" name="heure_appel_intervenant" class="form-input" x-model="params.heure_appel_intervenant" />
                                    </div>
                                    <div class="mb-5">
                                        <label for="heure_arrive_intervenant">Heure d'arrivée de l'intervenant</label>
                                        <input id="heure_arrive_intervenant" type="datetime-local" name="heure_arrive_intervenant" class="form-input" x-model="params.heure_arrive_intervenant" />
                                    </div> --}}
                                    <div class="mb-5">
                                        <label for="entite">POST</label><br>
                                        <span class="inline-block px-3 py-1 rounded-full border border-primary bg-primary/10 text-primary font-semibold text-sm"
                                            style="background-color: #f3f6fa; border-color: #67152e; color: #67152e;">
                                            {{ auth()->user()->entite->nom }}
                                        </span>
                                        <input type="hidden" name="post" :value="params.post ?? '{{ auth()->user()->entite->nom }}'">
                                    </div>
                                    {{-- <div class="mb-5">
                                        <select name="confidentialite" class="form-select" x-model="params.confidentialite">
                                            <option value="1">Confidentiel</option>
                                            <option value="0">Non Confidentiel</option>
                                        </select> --}}
                                    </div>
                                    <div class="mb-5">
                                        <label for="piece_jointe">Pièce jointe</label>
                                        <input id="piece_jointe" type="file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" class="form-input" name="piece_jointe" />
                                    </div>
                                </div>

                                <div class="w-px bg-gray-500 md:my-2  md:block h-auto md:h-auto"></div>
                                <!-- Colonne droite : Action et Commentaire (édition uniquement) -->
                                <div class="flex-1">
                                    <div class="mb-5">
                                        <label for="type_action">Type d'action</label>
                                        <select id="type_action" name="type_action" x-model="params.type_action" class="form-select">
                                            <option value="">Sélectionner</option>
                                            <option value="texte_libre">Texte libre</option>
                                            <option value="demande_validation">Demande à une entité</option>
                                            <option value="aviser">Aviser une entité</option>
                                            <option value="informer">Informer une liste de diffusion</option>
                                        </select>
                                    </div>
                                    <div class="mb-5" x-show="['demande_validation','aviser','informer'].includes(params.type_action)">
                                        <label for="destinataires">Destinataires</label>
                                        <select id="destinataires" name="destinataires[]" class="form-select" multiple x-model="params.destinataires">
                                            @foreach($entites as $entite)
                                                <option value="{{ $entite->email }}">{{ $entite->nom }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-5" x-show="['texte_libre','demande_validation','aviser','informer'].includes(params.type_action)">
                                        <label for="message_personnalise">Message</label>
                                        <textarea id="message_personnalise" name="message_personnalise" class="form-textarea resize-none min-h-[130px]" x-model="params.message_personnalise"></textarea>
                                    </div>
                                    <div class="mb-5">
                                        <label for="commentaire">Ajouter Commentaire</label>
                                        <textarea id="commentaire" name="commentaire"
                                            class="form-textarea resize-none min-h-[130px]" x-model="params.commentaire"></textarea>
                                    </div>
                                </div>
                            </div>
                        </template>
                        <!-- CREATION : une seule colonne, Action et Commentaire alignés avec les autres -->
                        <template x-if="params && typeof params === 'object' && (!('id' in params) || !params.id)">
                        <!-- On est en mode création -->
                            <div>
                                <div class="mb-5">
                                    <label for="date_evenement">Date et Heure PTP</label>
                                    <input id="date_evenement" name="date_evenement" type="datetime-local" class="form-input" x-model="params.date_evenement" />
                                </div>
                                <div class="mb-5">
                                    <label for="nature_evenement_id">Nature de l'évènement</label>
                                    <select id="nature_evenement_id" name="nature_evenement_id" x-model="params.nature_evenement_id" class="form-select mt-1 block w-full">
                                        <option value="">Sélectionner une nature d'événement</option>
                                        @foreach ($nature_evenements as $nat)
                                            <option value="{{ $nat->id }}">{{ $nat->libelle }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                {{-- <div class="mb-5">
                                    <select id="location_id" name="location_id" x-model="params.location_id" class="form-select mt-1 block w-full">
                                        <option value="">Sélectionner une localisation</option>
                                        @foreach ($locations as $location)
                                            <option value="{{ $location->id }}">{{ $location->libelle }}</option>
                                        @endforeach
                                    </select>
                                </div> --}}
                                <div class="mb-5">
                                    <label for="description">Description de l'évènement</label>
                                    <textarea id="description" name="description" class="form-textarea resize-none min-h-[130px]" x-model="params.description"></textarea>
                                </div>
                                <div class="mb-5">
                                    <label for="type_action">Type d'action</label>
                                    <select id="type_action" name="type_action" x-model="params.type_action" class="form-select">
                                        <option value="">Sélectionner</option>
                                        <option value="texte_libre">Texte libre</option>
                                        <option value="demande_validation">Demande à une entité</option>
                                        <option value="aviser">Aviser une entité</option>
                                        <option value="informer">Informer une liste de diffusion</option>
                                    </select>
                                </div>
                                <div class="mb-5" x-show="['demande_validation','aviser','informer'].includes(params.type_action)">
                                    <label for="destinataires">Destinataires</label>
                                    <select id="destinataires" name="destinataires[]" class="form-select" multiple x-model="params.destinataires">
                                        @foreach($entites as $entite)
                                            <option value="{{ $entite->email }}">{{ $entite->nom }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-5" x-show="['texte_libre','demande_validation','aviser','informer'].includes(params.type_action)">
                                    <label for="message_personnalise">Message</label>
                                    <textarea id="message_personnalise" name="message_personnalise" class="form-textarea resize-none min-h-[130px]" x-model="params.message_personnalise"></textarea>
                                </div>

                                {{-- <div class="mb-5">
                                    <label for="consequence_sur_pdt">Conséquence sur le PDT</label>
                                    <select id="consequence_sur_pdt" name="consequence_sur_pdt" x-model="params.consequence_sur_pdt" class="form-select mt-1 block w-full">
                                        <option value="">Sélectionner</option>
                                        <option value="1">OUI</option>
                                        <option value="0">NON</option>
                                    </select>
                                </div>
                                <div class="mb-5">
                                    <label for="impact_id">Impact</label>
                                    <select id="impact_id" name="impact_id" class="form-select mt-1 block w-full" x-model="params.impact_id">
                                        <option value="">Sélectionner un impact</option>
                                        @foreach ($impacts as $impact)
                                            <option value="{{ $impact->id }}" {{ old('impact_id') == $impact->id ? 'selected' : '' }}>
                                                {{ $impact->libelle }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div> --}}
                                {{-- <div class="mb-5">
                                    <label for="heure_appel_intervenant">Heure d'appel de l'intervenant</label>
                                    <input id="heure_appel_intervenant" type="datetime-local" name="heure_appel_intervenant" class="form-input" x-model="params.heure_appel_intervenant" />
                                </div>
                                <div class="mb-5">
                                    <label for="heure_arrive_intervenant">Heure d'arrivée de l'intervenant</label>
                                    <input id="heure_arrive_intervenant" type="datetime-local" name="heure_arrive_intervenant" class="form-input" x-model="params.heure_arrive_intervenant" />
                                </div> --}}
                                <div class="mb-5">
                                <label for="entite">POST</label><br>
                                <span class="inline-block px-3 py-1 rounded-full border border-primary bg-primary/10 text-primary font-semibold text-sm"
                                    style="background-color: #f3f6fa; border-color: #67152e; color: #67152e;">
                                    {{ auth()->user()->entite->nom }}
                                </span>
                                <input type="hidden" name="post" :value="params.post ?? '{{ auth()->user()->entite->nom }}'">
                            </div>
                                {{-- <div class="mb-5">
                                    <select name="confidentialite" class="form-select" x-model="params.confidentialite">
                                        <option value="1">Confidentiel</option>
                                        <option value="0">Non Confidentiel</option>
                                    </select>
                                </div> --}}
                                <div class="mb-5">
                                    <label for="commentaire">Commentaire</label>
                                    <textarea id="commentaire" name="commentaire"
                                        class="form-textarea resize-none min-h-[130px]" x-model="params.commentaire"></textarea>
                                </div>
                                {{-- <div class="mb-5">
                                    <label for="piece_jointe">Pièce jointe</label>
                                    <input id="piece_jointe" type="file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" class="form-input" x-model="params.piece_jointe" />
                                </div> --}}


                            </div>
                        </template>

                       <div class="flex justify-end items-center mt-8">
                    <button type="button" class="btn btn-outline-danger"
                        @click="addContactModal = false">Annuler</button>
                    <button type="submit" class="btn btn-primary ltr:ml-4 rtl:mr-4"
                        x-text="params && typeof params === 'object' && 'id' in params && params.id ? 'Mettre à jour' : 'Ajouter'"></button>
                </div>
                    </form>
                </div>
            </div>
        </div>
    </template>

</div>
