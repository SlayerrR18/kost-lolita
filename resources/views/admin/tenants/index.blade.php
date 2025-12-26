@extends('layouts.admin-layout')

@section('title', 'Data Penghuni - Admin Kost Lolita')

@section('content')
<div class="py-8">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-serif font-bold text-[#222831]">Data Penghuni</h1>
                <p class="text-gray-500 text-sm mt-1">
                    Total {{ $tenants->count() }} penghuni terdaftar.
                </p>
            </div>

            <form action="{{ route('admin.tenants.index') }}" method="GET" class="relative w-full md:w-auto">
                <input type="text" name="search" placeholder="Cari nama atau kamar..."
                       value="{{ request('search') }}"
                       class="pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:border-[#222831] focus:ring focus:ring-[#222831]/20 w-full md:w-72 transition-all shadow-sm">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3.5 text-gray-400 text-xs"></i>
            </form>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-[#222831] text-[#DFD0B8]">
                        <tr>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider rounded-tl-3xl">Profil Penghuni</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">Kamar & Durasi</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">Kontak</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-center">Status</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-right rounded-tr-3xl">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($tenants as $tenant)
                            <tr class="hover:bg-gray-50 transition-colors duration-200 group">

                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <img class="h-10 w-10 rounded-full object-cover border border-gray-200"
                                             src="https://ui-avatars.com/api/?name={{ urlencode($tenant->name) }}&background=222831&color=DFD0B8"
                                             alt="{{ $tenant->name }}">
                                        <div>
                                            <div class="text-sm font-bold text-[#222831]">{{ $tenant->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $tenant->email }}</div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-md bg-[#222831]/10 text-[#222831] text-xs font-bold border border-[#222831]/20">
                                            {{ optional($tenant->room)->room_number ?? 'N/A' }}
                                        </span>
                                    </div>
                                    <div class="text-[10px] text-gray-400 mt-1">
                                        Masuk: {{ $tenant->created_at->format('d M Y') }}
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="text-sm text-gray-700 font-medium">{{ $tenant->room && $tenant->room->orders && $tenant->room->orders->count() > 0 ? $tenant->room->orders->last()->phone : 'N/A' }}</span>
                                        @if($tenant->phone)
                                            <a href="https://wa.me/{{ $tenant->phone }}" target="_blank" class="text-[10px] text-green-600 hover:underline flex items-center gap-1 mt-0.5">
                                                <i class="fa-brands fa-whatsapp"></i> Chat WA
                                            </a>
                                        @endif
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-green-100 text-green-700 border border-green-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                                        Aktif
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.tenants.show', $tenant) }}" class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-500 hover:bg-[#222831] hover:text-[#DFD0B8] transition-colors border border-transparent hover:border-[#222831]" title="Lihat Detail">
                                            <i class="fa-regular fa-eye"></i>
                                        </a>

                                        <a href="{{ route('admin.tenants.edit', $tenant) }}" class="w-8 h-8 flex items-center justify-center rounded-lg text-blue-500 hover:bg-blue-50 transition-colors" title="Edit Data">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                                            <i class="fa-solid fa-users-slash text-3xl text-gray-300"></i>
                                        </div>
                                        <p class="text-gray-500 font-medium">Belum ada data penghuni.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(method_exists($tenants, 'links'))
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                    {{ $tenants->links() }}
                </div>
            @endif
        </div>
    </div>

</div>
@endsection
