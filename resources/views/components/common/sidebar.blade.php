<!-- filepath: c:\Projet\MainCourante\resources\views\components\common\sidebar.blade.php -->
<style>
    /* Forcer le thème blanc pour la sidebar */
    .sidebar {
        background-color: white !important;
    }

    .sidebar .nav-item > button,
    .sidebar .nav-item > a {
        color: #64748b !important; /* Gris pour les liens */
    }

    .sidebar .nav-item > button.active,
    .sidebar .nav-item > a.active {
        background-color: #e2e8f0 !important; /* Fond gris clair pour actif */
        color: #1e293b !important; /* Texte sombre pour actif */
    }

    .sidebar h2 {
        background-color: #f8fafc !important; /* Fond très clair pour les titres */
        color: #374151 !important;
    }

    .sidebar .nav-item svg {
        color: #64748b !important;
    }

    .sidebar .nav-item.active svg,
    .sidebar .nav-item > a.active svg,
    .sidebar .nav-item > button.active svg {
        color: #1e293b !important;
    }
</style>

<div class="text-gray-800">
    <nav x-data="sidebar"
        class="sidebar fixed min-h-screen h-full top-0 bottom-0 w-[260px] shadow-[5px_0_25px_0_rgba(94,92,154,0.1)] z-50 transition-all duration-300">

        <div class="bg-white h-full">
            <div class="flex justify-between items-center px-4 py-3">
                <a href="/dashboard" class="main-logo flex items-center shrink-0">
                    <img class="w-8 ml-[25px] flex-none" src="/assets/images/logo.png" style="width:7rem;margin-left: 20px"
                        alt="image" />
                </a>

                <a href="javascript:;"
                    class="collapse-icon w-8 h-8 rounded-full flex items-center hover:bg-gray-200 text-gray-600 transition duration-300 rtl:rotate-180"
                    @click="$store.app.toggleSidebar()">
                    <svg class="w-5 h-5 m-auto" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M13 19L7 12L13 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path opacity="0.5" d="M16.9998 19L10.9998 12L16.9998 5" stroke="currentColor"
                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>
            </div>

            <ul class="perfect-scrollbar relative font-semibold space-y-0.5 h-[calc(100vh-80px)] overflow-y-auto overflow-x-hidden p-4 py-0"
                x-data="{ activeDropdown: null }">

                <li class="menu nav-item">
                    @can('Consulter tableau de bord')
                    <button type="button" class="nav-link group" :class="{ 'active': activeDropdown === 'dashboard' }"
                        @click="activeDropdown === 'dashboard' ? activeDropdown = null : activeDropdown = 'dashboard'">
                        <div class="flex items-center">
                            <svg class="group-hover:text-blue-600 shrink-0" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path opacity="0.5"
                                    d="M2 12.2039C2 9.91549 2 8.77128 2.5192 7.82274C3.0384 6.87421 3.98695 6.28551 5.88403 5.10813L7.88403 3.86687C9.88939 2.62229 10.8921 2 12 2C13.1079 2 14.1106 2.62229 16.116 3.86687L18.116 5.10812C20.0131 6.28551 20.9616 6.87421 21.4808 7.82274C22 8.77128 22 9.91549 22 12.2039V13.725C22 17.6258 22 19.5763 20.8284 20.7881C19.6569 22 17.7712 22 14 22H10C6.22876 22 4.34315 22 3.17157 20.7881C2 19.5763 2 17.6258 2 13.725V12.2039Z"
                                    fill="currentColor" />
                                <path
                                    d="M9 17.25C8.58579 17.25 8.25 17.5858 8.25 18C8.25 18.4142 8.58579 18.75 9 18.75H15C15.4142 18.75 15.75 18.4142 15.75 18C15.75 17.5858 15.4142 17.25 15 17.25H9Z"
                                    fill="currentColor" />
                            </svg>
                            <span class="ltr:pl-3 rtl:pr-3 text-gray-700 group-hover:text-blue-700">Tableau de bord</span>
                        </div>
                        <div class="rtl:rotate-180" :class="{ '!rotate-90': activeDropdown === 'dashboard' }">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                    </button>
                    <ul x-cloak x-show="activeDropdown === 'dashboard'" x-collapse class="sub-menu text-gray-500">
                        <li>
                            <a href="/dashboard" class="hover:text-blue-600">Tableau de bord</a>
                        </li>
                    </ul>
                    @endcan
                </li>

                @can('Consulter liste événements')
                <h2 class="py-3 px-7 flex items-center uppercase font-extrabold bg-gray-100 -mx-4 mb-1">
                    <svg class="w-4 h-5 flex-none hidden" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"
                        fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span class="text-gray-700">Événements</span>
                </h2>

                <li class="nav-item">
                    <ul>
                        <li class="nav-item">
                            <a href="/evenements" class="group">
                                <div class="flex items-center">
                                    <svg class="group-hover:text-blue-600 shrink-0" width="20" height="20"
                                        viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.5"
                                            d="M21 15.9983V9.99826C21 7.16983 21 5.75562 20.1213 4.87694C19.3529 4.10856 18.175 4.01211 16 4H8C5.82497 4.01211 4.64706 4.10856 3.87868 4.87694C3 5.75562 3 7.16983 3 9.99826V15.9983C3 18.8267 3 20.2409 3.87868 21.1196C4.75736 21.9983 6.17157 21.9983 9 21.9983H15C17.8284 21.9983 19.2426 21.9983 20.1213 21.1196C21 20.2409 21 18.8267 21 15.9983Z"
                                            fill="currentColor" />
                                        <path
                                            d="M8 3.5C8 2.67157 8.67157 2 9.5 2H14.5C15.3284 2 16 2.67157 16 3.5V4.5C16 5.32843 15.3284 6 14.5 6H9.5C8.67157 6 8 5.32843 8 4.5V3.5Z"
                                            fill="currentColor" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M12 9.25C12.4142 9.25 12.75 9.58579 12.75 10V12.25L15 12.25C15.4142 12.25 15.75 12.5858 15.75 13C15.75 13.4142 15.4142 13.75 15 13.75L12.75 13.75L12.75 16C12.75 16.4142 12.4142 16.75 12 16.75C11.5858 16.75 11.25 16.4142 11.25 16L11.25 13.75H9C8.58579 13.75 8.25 13.4142 8.25 13C8.25 12.5858 8.58579 12.25 9 12.25L11.25 12.25L11.25 10C11.25 9.58579 11.5858 9.25 12 9.25Z"
                                            fill="currentColor" />
                                    </svg>
                                    <span class="ltr:pl-3 rtl:pr-3 text-gray-700 group-hover:text-blue-700">Journal des événements (main courante)</span>
                                </div>
                            </a>
                        </li>
                    </ul>
                </li>
                @endcan

                @canany(['Consulter liste entités', 'Consulter liste lieux', 'Consulter liste diffusion', 'Consulter liste impacts', 'Consulter liste nature événements'])
                <h2 class="py-3 px-7 flex items-center uppercase font-extrabold bg-gray-100 -mx-4 mb-1">
                    <svg class="w-4 h-5 flex-none hidden" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"
                        fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span class="text-gray-700">ADMINISTRATION</span>
                </h2>

                @can('Consulter liste entités')
                <li class="menu nav-item">
                    <a href="/entites/index" class="nav-link group">
                        <div class="flex items-center">
                            <svg class="group-hover:text-blue-600 shrink-0" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path opacity="0.5"
                                    d="M12 22C7.28595 22 4.92893 22 3.46447 20.5355C2 19.0711 2 16.714 2 12C2 7.28595 2 4.92893 3.46447 3.46447C4.92893 2 7.28595 2 12 2C16.714 2 19.0711 2 20.5355 3.46447C22 4.92893 22 7.28595 22 12C22 16.714 22 19.0711 20.5355 20.5355C19.0711 22 16.714 22 12 22Z"
                                    fill="currentColor" />
                                <path
                                    d="M18.75 8C18.75 8.41421 18.4142 8.75 18 8.75H6C5.58579 8.75 5.25 8.41421 5.25 8C5.25 7.58579 5.58579 7.25 6 7.25H18C18.4142 7.25 18.75 7.58579 18.75 8Z"
                                    fill="currentColor" />
                                <path
                                    d="M18.75 12C18.75 12.4142 18.4142 12.75 18 12.75H6C5.58579 12.75 5.25 12.4142 5.25 12C5.25 11.5858 5.58579 11.25 6 11.25H18C18.4142 11.25 18.75 11.5858 18.75 12Z"
                                    fill="currentColor" />
                                <path
                                    d="M18.75 16C18.75 16.4142 18.4142 16.75 18 16.75H6C5.58579 16.75 5.25 16.4142 5.25 16C5.25 15.5858 5.58579 15.25 6 15.25H18C18.4142 15.25 18.75 15.5858 18.75 16Z"
                                    fill="currentColor" />
                            </svg>
                            <span class="ltr:pl-3 rtl:pr-3 text-gray-700 group-hover:text-blue-700">Entité</span>
                        </div>
                    </a>
                </li>
                @endcan

                @can('Consulter liste lieux')
                <li class="menu nav-item">
                    <a href="/locations" class="nav-link group">
                        <div class="flex items-center">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path opacity="0.5" d="M19.7165 20.3624C21.143 19.5846 22 18.5873 22 17.5C22 16.3475 21.0372 15.2961 19.4537 14.5C17.6226 13.5794 14.9617 13 12 13C9.03833 13 6.37738 13.5794 4.54631 14.5C2.96285 15.2961 2 16.3475 2 17.5C2 18.6525 2.96285 19.7039 4.54631 20.5C6.37738 21.4206 9.03833 22 12 22C15.1066 22 17.8823 21.3625 19.7165 20.3624Z" fill="currentColor"></path>
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M5 8.51464C5 4.9167 8.13401 2 12 2C15.866 2 19 4.9167 19 8.51464C19 12.0844 16.7658 16.2499 13.2801 17.7396C12.4675 18.0868 11.5325 18.0868 10.7199 17.7396C7.23416 16.2499 5 12.0844 5 8.51464ZM12 11C13.1046 11 14 10.1046 14 9C14 7.89543 13.1046 7 12 7C10.8954 7 10 7.89543 10 9C10 10.1046 10.8954 11 12 11Z" fill="currentColor"></path>
                            </svg>
                            <span class="ltr:pl-3 rtl:pr-3 text-gray-700 group-hover:text-blue-700">Localisations</span>
                        </div>
                    </a>
                </li>
                @endcan

                @can('Consulter liste diffusion')
                <li class="menu nav-item">
                    <a href="/diffusions" class="nav-link group">
                        <div class="flex items-center">
                            <svg class="group-hover:text-blue-600 shrink-0" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path opacity="0.5"
                                    d="M2 12C2 8.22876 2 6.34315 3.17157 5.17157C4.34315 4 6.22876 4 10 4H14C17.7712 4 19.6569 4 20.8284 5.17157C22 6.34315 22 8.22876 22 12C22 15.7712 22 17.6569 20.8284 18.8284C19.6569 20 17.7712 20 14 20H10C6.22876 20 4.34315 20 3.17157 18.8284C2 17.6569 2 15.7712 2 12Z"
                                    fill="currentColor" />
                                <path
                                    d="M6 8C6.55228 8 7 8.44772 7 9V15C7 15.5523 6.55228 16 6 16C5.44772 16 5 15.5523 5 15V9C5 8.44772 5.44772 8 6 8Z"
                                    fill="currentColor" />
                                <path
                                    d="M10 10C10.5523 10 11 10.4477 11 11V13C11 13.5523 10.5523 14 10 14C9.44772 14 9 13.5523 9 13V11C9 10.4477 9.44772 10 10 10Z"
                                    fill="currentColor" />
                                <path
                                    d="M14 6C14.5523 6 15 6.44772 15 7V17C15 17.5523 14.5523 18 14 18C13.4477 18 13 17.5523 13 17V7C13 6.44772 13.4477 6 14 6Z"
                                    fill="currentColor" />
                                <path
                                    d="M18 9C18.5523 9 19 9.44772 19 10V14C19 14.5523 18.5523 15 18 15C17.4477 15 17 14.5523 17 14V10C17 9.44772 17.4477 9 18 9Z"
                                    fill="currentColor" />
                            </svg>
                            <span class="ltr:pl-3 rtl:pr-3 text-gray-700 group-hover:text-blue-700">Diffusion</span>
                        </div>
                    </a>
                </li>
                @endcan

                @can('Consulter liste impacts')
                <li class="menu nav-item">
                    <a href="/impacts" class="nav-link group">
                        <div class="flex items-center">
                            <svg class="group-hover:text-blue-600 shrink-0" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path opacity="0.5"
                                    d="M12 22C7.28595 22 4.92893 22 3.46447 20.5355C2 19.0711 2 16.714 2 12C2 7.28595 2 4.92893 3.46447 3.46447C4.92893 2 7.28595 2 12 2C16.714 2 19.0711 2 20.5355 3.46447C22 4.92893 22 7.28595 22 12C22 16.714 22 19.0711 20.5355 20.5355C19.0711 22 16.714 22 12 22Z"
                                    fill="currentColor" />
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M12 7.25C12.4142 7.25 12.75 7.58579 12.75 8V11.1893L15.4697 8.46967C15.7626 8.17678 16.2374 8.17678 16.5303 8.46967C16.8232 8.76256 16.8232 9.23744 16.5303 9.53033L13.8107 12.25H16C16.4142 12.25 16.75 12.5858 16.75 13C16.75 13.4142 16.4142 13.75 16 13.75H13.8107L16.5303 16.4697C16.8232 16.7626 16.8232 17.2374 16.5303 17.5303C16.2374 17.8232 15.7626 17.8232 15.4697 17.5303L12.75 14.8107V17C12.75 17.4142 12.4142 17.75 12 17.75C11.5858 17.75 11.25 17.4142 11.25 17V14.8107L8.53033 17.5303C8.23744 17.8232 7.76256 17.8232 7.46967 17.5303C7.17678 17.2374 7.17678 16.7626 7.46967 16.4697L10.1893 13.75H8C7.58579 13.75 7.25 13.4142 7.25 13C7.25 12.5858 7.58579 12.25 8 12.25H10.1893L7.46967 9.53033C7.17678 9.23744 7.17678 8.76256 7.46967 8.46967C7.76256 8.17678 8.23744 8.17678 8.53033 8.46967L11.25 11.1893V8C11.25 7.58579 11.5858 7.25 12 7.25Z"
                                    fill="currentColor" />
                            </svg>
                            <span class="ltr:pl-3 rtl:pr-3 text-gray-700 group-hover:text-blue-700">Impact</span>
                        </div>
                    </a>
                </li>
                @endcan

                @can('Consulter liste nature événements')
                <li class="menu nav-item">
                    <a href="/natures" class="nav-link group">
                        <div class="flex items-center">
                            <svg class="group-hover:text-blue-600 shrink-0" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M8.42229 20.6181C10.1779 21.5395 11.0557 22.0001 12 22.0001V12.0001L2.63802 7.07275C2.62423 7.09491 2.6107 7.11727 2.5974 7.13986C2 8.15436 2 9.41678 2 11.9416V12.0586C2 14.5834 2 15.8459 2.5974 16.8604C3.19479 17.8749 4.27063 18.4395 6.42229 19.5686L8.42229 20.6181Z"
                                    fill="currentColor" />
                                <path opacity="0.7"
                                    d="M17.5777 20.6181L15.5777 19.5686C13.4261 18.4395 12.3503 17.8749 11.753 16.8604C11.1556 15.8459 11.1556 14.5834 11.1556 12.0586V11.9416C11.1556 9.41678 11.1556 8.15436 11.753 7.13986C12.3503 6.12537 13.4261 5.56078 15.5777 4.43161L17.5777 3.38208C19.3334 2.46066 20.2112 2.00001 21.1556 2.46408C22.1 2.92816 22.1 4.18578 22.1 6.70102V17.2991C22.1 19.8143 22.1 21.0719 21.1556 21.536C20.2112 22.0001 19.3334 21.5395 17.5777 20.6181Z"
                                    fill="currentColor" />
                                <path opacity="0.4"
                                    d="M8.42229 20.6181C10.1779 21.5395 11.0557 22.0001 12 22.0001C12.9443 22.0001 13.8221 21.5395 15.5777 20.6181L17.5777 19.5686C19.7294 18.4395 20.8052 17.8749 21.4026 16.8604C22 15.8459 22 14.5834 22 12.0586V11.9416C22 9.41678 22 8.15436 21.4026 7.13986C20.8052 6.12537 19.7294 5.56078 17.5777 4.43161L15.5777 3.38208C13.8221 2.46066 12.9443 2.00001 12 2.00001C11.0557 2.00001 10.1779 2.46066 8.42229 3.38208L6.42229 4.43161C4.27063 5.56078 3.19479 6.12537 2.5974 7.13986C2 8.15436 2 9.41678 2 11.9416V12.0586C2 14.5834 2 15.8459 2.5974 16.8604C3.19479 17.8749 4.27063 18.4395 6.42229 19.5686L8.42229 20.6181Z"
                                    fill="currentColor" />
                            </svg>
                            <span class="ltr:pl-3 rtl:pr-3 text-gray-700 group-hover:text-blue-700">Nature des événements</span>
                        </div>
                    </a>
                </li>
                @endcan
                @endcanany

                @canany(['Consulter liste utilisateurs', 'Consulter liste rôles','Consulter liste privilège'])
                <h2 class="py-3 px-7 flex items-center uppercase font-extrabold bg-gray-100 -mx-4 mb-1">
                    <svg class="w-4 h-5 flex-none hidden" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span class="text-gray-700">GESTION UTILISATEURS</span>
                </h2>

                <li class="menu nav-item">
                    <button type="button" class="nav-link group" :class="{ 'active': activeDropdown === 'users' }"
                        @click="activeDropdown === 'users' ? activeDropdown = null : activeDropdown = 'users'">
                        <div class="flex items-center">
                            <svg class="group-hover:text-blue-600 shrink-0" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle opacity="0.5" cx="15" cy="6" r="3" fill="currentColor" />
                                <ellipse opacity="0.5" cx="16" cy="17" rx="5" ry="3" fill="currentColor" />
                                <circle cx="9.00098" cy="6" r="4" fill="currentColor" />
                                <ellipse cx="9.00098" cy="17.001" rx="7" ry="4" fill="currentColor" />
                            </svg>
                            <span class="ltr:pl-3 rtl:pr-3 text-gray-700 group-hover:text-blue-700">UTILISATEURS</span>
                        </div>
                        <div class="rtl:rotate-180" :class="{ '!rotate-90': activeDropdown === 'users' }">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                    </button>
                    <ul x-cloak x-show="activeDropdown === 'users'" x-collapse class="sub-menu text-gray-500">
                        @can('Consulter liste utilisateurs')
                        <li>
                            <a href="/users/index" class="hover:text-blue-600">Utilisateurs</a>
                        </li>
                        @endcan
                        @can('Consulter liste rôles')
                        <li>
                            <a href="/roles/index" class="hover:text-blue-600">Rôles</a>
                        </li>
                        @endcan
                        @can('Consulter liste privilège')
                        <li>
                            <a href="/permissions/index" class="hover:text-blue-600">Privilèges</a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcanany

                <li class="menu nav-item">
                    <button type="button" class="nav-link group"
                        :class="{ 'active': activeDropdown === 'authentication' }"
                        @click="activeDropdown === 'authentication' ? activeDropdown = null : activeDropdown = 'authentication'">
                        <div class="flex items-center">
                            <svg class="group-hover:text-blue-600 shrink-0" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path opacity="0.5"
                                    d="M2 16C2 13.1716 2 11.7574 2.87868 10.8787C3.75736 10 5.17157 10 8 10H16C18.8284 10 20.2426 10 21.1213 10.8787C22 11.7574 22 13.1716 22 16C22 18.8284 22 20.2426 21.1213 21.1213C20.2426 22 18.8284 22 16 22H8C5.17157 22 3.75736 22 2.87868 21.1213C2 20.2426 2 18.8284 2 16Z"
                                    fill="currentColor" />
                                <path
                                    d="M8 17C8.55228 17 9 16.5523 9 16C9 15.4477 8.55228 15 8 15C7.44772 15 7 15.4477 7 16C7 16.5523 7.44772 17 8 17Z"
                                    fill="currentColor" />
                                <path
                                    d="M12 17C12.5523 17 13 16.5523 13 16C13 15.4477 12.5523 15 12 15C11.4477 15 11 15.4477 11 16C11 16.5523 11.4477 17 12 17Z"
                                    fill="currentColor" />
                                <path
                                    d="M17 16C17 16.5523 16.5523 17 16 17C15.4477 17 15 16.5523 15 16C15 15.4477 15.4477 15 16 15C16.5523 15 17 15.4477 17 16Z"
                                    fill="currentColor" />
                                <path
                                    d="M6.75 8C6.75 5.10051 9.10051 2.75 12 2.75C14.8995 2.75 17.25 5.10051 17.25 8V10.0036C17.8174 10.0089 18.3135 10.022 18.75 10.0546V8C18.75 4.27208 15.7279 1.25 12 1.25C8.27208 1.25 5.25 4.27208 5.25 8V10.0546C5.68651 10.022 6.18264 10.0089 6.75 10.0036V8Z"
                                    fill="currentColor" />
                            </svg>
                            <span class="ltr:pl-3 rtl:pr-3 text-gray-700 group-hover:text-blue-700">Authentication</span>
                        </div>
                        <div class="rtl:rotate-180" :class="{ '!rotate-90': activeDropdown === 'authentication' }">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                    </button>
                    <ul x-cloak x-show="activeDropdown === 'authentication'" x-collapse class="sub-menu text-gray-500">
                        <li>
                            <form method="POST" action="{{ route('auth.logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left hover:text-red-600">Déconnexion</button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</div>

<script>
    document.addEventListener("alpine:init", () => {
        Alpine.data("sidebar", () => ({
            init() {
                const selector = document.querySelector('.sidebar ul a[href="' + window.location
                    .pathname + '"]');
                if (selector) {
                    selector.classList.add('active');
                    const ul = selector.closest('ul.sub-menu');
                    if (ul) {
                        let ele = ul.closest('li.menu').querySelectorAll('.nav-link');
                        if (ele) {
                            ele = ele[0];
                            setTimeout(() => {
                                ele.click();
                            });
                        }
                    }
                }
            },
        }));
    });
</script>
