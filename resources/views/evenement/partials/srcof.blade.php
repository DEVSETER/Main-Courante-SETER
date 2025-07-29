<x-layout.default>
<script>
    window.entites = @json($entites);
    window.nature_evenements = @json($nature_evenements);
    window.evenements = @json($evenements);
</script>

        <script src="//unpkg.com/alpinejs" defer></script>
        <script src="/assets/js/simple-datatables.js"></script>

<script>
document.addEventListener("alpine:init", () => {
    Alpine.data("evenementForm", () => ({
        uniqueActions(actions) {
            // Retourne une liste d'actions sans doublons (par id)
            if (!Array.isArray(actions)) return [];
            const seen = new Set();
            return actions.filter(a => {
                if (!a || !a.id) return false;
                if (seen.has(a.id)) return false;
                seen.add(a.id);
                return true;
            });
        },
        addContactModal: false,
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
            // Valeurs par défaut pour tous les champs
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
            // Initialisation réactive de params (sans this.$set, AlpineJS n'en a pas besoin)
            const defaults = this.defaultParams();
            Object.keys(defaults).forEach(key => {
                this.params[key] = defaults[key];
            });
            this.resetParams();
            window.addEventListener('edit-evenement', (e) => {
                const evt = window.evenements?.find(ev => ev.id == e.detail);
                if (evt) {
                    this.EditEvent(evt);
                }
            });
        },
        resetParams() {
            // On vide chaque propriété explicitement pour garder la réactivité AlpineJS
            const defaults = this.defaultParams();
            Object.keys(defaults).forEach(key => {
                this.params[key] = Array.isArray(defaults[key]) ? [] : defaults[key];
            });
            this.params.id = null;
            this.$nextTick(() => {
                // Reset tous les inputs file (si plusieurs)
                document.querySelectorAll('input[type="file"]').forEach(input => input.value = '');
            });
            // Debug : log pour vérifier le reset
            console.log('params après reset', this.params);
        },
        EditEvent(evenement = null) {
    this.resetParams();
    if (!evenement || typeof evenement !== 'object') {
        console.warn('Aucun événement à éditer ou type invalide');
        return;
    }

    const evt = JSON.parse(JSON.stringify(evenement));
    const defaults = this.defaultParams();
    Object.keys(defaults).forEach(key => {
        if (evt.hasOwnProperty(key)) {
            // Pour actions, appliquer uniqueActions
            if (key === 'actions' && Array.isArray(evt[key])) {
                this.params[key] = this.uniqueActions(evt[key]);
            } else {
                this.params[key] = Array.isArray(defaults[key]) && Array.isArray(evt[key]) ? [...evt[key]] : evt[key];
            }
        }
    });
    if (!Array.isArray(this.params.actions)) this.params.actions = [];

    this.addContactModal = true;
},

        async saveEvent() {
            let form = document.getElementById('evenement-form');
            let formData = new FormData(form);
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
            }
        }
    }));
});
</script>
         <meta name="csrf-token" content="{{ csrf_token() }}">

