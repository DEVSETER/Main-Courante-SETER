<div :class="{ 'dark text-white-dark': $store.app.semidark }">
    <nav x-data="sidebar"
        class="sidebar fixed min-h-screen h-full top-0 bottom-0 w-[260px] shadow-[5px_0_25px_0_rgba(94,92,154,0.1)] z-50 transition-all duration-300">
        <div class="bg-white dark:bg-[#0e1726] h-full">
            <div class="flex justify-between items-center px-4 py-3">
                <a href="/dashboard" class="main-logo flex items-center shrink-0">
                    {{-- <span class="text-2xl ltr:ml-1.5 rtl:mr-1.5  font-semibold  align-middle lg:inline dark:text-white-light">SETER
                    </span> --}}
                    <img class="w-8 ml-[25px]  flex-none" src="/assets/images/logo.png" style="width:7rem;margin-left: 20px"
                        alt="image" />
                </a>
                <a href="javascript:;"
                    class="collapse-icon w-8 h-8 rounded-full flex items-center hover:bg-gray-500/10 dark:hover:bg-dark-light/10 dark:text-white-light transition duration-300 rtl:rotate-180"
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
            <ul class="perfect-scrollbar relative font-semibold space-y-0.5 h-[calc(100vh-80px)] overflow-y-auto overflow-x-hidden  p-4 py-0"
                x-data="{ activeDropdown: null }">
                <li class="menu nav-item">
                    @can('Consulter tableau de bord')

                    <button type="button" class="nav-link group" :class="{ 'active': activeDropdown === 'dashboard' }"
                        @click="activeDropdown === 'dashboard' ? activeDropdown = null : activeDropdown = 'dashboard'">
                        <div class="flex items-center">

                            <svg class="group-hover:!text-primary shrink-0" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path opacity="0.5"
                                    d="M2 12.2039C2 9.91549 2 8.77128 2.5192 7.82274C3.0384 6.87421 3.98695 6.28551 5.88403 5.10813L7.88403 3.86687C9.88939 2.62229 10.8921 2 12 2C13.1079 2 14.1106 2.62229 16.116 3.86687L18.116 5.10812C20.0131 6.28551 20.9616 6.87421 21.4808 7.82274C22 8.77128 22 9.91549 22 12.2039V13.725C22 17.6258 22 19.5763 20.8284 20.7881C19.6569 22 17.7712 22 14 22H10C6.22876 22 4.34315 22 3.17157 20.7881C2 19.5763 2 17.6258 2 13.725V12.2039Z"
                                    fill="currentColor" />
                                <path
                                    d="M9 17.25C8.58579 17.25 8.25 17.5858 8.25 18C8.25 18.4142 8.58579 18.75 9 18.75H15C15.4142 18.75 15.75 18.4142 15.75 18C15.75 17.5858 15.4142 17.25 15 17.25H9Z"
                                    fill="currentColor" />
                            </svg>

                            <span
                                class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark"> Tableau de bord</span>
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
                            <a href="/dashboard">Tableau de bord</a>
                        </li>
                        {{-- <li>
                            <a href="/analytics">Analytics</a>
                        </li>
                        <li>
                            <a href="/finance">Finance</a>
                        </li>
                        <li>
                            <a href="/crypto">Crypto</a>
                        </li> --}}
                    </ul>
                </li>
                    @endcan
                        @can('Consulter liste événements')

                <h2
                    class="py-3 px-7 flex items-center uppercase font-extrabold bg-white-light/30 dark:bg-dark dark:bg-opacity-[0.08] -mx-4 mb-1">

                    <svg class="w-4 h-5 flex-none hidden" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"
                        fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span>Événements</span>
                </h2>

                <li class="nav-item">
                    <ul>



                         <li class="nav-item">
                            <a href="/evenements" class="group">
                                <div class="flex items-center">

                                    <svg class="group-hover:!text-primary shrink-0" width="20" height="20"
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
                                    <span
                                        class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Journal des événements (main courante)</span>
                                </div>
                            </a>
                        </li>

                    </ul>
                    @endcan
                </li>
                @canany(['Consulter liste entités', 'Consulter liste lieux', 'Consulter liste diffusion', 'Consulter liste impacts', 'Consulter liste nature événements'])
   <h2
                    class="py-3 px-7 flex items-center uppercase font-extrabold bg-white-light/30 dark:bg-dark dark:bg-opacity-[0.08] -mx-4 mb-1">

                    <svg class="w-4 h-5 flex-none hidden" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"
                        fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span>ADMINISTRATION</span>

                </h2>
                @can('Consulter liste entités')

   <li class="menu nav-item">
                    <a href="/entites/index" class="nav-link group">
                        <div class="flex items-center">

                            <svg class="group-hover:!text-primary shrink-0" width="20" height="20" viewBox="0 0 24 24"
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
                            <span
                                class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Entité</span>
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
                            <span
                                class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Localisations</span>
                        </div>
                    </a>
                </li>
                @endcan

                <li class="menu nav-item">
                    @can('Consulter liste diffusion')


                    <a href="/liste_diffusions" class="nav-link group">
                        <div class="flex items-center">

                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M16.9456 2.84731C18.3542 2.14979 19.0585 1.80104 19.5345 2.11769C20.0104 2.43435 19.9427 3.20671 19.8074 4.75143L19.7724 5.15106C19.7339 5.59003 19.7147 5.80951 19.7834 6.00845C19.852 6.2074 20.0008 6.36329 20.2984 6.67507L20.5694 6.95892C21.6166 8.05609 22.1402 8.60468 21.9676 9.16677C21.795 9.72887 21.0405 9.93221 19.5315 10.3389L19.1411 10.4441C18.7123 10.5597 18.4979 10.6175 18.3269 10.7517C18.156 10.8859 18.0478 11.0814 17.8314 11.4723L17.6344 11.8281C16.873 13.2038 16.4924 13.8916 15.9098 13.9223C15.3272 13.953 14.9285 13.3063 14.1312 12.013L13.925 11.6784C13.6984 11.3108 13.5851 11.1271 13.4108 11.0111C13.2365 10.8951 13.0208 10.86 12.5895 10.7898L12.1968 10.7259C10.6791 10.4789 9.92016 10.3554 9.7327 9.81228C9.54524 9.26918 10.0534 8.66616 11.0696 7.46012L11.3325 7.14811C11.6213 6.80539 11.7657 6.63403 11.8289 6.42812C11.8921 6.22222 11.867 6.00508 11.8168 5.57079L11.7711 5.17542C11.5945 3.64716 11.5062 2.88303 11.9729 2.51664C12.4396 2.15025 13.1523 2.42425 14.5776 2.97224L14.9464 3.11402C15.3514 3.26974 15.554 3.3476 15.7674 3.33634C15.9808 3.32508 16.1809 3.22598 16.5812 3.02776L16.9456 2.84731Z" fill="currentColor"></path>
                                                <g opacity="0.5">
                                                    <path d="M9.04452 11.3203C5.99048 13.2697 3.27111 16.7967 2.0908 20.0321C1.70785 21.0818 2.59069 22.0006 3.71668 22.0006H4.75C4.75007 21.6498 4.83224 21.2139 4.95372 20.7564C5.07876 20.2855 5.25886 19.743 5.48334 19.1616C5.93221 17.9992 6.57058 16.6505 7.33621 15.3652C8.09909 14.0845 9.0062 12.8366 10.0012 11.8992C10.0258 11.876 10.0506 11.853 10.0754 11.83C10.052 11.8229 10.0289 11.8157 10.0062 11.8084C9.72191 11.7169 9.36664 11.5713 9.04452 11.3203Z" fill="currentColor"></path>
                                                    <path d="M12.0202 12.2173C11.7015 12.4123 11.3705 12.67 11.0298 12.991C10.1729 13.7983 9.34809 14.9188 8.62489 16.1329C7.90444 17.3423 7.30253 18.6146 6.88264 19.7019C6.67275 20.2455 6.51136 20.7351 6.40349 21.1413C6.29223 21.5604 6.25008 21.8464 6.25 22.0006H9.08304C9.08314 20.8766 9.47243 18.7949 10.1769 16.7088C10.6939 15.1781 11.4097 13.5555 12.3322 12.2681L12.0202 12.2173Z" fill="currentColor"></path>
                                                    <path d="M13.2982 13.5134C12.6225 14.5571 12.0472 15.8587 11.5981 17.1888C10.9202 19.1961 10.5832 21.1042 10.583 22.0006H11.8718C12.9978 22.0006 13.9043 21.0942 13.9793 19.9804C14.1081 18.0663 14.4036 16.3411 14.7411 15.1142C14.407 14.918 14.1488 14.6602 13.9589 14.4372C13.7399 14.1801 13.5196 13.859 13.2982 13.5134Z" fill="currentColor"></path>
                                                </g>
                                            </svg>
                            <span
                            class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Liste de Difusion</span>
                        </div>
                    </a>
                      @endcan
                </li>
                @can('Consulter liste impacts')

                <li class="menu nav-item">
                 <a href="/impacts" class="nav-link group">
                     <div class="flex items-center">

                         <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                             <path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C8.22876 2 6.34315 2 5.17157 3.17157C4.10848 4.23467 4.01004 5.8857 4.00093 9H3C2.44772 9 2 9.44772 2 10V11C2 11.3148 2.14819 11.6111 2.4 11.8L4 13C4.00911 16.1143 4.10848 17.7653 5.17157 18.8284C5.41375 19.0706 5.68645 19.2627 6 19.4151V20.9999C6 21.5522 6.44772 21.9999 7 21.9999H8.5C9.05228 21.9999 9.5 21.5522 9.5 20.9999V19.9815C10.2271 20 11.0542 20 12 20C12.9458 20 13.7729 20 14.5 19.9815V20.9999C14.5 21.5522 14.9477 21.9999 15.5 21.9999H17C17.5523 21.9999 18 21.5522 18 20.9999V19.4151C18.3136 19.2627 18.5862 19.0706 18.8284 18.8284C19.8915 17.7653 19.9909 16.1143 20 13L21.6 11.8C21.8518 11.6111 22 11.3148 22 11V10C22 9.44772 21.5523 9 21 9H19.9991C19.99 5.8857 19.8915 4.23467 18.8284 3.17157C17.6569 2 15.7712 2 12 2ZM5.5 9.5C5.5 10.9142 5.5 11.6213 5.93934 12.0607C6.37868 12.5 7.08579 12.5 8.5 12.5H12H15.5C16.9142 12.5 17.6213 12.5 18.0607 12.0607C18.5 11.6213 18.5 10.9142 18.5 9.5V7C18.5 5.58579 18.5 4.87868 18.0607 4.43934C17.6213 4 16.9142 4 15.5 4H12H8.5C7.08579 4 6.37868 4 5.93934 4.43934C5.5 4.87868 5.5 5.58579 5.5 7V9.5ZM6.25 16C6.25 15.5858 6.58579 15.25 7 15.25H8.5C8.91421 15.25 9.25 15.5858 9.25 16C9.25 16.4142 8.91421 16.75 8.5 16.75H7C6.58579 16.75 6.25 16.4142 6.25 16ZM17.75 16C17.75 15.5858 17.4142 15.25 17 15.25H15.5C15.0858 15.25 14.75 15.5858 14.75 16C14.75 16.4142 15.0858 16.75 15.5 16.75H17C17.4142 16.75 17.75 16.4142 17.75 16Z" fill="currentColor"></path>
                                         </svg>
                         <span
                             class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Type D'impact</span>
                     </div>
                 </a>
             </li>
                @endcan

                @can('Consulter liste nature événements')

             <li class="menu nav-item">
                 <a href="/nature_evenements" class="nav-link group">
                     <div class="flex items-center">

                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M12.858 20H10.221C6.3456 20 4.40789 20 3.20394 18.8284C2 17.6569 2 15.7712 2 12C2 8.22876 2 6.34315 3.20394 5.17157C4.40789 4 6.34561 4 10.221 4H12.858C15.0854 4 16.1992 4 17.1289 4.50143C18.0586 5.00286 18.6488 5.92191 19.8294 7.76001L20.5102 8.82001C21.5034 10.3664 22 11.1396 22 12C22 12.8604 21.5034 13.6336 20.5102 15.18L19.8294 16.24C18.6488 18.0781 18.0586 18.9971 17.1289 19.4986C16.1992 20 15.0854 20 12.858 20ZM7 7.05423C7.41421 7.05423 7.75 7.37026 7.75 7.76011V16.2353C7.75 16.6251 7.41421 16.9412 7 16.9412C6.58579 16.9412 6.25 16.6251 6.25 16.2353V7.76011C6.25 7.37026 6.58579 7.05423 7 7.05423Z" fill="currentColor"></path>
                                            </svg>
                         <span
                             class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Nature évènement</span>
                     </div>
                 </a>
             </li>
                @endcan
                @endcanany

