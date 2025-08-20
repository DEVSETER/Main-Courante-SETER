<?php
// echo phpinfo();
?>
<x-layout.auth>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
    <style>
        .p-5 {
            padding: 3.25rem;
        }

        /* Styles pour maintenir la taille fixe au zoom */
        .fixed-size-container {
            width: 320px !important;
            min-width: 320px !important;
            max-width: 320px !important;
        }

        .fixed-size-title {
            font-size: 28px !important;
            line-height: 32px !important;
        }

        .fixed-size-logo {
            width: 160px !important;
            height: 160px !important;
        }

        .fixed-size-button {
            height: 48px !important;
            font-size: 16px !important;
            padding: 12px 24px !important;
        }

        .fixed-size-input {
            height: 44px !important;
            font-size: 14px !important;
            padding: 12px 16px !important;
        }

        .fixed-size-text {
            font-size: 14px !important;
            line-height: 20px !important;
        }

        .fixed-size-small-text {
            font-size: 12px !important;
            line-height: 16px !important;
        }

        .fixed-spacing-mb-1 { margin-bottom: 8px !important; }
        .fixed-spacing-mb-2 { margin-bottom: 16px !important; }
        .fixed-spacing-mb-3 { margin-bottom: 24px !important; }
        .fixed-spacing-mb-4 { margin-bottom: 32px !important; }
        .fixed-spacing-mt-1 { margin-top: 8px !important; }
        .fixed-spacing-mt-2 { margin-top: 16px !important; }
        .fixed-spacing-mt-3 { margin-top: 24px !important; }
        .fixed-spacing-gap { gap: 16px !important; }

        /* Responsive override pour maintenir les tailles */
        @media (max-width: 768px) {
            .fixed-size-container {
                width: 280px !important;
                min-width: 280px !important;
                max-width: 280px !important;
            }
        }
    </style>
    <div x-data="ssoAuth">
        <div class="absolute inset-0">
            <img src="/assets/images/auth/bg-gradient.png" alt="image" class="h-full w-full object-cover" />
        </div>
        <div class="relative flex min-h-screen items-center justify-center bg-[url(/assets/images/auth/map.png)] bg-cover bg-center bg-no-repeat px-6 py-10 dark:bg-[#060818] sm:px-16" >
            <!-- Images de décoration -->
            <img src="/assets/images/auth/coming-soon-object1.png" alt="image" class="absolute left-0 top-1/2 h-full max-h-[893px] -translate-y-1/2" />
            <img src="/assets/images/auth/coming-soon-object2.png" alt="image" class="absolute left-24 top-0 h-40 md:left-[30%]" />
            <img src="/assets/images/auth/coming-soon-object3.png" alt="image" class="absolute right-0 top-0 h-[300px]" />
            <img src="/assets/images/auth/polygon-object.svg" alt="image" class="absolute bottom-0 end-[28%]" />

            <div class="relative flex w-full max-w-[1502px] flex-col justify-between overflow-hidden rounded-md bg-white/60 backdrop-blur-lg dark:bg-black/50 lg:min-h-[658px] lg:flex-row lg:gap-10 xl:gap-0 " >
                <!-- Panneau gauche avec dégradé -->
                <div class="relative hidden w-full items-center justify-center p-5 lg:inline-flex lg:max-w-[835px] xl:-ms-28 ltr:xl:skew-x-[14deg] rtl:xl:skew-x-[-14deg]"
                     style="background: linear-gradient(225deg, #67152e 90%, #ebba7d 10%);">
                    <div class="absolute inset-y-0 w-8 from-primary/10 via-transparent to-transparent ltr:-right-10 ltr:bg-gradient-to-r rtl:-left-10 rtl:bg-gradient-to-l xl:w-16 ltr:xl:-right-20 rtl:xl:-left-20"></div>
                    <div class="ltr:xl:-skew-x-[14deg] rtl:xl:skew-x-[14deg]">
                        <a href="/" class="w-48 block lg:w-72 ms-10">
                            <img src="/assets/logoauth.png" alt="Logo" class="w-full" />
                        </a>
                        <div class="mt-24 hidden w-full max-w-[430px] lg:block">
                            <img src="/assets/images/LOGO_TER.png" alt="Cover Image" class="w-full" />
                        </div>
                    </div>
                </div>

                <!-- Panneau droit - Formulaire de connexion -->
                <div class="relative flex w-full flex-col items-center justify-center gap-6 px-4 pb-16 pt-6 sm:px-6 lg:max-w-[667px]">
                    <!-- Logo mobile -->
                    <div class="flex max-w-[240px] items-center lg:hidden">
                        <a href="/" class="block w-16">
                            <img src="/assets/maincourante.png" alt="Logo" class="w-full" />
                        </a>
                    </div>

                    <!-- Contenu principal -->
                    <div class="w-full max-w-[400px] lg:mt-16">
                        <!-- Titre -->
                        <h1 class="text-4xl md:text-5xl font-bold text-center mb-8 bg-gradient-to-r from-[#67152e] to-[#ebba7d] bg-clip-text text-transparent drop-shadow-lg">
                            Bienvenue !
                        </h1>

                        <!-- Logo central -->
                        <div class="mb-10 flex justify-center">
                            <div class="w-52 h-52">
                                <img src="/assets/hellologo.png" alt="Logo" class="w-full h-full object-contain" />
                            </div>
                        </div>

                        <!-- Formulaire de connexion -->
                        <div x-show="!showEmailForm && !showTokenSent" class="space-y-6">
    <div class="text-center">
        <p class="text-gray-600 mb-6">Connectez-vous via votre compte d'entreprise</p>
    </div>

    <!--  Bouton SSO principal -->
    <a href="{{ route('auth.sso.initiate') }}" class="flex items-center">

    <button
        :disabled="loading"
        class="w-full text-white font-semibold py-4 px-6 rounded-lg transition-all duration-300 shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed text-lg flex items-center justify-center"
        style="background-color: #67152e !important;">
        <span x-show="!loading">
            🚀 Connexion
        </span>
        <span x-show="loading" class="flex items-center justify-center">
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Connexion en cours...
        </span>
    </button>