<div x-data="evenementForm" x-init="init()" class="container mx-auto p-6">
    <div class="flex items-center justify-between flex-wrap gap-4">
        <h2 class="text-xl">Main Courante {{ auth()->user()->entite->code }}</h2>
        <div class="flex sm:flex-row flex-col sm:items-center sm:gap-3 gap-4 w-full sm:w-auto">
            <div class="flex gap-3">
                <div>
                    <button class="btn btn-primary ml-auto flex items-center"
                        style="background-color: #67152e; border-color: #67152e; color: #fff;"
                        @click="resetParams(); addContactModal = true">
                        <svg width="24" height="24" ...></svg>
                        Ajouter un nouvel evenement
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- MODAL UNIQUE -->
    <template x-if="addContactModal">
        <div class="fixed inset-0 bg-[black]/60 z-[999] flex items-center justify-center min-h-screen px-4 overflow-y-auto" @click.self="addContactModal = false">
            <div x-show="addContactModal" x-transition x-transition.duration.300
                :class="(params && typeof params === 'object' && 'id' in params && params.id !== null && params.id !== undefined)
                    ? 'panel border-0 p-10 rounded-lg overflow-hidden w-[99vw] max-w-[85vw] md:max-w-[85vw] my-8 max-h-[90vh] overflow-y-auto'
                    : 'panel border-0 p-10 rounded-lg overflow-hidden md:w-full max-w-lg w-[99%] my-8 max-h-[90vh] overflow-y-auto'">
                <button type="button"
                    class="absolute top-4 ltr:right-4 rtl:left-4 text-white-dark hover:text-dark"
                    @click="resetParams();addContactModal = false">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                        stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
                <h3 class="text-lg font-medium bg-[#fbfbfb] dark:bg-[#121c2c] ltr:pl-5 rtl:pr-5 py-3 ltr:pr-[50px] rtl:pl-[50px]"
                    x-text="params && typeof params === 'object' && 'id' in params && params.id ? 'Mettre à jour l\'evenement' : 'Ajouter evenement SR'"></h3>
                <div class="p-5">
                    <form @submit.prevent="saveEvent"
                        :action="params && typeof params === 'object' && 'id' in params && params.id ? `/evenements/${params.id}` : '/evenements/store'" method="POST"
                        id="evenement-form">
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
                                        <label for="date_evenement">Date et Heure SR</label>
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
                                    <div class="mb-5">
                                        <select id="location_id" name="location_id" x-model="params.location_id" class="form-select mt-1 block w-full">
                                            <option value="">Sélectionner une localisation</option>
                                            @foreach ($locations as $location)
                                                <option value="{{ $location->id }}">{{ $location->libelle }}</option>
                                            @endforeach
                                        </select>
                                    </div>
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
                                    <div class="mb-5">
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
                                    </div>
                                    <div class="mb-5">
                                        <label for="heure_appel_intervenant">Heure d'appel de l'intervenant</label>
                                        <input id="heure_appel_intervenant" type="datetime-local" name="heure_appel_intervenant" class="form-input" x-model="params.heure_appel_intervenant" />
                                    </div>
                                    <div class="mb-5">
                                        <label for="heure_arrive_intervenant">Heure d'arrivée de l'intervenant</label>
                                        <input id="heure_arrive_intervenant" type="datetime-local" name="heure_arrive_intervenant" class="form-input" x-model="params.heure_arrive_intervenant" />
                                    </div>
                                    <div class="mb-5">
                                        <label for="entite">POST</label><br>
                                        <span class="inline-block px-3 py-1 rounded-full border border-primary bg-primary/10 text-primary font-semibold text-sm"
                                            style="background-color: #f3f6fa; border-color: #67152e; color: #67152e;">
                                            {{ auth()->user()->entite->nom }}
                                        </span>
                                        <input type="hidden" name="post" :value="params.post ?? '{{ auth()->user()->entite->nom }}'">
                                    </div>
                                    <div class="mb-5">
                                        <select name="confidentialite" class="form-select" x-model="params.confidentialite">
                                            <option value="1">Confidentiel</option>
                                            <option value="0">Non Confidentiel</option>
                                        </select>
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
                                    <label for="date_evenement">Date et Heure</label>
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
                                <div class="mb-5">
                                    <select id="location_id" name="location_id" x-model="params.location_id" class="form-select mt-1 block w-full">
                                        <option value="">Sélectionner une localisation</option>
                                        @foreach ($locations as $location)
                                            <option value="{{ $location->id }}">{{ $location->libelle }}</option>
                                        @endforeach
                                    </select>
                                </div>
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

                                <div class="mb-5">
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
                                </div>
                                <div class="mb-5">
                                    <label for="heure_appel_intervenant">Heure d'appel de l'intervenant</label>
                                    <input id="heure_appel_intervenant" type="datetime-local" name="heure_appel_intervenant" class="form-input" x-model="params.heure_appel_intervenant" />
                                </div>
                                <div class="mb-5">
                                    <label for="heure_arrive_intervenant">Heure d'arrivée de l'intervenant</label>
                                    <input id="heure_arrive_intervenant" type="datetime-local" name="heure_arrive_intervenant" class="form-input" x-model="params.heure_arrive_intervenant" />
                                </div>
                                <div class="mb-5">
                                <label for="entite">POST</label><br>
                                <span class="inline-block px-3 py-1 rounded-full border border-primary bg-primary/10 text-primary font-semibold text-sm"
                                    style="background-color: #f3f6fa; border-color: #67152e; color: #67152e;">
                                    {{ auth()->user()->entite->nom }}
                                </span>
                                <input type="hidden" name="post" :value="params.post ?? '{{ auth()->user()->entite->nom }}'">
                            </div>
                                <div class="mb-5">
                                    <select name="confidentialite" class="form-select" x-model="params.confidentialite">
                                        <option value="1">Confidentiel</option>
                                        <option value="0">Non Confidentiel</option>
                                    </select>
                                </div>
                                <div class="mb-5">
                                    <label for="commentaire">Commentaire</label>
                                    <textarea id="commentaire" name="commentaire"
                                        class="form-textarea resize-none min-h-[130px]" x-model="params.commentaire"></textarea>
                                </div>
                                <div class="mb-5">
                                    <label for="piece_jointe">Pièce jointe</label>
                                    <input id="piece_jointe" type="file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" class="form-input" x-model="params.piece_jointe" />
                                </div>


                            </div>
                        </template>

                        <div class="flex justify-end items-center mt-8" >
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
<div x-data="miscellaneous" x-init="init()">
    <div class="space-y-6">

        <div class="panel flex items-center justify-between overflow-x-auto whitespace-nowrap p-3 text-primary">
            <div>
                <!-- À placer avant le tableau -->
            <button
    type="button"
    class="btn btn-outline-primary p-2"
    :class="{ 'bg-primary text-white': editMode }"
    @click="editMode = !editMode; reloadTable();"
