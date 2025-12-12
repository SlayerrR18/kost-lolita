@extends('layouts.user-layout')

@section('title', 'Laporan & Masukan')

@section('content')
<div x-data="{ filter: 'all' }" class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-serif font-bold text-[#222831]">Pusat Bantuan</h1>
                <p class="text-gray-500 text-sm mt-1">Laporkan masalah fasilitas atau berikan masukan untuk kami.</p>
            </div>

            <a href="{{ route('user.reports.create') }}"
               class="inline-flex items-center gap-2 bg-[#222831] text-[#DFD0B8] px-6 py-3 rounded-xl font-bold shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                <i class="fa-solid fa-pen-to-square"></i>
                <span>Buat Laporan Baru</span>
            </a>
        </div>

        <div class="flex gap-4 overflow-x-auto pb-4 mb-4 border-b border-gray-200">
            <button @click="filter = 'all'"
                    class="px-4 py-2 rounded-full text-sm font-bold transition-all whitespace-nowrap border"
                    :class="filter === 'all' ? 'bg-[#222831] text-white border-[#222831]' : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50'">
                Semua Laporan
            </button>
            <button @click="filter = 'process'"
                    class="px-4 py-2 rounded-full text-sm font-bold transition-all whitespace-nowrap border"
                    :class="filter === 'process' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50'">
                Sedang Proses
            </button>
            <button @click="filter = 'done'"
                    class="px-4 py-2 rounded-full text-sm font-bold transition-all whitespace-nowrap border"
                    :class="filter === 'done' ? 'bg-green-600 text-white border-green-600' : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50'">
                Selesai
            </button>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-200 text-green-800 px-4 py-3 rounded-xl flex items-center gap-3 mb-6 shadow-sm"
                 x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)">
                <i class="fa-solid fa-circle-check text-lg"></i>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @if($reports->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($reports as $report)
                    @php
                        // Mapping status untuk filter AlpineJS
                        $filterKey = ($report->status == 'selesai') ? 'done' : 'process';

                        // Style Badge
                        $badgeClass = match($report->status) {
                            'dikirim' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                            'sedang_dikerjakan' => 'bg-blue-100 text-blue-700 border-blue-200',
                            'selesai' => 'bg-green-100 text-green-700 border-green-200',
                            default => 'bg-gray-100 text-gray-700 border-gray-200'
                        };

                        $iconStatus = match($report->status) {
                            'dikirim' => 'fa-paper-plane',
                            'sedang_dikerjakan' => 'fa-screwdriver-wrench',
                            'selesai' => 'fa-check-double',
                            default => 'fa-question'
                        };

                        // Ikon Kategori (Opsional: bisa disesuaikan jika ada kolom kategori)
                        // Disini saya pakai default ikon 'wrench' (perbaikan)
                        $categoryIcon = 'fa-hammer';
                    @endphp

                    <div x-show="filter === 'all' || filter === '{{ $filterKey }}'"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-all group relative overflow-hidden">

                        <div class="absolute top-4 right-4">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold uppercase border {{ $badgeClass }}">
                                <i class="fa-solid {{ $iconStatus }}"></i> {{ str_replace('_', ' ', $report->status) }}
                            </span>
                        </div>

                        <div class="flex items-start gap-4 mb-4">
                            <div class="w-12 h-12 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-center text-gray-400 group-hover:bg-[#222831] group-hover:text-[#DFD0B8] transition-colors">
                                <i class="fa-solid {{ $categoryIcon }} text-xl"></i>
                            </div>

                            <div class="pr-20"> <h3 class="text-lg font-bold text-[#222831] line-clamp-1">
                                    {{ Str::limit($report->message, 40) }}
                                </h3>
                                <p class="text-xs text-gray-400 mt-1">
                                    <i class="fa-regular fa-calendar mr-1"></i> {{ $report->date->format('d M Y') }}
                                    <span class="mx-1">•</span>
                                    {{ $report->date->format('H:i') }} WIB
                                </p>
                            </div>
                        </div>

                        <p class="text-sm text-gray-600 line-clamp-2 mb-4 h-10">
                            {{ $report->message }}
                        </p>

                        <div class="pt-4 border-t border-gray-50 flex justify-between items-center">
                            @if($report->photo_path)
                                <span class="text-xs text-blue-600 font-medium flex items-center gap-1">
                                    <i class="fa-solid fa-image"></i> Ada Lampiran Foto
                                </span>
                            @else
                                <span class="text-xs text-gray-400 italic">Tanpa lampiran</span>
                            @endif

                            <a href="{{ route('user.reports.show', $report->id) }}" class="text-sm font-bold text-[#222831] hover:text-[#DFD0B8] flex items-center gap-1 group/link">
                                Detail <i class="fa-solid fa-arrow-right text-xs transition-transform group-hover/link:translate-x-1"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-16 bg-white rounded-3xl border-2 border-dashed border-gray-200">
                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300">
                    <i class="fa-regular fa-clipboard text-4xl"></i>
                </div>
                <h3 class="text-xl font-bold text-[#222831]">Belum Ada Laporan</h3>
                <p class="text-gray-500 text-sm mt-2 mb-6">Jika ada fasilitas rusak atau keluhan, sampaikan di sini.</p>
                <a href="{{ route('user.reports.create') }}" class="px-6 py-2.5 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 transition">
                    Buat Laporan Sekarang
                </a>
            </div>
        @endif

    </div>
</div>
@endsection