</a>

    <!--  NOUVEAU : Option email seulement si Wallix échoue -->
    <div x-show="showEmailOption" x-transition class="text-center">
        <p class="text-sm text-gray-500 mb-2">Problème de connexion ?</p>
        <button @click="showEmailForm = true"
                class="text-[#67152e] hover:text-[#8b2042] font-medium underline">
            Recevoir un lien par email
        </button>
    </div>
</div>

                        <!-- Formulaire email -->
                        <div x-show="showEmailForm" x-transition class="space-y-6">
                            <div class="text-center">
                                <h2 class="text-xl font-semibold text-gray-700 mb-2">Connexion par email</h2>
                                <p class="text-gray-600">Saisissez votre adresse email pour recevoir un lien de connexion</p>
                            </div>

                            <form @submit.prevent="sendEmailToken()">
                                <div class="mb-4">
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                        Adresse email
                                    </label>
                                    <input type="email"
                                           id="email"
                                           x-model="email"
                                           required
                                           class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-[#67152e] focus:ring-2 focus:ring-[#67152e]/20"
                                           placeholder="votre.email@entreprise.com">
                                </div>

                                <button type="submit"
                                        :disabled="loading || !email"
                                        class="w-full bg-[#67152e] hover:bg-[#8b2042] text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-300 disabled:opacity-50 disabled:cursor-not-allowed">
                                    <span x-show="!loading">📧 Envoyer le lien</span>
                                    <span x-show="loading">Envoi en cours...</span>
                                </button>
                            </form>

                            <div class="text-center">
                                <button @click="showEmailForm = false"
                                        class="text-gray-500 hover:text-gray-700 text-sm underline">
                                    ← Retour aux options de connexion
                                </button>
                            </div>
                        </div>

                        <!-- Confirmation d'envoi d'email -->
                        <div x-show="showTokenSent" x-transition class="space-y-6 text-center">
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                <div class="text-green-600 text-4xl mb-2">✅</div>
                                <h2 class="text-xl font-semibold text-green-800 mb-2">Email envoyé !</h2>
                                <p class="text-green-700 mb-4">
                                    Un lien de connexion a été envoyé à <strong x-text="email"></strong>
                                </p>
                                <p class="text-sm text-green-600">
                                    Le lien expire dans <span x-text="Math.floor(tokenExpiry/60)"></span> minutes
                                </p>
                            </div>

                            <button @click="resendToken()"
                                    :disabled="loading"
                                    class="text-[#67152e] hover:text-[#8b2042] font-medium underline disabled:opacity-50">
                                Renvoyer l'email
                            </button>

                            <div class="text-center">
                                <button @click="resetForm()"
                                        class="text-gray-500 hover:text-gray-700 text-sm underline">
                                    ← Nouvelle tentative de connexion
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <p class="absolute bottom-6 w-full text-center text-sm text-gray-500 dark:text-white">
                        © <span id="footer-year">2025</span> MAIN COURANTE V1 SETER BY DSI
                    </p>
                </div>
            </div>
        </div>
    </div>

   <script>
document.addEventListener('alpine:init', () => {
    Alpine.data('ssoAuth', () => ({
        loading: false,
        showEmailForm: true,
        showTokenSent: false,
        showEmailOption: false,
        email: '',
        tokenExpiry: 0,

        async initiateSSO() {
    console.log('🚀 Début initiateSSO');
    this.loading = true;
    this.showEmailOption = false;

    try {
        const response = await fetch('/connexion/sso', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            redirect: 'follow',
            body: JSON.stringify({ sso_only: true })
        });

        console.log('📡 Response status:', response.status);

        // Si le serveur retourne un json (pas de redirection)
        const contentType = response.headers.get('content-type');
        if (contentType && contentType.includes('application/json')) {
            const result = await response.json();
            console.log('📥 Result JSON:', result);

            if (result.success && result.redirect_url) {
                // Si le serveur nous donne une URL, rediriger manuellement
                console.log('🔄 Redirection vers:', result.redirect_url);
                window.location.href = result.redirect_url;
                return; // Arrêter l'exécution
            } else if (result.success && result.method === 'wallix_sso') {
                // Déjà en cours de redirection
                this.showMessage('Redirection vers Wallix...', 'info');
                return; // Arrêter l'exécution
            } else {
                // Erreur ou SSO non disponible
                this.showEmailOption = true;
                this.showMessage(result.error || 'SSO non disponible. Utilisez la connexion par email.', 'warning');
            }
        } else if (response.redirected) {
            // Si la réponse a été redirigée, suivre la redirection
            console.log('🔄 Redirection automatique vers:', response.url);
            window.location.href = response.url;
            return; // Arrêter l'exécution
        } else if (!response.ok) {
            // Erreur HTTP
            throw new Error(`HTTP error! status: ${response.status}`);
        }
    } catch (error) {
        console.error('❌ Erreur SSO:', error);

        this.showEmailOption = true;

        if (error.message.includes('404')) {
            this.showMessage('Service d\'authentification non trouvé. Utilisez la connexion par email.', 'error');
        } else if (error.message.includes('CORS')) {
            this.showMessage('Problème de connexion au service d\'authentification. Utilisez la connexion par email.', 'error');
        } else {
            this.showMessage('Problème de connexion. Vous pouvez utiliser la connexion par email.', 'error');
        }
    } finally {
        this.loading = false;
    }
},

        async sendEmailToken() {
            if (!this.email) {
                this.showMessage('Veuillez saisir votre email', 'warning');
                return;
            }

            console.log('📧 Début sendEmailToken pour:', this.email);
            this.loading = true;

            try {
                const response = await fetch('/connexion/email', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        email: this.email,
                        force_email: true
                    })
                });

                console.log('📡 Email response status:', response.status);

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const result = await response.json();
                console.log('📥 Email result:', result);

                if (result.success) {
                    this.tokenExpiry = result.expires_in || 3600;
                    this.showEmailForm = false;
                    this.showTokenSent = true;
                    this.showMessage('Email envoyé avec succès !', 'success');
                } else {
                    this.showMessage(result.error || 'Erreur lors de l\'envoi', 'error');
                }
            } catch (error) {
                console.error('❌ Erreur envoi email:', error);
                this.showMessage('Erreur lors de l\'envoi de l\'email', 'error');
            } finally {
                this.loading = false;
            }
        },

        async resendToken() {
            console.log('🔄 Début resendToken pour:', this.email);
            this.loading = true;

            try {
                const response = await fetch('/connexion/email/resend', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ email: this.email })
                });

                console.log('📡 Resend response status:', response.status);

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const result = await response.json();
                console.log('📥 Resend result:', result);

                if (result.success) {
                    this.tokenExpiry = result.expires_in || 3600;
                    this.showMessage('Email renvoyé avec succès !', 'success');
                } else {
                    this.showMessage(result.error || 'Erreur lors du renvoi', 'error');
                }
            } catch (error) {
                console.error('❌ Erreur resend:', error);
                this.showMessage('Erreur lors du renvoi', 'error');
            } finally {
                this.loading = false;
            }
        },

        resetForm() {
            this.showEmailForm = true;
            this.showTokenSent = false;
            this.showEmailOption = false; //  NOUVEAU : Réinitialiser l'option email
            this.email = '';
            this.tokenExpiry = 0;
            this.loading = false;
        },

        showMessage(message, type = 'info') {
            console.log(`📢 Message [${type}]:`, message);

            if (window.Swal) {
                const icons = {
                    success: 'success',
                    error: 'error',
                    warning: 'warning',
                    info: 'info'
                };

                Swal.fire({
                    icon: icons[type] || 'info',
                    title: message,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 4000,
                    timerProgressBar: true
                });
            } else {
                alert(`[${type.toUpperCase()}] ${message}`);
            }
        }
    }));
});
</script>
</x-layout.auth>