>
    <template x-if="!editMode">
        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-5 h-5">
            <path d="M4 12h16M12 4v16" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </template>
    <template x-if="editMode">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path opacity="0.5" d="M22 10.5V12C22 16.714 22 19.0711 20.5355 20.5355C19.0711 22 16.714 22 12 22C7.28595 22 4.92893 22 3.46447 20.5355C2 19.0711 2 16.714 2 12C2 7.28595 2 4.92893 3.46447 3.46447C4.92893 2 7.28595 2 12 2H13.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
            <path d="M17.3009 2.80624L16.652 3.45506L10.6872 9.41993C10.2832 9.82394 10.0812 10.0259 9.90743 10.2487C9.70249 10.5114 9.52679 10.7957 9.38344 11.0965C9.26191 11.3515 9.17157 11.6225 8.99089 12.1646L8.41242 13.9L8.03811 15.0229C7.9492 15.2897 8.01862 15.5837 8.21744 15.7826C8.41626 15.9814 8.71035 16.0508 8.97709 15.9619L10.1 15.5876L11.8354 15.0091C12.3775 14.8284 12.6485 14.7381 12.9035 14.6166C13.2043 14.4732 13.4886 14.2975 13.7513 14.0926C13.9741 13.9188 14.1761 13.7168 14.5801 13.3128L20.5449 7.34795L21.1938 6.69914C22.2687 5.62415 22.2687 3.88124 21.1938 2.80624C20.1188 1.73125 18.3759 1.73125 17.3009 2.80624Z" stroke="currentColor" stroke-width="1.5"></path>
            <path opacity="0.5" d="M16.6522 3.45508C16.6522 3.45508 16.7333 4.83381 17.9499 6.05034C19.1664 7.26687 20.5451 7.34797 20.5451 7.34797M10.1002 15.5876L8.4126 13.9" stroke="currentColor" stroke-width="1.5"></path>
        </svg>
    </template>
    <span x-text="editMode ? 'Désactiver édition' : 'Activer édition'" class="ml-2"></span>
