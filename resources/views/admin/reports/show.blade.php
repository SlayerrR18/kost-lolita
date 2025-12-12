@extends('layouts.admin-layout')

@section('title', 'Detail Laporan #' . $report->id)

@section('content')
<div x-data="{ imgModal: false }" class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('admin.reports.index') }}" class="w-10 h-10 flex items-center justify-center rounded-full bg-white border border-gray-200 text-gray-500 hover:bg-[#222831] hover:text-[#DFD0B8] transition-colors shadow-sm">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Tiket #{{ $report->id }}</span>
                <h1 class="text-2xl font-serif font-bold text-[#222831]">Detail Laporan</h1>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

            <div class="lg:col-span-2 space-y-6">

                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 relative">

                    <div class="flex items-start justify-between mb-6">
                        <div class="flex items-center gap-4">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($report->user->name ?? 'Deleted') }}&background=222831&color=DFD0B8"
                                 class="w-12 h-12 rounded-full border-2 border-gray-100" alt="Avatar">
                            <div>
                                <h3 class="font-bold text-lg text-[#222831]">{{ $report->user->name ?? 'User Terhapus' }}</h3>
                                <p class="text-sm text-gray-500">Penghuni Kamar {{ optional($report->user->orders->where('status','approved')->last()->room)->room_number ?? '-' }}</p>
                            </div>
                        </div>
                        <span class="text-xs text-gray-400 bg-gray-50 px-3 py-1 rounded-full border border-gray-100">
                            {{ $report->created_at->format('d M Y, H:i') }}
                        </span>
                    </div>

                    <div class="prose prose-sm max-w-none text-gray-700 bg-gray-50/50 p-6 rounded-2xl border border-gray-100">
                        <p class="leading-relaxed whitespace-pre-line">{{ $report->message }}</p>
                    </div>

                    @if($report->photo)
                        <div class="mt-6">
                            <p class="text-xs font-bold text-gray-400 uppercase mb-3 flex items-center gap-2">
                                <i class="fa-solid fa-paperclip"></i> Bukti Lampiran
                            </p>
                            <div class="relative group w-fit cursor-pointer overflow-hidden rounded-xl border border-gray-200"
                                 @click="imgModal = true">
                                <img src="{{ asset('storage/'.$report->photo) }}" class="h-40 w-auto object-cover transition-transform duration-500 group-hover:scale-110">
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white">
                                    <div class="text-center">
                                        <i class="fa-solid fa-magnifying-glass-plus text-2xl mb-1"></i>
                                        <p class="text-[10px] font-bold uppercase">Zoom</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                @if($report->response)
                    <div class="bg-blue-50/50 rounded-3xl shadow-sm border border-blue-100 p-8 ml-8 relative">
                        <div class="absolute -top-6 left-8 w-0.5 h-6 bg-blue-100"></div>

                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white shadow-md">
                                    <i class="fa-solid fa-headset"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-blue-900">Tanggapan Admin</h3>
                                    <p class="text-xs text-blue-400">Customer Support</p>
                                </div>
                            </div>
                            <span class="text-xs text-blue-400">
                                {{ $report->updated_at->diffForHumans() }}
                            </span>
                        </div>

                        <div class="prose prose-sm max-w-none text-blue-900 bg-white p-5 rounded-2xl shadow-sm border border-blue-100">
                            <p class="leading-relaxed whitespace-pre-line">{{ $report->response }}</p>
                        </div>
                    </div>
                @endif

            </div>

            <div class="lg:col-span-1">
                <div class="bg-white rounded-3xl shadow-lg border border-gray-100 p-6 sticky top-8">

                    <div class="flex justify-between items-center mb-6 pb-4 border-b border-gray-100">
                        <h3 class="font-bold text-[#222831]">Status Laporan</h3>

                        @if($report->status == 'dikirim')
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-bold border border-yellow-200">Baru Masuk</span>
                        @elseif($report->status == 'sedang_dikerjakan')
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-bold border border-blue-200">Sedang Proses</span>
                        @else
                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold border border-green-200">Selesai</span>
                        @endif
                    </div>

                    <form action="{{ route('admin.reports.update', $report->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-5">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Update Status</label>
                            <div class="relative">
                                <select name="status" class="w-full appearance-none bg-gray-50 border border-gray-200 text-gray-700 rounded-xl px-4 py-3 pr-8 focus:outline-none focus:ring-2 focus:ring-[#222831] focus:border-transparent cursor-pointer font-medium">
                                    <option value="dikirim" {{ $report->status == 'dikirim' ? 'selected' : '' }}>⏳ Menunggu (Baru)</option>
                                    <option value="sedang_dikerjakan" {{ $report->status == 'sedang_dikerjakan' ? 'selected' : '' }}>🛠️ Sedang Dikerjakan</option>
                                    <option value="selesai" {{ $report->status == 'selesai' ? 'selected' : '' }}>✅ Selesai</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-gray-500">
                                    <i class="fa-solid fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Berikan Tanggapan</label>
                            <textarea name="response" rows="6"
                                      class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#222831] focus:border-transparent placeholder-gray-400"
                                      placeholder="Tulis pesan balasan untuk penghuni di sini...">{{ old('response', $report->response) }}</textarea>
                        </div>

                        <button type="submit" class="w-full bg-[#222831] text-[#DFD0B8] font-bold py-3.5 rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center gap-2">
                            <i class="fa-solid fa-paper-plane"></i> Simpan & Kirim
                        </button>
                    </form>

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
                @if($report->photo)
                    <img src="{{ asset('storage/'.$report->photo) }}" class="w-full h-auto rounded-lg shadow-2xl border border-gray-800">
                @endif
            </div>
        </div>
    </div>

</div>
@endsection
