@extends('layouts.user-layout')

@section('title', 'Detail Laporan #' . $report->id)

@section('content')
<div x-data="{ imgModal: false, imgSrc: '' }" class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('user.reports.index') }}" class="w-10 h-10 flex items-center justify-center rounded-full bg-white border border-gray-200 text-gray-500 hover:bg-[#222831] hover:text-[#DFD0B8] transition-colors shadow-sm">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <div class="flex items-center gap-3">
                    <h1 class="text-2xl font-serif font-bold text-[#222831]">Laporan #{{ $report->id }}</h1>
                    @php
                        $statusClass = match($report->status) {
                            'dikirim' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                            'sedang_dikerjakan' => 'bg-blue-100 text-blue-700 border-blue-200',
                            'selesai' => 'bg-green-100 text-green-700 border-green-200',
                            default => 'bg-gray-100 text-gray-500'
                        };
                        $statusLabel = match($report->status) {
                            'dikirim' => 'Terkirim',
                            'sedang_dikerjakan' => 'Diproses',
                            'selesai' => 'Selesai',
                            default => 'Unknown'
                        };
                    @endphp
                    <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $statusClass }}">
                        {{ $statusLabel }}
                    </span>
                </div>
                <p class="text-gray-500 text-sm mt-1">Pantau perkembangan laporan Anda di sini.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

            <div class="lg:col-span-2 space-y-6">

                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 relative">
                    <div class="absolute -top-4 -left-4 w-12 h-12 rounded-full border-4 border-gray-50 bg-[#222831] flex items-center justify-center text-[#DFD0B8] shadow-md z-10">
                        <i class="fa-solid fa-user"></i>
                    </div>

                    <div class="ml-2 mt-2">
                        <div class="flex justify-between items-start mb-4 pl-4">
                            <div>
                                <h3 class="font-bold text-[#222831]">Laporan Anda</h3>
                                <p class="text-xs text-gray-400">Penghuni Kamar</p>
                            </div>
                            <span class="text-xs text-gray-400 bg-gray-50 px-2 py-1 rounded-lg border border-gray-100">
                                {{ $report->created_at->format('d M Y, H:i') }}
                            </span>
                        </div>

                        <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100 text-gray-700 text-sm leading-relaxed whitespace-pre-line">
                            {{ $report->message }}
                        </div>

                        @if($report->photo)
                            <div class="mt-4 pl-2">
                                <p class="text-[10px] font-bold text-gray-400 uppercase mb-2 flex items-center gap-1">
                                    <i class="fa-solid fa-paperclip"></i> Lampiran Foto Anda
                                </p>
                                <div class="relative group w-fit cursor-pointer overflow-hidden rounded-xl border border-gray-200 shadow-sm"
                                     @click="imgModal = true; imgSrc = '{{ asset('storage/'.$report->photo) }}'">
                                    <img src="{{ asset('storage/'.$report->photo) }}" class="h-28 w-auto object-cover transition-transform duration-500 group-hover:scale-110">
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white">
                                        <i class="fa-solid fa-magnifying-glass-plus"></i>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                @if($report->response || $report->response_photo)
                    <div class="bg-blue-50/40 rounded-3xl shadow-sm border border-blue-100 p-6 relative ml-4 md:ml-8">
                        <div class="absolute -top-4 -right-4 w-12 h-12 rounded-full border-4 border-white bg-blue-600 flex items-center justify-center text-white shadow-md z-10">
                            <i class="fa-solid fa-headset"></i>
                        </div>

                        <div class="mr-2 mt-2">
                            <div class="flex justify-between items-start mb-4 pr-4">
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

                            @if($report->response)
                                <div class="bg-white rounded-2xl p-5 border border-blue-100 text-blue-900 text-sm leading-relaxed whitespace-pre-line shadow-sm">
                                    {{ $report->response }}
                                </div>
                            @endif

                            @if($report->response_photo)
                                <div class="mt-4 pr-2 text-right">
                                    <p class="text-[10px] font-bold text-blue-400 uppercase mb-2 flex items-center justify-end gap-1">
                                        Bukti Pengerjaan <i class="fa-solid fa-image"></i>
                                    </p>
                                    <div class="relative group w-fit ml-auto cursor-pointer overflow-hidden rounded-xl border border-blue-200 shadow-sm"
                                         @click="imgModal = true; imgSrc = '{{ asset('storage/'.$report->response_photo) }}'">
                                        <img src="{{ asset('storage/'.$report->response_photo) }}" class="h-32 w-auto object-cover transition-transform duration-500 group-hover:scale-110">
                                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white">
                                            <i class="fa-solid fa-magnifying-glass-plus"></i>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @elseif($report->status != 'selesai')
                    <div class="flex flex-col items-center justify-center py-8 text-center bg-gray-50 rounded-3xl border-2 border-dashed border-gray-200 opacity-70">
                        <i class="fa-solid fa-hourglass-start text-3xl text-gray-300 mb-2"></i>
                        <p class="text-sm text-gray-500 font-medium">Menunggu tanggapan pengelola...</p>
                    </div>
                @endif

            </div>

            <div class="lg:col-span-1">
                <div class="bg-white rounded-3xl shadow-lg border border-gray-100 p-6 sticky top-6">
                    <h3 class="font-bold text-[#222831] mb-6 border-b border-gray-100 pb-3 flex items-center gap-2">
                        <i class="fa-solid fa-list-check text-[#DFD0B8]"></i> Status Terkini
                    </h3>

                    <div class="space-y-0 relative">
                        <div class="absolute left-[15px] top-2 bottom-4 w-0.5 bg-gray-100 -z-10"></div>

                        <div class="flex gap-4 pb-8 relative group">
                            <div class="w-8 h-8 rounded-full border-4 border-white shadow-sm flex items-center justify-center z-10 shrink-0 bg-green-500 text-white">
                                <i class="fa-solid fa-check text-xs"></i>
                            </div>
                            <div class="pt-1">
                                <p class="text-xs font-bold uppercase text-green-600 mb-0.5">Laporan Diterima</p>
                                <p class="text-sm font-bold text-gray-800">Terkirim ke Admin</p>
                                <p class="text-[10px] text-gray-400 mt-1 flex items-center gap-1">
                                    <i class="fa-regular fa-clock"></i> {{ $report->created_at->format('d M Y, H:i') }}
                                </p>
                            </div>
                        </div>

                        @php
                            $isProcessing = in_array($report->status, ['sedang_dikerjakan', 'selesai']);
                            $isCurrentProcessing = $report->status == 'sedang_dikerjakan';
                        @endphp
                        <div class="flex gap-4 pb-8 relative group">
                            <div class="w-8 h-8 rounded-full border-4 border-white shadow-sm flex items-center justify-center z-10 shrink-0 transition-colors duration-300
                                {{ $isProcessing ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-400' }}">
                                <i class="fa-solid fa-screwdriver-wrench text-xs {{ $isCurrentProcessing ? 'animate-spin-slow' : '' }}"></i>
                            </div>
                            <div class="pt-1">
                                <p class="text-xs font-bold uppercase mb-0.5 {{ $isProcessing ? 'text-blue-600' : 'text-gray-400' }}">Proses Pengerjaan</p>
                                <p class="text-sm font-bold {{ $isProcessing ? 'text-gray-800' : 'text-gray-400' }}">Sedang Ditangani</p>

                                @if($report->processing_at)
                                    <p class="text-[10px] text-gray-500 mt-1 flex items-center gap-1">
                                        <i class="fa-regular fa-clock"></i> {{ $report->processing_at->format('d M Y, H:i') }}
                                    </p>
                                @elseif($isCurrentProcessing)
                                    <p class="text-[10px] text-blue-500 mt-1 animate-pulse">Sedang berlangsung...</p>
                                @else
                                    <p class="text-[10px] text-gray-300 mt-1">-</p>
                                @endif
                            </div>
                        </div>

                        @php
                            $isFinished = $report->status == 'selesai';
                        @endphp
                        <div class="flex gap-4 relative group">
                            <div class="w-8 h-8 rounded-full border-4 border-white shadow-sm flex items-center justify-center z-10 shrink-0 transition-colors duration-300
                                {{ $isFinished ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-400' }}">
                                <i class="fa-solid fa-flag-checkered text-xs"></i>
                            </div>
                            <div class="pt-1">
                                <p class="text-xs font-bold uppercase mb-0.5 {{ $isFinished ? 'text-green-600' : 'text-gray-400' }}">Tahap Akhir</p>
                                <p class="text-sm font-bold {{ $isFinished ? 'text-gray-800' : 'text-gray-400' }}">Selesai</p>

                                @if($report->completed_at)
                                    <p class="text-[10px] text-gray-500 mt-1 flex items-center gap-1">
                                        <i class="fa-regular fa-clock"></i> {{ $report->completed_at->format('d M Y, H:i') }}
                                    </p>
                                @else
                                    <p class="text-[10px] text-gray-300 mt-1">-</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 pt-6 border-t border-gray-100">
                        <a href="{{ route('messages.index') }}" target="_blank" class="flex items-center justify-center gap-2 w-full py-3 bg-[#25D366]/10 text-[#222831] font-bold text-sm rounded-xl hover:bg-[#25D366]/20 transition group">
                            <i class="fa-brands fa-whatsapp text-lg group-hover:scale-110 transition-transform"></i> Hubungi Admin
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
                <img :src="imgSrc" class="w-full h-auto max-h-[85vh] object-contain rounded-lg shadow-2xl border border-gray-700 bg-black">
            </div>
        </div>
    </div>

</div>
@endsection