</button>

                    </div>
                    <div>&nbsp;</div>


        </div>

        <div class="panel">
            <h5 class="md:absolute md:top-[25px] md:mb-0 mb-5 font-semibold text-lg dark:text-white-light">UTILISATEURS</h5>
            <div class="relative">
                <div class="sm:absolute sm:top-0 sm:ltr:right-56 sm:rtl:left-56 sm:mb-0 mb-5">
                    <div class="flex items-center">
                        <div class="theme-dropdown relative" x-data="{ columnDropdown: false, addContactModal: false, params: {} }" @click.outside="columnDropdown = false">
                            <a href="javascript:;" class="flex items-center border font-semibold border-[#e0e6ed] dark:border-[#253b5c] rounded-md px-4 py-2 text-sm dark:bg-[#1b2e4b] dark:text-white-dark" @click="columnDropdown = ! columnDropdown">
                                <span class="ltr:mr-1 rtl:ml-1">COLONNES</span>
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M19 9L12 15L5 9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                            </a>
                            <div class="ltr:left-0 rtl:right-0 top-11 rounded hidden absolute min-w-[150px] bg-white py-2 z-[10] text-dark dark:text-white-light dark:bg-[#1b2e4b] shadow w-[250px]" :class="columnDropdown && '!block'">
                                <ul class="font-semibold px-4 space-y-2">
                                    <template x-for="(col,i) in columns" :key="i">
                                        <li>
                                            <div>
                                                <label class="cursor-pointer">
                    <input type="checkbox" class="form-checkbox"
                        :id="`chk-${i}`"
                        :value="i"
                        @change="showHideColumns(i, $event.target.checked)"
                        :checked="columns[i].hidden" />                                                    <span :for="`chk-${i}`" class="ltr:ml-2 rtl:mr-2" x-text="col.name"></span>
                                                </label>
                                            </div>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                {{--Lis des MCs par  --}}
               <table id="myTable1" class="table-auto w-full border border-gray-200 bg-white text-sm" ></table>

            </div>
        </div>
    </div>
