<nav x-data="{ open: false, dropdownOpen: false }" class="bg-white border-b border-gray-200 sticky top-0 z-40 shadow-sm font-sans">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">

            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="/" class="flex items-center gap-2 group">
                        <div class="bg-[#222831] p-1.5 rounded-lg shadow-md transition-transform group-hover:rotate-6">
                            <img src="{{ asset('img/Logo.png') }}" class="block h-8 w-auto brightness-0 invert" alt="Logo" />
                        </div>
                        <span class="font-serif text-xl font-bold text-[#222831] tracking-wide group-hover:text-gray-700 transition">
                            Kost <span class="text-[#DFD0B8]">Lolita</span>
                        </span>
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    @php
                        $navClass = "inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out h-full";
                        $activeStyle = "border-[#DFD0B8] text-[#222831] font-bold";
                        $inactiveStyle = "border-transparent text-gray-500 hover:text-[#222831] hover:border-gray-300";
                    @endphp

                    @if(auth()->user()->role !== 'admin')
                        <a href="{{ route('user.orders.index') }}"
                           class="{{ $navClass }} {{ request()->routeIs('user.orders.*') ? $activeStyle : $inactiveStyle }}">
                            {{ __('Status Pesanan') }}
                        </a>
                    @endif
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <div class="relative">
                    <button @click="dropdownOpen = !dropdownOpen" @click.away="dropdownOpen = false" class="inline-flex items-center gap-3 px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-full text-gray-500 bg-gray-50 hover:text-[#222831] hover:bg-[#DFD0B8]/20 focus:outline-none transition ease-in-out duration-150">

                        <div class="text-right hidden md:block">
                            <div class="font-bold text-[#222831]">{{ Auth::user()->name }}</div>
                            <div class="text-xs text-gray-400">
                                @if(auth()->user()->role === 'admin')
                                    Admin
                                @else
                                    Pengguna
                                @endif
                            </div>
                        </div>

                        <img class="h-9 w-9 rounded-full object-cover border-2 border-[#DFD0B8]"
                             src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=222831&color=DFD0B8&bold=true"
                             alt="{{ Auth::user()->name }}">

                        <div class="ms-1">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </button>

                    <div x-show="dropdownOpen"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-48 rounded-xl shadow-lg bg-white ring-1 ring-black ring-opacity-5 py-1 z-50"
                         style="display: none;">

                        @php
                            $dashboardRoute = auth()->user()->role === 'admin' ? 'admin.dashboard' : 'user.dashboard';
                        @endphp

                        <a href="{{ route($dashboardRoute) }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-[#DFD0B8]/20 hover:text-[#222831] transition-colors">
                            <i class="fa-regular fa-chart-bar mr-2 text-gray-400"></i> {{ __('Dashboard') }}
                        </a>

                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-[#DFD0B8]/20 hover:text-[#222831] transition-colors">
                            <i class="fa-regular fa-user mr-2 text-gray-400"></i> {{ __('Profile') }}
                        </a>

                        <div class="border-t border-gray-100"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a href="{{ route('logout') }}"
                               onclick="event.preventDefault(); this.closest('form').submit();"
                               class="block px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                <i class="fa-solid fa-arrow-right-from-bracket mr-2"></i> {{ __('Log Out') }}
                            </a>
                        </form>
                    </div>
                </div>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-[#222831] hover:bg-gray-100 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white border-t border-gray-100">
        <div class="pt-2 pb-3 space-y-1">
            @php
                $respClass = "block w-full ps-3 pe-4 py-2 border-l-4 text-start text-base font-medium transition duration-150 ease-in-out";
                $respActive = "border-[#222831] text-[#222831] bg-[#DFD0B8]/20 font-bold";
                $respInactive = "border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300";
            @endphp

            @if(auth()->user()->role !== 'admin')
                <a href="{{ route('user.orders.index') }}" class="{{ $respClass }} {{ request()->routeIs('user.orders.*') ? $respActive : $respInactive }}">
                    {{ __('Status Pesanan') }}
                </a>
            @endif
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200 bg-gray-50">
            <div class="px-4 flex items-center gap-3 mb-3">
                <img class="h-10 w-10 rounded-full object-cover border border-white shadow-sm"
                     src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=222831&color=DFD0B8&bold=true"
                     alt="">
                <div>
                    <div class="font-bold text-base text-[#222831]">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                @php
                    $dashboardRoute = auth()->user()->role === 'admin' ? 'admin.dashboard' : 'user.dashboard';
                @endphp

                <a href="{{ route($dashboardRoute) }}" class="{{ $respClass }} border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300">
                    {{ __('Dashboard') }}
                </a>

                <a href="{{ route('profile.edit') }}" class="{{ $respClass }} border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300">
                    {{ __('Profile') }}
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}"
                       onclick="event.preventDefault(); this.closest('form').submit();"
                       class="block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-red-600 hover:text-red-800 hover:bg-red-50 hover:border-red-200 transition duration-150 ease-in-out">
                        {{ __('Log Out') }}
                    </a>
                </form>
            </div>
        </div>
    </div>
</nav>
