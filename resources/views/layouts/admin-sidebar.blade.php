<aside :class="sidebarOpen ? 'translate-x-0 ease-out' : '-translate-x-full ease-in'"
       class="fixed inset-y-0 left-0 z-30 w-72 overflow-y-auto transition duration-300 transform bg-white lg:translate-x-0 lg:static lg:inset-0 flex flex-col border-r border-gray-200 shadow-sm">

    <div class="flex items-center justify-center h-24 border-b border-gray-100">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 group">
            <div class="w-10 h-10 rounded-lg bg-[#ffffff] flex items-center justify-center text-[#DFD0B8] text-xl shadow-md transition-transform group-hover:rotate-6">
                <img src="{{ asset('img/Logo.png') }}" alt="Kost Lolita" class="h-10 transition-transform duration-500 group-hover:rotate-6">
            </div>
            <div class="flex flex-col">
                <span class="font-serif text-2xl font-bold text-[#222831] tracking-wide">
                    Kost <span class="text-[#DFD0B8]">Lolita</span>
                </span>
                <span class="text-[10px] uppercase tracking-[0.2em] text-gray-400 font-sans">Owner</span>
            </div>
        </a>
    </div>

    <nav class="mt-8 flex-1 px-4 space-y-2 font-sans">

        <p class="px-4 text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Menu Utama</p>

        <a href="{{ route('admin.dashboard') }}"
           class="flex items-center px-4 py-3.5 rounded-xl transition-all duration-300 group
           {{ request()->routeIs('admin.dashboard')
              ? 'bg-[#DFD0B8] text-[#222831] font-bold shadow-md transform translate-x-1'
              : 'text-gray-500 hover:bg-gray-50 hover:text-[#222831] hover:translate-x-1' }}">

            <i class="fa-solid fa-gauge-high w-6 text-lg {{ request()->routeIs('admin.dashboard') ? '' : 'text-gray-400 group-hover:text-[#222831]' }}"></i>
            <span class="mx-3">Dashboard</span>

            @if(request()->routeIs('admin.dashboard'))
                <i class="fa-solid fa-chevron-right ml-auto text-xs opacity-70"></i>
            @endif
        </a>

        <a href="{{ route('admin.rooms.index') }}"
           class="flex items-center px-4 py-3.5 rounded-xl transition-all duration-300 group
           {{ request()->routeIs('admin.rooms.*')
              ? 'bg-[#DFD0B8] text-[#222831] font-bold shadow-md transform translate-x-1'
              : 'text-gray-500 hover:bg-gray-50 hover:text-[#222831] hover:translate-x-1' }}">

            <i class="fa-solid fa-bed w-6 text-lg {{ request()->routeIs('admin.rooms.*') ? '' : 'text-gray-400 group-hover:text-[#222831]' }}"></i>
            <span class="mx-3">Manajemen Kamar</span>
        </a>

        <a href="#"
           class="flex items-center px-4 py-3.5 rounded-xl transition-all duration-300 group text-gray-500 hover:bg-gray-50 hover:text-[#222831] hover:translate-x-1">
            <i class="fa-solid fa-users w-6 text-lg text-gray-400 group-hover:text-[#222831]"></i>
            <span class="mx-3">Data Penghuni</span>
        </a>

        <div x-data="{ openKeuangan: {{ request()->routeIs('admin.finance.*') ? 'true' : 'false' }} }">

            <button @click="openKeuangan = !openKeuangan"
                    type="button"
                    class="flex items-center w-full px-4 py-3.5 rounded-xl transition-all duration-300 group
                    {{ request()->routeIs('admin.finance.*')
                       ? 'text-[#222831] bg-gray-50 font-semibold'
                       : 'text-gray-500 hover:bg-gray-50 hover:text-[#222831] hover:translate-x-1' }}">

                <i class="fa-solid fa-chart-line w-6 text-lg {{ request()->routeIs('admin.finance.*') ? 'text-[#222831]' : 'text-gray-400 group-hover:text-[#222831]' }}"></i>
                <span class="mx-3 text-left flex-1">Laporan Keuangan</span>

                <i class="fa-solid fa-chevron-right text-xs transition-transform duration-200"
                   :class="openKeuangan ? 'rotate-90' : ''"></i>
            </button>

            <div x-show="openKeuangan"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="mt-1 space-y-1">

                <a href="{{-- route('admin.finance.income') --}} #"
                   class="flex items-center pl-14 pr-4 py-2.5 rounded-xl transition-all duration-300 group text-sm
                   {{ request()->routeIs('admin.finance.income')
                      ? 'bg-[#DFD0B8] text-[#222831] font-bold shadow-sm'
                      : 'text-gray-500 hover:text-[#222831] hover:bg-gray-50' }}">
                    <span class="w-1.5 h-1.5 rounded-full mr-3 {{ request()->routeIs('admin.finance.income') ? 'bg-[#222831]' : 'bg-gray-300 group-hover:bg-[#222831]' }}"></span>
                    Pemasukan
                </a>

                <a href="{{-- route('admin.finance.expense') --}} #"
                   class="flex items-center pl-14 pr-4 py-2.5 rounded-xl transition-all duration-300 group text-sm
                   {{ request()->routeIs('admin.finance.expense')
                      ? 'bg-[#DFD0B8] text-[#222831] font-bold shadow-sm'
                      : 'text-gray-500 hover:text-[#222831] hover:bg-gray-50' }}">
                    <span class="w-1.5 h-1.5 rounded-full mr-3 {{ request()->routeIs('admin.finance.expense') ? 'bg-[#222831]' : 'bg-gray-300 group-hover:bg-[#222831]' }}"></span>
                    Pengeluaran
                </a>
            </div>
        </div>

         <a href="#"
           class="flex items-center px-4 py-3.5 rounded-xl transition-all duration-300 group text-gray-500 hover:bg-gray-50 hover:text-[#222831] hover:translate-x-1">
            <i class="fa-solid fa-book w-6 text-lg text-gray-400 group-hover:text-[#222831]"></i>
            <span class="mx-3">Konfirmasi Pembayaran</span>
        </a>

        <div class="my-4 border-t border-gray-100"></div>
        <p class="px-4 text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Lainnya</p>


        <a href="#"
           class="flex items-center px-4 py-3.5 rounded-xl transition-all duration-300 group text-gray-500 hover:bg-gray-50 hover:text-[#222831] hover:translate-x-1">
            <i class="fa-solid fa-wallet w-6 text-lg text-gray-400 group-hover:text-[#222831]"></i>
            <span class="mx-3">Laporan Dan Masukan</span>
        </a>

        <a href="#"
           class="flex items-center px-4 py-3.5 rounded-xl transition-all duration-300 group text-gray-500 hover:bg-gray-50 hover:text-[#222831] hover:translate-x-1">
            <i class="fa-solid fa-envelope w-6 text-lg text-gray-400 group-hover:text-[#222831]"></i>
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
            <span class="text-sm font-bold text-gray-700 group-hover:text-red-600">Logout</span>
            <span class="text-[10px] text-gray-400 group-hover:text-red-400">Akhiri sesi anda</span>
        </div>
    </button>
</div>
</aside>
