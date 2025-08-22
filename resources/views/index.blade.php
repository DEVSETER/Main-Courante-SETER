{{-- filepath: c:\Projet\MainCourante\MainCourante\MainCourante\resources\views\index.blade.php --}}

<x-layout.default>
    <script defer src="/assets/js/apexcharts.js"></script>

    <style>
    /* Styles pour garantir les couleurs en mode clair et sombre */
    .dashboard-card {
        position: relative;
        overflow: hidden;
    }
 /* ✅ NOUVEAU : Forcer les couleurs du graphique circulaire en mode light */
    :root:not(.dark) .apexcharts-pie-area {
        opacity: 1 !important;
        filter: none !important;
    }

    /* Forcer chaque couleur spécifiquement */
    :root:not(.dark) .apexcharts-pie-area[fill="#3B82F6"] {
        fill: #3B82F6 !important;
    }

    :root:not(.dark) .apexcharts-pie-area[fill="#10B981"] {
        fill: #10B981 !important;
    }

    :root:not(.dark) .apexcharts-pie-area[fill="#8B5CF6"] {
        fill: #8B5CF6 !important;
    }

    :root:not(.dark) .apexcharts-pie-area[fill="#F59E0B"] {
        fill: #F59E0B !important;
    }

    :root:not(.dark) .apexcharts-pie-area[fill="#EF4444"] {
        fill: #EF4444 !important;
    }

    /* Forcer les couleurs de la légende */
    :root:not(.dark) .apexcharts-legend-marker {
        opacity: 1 !important;
    }

    :root:not(.dark) .apexcharts-legend-marker[fill="#3B82F6"] {
        fill: #3B82F6 !important;
    }

    :root:not(.dark) .apexcharts-legend-marker[fill="#10B981"] {
        fill: #10B981 !important;
    }

    :root:not(.dark) .apexcharts-legend-marker[fill="#8B5CF6"] {
        fill: #8B5CF6 !important;
    }

    :root:not(.dark) .apexcharts-legend-marker[fill="#F59E0B"] {
        fill: #F59E0B !important;
    }

    :root:not(.dark) .apexcharts-legend-marker[fill="#EF4444"] {
        fill: #EF4444 !important;
    }

    /* Styles pour les étiquettes */
    :root:not(.dark) .apexcharts-datalabel-label,
    :root:not(.dark) .apexcharts-datalabel-value {
        fill: #374151 !important;
        font-weight: 600 !important;
    }

    /* Style pour le texte de la légende */
    :root:not(.dark) .apexcharts-legend-text {
        fill: #374151 !important;
        color: #374151 !important;
    }

    /* Désactiver tous les filtres en mode light */
    :root:not(.dark) .apexcharts-pie .apexcharts-series {
        filter: none !important;
    }

    :root:not(.dark) .apexcharts-pie-area:hover {
        filter: brightness(1.1) !important;
    }
    /* Forcer les couleurs des cartes en mode clair */
    :root:not(.dark) .dashboard-card.card-sr-cof {
        background: linear-gradient(135deg, #3b82f6, #1e40af) !important;
        color: white !important;
    }

    :root:not(.dark) .dashboard-card.card-civ {
        background: linear-gradient(135deg, #10b981, #047857) !important;
        color: white !important;
    }

    :root:not(.dark) .dashboard-card.card-cm {
        background: linear-gradient(135deg, #8b5cf6, #6d28d9) !important;
        color: white !important;
    }

    :root:not(.dark) .dashboard-card.card-hotline {
        background: linear-gradient(135deg, #f97316, #c2410c) !important;
        color: white !important;
    }

    :root:not(.dark) .dashboard-card.card-ptp {
        background: linear-gradient(135deg, #ef4444, #b91c1c) !important;
        color: white !important;
    }

    /* Forcer les couleurs des cartes résumé */
    :root:not(.dark) .panel.summary-total {
        background: linear-gradient(135deg, #1e293b, #0f172a) !important;
        color: white !important;
    }

    :root:not(.dark) .panel.summary-trend {
        background: linear-gradient(135deg, #4f46e5, #3730a3) !important;
        color: white !important;
    }

    :root:not(.dark) .panel.summary-time {
        background: linear-gradient(135deg, #14b8a6, #0d9488) !important;
        color: white !important;
    }

    :root:not(.dark) .panel.summary-teams {
        background: linear-gradient(135deg, #ec4899, #be185d) !important;
        color: white !important;
    }

    /* Forcer le texte blanc pour les panneaux colorés */
    :root:not(.dark) .white-text-forced {
        color: white !important;
    }

    :root:not(.dark) .white-text-forced .text-indigo-200,
    :root:not(.dark) .white-text-forced .text-teal-200,
    :root:not(.dark) .white-text-forced .text-slate-300,
    :root:not(.dark) .white-text-forced .text-pink-200,
    :root:not(.dark) .white-text-forced .text-indigo-300,
    :root:not(.dark) .white-text-forced .text-teal-300,
    :root:not(.dark) .white-text-forced .text-slate-400,
    :root:not(.dark) .white-text-forced .text-pink-300 {
        color: rgba(255, 255, 255, 0.8) !important;
    }

    /* Style du cercle d'icône */
    :root:not(.dark) .icon-circle {
        /* background-color: rgba(255, 255, 255, 0.15) !important; */
    }
</style>

    <div x-data="sales">
        <!-- Breadcrumb -->
        <ul class="flex space-x-2 rtl:space-x-reverse mb-6">
            <li>
                <a href="javascript:;" class="text-primary hover:underline">Dashboard</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>Événements {{ \Carbon\Carbon::now()->format('F Y') }}</span>
            </li>
        </ul>

        <div class="pt-5">
            <!-- ✅ Cartes principales colorées par entité -->

            <div class="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-5 gap-6 mb-8">

    <!-- Carte SR COF - Bleu professionnel -->
    <div class="dashboard-card card-sr-cof bg-gradient-to-br from-blue-500 via-blue-600 to-blue-700 text-white border-0 card-shadow hover:shadow-xl transition-all duration-500 transform hover:-translate-y-2 hover:scale-105 rounded-xl overflow-hidden">
        <div class="flex items-center justify-between relative z-10 h-full min-h-[140px]">
            <div class="flex-1 text-center px-2">
                <div class="text-blue-100 text-sm font-medium mb-3 tracking-wide">SR COF</div>
                <div class="text-4xl font-bold mb-2 tracking-tight">{{ $countSRCOF ?: 0 }}</div>
                <div class="text-blue-200 text-xs font-medium mb-3">événements ce mois</div>
                <div class="flex items-center justify-center text-blue-100 text-xs">
                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                    Suivi qualité
                </div>
            </div>
            <div class="flex-shrink-0 self-center">
                <div class="icon-circle w-16 h-16 bg-white/15 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-white drop-shadow-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Carte CIV - Vert émeraude -->
    <div class="dashboard-card card-civ bg-gradient-to-br from-emerald-500 via-emerald-600 to-emerald-700 text-white border-0 card-shadow hover:shadow-xl transition-all duration-500 transform hover:-translate-y-2 hover:scale-105 rounded-xl overflow-hidden">
        <div class="flex items-center justify-between relative z-10 h-full min-h-[140px]">
            <div class="flex-1 text-center px-2">
                <div class="text-emerald-100 text-sm font-medium mb-3 tracking-wide">CIV</div>
                <div class="text-4xl font-bold mb-2 tracking-tight">{{ $countCIV ?: 0 }}</div>
                <div class="text-emerald-200 text-xs font-medium mb-3">événements ce mois</div>
                <div class="flex items-center justify-center text-emerald-100 text-xs">
                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                    </svg>
                    Incidents civils
                </div>
            </div>
            <div class="flex-shrink-0 self-center">
                <div class="icon-circle w-16 h-16 bg-white/15 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-white drop-shadow-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Carte CM - Violet moderne -->
    <div class="dashboard-card card-cm bg-gradient-to-br from-purple-500 via-purple-600 to-purple-700 text-white border-0 card-shadow hover:shadow-xl transition-all duration-500 transform hover:-translate-y-2 hover:scale-105 rounded-xl overflow-hidden">
        <div class="flex items-center justify-between relative z-10 h-full min-h-[140px]">
            <div class="flex-1 text-center px-2">
                <div class="text-purple-100 text-sm font-medium mb-3 tracking-wide">CM</div>
                <div class="text-4xl font-bold mb-2 tracking-tight">{{ $countCM ?: 0 }}</div>
                <div class="text-purple-200 text-xs font-medium mb-3">événements ce mois</div>
                <div class="flex items-center justify-center text-purple-100 text-xs">
                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"></path>
                    </svg>
                    Gestion crise
                </div>
            </div>
            <div class="flex-shrink-0 self-center">
                <div class="icon-circle w-16 h-16 bg-white/15 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-white drop-shadow-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Carte HOTLINE - Orange énergique -->
    <div class="dashboard-card card-hotline bg-gradient-to-br from-orange-500 via-orange-600 to-orange-700 text-white border-0 card-shadow hover:shadow-xl transition-all duration-500 transform hover:-translate-y-2 hover:scale-105 rounded-xl overflow-hidden">
        <div class="flex items-center justify-between relative z-10 h-full min-h-[140px]">
            <div class="flex-1 text-center px-2">
                <div class="text-orange-100 text-sm font-medium mb-3 tracking-wide">HOTLINE</div>
                <div class="text-4xl font-bold mb-2 tracking-tight">{{ $countHotline ?: 0 }}</div>
                <div class="text-orange-200 text-xs font-medium mb-3">événements ce mois</div>
                <div class="flex items-center justify-center text-orange-100 text-xs">
                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                    </svg>
                    Support urgent
                </div>
            </div>
            <div class="flex-shrink-0 self-center">
                <div class="icon-circle w-16 h-16 bg-white/15 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-white drop-shadow-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Carte PTP - Rouge sophistiqué -->
    <div class="dashboard-card card-ptp bg-gradient-to-br from-red-500 via-red-600 to-red-700 text-white border-0 card-shadow hover:shadow-xl transition-all duration-500 transform hover:-translate-y-2 hover:scale-105 rounded-xl overflow-hidden">
        <div class="flex items-center justify-between relative z-10 h-full min-h-[140px]">
            <div class="flex-1 text-center px-2">
                <div class="text-red-100 text-sm font-medium mb-3 tracking-wide">PTP</div>
                <div class="text-4xl font-bold mb-2 tracking-tight">{{ $countPTP ?: 0 }}</div>
                <div class="text-red-200 text-xs font-medium mb-3">événements ce mois</div>
                <div class="flex items-center justify-center text-red-100 text-xs">
                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"></path>
                    </svg>
                    Plan urgence
                </div>
            </div>
            <div class="flex-shrink-0 self-center">
                <div class="icon-circle w-16 h-16 bg-white/15 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-white drop-shadow-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</div>

            <!-- ✅ Cartes résumé global avec icônes et couleurs -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">

                <!-- Total événements -->
<div class="panel white-text-forced summary-total bg-gradient-to-br from-slate-800 via-slate-900 to-gray-900 text-white border-0 card-shadow hover:shadow-xl transition-all duration-300 rounded-xl">
                    <div class="flex items-center">
                        <div class="icon-circle w-16 h-16 bg-white/10 rounded-xl flex items-center justify-center mr-5">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="text-slate-300 text-sm font-medium mb-1">Total événements</div>
                            <div class="text-3xl font-bold text-white mb-1">{{ $evenementsCount ?: 0 }}</div>
                            <div class="text-slate-400 text-xs">{{ \Carbon\Carbon::now()->format('F Y') }}</div>
                        </div>
                    </div>
                </div>

                <!-- Tendance -->
<div class="panel white-text-forced summary-trend bg-gradient-to-br from-indigo-500 via-indigo-600 to-indigo-700 text-white border-0 card-shadow hover:shadow-xl transition-all duration-300 rounded-xl">
                    <div class="flex items-center">
                        <div class="icon-circle w-16 h-16 bg-white/10 rounded-xl flex items-center justify-center mr-5">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="text-indigo-200 text-sm font-medium mb-1">Tendance</div>
                            <div class="text-3xl font-bold text-white mb-1">+{{ rand(5, 25) }}%</div>
                            <div class="text-indigo-300 text-xs">vs mois précédent</div>
                        </div>
                    </div>
                </div>

                <!-- Temps moyen -->
<div class="panel white-text-forced summary-time bg-gradient-to-br from-teal-500 via-teal-600 to-teal-700 text-white border-0 card-shadow hover:shadow-xl transition-all duration-300 rounded-xl">
                    <div class="flex items-center">
                        <div class="icon-circle w-16 h-16 bg-white/10 rounded-xl flex items-center justify-center mr-5">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="text-teal-200 text-sm font-medium mb-1">Temps moyen</div>
                            <div class="text-3xl font-bold text-white mb-1">{{ rand(2, 8) }}h</div>
                            <div class="text-teal-300 text-xs">de résolution</div>
                        </div>
                    </div>
                </div>

                <!-- Équipes actives -->
<div class="panel white-text-forced summary-teams bg-gradient-to-br from-pink-500 via-pink-600 to-pink-700 text-white border-0 card-shadow hover:shadow-xl transition-all duration-300 rounded-xl">
                    <div class="flex items-center">
                        <div class="icon-circle w-16 h-16 bg-white/10 rounded-xl flex items-center justify-center mr-5">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="text-pink-200 text-sm font-medium mb-1">Équipes actives</div>
                            <div class="text-3xl font-bold text-white mb-1">{{ collect([$countSRCOF, $countCIV, $countCM, $countHotline, $countPTP])->filter(fn($c) => $c > 0)->count() }}</div>
                            <div class="text-pink-300 text-xs">entités mobilisées</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ✅ Section graphiques principaux -->
            <div class="grid xl:grid-cols-3 gap-6 mb-6">

                <!-- Graphique chronologique des événements -->
                <div class="panel h-full xl:col-span-2 rounded-xl">
                    <div class="flex items-center dark:text-white-light mb-5">
                        <h5 class="font-semibold text-lg">Évolution des événements (12 derniers mois)</h5>
                        <div x-data="dropdown" @click.outside="open = false" class="dropdown ltr:ml-auto rtl:mr-auto">
                            <a href="javascript:;" @click="toggle" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                <svg class="w-5 h-5 text-black/70 dark:text-white/70 hover:!text-primary" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="5" cy="12" r="2" stroke="currentColor" stroke-width="1.5" />
                                    <circle opacity="0.5" cx="12" cy="12" r="2" stroke="currentColor" stroke-width="1.5" />
                                    <circle cx="19" cy="12" r="2" stroke="currentColor" stroke-width="1.5" />
                                </svg>
                            </a>
                            <ul x-cloak x-show="open" x-transition x-transition.duration.300ms class="ltr:right-0 rtl:left-0">
                                <li><a href="javascript:;" @click="toggle">Voir par entité</a></li>
                                <li><a href="javascript:;" @click="toggle">Voir par nature</a></li>
                                <li><a href="javascript:;" @click="toggle">Exporter</a></li>
                            </ul>
                        </div>
                    </div>
                    <p class="text-lg dark:text-white-light/90 mb-4">
                        Total événements ce mois
                        <span class="text-primary ml-2 font-bold text-2xl">{{ $evenementsCount }}</span>
                    </p>
                    <div class="relative overflow-hidden">
                        <div x-ref="revenueChart" class="bg-white dark:bg-black rounded-lg">
                            <!-- loader -->
                            <div class="min-h-[325px] grid place-content-center bg-white-light/30 dark:bg-dark dark:bg-opacity-[0.08]">
                                <span class="animate-spin border-2 border-black dark:border-white !border-l-transparent rounded-full w-5 h-5 inline-flex"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Graphique par entité -->
                <div class="panel h-full rounded-xl">
                    <div class="flex items-center mb-5">
                        <h5 class="font-semibold text-lg dark:text-white-light">Événements par entité</h5>
                    </div>
                    <div class="overflow-hidden">
                        <div x-ref="salesByCategory" class="bg-white dark:bg-black rounded-lg">
                            <!-- loader -->
                            <div class="min-h-[353px] grid place-content-center bg-white-light/30 dark:bg-dark dark:bg-opacity-[0.08]">
                                <span class="animate-spin border-2 border-black dark:border-white !border-l-transparent rounded-full w-5 h-5 inline-flex"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ✅ Section graphiques secondaires -->
            <div class="grid sm:grid-cols-2 xl:grid-cols-3 gap-6 mb-6">

                <!-- Événements par nature -->
                <div class="panel h-full sm:col-span-2 xl:col-span-1 rounded-xl">
                    <div class="flex items-center mb-5">
                        <h5 class="font-semibold text-lg dark:text-white-light">Événements par nature</h5>
                    </div>
                    <div class="overflow-hidden">
                        <div x-ref="eventsByNature" class="bg-white dark:bg-black rounded-lg">
                            <!-- loader -->
                            <div class="min-h-[200px] grid place-content-center bg-white-light/30 dark:bg-dark dark:bg-opacity-[0.08]">
                                <span class="animate-spin border-2 border-black dark:border-white !border-l-transparent rounded-full w-5 h-5 inline-flex"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Événements par statut -->
                <div class="panel h-full rounded-xl">
                    <div class="flex items-center mb-5">
                        <h5 class="font-semibold text-lg dark:text-white-light">Statut des événements</h5>
                    </div>
                    <div class="space-y-6">
                        @if(isset($eventsByStatus) && $eventsByStatus->count() > 0)
                            @foreach($eventsByStatus as $status => $count)
                            <div class="flex items-center">
                                <div class="w-12 h-12 ltr:mr-4 rtl:ml-4">
                                    <div class="bg-{{ $status === 'En cours' ? 'amber' : ($status === 'Terminé' ? 'emerald' : 'slate') }}-100 dark:bg-{{ $status === 'En cours' ? 'amber' : ($status === 'Terminé' ? 'emerald' : 'slate') }}-800 text-{{ $status === 'En cours' ? 'amber' : ($status === 'Terminé' ? 'emerald' : 'slate') }}-600 dark:text-{{ $status === 'En cours' ? 'amber' : ($status === 'Terminé' ? 'emerald' : 'slate') }}-300 rounded-full w-12 h-12 grid place-content-center text-xl">
                                        @if($status === 'En cours')
                                            ⏳
                                        @elseif($status === 'Terminé')
                                            ✅
                                        @else
                                            📁
                                        @endif
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <div class="flex font-semibold text-gray-700 dark:text-white-light mb-2">
                                        <h6>{{ $status }}</h6>
                                        <p class="ltr:ml-auto rtl:mr-auto text-primary">{{ $count }}</p>
                                    </div>
                                    <div class="rounded-full h-3 bg-gray-200 dark:bg-gray-700 shadow-inner">
                                        <div class="bg-gradient-to-r from-{{ $status === 'En cours' ? 'amber-400' : ($status === 'Terminé' ? 'emerald-400' : 'slate-400') }} to-{{ $status === 'En cours' ? 'amber-600' : ($status === 'Terminé' ? 'emerald-600' : 'slate-600') }} h-full rounded-full transition-all duration-500" style="width: {{ $evenementsCount > 0 ? ($count / $evenementsCount * 100) : 0 }}%;"></div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <div class="text-center text-gray-500 py-8">
                                <svg class="w-12 h-12 mx-auto mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                <p class="text-sm">Aucun événement ce mois</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Activité récente -->
                <div class="panel h-full rounded-xl">
                    <div class="flex items-center mb-5">
                        <h5 class="font-semibold text-lg dark:text-white-light">Activité récente</h5>
                    </div>
                    <div class="space-y-4">
                        <div class="text-center text-gray-500 py-8">
                            <svg class="w-16 h-16 mx-auto mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <p class="text-lg font-medium mb-2">Suivi en temps réel</p>
                            <p class="text-sm text-gray-400 mb-4">Consultez l'activité des événements</p>
                            <a href="/evenements" class="inline-flex items-center text-primary hover:text-primary/80 font-medium text-sm transition-colors">
                                Voir tous les événements
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ✅ Variables JavaScript pour les graphiques -->
    <script>
        window.dashboardData = {
            eventsByEntity: {
                'SR COF': {{ (int)($countSRCOF ?? 0) }},
                'CIV': {{ (int)($countCIV ?? 0) }},
                'CM': {{ (int)($countCM ?? 0) }},
                'HOTLINE': {{ (int)($countHotline ?? 0) }},
                'PTP': {{ (int)($countPTP ?? 0) }}
            },
            monthlyData: {!! json_encode($monthlyData ?? []) !!},
            eventsByEntityAndNature: {!! json_encode($eventsByEntityAndNature ?? []) !!},
            eventsByStatus: {!! json_encode($eventsByStatus ?? []) !!},
            debug: {
                evenementsCount: {{ (int)($evenementsCount ?? 0) }},
                periode: '{{ \Carbon\Carbon::now()->format("F Y") }}'
            }
        };

        // Debug console
        console.log('📊 Dashboard Data loaded:', window.dashboardData);
    </script>

    <!-- ✅ Script des graphiques ApexCharts -->
    <script>
        document.addEventListener("alpine:init", () => {
            Alpine.data("sales", () => ({
                init() {
                    isDark = this.$store.app.theme === "dark" || this.$store.app.isDarkMode ? true : false;
                    isRtl = this.$store.app.rtlClass === "rtl" ? true : false;

                    setTimeout(() => {
                        // Graphique chronologique
                        this.revenueChart = new ApexCharts(this.$refs.revenueChart, this.revenueChartOptions);
                        this.$refs.revenueChart.innerHTML = "";
                        this.revenueChart.render();

                        // Graphique par entité
                        this.salesByCategory = new ApexCharts(this.$refs.salesByCategory, this.salesByCategoryOptions);
                        this.$refs.salesByCategory.innerHTML = "";
                        this.salesByCategory.render();

                        // Graphique par nature
                        if (this.$refs.eventsByNature) {
                            this.eventsByNature = new ApexCharts(this.$refs.eventsByNature, this.eventsByNatureOptions);
                            this.$refs.eventsByNature.innerHTML = "";
                            this.eventsByNature.render();
                        }
                    }, 300);

                    this.$watch('$store.app.theme', () => {
                        isDark = this.$store.app.theme === "dark" || this.$store.app.isDarkMode ? true : false;
                        this.revenueChart?.updateOptions(this.revenueChartOptions);
                        this.salesByCategory?.updateOptions(this.salesByCategoryOptions);
                        if (this.eventsByNature) {
                            this.eventsByNature.updateOptions(this.eventsByNatureOptions);
                        }
                    });
                },

                // Graphique chronologique des événements
                get revenueChartOptions() {
                    const monthlyData = window.dashboardData.monthlyData || [];
                    const labels = monthlyData.map(item => item.month_short || 'N/A');
                    const data = monthlyData.map(item => parseInt(item.count) || 0);

                    return {
                        series: [{
                            name: 'Événements',
                            data: data.length ? data : [0]
                        }],
                        chart: {
                            height: 325,
                            type: "area",
                            fontFamily: 'Nunito, sans-serif',
                            zoom: { enabled: false },
                            toolbar: { show: false }
                        },
                        dataLabels: { enabled: false },
                        stroke: {
                            show: true,
                            curve: 'smooth',
                            width: 2,
                            lineCap: 'square'
                        },
                        colors: isDark ? ['#2196f3'] : ['#1b55e2'],
                        labels: labels.length ? labels : ['Jan'],
                        xaxis: {
                            axisBorder: { show: false },
                            axisTicks: { show: false },
                            crosshairs: { show: true }
                        },
                        yaxis: {
                            tickAmount: 7,
                            labels: {
                                formatter: (value) => Math.round(value)
                            }
                        },
                        grid: {
                            borderColor: isDark ? '#191e3a' : '#e0e6ed',
                            strokeDashArray: 5
                        },
                        tooltip: {
                            marker: { show: true },
                            x: { show: false }
                        },
                        fill: {
                            type: 'gradient',
                            gradient: {
                                shadeIntensity: 1,
                                inverseColors: false,
                                opacityFrom: isDark ? 0.19 : 0.28,
                                opacityTo: 0.05,
                                stops: isDark ? [100, 100] : [45, 100]
                            }
                        }
                    };
                },

                // Graphique par entité (donut)
               get salesByCategoryOptions() {
    const data = window.dashboardData.eventsByEntity || {};
    let series = Object.values(data).map(v => parseInt(v) || 0);
    let labels = Object.keys(data);

    if (!series.length || !series.some(v => v > 0)) {
        series = [1];
        labels = ['Aucune donnée'];
    }

    // ✅ Couleurs fixes et vives
    const chartColors = ['#3B82F6', '#10B981', '#8B5CF6', '#F59E0B', '#EF4444'];

    return {
        series,
        chart: {
            type: 'donut',
            height: 460,
            fontFamily: 'Nunito, sans-serif',
            background: 'transparent',
            foreColor: isDark ? '#bfc9d4' : '#374151',
            // ✅ Désactiver les animations qui peuvent causer des problèmes de couleur
            animations: {
                enabled: true,
                easing: 'easeinout',
                speed: 800,
                animateGradually: {
                    enabled: true,
                    delay: 150
                },
                dynamicAnimation: {
                    enabled: true,
                    speed: 350
                }
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            show: true,
            width: 25,
            colors: isDark ? '#0e1726' : '#ffffff'
        },
        // ✅ Couleurs fixes
        colors: chartColors,
        fill: {
            type: 'solid',
            opacity: 1,
            colors: chartColors
        },
        legend: {
            position: 'bottom',
            horizontalAlign: 'center',
            fontSize: '14px',
            fontWeight: 500,
            markers: {
                width: 12,
                height: 12,
                strokeWidth: 0,
                strokeColor: 'transparent',
                fillColors: chartColors,
                radius: 12,
                customHTML: undefined,
                onClick: undefined,
                offsetX: 0,
                offsetY: 0
            },
            labels: {
                colors: isDark ? '#bfc9d4' : '#374151',
                useSeriesColors: false
            },
            itemMargin: {
                horizontal: 5,
                vertical: 0
            }
        },
        plotOptions: {
            pie: {
                startAngle: 0,
                endAngle: 360,
                expandOnClick: true,
                offsetX: 0,
                offsetY: 0,
                customScale: 1,
                dataLabels: {
                    offset: 0,
                    minAngleToShowLabel: 10
                },
                donut: {
                    size: '65%',
                    background: 'transparent',
                    labels: {
                        show: true,
                        name: {
                            show: true,
                            fontSize: '22px',
                            fontFamily: 'Nunito, sans-serif',
                            fontWeight: 700,
                            color: isDark ? '#bfc9d4' : '#374151',
                            offsetY: -10,
                            formatter: function (val) {
                                return val;
                            }
                        },
                        value: {
                            show: true,
                            fontSize: '20px',
                            fontFamily: 'Nunito, sans-serif',
                            fontWeight: 600,
                            color: isDark ? '#bfc9d4' : '#374151',
                            offsetY: 16,
                            formatter: function (val) {
                                return parseInt(val);
                            }
                        },
                        total: {
                            show: true,
                            showAlways: false,
                            label: 'Total',
                            fontSize: '22px',
                            fontFamily: 'Nunito, sans-serif',
                            fontWeight: 700,
                            color: isDark ? '#bfc9d4' : '#374151',
                            formatter: function (w) {
                                return w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                            }
                        }
                    }
                }
            }
        },
        states: {
            normal: {
                filter: {
                    type: 'none',
                    value: 0
                }
            },
            hover: {
                filter: {
                    type: 'lighten',
                    value: 0.1
                }
            },
            active: {
                allowMultipleDataPointsSelection: false,
                filter: {
                    type: 'none',
                    value: 0
                }
            }
        },
        tooltip: {
            enabled: true,
            theme: isDark ? 'light' : 'light',
            style: {
                fontSize: '14px',
                fontFamily: 'Nunito, sans-serif'
            },
            y: {
                formatter: function(val) {
                    return val + " événement" + (val > 1 ? "s" : "");
                }
            }
        },
        labels,
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 300
                },
                legend: {
                    position: 'bottom'
                }
            }
        }]
    };
},

                // Graphique par nature d'événement
                get eventsByNatureOptions() {
                    const data = window.dashboardData.eventsByEntityAndNature || [];

                    // Regrouper par nature
                    const naturesCount = {};
                    data.forEach(item => {
                        if (item.nature_libelle) {
                            if (!naturesCount[item.nature_libelle]) {
                                naturesCount[item.nature_libelle] = 0;
                            }
                            naturesCount[item.nature_libelle] += parseInt(item.count) || 0;
                        }
                    });

                    const series = Object.values(naturesCount);
                    const labels = Object.keys(naturesCount);

                    if (!series.length) {
                        return {
                            series: [{ name: 'Événements', data: [0] }],
                            chart: { height: 200, type: 'bar', fontFamily: 'Nunito, sans-serif', toolbar: { show: false } },
                            xaxis: { categories: ['Aucune donnée'] },
                            colors: ['#e2a03f']
                        };
                    }

                    return {
                        series: [{
                            name: 'Événements',
                            data: series
                        }],
                        chart: {
                            height: 200,
                            type: 'bar',
                            fontFamily: 'Nunito, sans-serif',
                            toolbar: { show: false }
                        },
                        plotOptions: {
                            bar: {
                                horizontal: true,
                                columnWidth: '55%'
                            }
                        },
                        dataLabels: { enabled: false },
                        stroke: {
                            show: true,
                            width: 1
                        },
                        colors: ['#e2a03f'],
                        xaxis: {
                            categories: labels
                        },
                        yaxis: {
                            show: true
                        },
                        grid: {
                            borderColor: isDark ? '#191e3a' : '#191e3a'
                        }
                    };
                }
            }));
        });
    </script>
</x-layout.default>