</div>
<script>
    window.entites = @json($entites);
    window.nature_evenements = @json($nature_evenements);
    window.locations = @json($locations);
    window.impacts = @json($impacts);

    document.addEventListener("alpine:init", () => {
        Alpine.data("miscellaneous", () => ({
            editMode: false,
            columns: [
                { name: 'Numéro', hidden: false },
                { name: 'Date et heure', hidden: false },
                { name: 'Nature de l\'événement', hidden: false },
                { name: 'Localisation', hidden: false },
                { name: 'Description', hidden: false },
                { name: 'Conséquence sur le PDT', hidden: false },
                { name: 'Rédacteur', hidden: false },
                { name: 'Statut', hidden: false },
                { name: 'Description localisation', hidden: false },
                { name: 'Date clôture', hidden: false },
                { name: 'Confidentialité', hidden: false },
                { name: 'Impact', hidden: false },
                { name: 'Heure appel intervenant', hidden: false },
                { name: 'Heure arrivée intervenant', hidden: false },
                { name: 'Commentaire', hidden: false },
                { name: 'Action', hidden: false },
                { name: 'POST', hidden: false },
                { name: 'Pièce jointe', hidden: false },
                { name: 'Actions', hidden: false },
            ],
            datatable1: null,
            evenements: @json($evenements),
            displayType: 'list',
            tableContainer: null,

            setDisplayType(type) {
                this.displayType = type;
                this.reloadTable();
            },
                    async refreshEvenements() {
                        try {
                            const response = await fetch('/evenements/json', {
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json',
                                }
                            });
                            if (response.ok) {
                                this.evenements = await response.json();
                                this.reloadTable();
                            }
                        } catch (e) {
                            console.error('Erreur lors du rafraîchissement des événements', e);
                        }
                    },

            reloadTable() {
    if (this.datatable1) {
        try {
            this.datatable1.destroy();
            this.datatable1 = null;
        } catch (e) {
            this.datatable1 = null;
        }
    }
    // Vider le contenu du tableau sans le supprimer du DOM
    const table = document.querySelector('#myTable1');
   if (table) {
    if (table.querySelector('thead')) table.querySelector('thead').innerHTML = '';
    if (table.querySelector('tbody')) table.querySelector('tbody').innerHTML = '';
}
            this.$nextTick(() => {
                setTimeout(() => {
                    this.init();
                }, 100);
            });
        },

            showHideColumns(index, checked) {
                this.columns[index].hidden = checked;
                this.reloadTable();
            },

            init() {
                const tableElement = document.querySelector('#myTable1');
                if (!tableElement) return;


                // Définition des couleurs
                let natureColors = {
                    1: 'badge badge-outline-primary',
                    2: 'badge badge-outline-success',
                    3: 'badge badge-outline-warning',
                    4: 'badge badge-outline-info',
                    5: 'badge badge-outline-danger',
                };
                let statutColors = {
                    'en_cours': 'badge badge-outline-warning',
                    'cloture': 'badge badge-outline-success',
                };
                let impactColors = {
                    1: 'badge badge-outline-primary',
                    2: 'badge badge-outline-danger',
                    3: 'badge badge-outline-info',
                };
                let locationColors = {
                    1: 'badge badge-outline-primary',
                    2: 'badge badge-outline-success',
                    3: 'badge badge-outline-warning',
                };
                let typeColors = {
                    administration: 'badge badge-outline-primary',
                    organisation: 'badge badge-outline-success',
                    exploitation: 'badge badge-outline-warning',
                    reporting: 'badge badge-outline-info',
                    communication: 'badge badge-outline-danger',
                };

                // Colonnes visibles
                let visibleColumns = this.columns.map((col, i) => ({...col, index: i})).filter(col => !col.hidden);
                let headers = visibleColumns.map(col => col.name);

                // Génération des données (UNE SEULE FOIS)
                let data = [];
                if (this.evenements && this.evenements.length > 0) {
                    data = this.evenements.map((evt) => {
                        // Filtrage des actions en doublon (par id)
                        let uniqueActions = [];
                        if (Array.isArray(evt.actions)) {
                            const seen = new Set();
                            uniqueActions = evt.actions.filter(a => {
                                if (!a || !a.id) return false;
                                if (seen.has(a.id)) return false;
                                seen.add(a.id);
                                return true;
                            });
                        }
                        let row;
                        if (this.editMode) {
                            row = [
                evt.id ?? '',
                `<span contenteditable="true" data-field="date_evenement" data-id="${evt.id}" style="white-space:nowrap;">${
                    evt.date_evenement
                        ? new Date(evt.date_evenement).toLocaleString('fr-FR', { year: 'numeric', month: '2-digit', day: '2-digit', hour: '2-digit', minute: '2-digit' })
                        : ''
                }</span>`,
                `<select class="form-select" data-field="nature_evenement_id" data-id="${evt.id}"><option value="">Sélectionner</option>${window.nature_evenements.map(n => `<option value="${n.id}" ${evt.nature_evenement_id == n.id ? 'selected' : ''} data-badge="${natureColors[n.id] || 'badge badge-outline-secondary'}">${n.libelle}</option>`).join('')}</select>`,
                `<select class="form-select" data-field="location_id" data-id="${evt.id}"><option value="">Sélectionner</option>${window.locations.map(l => `<option value="${l.id}" ${evt.location_id == l.id ? 'selected' : ''} data-badge="${locationColors[l.id] || 'badge badge-outline-secondary'}">${l.libelle}</option>`).join('')}</select>`,
                `<span contenteditable="true" data-field="description" data-id="${evt.id}">${evt.description ?? ''}</span>`,
                `<select class="form-select" data-field="consequence_sur_pdt" data-id="${evt.id}"><option value="">Sélectionner</option><option value="1" ${evt.consequence_sur_pdt == 1 ? 'selected' : ''}>OUI</option><option value="0" ${evt.consequence_sur_pdt == 0 ? 'selected' : ''}>NON</option></select>`,
                `<span contenteditable="true" data-field="redacteur" data-id="${evt.id}">${evt.redacteur ?? ''}</span>`,
                `<div style="display:flex;align-items:center;gap:8px;white-space:nowrap;">
                    ${
                        evt.statut == 'en_cours'
                            ? '<span class="badge badge-outline-success">En cours</span>'
                            : evt.statut == 'cloture'
                                ? '<span class="badge badge-outline-danger">Clôturé</span>'
                                : '<span class="badge badge-outline-secondary">Non défini</span>'
                    }
                    <button type="button" class="btn btn-sm btn-toggle-statut" data-id="${evt.id}" title="Changer le statut"
                        style="background:transparent;border:none;cursor:pointer;padding:0;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5">
                            <path d="M12.0789 3V2.25V3ZM3.67981 11.3333H2.92981H3.67981ZM3.67981 13L3.15157 13.5324C3.44398 13.8225 3.91565 13.8225 4.20805 13.5324L3.67981 13ZM5.88787 11.8657C6.18191 11.574 6.18377 11.0991 5.89203 10.8051C5.60029 10.511 5.12542 10.5092 4.83138 10.8009L5.88787 11.8657ZM2.52824 10.8009C2.2342 10.5092 1.75933 10.511 1.46759 10.8051C1.17585 11.0991 1.17772 11.574 1.47176 11.8657L2.52824 10.8009ZM18.6156 7.39279C18.8325 7.74565 19.2944 7.85585 19.6473 7.63892C20.0001 7.42199 20.1103 6.96007 19.8934 6.60721L18.6156 7.39279ZM12.0789 2.25C7.03155 2.25 2.92981 6.3112 2.92981 11.3333H4.42981C4.42981 7.15072 7.84884 3.75 12.0789 3.75V2.25ZM2.92981 11.3333L2.92981 13H4.42981L4.42981 11.3333H2.92981ZM4.20805 13.5324L5.88787 11.8657L4.83138 10.8009L3.15157 12.4676L4.20805 13.5324ZM4.20805 12.4676L2.52824 10.8009L1.47176 11.8657L3.15157 13.5324L4.20805 12.4676ZM19.8934 6.60721C18.287 3.99427 15.3873 2.25 12.0789 2.25V3.75C14.8484 3.75 17.2727 5.20845 18.6156 7.39279L19.8934 6.60721Z" fill="currentColor"></path>
                            <path opacity="0.5" d="M11.8825 21V21.75V21ZM20.3137 12.6667H21.0637H20.3137ZM20.3137 11L20.8409 10.4666C20.5487 10.1778 20.0786 10.1778 19.7864 10.4666L20.3137 11ZM18.1002 12.1333C17.8056 12.4244 17.8028 12.8993 18.094 13.1939C18.3852 13.4885 18.86 13.4913 19.1546 13.2001L18.1002 12.1333ZM21.4727 13.2001C21.7673 13.4913 22.2421 13.4885 22.5333 13.1939C22.8245 12.8993 22.8217 12.4244 22.5271 12.1332L21.4727 13.2001ZM5.31769 16.6061C5.10016 16.2536 4.63806 16.1442 4.28557 16.3618C3.93307 16.5793 3.82366 17.0414 4.0412 17.3939L5.31769 16.6061ZM11.8825 21.75C16.9448 21.75 21.0637 17.6915 21.0637 12.6667H19.5637C19.5637 16.8466 16.133 20.25 11.8825 20.25V21.75ZM21.0637 12.6667V11H19.5637V12.6667H21.0637ZM19.7864 10.4666L18.1002 12.1333L19.1546 13.2001L20.8409 11.5334L19.7864 10.4666ZM19.7864 11.5334L21.4727 13.2001L22.5271 12.1332L20.8409 10.4666L19.7864 11.5334ZM4.0412 17.3939C5.65381 20.007 8.56379 21.75 11.8825 21.75V20.25C9.09999 20.25 6.6656 18.7903 5.31769 16.6061L4.0412 17.3939Z" fill="currentColor"></path>
                        </svg>
                    </button>
                </div>`,
                `<span contenteditable="true" data-field="location_description" data-id="${evt.id}">${evt.location_description ?? ''}</span>`,
                `<span contenteditable="true" data-field="date_cloture" data-id="${evt.id}">${evt.date_cloture ? new Date(evt.date_cloture).toLocaleString('fr-FR', { year: 'numeric', month: '2-digit', day: '2-digit', hour: '2-digit', minute: '2-digit' }) : ''}</span>`,
                `<select class="form-select" data-field="confidentialite" data-id="${evt.id}"><option value="">Sélectionner</option><option value="1" ${evt.confidentialite == 1 ? 'selected' : ''}>Confidentiel</option><option value="0" ${evt.confidentialite == 0 ? 'selected' : ''}>Non confidentiel</option></select>`,
                `<select class="form-select" data-field="impact_id" data-id="${evt.id}"><option value="">Sélectionner</option>${window.impacts.map(i => `<option value="${i.id}" ${evt.impact_id == i.id ? 'selected' : ''} data-badge="${impactColors[i.id] || 'badge badge-outline-secondary'}">${i.libelle}</option>`).join('')}</select>`,
                `<span contenteditable="true" data-field="heure_appel_intervenant" data-id="${evt.id}">${evt.heure_appel_intervenant ?? ''}</span>`,
                `<span contenteditable="true" data-field="heure_arrive_intervenant" data-id="${evt.id}">${evt.heure_arrive_intervenant ?? ''}</span>`,
                `<span contenteditable="true" data-field="commentaire" data-id="${evt.id}">${evt.commentaires && evt.commentaires.length ? evt.commentaires.map(c => c.text).join(', ') : ''}</span>`,
                `<div style="display:flex;flex-wrap:wrap;gap:4px;">${uniqueActions && uniqueActions.length ? uniqueActions.filter(a => a.commentaire && a.commentaire.trim() !== '').map(a => `<span class="badge ${typeColors[a.type] || 'badge-outline-secondary'}">${a.commentaire}</span>`).join('') : ''}</div>`,
                `<span contenteditable="true" data-field="entite" data-id="${evt.id}">${evt.entite && evt.entite.nom ? evt.entite.nom : ''}</span>`,
                evt.piece_jointe ? `<a href="/storage/${evt.piece_jointe}" target="_blank">Voir</a>` : '',
                'Edition',
            ];
                        } else {
                            row = [
                evt.id ?? '',
                evt.date_evenement
                    ? `<span style="white-space:nowrap;">${new Date(evt.date_evenement).toLocaleString('fr-FR', { year: 'numeric', month: '2-digit', day: '2-digit', hour: '2-digit', minute: '2-digit' })}</span>`
                    : '',
                evt.nature_evenement ? evt.nature_evenement.libelle : (evt.nature_evenement_id ?? ''),
                evt.location ? evt.location.libelle : (evt.location_id ?? ''),
                evt.description ?? '',
                (evt.consequence_sur_pdt == 1) ? 'OUI' : (evt.consequence_sur_pdt == 0) ? 'NON' : '',
                evt.redacteur ?? '',
                evt.statut == 'en_cours'
                    ? `<span class="badge badge-outline-success">En cours</span>`
                    : evt.statut == 'cloture'
                        ? `<span class="badge badge-outline-danger">Clôturé</span>`
                        : '',
                (evt.location_description ?? ''),
                evt.date_cloture ? new Date(evt.date_cloture).toLocaleString('fr-FR', { year: 'numeric', month: '2-digit', day: '2-digit', hour: '2-digit', minute: '2-digit' }) : '',
                evt.confidentialite == 1 ? 'Confidentiel' : (evt.confidentialite == 0 ? 'Non confidentiel' : ''),
                evt.impacts && evt.impacts.length ? evt.impacts.map(i => i.libelle).join(', ')
                    : (evt.impact_id ?? ''),
                evt.heure_appel_intervenant ? new Date(evt.heure_appel_intervenant).toLocaleString('fr-FR', { year: 'numeric', month: '2-digit', day: '2-digit', hour: '2-digit', minute: '2-digit' }) : '',
                evt.heure_arrive_intervenant ? new Date(evt.heure_arrive_intervenant).toLocaleString('fr-FR', { year: 'numeric', month: '2-digit', day: '2-digit', hour: '2-digit', minute: '2-digit' }) : '',
                evt.commentaires && evt.commentaires.length
                    ? evt.commentaires.map(c => c.text).join('<br>') : '',
                `<div style="display:flex;flex-wrap:wrap;gap:4px;">
                    ${uniqueActions && uniqueActions.length
                        ? uniqueActions.filter(a => a.commentaire && a.commentaire.trim() !== '')
                            .map(a => `<span class="badge ${typeColors[a.type] || 'badge-outline-secondary'}">${a.commentaire}</span>`)
                            .join('') : ''}
                </div>`,
                evt.entite && evt.entite.nom ? evt.entite.nom : '',
                evt.piece_jointe ? `<a href="/storage/${evt.piece_jointe}" target="_blank">Voir</a>` : '',
                `<ul class="flex items-center justify-center gap-2">
                    <li>
                        <button type="button" class="btn btn-sm "
                                onclick="window.dispatchEvent(new CustomEvent('edit-evenement', { detail: ${evt.id} }))">
                            <svg width="24" height="24" viewBox="0 0 24 24"
                                fill="none" xmlns="http://www.w3.org/2000/svg"
                                class="w-4.5 h-4.5 ltr:mr-2 rtl:ml-2">
                                <path
                                    d="M15.2869 3.15178L14.3601 4.07866L5.83882 12.5999L5.83881 12.5999C5.26166 13.1771 4.97308 13.4656 4.7249 13.7838C4.43213 14.1592 4.18114 14.5653 3.97634 14.995C3.80273 15.3593 3.67368 15.7465 3.41556 16.5208L2.32181 19.8021L2.05445 20.6042C1.92743 20.9852 2.0266 21.4053 2.31063 21.6894C2.59466 21.9734 3.01478 22.0726 3.39584 21.9456L4.19792 21.6782L7.47918 20.5844L7.47919 20.5844C8.25353 20.3263 8.6407 20.1973 9.00498 20.0237C9.43469 19.8189 9.84082 19.5679 10.2162 19.2751C10.5344 19.0269 10.8229 18.7383 11.4001 18.1612L11.4001 18.1612L19.9213 9.63993L20.8482 8.71306C22.3839 7.17735 22.3839 4.68748 20.8482 3.15178C19.3125 1.61607 16.8226 1.61607 15.2869 3.15178Z"
                                    stroke="currentColor" stroke-width="1.5" />
                                <path opacity="0.5"
                                    d="M14.36 4.07812C14.36 4.07812 14.4759 6.04774 16.2138 7.78564C17.9517 9.52354 19.9213 9.6394 19.9213 9.6394M4.19789 21.6777L2.32178 19.8015"
                                    stroke="currentColor" stroke-width="1.5" />
                            </svg>
                        </button>
                    </li>
                    <li>
                        <form action="/evenements/${evt.id}" method="POST" style="display:inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet événement ?');">
                            <input type="hidden" name="_method" value="DELETE">
                            <input type="hidden" name="_token" value="${document.querySelector('meta[name=\"csrf-token\"]').getAttribute('content')}">
                            <button type="submit">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-danger">
                                    <circle opacity="0.5" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.5"></circle>
                                    <path d="M14.5 9.50002L9.5 14.5M9.49998 9.5L14.5 14.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                                </svg>
                            </button>
                        </form>
                    </li>
                </ul>`,
            ];
                        }
        return visibleColumns.map(col => row[col.index]);
    });
}

                // Initialiser DataTable
                try {
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

                    setTimeout(() => {
                        this.attachEventListeners();
                    }, 200);

                } catch (error) {
                    console.error('Erreur lors de l\'initialisation du DataTable:', error);
                }
            },


            attachEventListeners() {
                document.querySelectorAll('#myTable1 [contenteditable="true"]').forEach(el => {
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

                document.querySelectorAll('#myTable1 select').forEach(el => {
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

                document.querySelectorAll('#myTable1 .btn-toggle-statut').forEach(btn => {
                    btn.onclick = null;
                    btn.onclick = async (e) => {
                        e.stopPropagation();
                        const id = btn.getAttribute('data-id');
                        const evt = this.evenements.find(ev => ev.id == id);
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
                        // Recharger toute la liste après modification
                        await this.refreshEvenements();
                    };
                });

                if (!this.editMode) {
                    document.querySelectorAll('#myTable1 tbody tr').forEach(tr => {
                        const id = tr.querySelector('td')?.innerText;
                        if (id) {
                            tr.style.cursor = 'pointer';
                            tr.onclick = (e) => {
                                if (!e.target.closest('a') && !e.target.closest('button') && e.target.tagName !== 'INPUT') {
                                    window.dispatchEvent(new CustomEvent('edit-evenement', { detail: id }));
                                }
                            };
                        }
                    });
                }
            }
        }));
    });
</script>

<style>
    #myTable1 th, #myTable1 td {
        border-top: 1px solid #e5e7eb !important;
        border-bottom: 1px solid #e5e7eb !important;
        border-left: none !important;
        border-right: none !important;
        background: #fff;

        padding: 8px 12px;
    }
    #myTable1 {
        border-collapse: collapse !important;
        background: #fff;
    }
    #myTable1 thead th {
        background: #f9fafb;
    }

    /* Masquer la flèche native sur tous les selects du tableau */
    #myTable1 select.form-select {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        background: none !important;
        padding-right: 8px !important;
        background-image: none !important;
        box-shadow: none !important;
    }
.badge-outline-success {
    border: 1px solid #22c55e !important;
    background: #f0fdf4 !important;
    color: #16a34a !important;
}
.badge-outline-danger {
    border: 1px solid #ef4444 !important;
    background: #fef2f2 !important;
    color: #b91c1c !important;
}
#myTable1 td[data-field="date_evenement"], #myTable1 th[data-field="date_evenement"] {
    white-space: nowrap;
}

.my-8 {
    margin-top: 38rem !important;
    margin-bottom: 2rem;
}
</style>
</x-layout.default>

