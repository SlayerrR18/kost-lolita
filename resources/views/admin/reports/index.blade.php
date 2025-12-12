@extends('layouts.admin-layout')

@section('title', 'Manajemen Laporan')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-8">
            <h1 class="text-3xl font-serif font-bold text-[#222831]">Laporan Masuk</h1>
            <p class="text-gray-500 text-sm mt-1">Pantau keluhan, kerusakan fasilitas, dan masukan dari penghuni.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase">Perlu Tindakan</p>
                    <h3 class="text-2xl font-bold text-yellow-600 mt-1">
                        {{ $reports->where('status', 'dikirim')->count() }}
                    </h3>
                </div>
                <div class="w-10 h-10 rounded-full bg-yellow-50 flex items-center justify-center text-yellow-600">
                    <i class="fa-solid fa-bell"></i>
                </div>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase">Sedang Proses</p>
                    <h3 class="text-2xl font-bold text-blue-600 mt-1">
                        {{ $reports->where('status', 'sedang_dikerjakan')->count() }}
                    </h3>
                </div>
                <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600">
                    <i class="fa-solid fa-screwdriver-wrench"></i>
                </div>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase">Selesai</p>
                    <h3 class="text-2xl font-bold text-green-600 mt-1">
                        {{ $reports->where('status', 'selesai')->count() }}
                    </h3>
                </div>
                <div class="w-10 h-10 rounded-full bg-green-50 flex items-center justify-center text-green-600">
                    <i class="fa-solid fa-check-double"></i>
                </div>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 mb-6 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-2">
                <div class="bg-[#222831] text-[#DFD0B8] w-10 h-10 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-filter"></i>
                </div>
                <span class="font-bold text-gray-700">Filter Data</span>
            </div>

            <form method="GET" class="flex flex-1 w-full md:w-auto justify-end gap-3">
                <div class="relative w-full md:w-64">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari pelapor atau isi..."
                           class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#222831] transition">
                </div>

                <select name="status" class="py-2.5 px-4 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#222831] cursor-pointer">
                    <option value="">Semua Status</option>
                    <option value="dikirim" {{ request('status') == 'dikirim' ? 'selected' : '' }}>Baru Masuk</option>
                    <option value="sedang_dikerjakan" {{ request('status') == 'sedang_dikerjakan' ? 'selected' : '' }}>Sedang Proses</option>
                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>

                <button type="submit" class="px-5 py-2.5 bg-[#222831] text-[#DFD0B8] rounded-xl text-sm font-bold hover:shadow-lg transition">
                    Cari
                </button>
            </form>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-green-100 border border-green-200 text-green-800 px-4 py-3 rounded-xl flex items-center gap-3 shadow-sm" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
                <i class="fa-solid fa-circle-check text-lg"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-[#222831] text-[#DFD0B8]">
                        <tr>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider rounded-tl-3xl">Pelapor</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">Isi Laporan</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-right rounded-tr-3xl">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($reports as $report)
                            @php
                                $statusColor = match($report->status) {
                                    'dikirim' => 'border-l-4 border-yellow-400',
                                    'sedang_dikerjakan' => 'border-l-4 border-blue-500',
                                    'selesai' => 'border-l-4 border-green-500',
                                    default => 'border-l-4 border-gray-200'
                                };
                            @endphp
                            <tr class="hover:bg-gray-50 transition duration-150 {{ $statusColor }}">

                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($report->user->name ?? 'User') }}&background=random&color=fff"
                                             class="w-10 h-10 rounded-full border border-gray-200" alt="Avatar">
                                        <div>
                                            <div class="font-bold text-[#222831] text-sm">{{ $report->user->name ?? 'User Tidak Dikenal' }}</div>
                                            <div class="text-xs text-gray-500 flex items-center gap-1">
                                                <i class="fa-solid fa-door-closed"></i>
                                                Kamar {{ optional($report->user->orders->where('status','approved')->last()->room)->room_number ?? '-' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="max-w-xs">
                                        <p class="text-sm text-gray-700 line-clamp-1 font-medium">{{ Str::limit($report->message, 50) }}</p>
                                        @if($report->photo)
                                            <span class="text-[10px] text-blue-500 flex items-center gap-1 mt-1">
                                                <i class="fa-solid fa-image"></i> Lampiran Foto
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-600 font-medium">{{ $report->date->format('d M Y') }}</div>
                                    <div class="text-xs text-gray-400">{{ $report->created_at->format('H:i') }} WIB</div>
                                </td>

                                <td class="px-6 py-4">
                                    @if($report->status == 'dikirim')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-700 border border-yellow-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 animate-pulse"></span> Baru
                                        </span>
                                    @elseif($report->status == 'sedang_dikerjakan')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700 border border-blue-200">
                                            <i class="fa-solid fa-spinner fa-spin"></i> Proses
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-200">
                                            <i class="fa-solid fa-check-double"></i> Selesai
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.reports.show', $report->id) }}"
                                           class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-gray-500 hover:bg-[#222831] hover:text-[#DFD0B8] hover:border-[#222831] transition shadow-sm"
                                           title="Lihat Detail">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>

                                        <form action="{{ route('admin.reports.destroy', $report->id) }}" method="POST" onsubmit="return confirm('Hapus laporan ini secara permanen?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="w-8 h-8 flex items-center justify-center rounded-lg border border-red-100 text-red-500 bg-red-50 hover:bg-red-600 hover:text-white transition shadow-sm"
                                                    title="Hapus">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                                            <i class="fa-regular fa-folder-open text-3xl"></i>
                                        </div>
                                        <p class="font-medium text-gray-600">Tidak ada laporan ditemukan.</p>
                                        <p class="text-xs mt-1">Coba ubah filter pencarian Anda.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($reports->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                    {{ $reports->withQueryString()->links() }}
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