@can('Consulter rapports')


 <h2
                    class="py-3 px-7 flex items-center uppercase font-extrabold bg-white-light/30 dark:bg-dark dark:bg-opacity-[0.08] -mx-4 mb-1">

                    <svg class="w-4 h-5 flex-none hidden" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"
                        fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span>RAPPORTS</span>

                </h2>
        <li class="menu nav-item">
                    <a href="/rapports" class="nav-link group">
                        <div class="flex items-center">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M3 15C3 17.8284 3 19.2426 3.87868 20.1213C4.75736 21 6.17157 21 9 21H15C17.8284 21 19.2426 21 20.1213 20.1213C21 19.2426 21 17.8284 21 15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                <path d="M12 16V3M12 3L16 7.375M12 3L8 7.375" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                            </svg>


                            <span
                                class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark"> Export PDF / Excel</span>
                        </div>
                    </a>
                </li>
                @can('Consulter liste archive')

                <li class="menu nav-item">
                    <a href="/archive" class="nav-link group">
                        <div class="flex items-center">

                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M22 14V11.7979C22 11.4227 21.9978 10.75 21.9978 10.75L22 10H2V10.75V14C2 17.7712 2 19.6569 3.17157 20.8284C4.34315 22 6.22876 22 10 22H14C17.7712 22 19.6569 22 20.8284 20.8284C22 19.6569 22 17.7712 22 14Z" fill="currentColor"></path>
                                <path opacity="0.5" d="M11 4L10.4497 3.44975C10.1763 3.17633 10.0396 3.03961 9.89594 2.92051C9.27652 2.40704 8.51665 2.09229 7.71557 2.01738C7.52976 2 7.33642 2 6.94975 2C6.06722 2 5.62595 2 5.25839 2.06935C3.64031 2.37464 2.37464 3.64031 2.06935 5.25839C2 5.62595 2 6.06722 2 6.94975V9.25V10H22L21.9531 9.25C21.8809 8.20117 21.6973 7.51276 21.2305 6.99383C21.1598 6.91514 21.0849 6.84024 21.0062 6.76946C20.1506 6 18.8345 6 16.2021 6H15.8284C14.6747 6 14.0979 6 13.5604 5.84678C13.2651 5.7626 12.9804 5.64471 12.7121 5.49543C12.2237 5.22367 11.8158 4.81578 11 4Z" fill="currentColor"></path>
                            </svg>
                            <span
                            class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark"> Archive des événements</span>
                        </div>
                    </a>
                </li>
                @endcan

 <li class="menu nav-item">
                    <a href="rapports" class="nav-link group">
                        <div class="flex items-center">
 <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M3.46447 3.46447C2 4.92893 2 7.28595 2 12C2 16.714 2 19.0711 3.46447 20.5355C4.92893 22 7.28595 22 12 22C16.714 22 19.0711 22 20.5355 20.5355C22 19.0711 22 16.714 22 12C22 7.28595 22 4.92893 20.5355 3.46447C19.0711 2 16.714 2 12 2C7.28595 2 4.92893 2 3.46447 3.46447ZM10.5431 7.51724C10.8288 7.2173 10.8172 6.74256 10.5172 6.4569C10.2173 6.17123 9.74256 6.18281 9.4569 6.48276L7.14286 8.9125L6.5431 8.28276C6.25744 7.98281 5.78271 7.97123 5.48276 8.2569C5.18281 8.54256 5.17123 9.01729 5.4569 9.31724L6.59976 10.5172C6.74131 10.6659 6.9376 10.75 7.14286 10.75C7.34812 10.75 7.5444 10.6659 7.68596 10.5172L10.5431 7.51724ZM13 8.25C12.5858 8.25 12.25 8.58579 12.25 9C12.25 9.41422 12.5858 9.75 13 9.75H18C18.4142 9.75 18.75 9.41422 18.75 9C18.75 8.58579 18.4142 8.25 18 8.25H13ZM10.5431 14.5172C10.8288 14.2173 10.8172 13.7426 10.5172 13.4569C10.2173 13.1712 9.74256 13.1828 9.4569 13.4828L7.14286 15.9125L6.5431 15.2828C6.25744 14.9828 5.78271 14.9712 5.48276 15.2569C5.18281 15.5426 5.17123 16.0173 5.4569 16.3172L6.59976 17.5172C6.74131 17.6659 6.9376 17.75 7.14286 17.75C7.34812 17.75 7.5444 17.6659 7.68596 17.5172L10.5431 14.5172ZM13 15.25C12.5858 15.25 12.25 15.5858 12.25 16C12.25 16.4142 12.5858 16.75 13 16.75H18C18.4142 16.75 18.75 16.4142 18.75 16C18.75 15.5858 18.4142 15.25 18 15.25H13Z" fill="currentColor"></path>
                                            </svg>

                            <span
                                class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark"> Générer un rapport</span>
                        </div>
                    </a>
                </li>


