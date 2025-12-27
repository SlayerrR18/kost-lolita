<aside :class="sidebarOpen ? 'translate-x-0 ease-out' : '-translate-x-full ease-in'"
       class="fixed inset-y-0 left-0 z-30 w-72 overflow-y-auto transition duration-300 transform bg-white lg:translate-x-0 lg:static lg:inset-0 flex flex-col border-r border-gray-200 shadow-sm">

    <div class="flex items-center justify-center h-24 border-b border-gray-100">
        <a href="{{ route('user.dashboard') }}" class="flex items-center gap-3 group">
            <div class="w-10 h-10 rounded-lg bg-[#222831] flex items-center justify-center text-[#DFD0B8] text-xl shadow-md transition-transform group-hover:rotate-6">
                <img src="{{ asset('img/Logo.png') }}" alt="Logo" class="h-6 w-auto brightness-0 invert" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                <i class="fa-solid fa-house-user" style="display:none;"></i>
            </div>
            <div class="flex flex-col">
                <span class="font-serif text-2xl font-bold text-[#222831] tracking-wide">
                    Kost <span class="text-[#DFD0B8]">Lolita</span>
                </span>
                <span class="text-[10px] uppercase tracking-[0.2em] text-gray-400 font-sans">Penghuni</span>
            </div>
        </a>
    </div>

    <nav class="mt-8 flex-1 px-4 space-y-2 font-sans">

        <p class="px-4 text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Menu Utama</p>

        <a href="{{ route('user.dashboard') }}"
           class="flex items-center px-4 py-3.5 rounded-xl transition-all duration-300 group
           {{ request()->routeIs('user.dashboard')
              ? 'bg-[#DFD0B8] text-[#222831] font-bold shadow-md transform translate-x-1'
              : 'text-gray-500 hover:bg-gray-50 hover:text-[#222831] hover:translate-x-1' }}">
            <i class="fa-solid fa-gauge-high w-6 text-lg {{ request()->routeIs('user.dashboard') ? '' : 'text-gray-400 group-hover:text-[#222831]' }}"></i>
            <span class="mx-3">Dashboard</span>
        </a>

        <a href="{{ route('user.contract.index') }}"
            class="flex items-center px-4 py-3.5 rounded-xl transition-all duration-300 group
            {{ request()->routeIs('user.contract.*')
                ? 'bg-[#DFD0B8] text-[#222831] font-bold shadow-md transform translate-x-1'
                : 'text-gray-500 hover:bg-gray-50 hover:text-[#222831] hover:translate-x-1' }}">
                <i class="fa-solid fa-file-contract w-6 text-lg {{ request()->routeIs('user.contract.*') ? '' : 'text-gray-400 group-hover:text-[#222831]' }}"></i>
                <span class="mx-3">Kontrak Saya</span>
        </a>

       <a href="{{ route('user.finance.index') }}"
            class="flex items-center px-4 py-3.5 rounded-xl transition-all duration-300 group
            {{ request()->routeIs('user.finance.index')
                ? 'bg-[#DFD0B8] text-[#222831] font-bold shadow-md transform translate-x-1'
                : 'text-gray-500 hover:bg-gray-50 hover:text-[#222831] hover:translate-x-1' }}">
                <i class="fa-solid fa-money-bill-transfer w-6 text-lg {{ request()->routeIs('user.finance.index') ? '' : 'text-gray-400 group-hover:text-[#222831]' }}"></i>
                <span class="mx-3">Laporan Transaksi</span>
        </a>

        <div class="my-4 border-t border-gray-100"></div>
        <p class="px-4 text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Menu Lainnya</p>

        <a href="{{ route('user.reports.index') }}"
            class="flex items-center px-4 py-3.5 rounded-xl transition-all duration-300 group
            {{ request()->routeIs('user.reports.*')
                ? 'bg-[#DFD0B8] text-[#222831] font-bold shadow-md transform translate-x-1'
                : 'text-gray-500 hover:bg-gray-50 hover:text-[#222831] hover:translate-x-1' }}">
                <i class="fa-solid fa-bullhorn w-6 text-lg {{ request()->routeIs('user.reports.*') ? '' : 'text-gray-400 group-hover:text-[#222831]' }}"></i>
                <span class="mx-3">Laporan & Masukan</span>
        </a>

        <a href="{{ route('messages.index') }}"
            class="flex items-center px-4 py-3.5 rounded-xl transition-all duration-300 group
            {{ request()->routeIs('messages.*')
                ? 'bg-[#DFD0B8] text-[#222831] font-bold shadow-md transform translate-x-1'
                : 'text-gray-500 hover:bg-gray-50 hover:text-[#222831] hover:translate-x-1' }}">
                <i class="fa-solid fa-envelope w-6 text-lg {{ request()->routeIs('messages.*') ? '' : 'text-gray-400 group-hover:text-[#222831]' }}"></i>
                <span class="mx-3">Pesan</span>
        </a>
    </nav>

    <div class="p-4 border-t border-gray-100 bg-gray-50/50">
        <button @click="logoutModalOpen = true"
                type="button"
                class="flex items-center w-full px-4 py-3 rounded-xl text-red-500 hover:bg-red-50 transition-colors duration-200 group border border-transparent hover:border-red-100">
            <div class="bg-red-100 p-2 rounded-lg group-hover:bg-red-200 transition text-red-600">
                <i class="fa-solid fa-right-from-bracket text-lg"></i>
            </div>
            <div class="flex flex-col text-left ml-3">
                <span class="text-sm font-bold text-gray-700 group-hover:text-red-600">Keluar</span>
                <span class="text-[10px] text-gray-400 group-hover:text-red-400">Akhiri sesi</span>
            </div>
        </button>
    </div>
</aside>
