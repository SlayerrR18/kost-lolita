@extends('layouts.user-layout')

@section('title', 'Detail Laporan #' . $report->id)

@section('content')
<div x-data="{ imgModal: false, imgSrc: '' }" class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('user.reports.index') }}" class="w-10 h-10 flex items-center justify-center rounded-full bg-white border border-gray-200 text-gray-500 hover:bg-[#222831] hover:text-[#DFD0B8] transition-colors shadow-sm">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <div class="flex items-center gap-3">
                    <h1 class="text-2xl font-serif font-bold text-[#222831]">Laporan #{{ $report->id }}</h1>
                    @if($report->status == 'selesai')
                        <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-bold border border-green-200">Selesai</span>
                    @elseif($report->status == 'sedang_dikerjakan')
                        <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-bold border border-blue-200">Diproses</span>
                    @else
                        <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-bold border border-yellow-200">Terkirim</span>
                    @endif
                </div>
                <p class="text-gray-500 text-sm mt-1">Detail keluhan dan tanggapan dari pengelola.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

            <div class="lg:col-span-2 space-y-6">

                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 relative">
                    <div class="absolute -top-4 -left-4 w-12 h-12 rounded-full border-4 border-gray-50 bg-[#222831] flex items-center justify-center text-[#DFD0B8] shadow-md">
                        <i class="fa-solid fa-user"></i>
                    </div>

                    <div class="ml-4">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="font-bold text-[#222831]">Anda</h3>
                                <p class="text-xs text-gray-400">Penghuni Kamar</p>
                            </div>
                            <span class="text-xs text-gray-400 bg-gray-50 px-2 py-1 rounded-lg">
                                {{ $report->created_at->format('d M Y, H:i') }}
                            </span>
                        </div>

                        <div class="prose prose-sm max-w-none text-gray-700">
                            <p class="leading-relaxed">{{ $report->message }}</p>
                        </div>

                        @if($report->photo)
                            <div class="mt-6 pt-4 border-t border-gray-100">
                                <p class="text-xs font-bold text-gray-400 uppercase mb-2 flex items-center gap-2">
                                    <i class="fa-solid fa-paperclip"></i> Bukti Lampiran
                                </p>
                                <div class="relative group w-fit cursor-pointer overflow-hidden rounded-xl border border-gray-200"
                                     @click="imgModal = true; imgSrc = '{{ asset('storage/'.$report->photo) }}'">
                                    <img src="{{ asset('storage/'.$report->photo) }}" class="h-32 w-auto object-cover transition-transform duration-500 group-hover:scale-110">
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white">
                                        <i class="fa-solid fa-magnifying-glass-plus"></i>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                @if($report->response)
                    <div class="bg-blue-50/50 rounded-3xl shadow-sm border border-blue-100 p-6 relative ml-8">
                        <div class="absolute -top-4 -right-4 w-12 h-12 rounded-full border-4 border-white bg-blue-600 flex items-center justify-center text-white shadow-md">
                            <i class="fa-solid fa-headset"></i>
                        </div>

                        <div class="mr-4">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="font-bold text-blue-900 flex items-center gap-2">
                                        Admin Pengelola <i class="fa-solid fa-circle-check text-blue-500 text-xs"></i>
                                    </h3>
                                    <p class="text-xs text-blue-400">Customer Support</p>
                                </div>
                                <span class="text-xs text-blue-400 bg-white px-2 py-1 rounded-lg border border-blue-100">
                                    {{ $report->updated_at->diffForHumans() }}
                                </span>
                            </div>

                            <div class="prose prose-sm max-w-none text-blue-900 bg-white p-4 rounded-xl rounded-tr-none shadow-sm border border-blue-100">
                                <p class="leading-relaxed whitespace-pre-line">{{ $report->response }}</p>
                            </div>

                            <div class="mt-2 text-right">
                                <p class="text-[10px] text-blue-400">Ditanggapi oleh: {{ $report->handler->name ?? 'Admin' }}</p>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-8 text-center bg-gray-50 rounded-3xl border-2 border-dashed border-gray-200">
                        <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center text-gray-400 mb-2 animate-pulse">
                            <i class="fa-solid fa-ellipsis"></i>
                        </div>
                        <p class="text-sm text-gray-500 font-medium">Menunggu tanggapan admin...</p>
                        <p class="text-xs text-gray-400">Biasanya dibalas dalam 1x24 jam.</p>
                    </div>
                @endif

            </div>

            <div class="lg:col-span-1">
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 sticky top-8">
                    <h3 class="font-bold text-[#222831] mb-6 border-b border-gray-100 pb-3">Progres Laporan</h3>

                    <div class="relative pl-4 border-l-2 border-gray-100 space-y-8 ml-2">

                        <div class="relative">
                            <div class="absolute -left-[21px] bg-green-500 h-4 w-4 rounded-full border-4 border-white shadow-sm"></div>
                            <p class="text-xs text-gray-400 uppercase font-bold mb-1">Step 1</p>
                            <h4 class="font-bold text-gray-800 text-sm">Laporan Diterima</h4>
                            <p class="text-xs text-gray-500 mt-1">Laporan Anda telah masuk ke sistem kami pada {{ $report->created_at->format('d M Y') }}.</p>
                        </div>

                        <div class="relative">
                            <div class="absolute -left-[21px] h-4 w-4 rounded-full border-4 border-white shadow-sm
                                {{ in_array($report->status, ['sedang_dikerjakan', 'selesai']) ? 'bg-blue-500' : 'bg-gray-300' }}"></div>
                            <p class="text-xs text-gray-400 uppercase font-bold mb-1">Step 2</p>
                            <h4 class="font-bold text-sm {{ in_array($report->status, ['sedang_dikerjakan', 'selesai']) ? 'text-gray-800' : 'text-gray-400' }}">Sedang Ditangani</h4>
                            @if($report->status == 'sedang_dikerjakan')
                                <p class="text-xs text-blue-600 mt-1 font-medium animate-pulse">Teknisi/Admin sedang bekerja.</p>
                            @endif
                        </div>

                        <div class="relative">
                            <div class="absolute -left-[21px] h-4 w-4 rounded-full border-4 border-white shadow-sm
                                {{ $report->status == 'selesai' ? 'bg-green-500' : 'bg-gray-300' }}"></div>
                            <p class="text-xs text-gray-400 uppercase font-bold mb-1">Step 3</p>
                            <h4 class="font-bold text-sm {{ $report->status == 'selesai' ? 'text-gray-800' : 'text-gray-400' }}">Selesai</h4>
                            @if($report->status == 'selesai')
                                <p class="text-xs text-green-600 mt-1">Masalah telah terselesaikan.</p>
                            @endif
                        </div>

                    </div>

                    <div class="mt-8 pt-6 border-t border-gray-100">
                        <p class="text-xs text-gray-500 mb-3 text-center">Masih ada kendala?</p>
                        <a href="https://wa.me/6281234567890" target="_blank" class="flex items-center justify-center gap-2 w-full py-2.5 bg-green-50 text-green-700 font-bold text-sm rounded-xl hover:bg-green-100 transition">
                            <i class="fa-brands fa-whatsapp"></i> Chat Admin
                        </a>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <div x-show="imgModal" style="display: none;" class="fixed inset-0 z-[999] overflow-y-auto" x-cloak>
        <div class="fixed inset-0 bg-black/90 backdrop-blur-sm transition-opacity" @click="imgModal = false" x-show="imgModal" x-transition.opacity></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div x-show="imgModal" x-transition.scale class="relative max-w-4xl w-full">
                <button @click="imgModal = false" class="absolute -top-12 right-0 text-white hover:text-gray-300 text-2xl focus:outline-none">
                    <i class="fa-solid fa-xmark"></i>
                </button>
                <img :src="imgSrc" class="w-full h-auto rounded-lg shadow-2xl border border-gray-800">
            </div>
        </div>
    </div>

</div>
@endsection