@endcan
@canany(['Consulter liste utilisateurs', 'Consulter liste rôles','Consulter liste privilège'])

                <h2
                    class="py-3 px-7 flex items-center uppercase font-extrabold bg-white-light/30 dark:bg-dark dark:bg-opacity-[0.08] -mx-4 mb-1">

                    <svg class="w-4 h-5 flex-none hidden" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span>GESTION UTILISATEURS</span>
                </h2>

                <li class="menu nav-item">

                    <button type="button" class="nav-link group" :class="{ 'active': activeDropdown === 'users' }"
                        @click="activeDropdown === 'users' ? activeDropdown = null : activeDropdown = 'users'">
                        <div class="flex items-center">

                            <svg class="group-hover:!text-primary shrink-0" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle opacity="0.5" cx="15" cy="6" r="3"
                                    fill="currentColor" />
                                <ellipse opacity="0.5" cx="16" cy="17" rx="5" ry="3"
                                    fill="currentColor" />
                                <circle cx="9.00098" cy="6" r="4" fill="currentColor" />
                                <ellipse cx="9.00098" cy="17.001" rx="7" ry="4"
                                    fill="currentColor" />
                            </svg>
                            <span
                                class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">UTILISATEURS</span>
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

                        <li>
                            @can('Consulter liste utilisateurs')

                            <a href="/users/index"> Utilisateurs</a>
                            @endcan
                        </li>
                        <li>
                            @can('Consulter liste rôles')

                            <a href="/roles/index">Rôles </a>
                            @endcan
                        </li>
                         <li>
                            @can('Consulter liste privilège')

                            <a href="/permissions/index">Privilèges </a>
                            @endcan
                        </li>
                    </ul>
                </li>
@endcanany
                <li class="menu nav-item">
                    <button type="button" class="nav-link group"
                        :class="{ 'active': activeDropdown === 'authentication' }"
                        @click="activeDropdown === 'authentication' ? activeDropdown = null : activeDropdown = 'authentication'">
                        <div class="flex items-center">

                            <svg class="group-hover:!text-primary shrink-0" width="20" height="20" viewBox="0 0 24 24"
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
                            <span
                                class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Authentication</span>
                        </div>
                        <div class="rtl:rotate-180" :class="{ '!rotate-90': activeDropdown === 'authentication' }">

                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                    </button>
                    <ul x-cloak x-show="activeDropdown === 'authentication'" x-collapse
                        class="sub-menu text-gray-500">
                        <li>
                            <a href="/auth/boxed-signin" target="_blank">Deconnexion</a>
                        </li>

                        <li>
                            <a href="/auth/cover-password-reset" target="_blank">Recover ID Cover</a>
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
