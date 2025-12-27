@extends('layouts.user-layout')

@section('title', 'Kontrak & Profil Saya')

@section('content')
<div x-data="{
    extendModal: false,
    contactModal: false,
    changeRoom: false,
    duration: 1,
    availableRooms: [],
    originalPrice: {{ $latestContract ? $latestContract->room->price : 0 }},
    pricePerMonth: {{ $latestContract ? $latestContract->room->price : 0 }}
}" x-init="fetch('{{ route('user.contract.available-rooms') }}')
        .then(res => res.json())
        .then(data => availableRooms = data)
        .catch(() => availableRooms = [])
" class="min-h-screen bg-gray-50 py-8">

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-serif font-bold text-[#222831]">Kontrak & Profil Saya</h1>
                <p class="text-gray-500 text-sm mt-1">Kelola kontrak aktif, profil, dan perpanjangan sewa.</p>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-200 text-green-800 px-4 py-2 rounded-lg text-sm flex items-center shadow-sm"
                     x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)">
                    <i class="fa-solid fa-check-circle mr-2"></i> {{ session('success') }}
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <div class="space-y-6">

                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 text-center relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-2 bg-[#222831]"></div>

                    @if($latestContract)
                        @php
                            $endDate = \Carbon\Carbon::parse($latestContract->start_date)->addMonths($latestContract->rent_duration);
                            $daysLeft = now()->diffInDays($endDate, false);

                            // Hitung persentase untuk lingkaran progress
                            $totalDays = \Carbon\Carbon::parse($latestContract->start_date)->diffInDays($endDate);
                            $progress = 100;
                            if($totalDays > 0 && $daysLeft > 0) {
                                $progress = ($daysLeft / $totalDays) * 100;
                            }
                            $strokeDash = 226; // Keliling lingkaran r=36
                            $strokeOffset = $strokeDash - ($strokeDash * $progress / 100);
                        @endphp

                        <h3 class="text-gray-400 text-xs font-bold uppercase tracking-widest mb-4">Sisa Masa Sewa</h3>

                        @if($daysLeft > 0)
                            <div class="relative w-32 h-32 mx-auto mb-4 flex items-center justify-center">
                                <svg class="transform -rotate-90 w-32 h-32">
                                    <circle cx="64" cy="64" r="56" stroke="#f3f4f6" stroke-width="8" fill="transparent" />
                                    <circle cx="64" cy="64" r="56" stroke="{{ $daysLeft < 7 ? '#ef4444' : '#222831' }}" stroke-width="8" fill="transparent"
                                            stroke-dasharray="351" stroke-dashoffset="{{ 351 - (351 * $progress) / 100 }}"
                                            class="transition-all duration-1000 ease-out" />
                                </svg>
                                <div class="absolute inset-0 flex flex-col items-center justify-center">
                                    <span class="text-3xl font-bold text-[#222831]">{{ (int)$daysLeft }}</span>
                                    <span class="text-[10px] text-gray-500 uppercase font-bold">Hari Lagi</span>
                                </div>
                            </div>

                            @if(!$isExtensionPending)
                                <button @click="extendModal = true" class="w-full py-2.5 bg-[#222831] text-[#DFD0B8] rounded-xl font-bold text-sm hover:shadow-lg hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-file-signature"></i> Perpanjang
                                </button>
                            @else
                                <div class="w-full py-2.5 bg-yellow-50 text-yellow-700 rounded-xl font-bold text-sm border border-yellow-200 flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-clock"></i> Menunggu Konfirmasi
                                </div>
                            @endif
                        @else
                            <div class="text-red-500 font-bold text-lg mb-4 bg-red-50 py-3 rounded-xl">Masa Sewa Habis</div>
                            <a href="{{ route('landing') }}#rooms" class="block w-full py-2.5 bg-[#222831] text-white rounded-xl font-bold text-sm hover:bg-black transition text-center">
                                Sewa Lagi
                            </a>
                        @endif
                    @else
                        <div class="py-8">
                            <i class="fa-solid fa-file-circle-xmark text-4xl text-gray-300 mb-3"></i>
                            <p class="text-sm text-gray-500">Tidak ada kontrak aktif.</p>
                        </div>
                    @endif
                </div>

                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
                    <h3 class="font-bold text-[#222831] mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-user text-[#DFD0B8]"></i> Profil Penyewa
                    </h3>

                    <div class="flex items-center gap-4 mb-4">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=222831&color=DFD0B8"
                             class="w-12 h-12 rounded-full border-2 border-gray-100" alt="Avatar">
                        <div>
                            <p class="text-sm font-bold text-gray-800">{{ $user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $user->email }}</p>
                        </div>
                    </div>

                    <div class="space-y-3 pt-3 border-t border-gray-100">
                        <div>
                            <p class="text-[10px] text-gray-400 uppercase font-bold">No. HP</p>
                            <p class="text-sm text-gray-700">{{ $user->phone ?? ($latestContract->phone ?? '-') }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-400 uppercase font-bold">NIK / Identitas</p>
                            <p class="text-sm text-gray-700">{{ $user->id_number ?? ($latestContract->id_number ?? '-') }}</p>
                        </div>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('user.profile.edit') }}" class="text-xs font-bold text-[#222831] underline hover:text-[#DFD0B8]">Edit Akun</a>
                    </div>
                </div>

                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-bold text-[#222831] flex items-center gap-2">
                            <i class="fa-solid fa-kit-medical text-red-500"></i> Darurat
                        </h3>
                        <button @click="contactModal = true" class="text-xs font-bold text-gray-400 hover:text-[#222831] underline">
                            Update
                        </button>
                    </div>

                    @php $contact = $user->emergency_contact; @endphp

                    @if(!empty($user->emergency_contact['name']))
                        <div class="space-y-2">
                            <p class="text-sm font-bold text-gray-800">{{ $user->emergency_contact['name'] }} <span class="text-xs font-normal text-gray-500">({{ $user->emergency_contact['relation'] ?? 'Kerabat' }})</span></p>
                            <a href="tel:{{ $user->emergency_contact['phone'] }}" class="inline-block bg-red-50 text-red-600 px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-red-100 transition">
                                <i class="fa-brands fa-whatsapp mr-1"></i> {{ $user->emergency_contact['phone'] }}
                            </a>
                        </div>
                    @else
                        <div class="text-center py-2">
                            <p class="text-xs text-gray-400 mb-2">Belum diatur.</p>
                            <button @click="contactModal = true" class="text-xs font-bold text-[#222831] border border-gray-300 px-3 py-1 rounded-lg hover:bg-gray-50">+ Tambah</button>
                        </div>
                    @endif
                </div>
            </div>

            <div class="lg:col-span-2 space-y-6">

                @if($latestContract)
                    <div class="bg-white rounded-3xl shadow-lg border border-gray-100 overflow-hidden relative">
                        <div class="bg-[#222831] px-8 py-6 text-white flex justify-between items-center">
                            <div>
                                <h2 class="font-serif font-bold text-xl text-[#DFD0B8]">Perjanjian Sewa</h2>
                                <p class="text-xs opacity-60 mt-1">No. Kontrak: #ORD-{{ $latestContract->id }}</p>
                            </div>
                            <div class="bg-white/10 p-3 rounded-xl text-[#DFD0B8]">
                                <i class="fa-solid fa-file-signature text-2xl"></i>
                            </div>
                        </div>

                        <div class="p-8 relative">
                            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 opacity-[0.03] pointer-events-none">
                                <img src="{{ asset('img/Logo.png') }}" class="w-72 grayscale" alt="">
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 relative z-10">
                                <div>
                                    <p class="text-xs font-bold text-gray-400 uppercase mb-2">Objek Sewa</p>
                                    <div class="flex items-center gap-4">
                                        <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center text-gray-400 border border-gray-200">
                                            <i class="fa-solid fa-door-closed text-3xl"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-2xl font-bold text-[#222831]">Kamar {{ $latestContract->room->room_number }}</h3>
                                            <p class="text-sm text-gray-500">Kost Lolita - Tipe Standard</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-sm text-gray-600">Biaya Sewa</span>
                                        <span class="font-bold text-lg text-[#222831]">Rp {{ number_format($latestContract->room->price, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between items-center text-sm">
                                        <span class="text-gray-500">Periode Tagihan</span>
                                        <span class="font-medium text-gray-800">Per Bulan</span>
                                    </div>
                                </div>
                            </div>

                            <hr class="border-dashed border-gray-200 my-8">

                            <div class="grid grid-cols-2 gap-4 mb-8">
                                <div>
                                    <p class="text-xs text-gray-400 uppercase font-bold mb-1">Mulai Tanggal</p>
                                    <p class="font-medium text-gray-800 text-lg">
                                            {{ \Carbon\Carbon::parse($firstContract->start_date ?? $latestContract->start_date)->format('d F Y') }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 uppercase font-bold mb-1">Berakhir Tanggal</p>
                                    <p class="font-medium text-gray-800 text-lg">
                                        {{ $endDate->format('d F Y') }}
                                    </p>
                                </div>
                            </div>

                            <div>
                                <h3 class="text-sm font-bold text-[#222831] mb-3 flex items-center gap-2">
                                    <i class="fa-solid fa-list-check text-[#DFD0B8]"></i> Fasilitas Termasuk
                                </h3>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($latestContract->room->facilities ?? [] as $f)
                                        <span class="px-3 py-1.5 bg-white border border-gray-200 rounded-lg text-xs font-bold text-gray-600 flex items-center gap-2 shadow-sm">
                                            <i class="fa-solid fa-check text-green-500"></i> {{ $f }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>

                            <div class="mt-8 bg-blue-50 rounded-xl p-4 border border-blue-100 flex gap-3 items-start">
                                <i class="fa-solid fa-circle-info text-blue-600 mt-0.5"></i>
                                <div class="text-xs text-blue-800 leading-relaxed space-y-1">
                                    <p><strong>Catatan:</strong> Ini adalah kontrak digital yang sah. Harap perhatikan tanggal jatuh tempo.</p>
                                    <ul class="list-disc pl-4">
                                        <li>Keterlambatan pembayaran > 3 hari dikenakan denda.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-12 text-center">
                        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fa-solid fa-folder-open text-4xl text-gray-300"></i>
                        </div>
                        <h2 class="text-xl font-bold text-[#222831] mb-2">Tidak Ada Data Kontrak</h2>
                        <p class="text-gray-500 text-sm mb-6">Silakan lakukan pemesanan kamar terlebih dahulu.</p>
                        <a href="{{ route('landing') }}#rooms" class="inline-block bg-[#222831] text-[#DFD0B8] px-6 py-2.5 rounded-xl font-bold text-sm hover:shadow-lg transition">
                            Lihat Kamar
                        </a>
                    </div>
                @endif

                @if($history->count() > 0)
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                            <h3 class="font-bold text-[#222831] text-sm">Riwayat Kontrak Sebelumnya</h3>
                        </div>
                        <div class="divide-y divide-gray-100">
                            @foreach($history as $hist)
                                <div class="px-6 py-4 flex flex-col sm:flex-row sm:justify-between sm:items-center hover:bg-gray-50 transition gap-2">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center text-gray-500 font-bold text-xs">
                                            {{ $hist->room->room_number }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-[#222831]">Periode {{ \Carbon\Carbon::parse($hist->start_date)->format('M Y') }}</p>
                                            <p class="text-xs text-gray-500">Durasi {{ $hist->rent_duration }} Bulan</p>
                                        </div>
                                    </div>
                                    <span class="inline-flex px-3 py-1 rounded-full bg-gray-100 text-gray-500 text-xs font-bold border border-gray-200">
                                        Selesai
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>

        </div>

    </div>

    <div x-show="extendModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="extendModal = false" x-show="extendModal" x-transition.opacity></div>

        <div class="flex min-h-full items-center justify-center p-4">
            <div x-show="extendModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
                 class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl p-6 border border-gray-100">

                <div class="text-center mb-6 border-b border-gray-100 pb-4">
                    <h3 class="text-xl font-serif font-bold text-[#222831]">Perpanjang Sewa</h3>
                    <p class="text-sm text-gray-500 mt-1">Kamar {{ $latestContract->room->room_number ?? '-' }}</p>
                </div>

                <form action="{{ route('user.contract.extend') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Durasi Tambahan</label>
                            <select name="duration" x-model="duration" class="w-full rounded-xl border-gray-300 focus:border-[#222831] focus:ring-[#222831]/20 py-3">
                                @foreach([1,2,3,4,5,6,7,8,9,10,11,12] as $m)
                                    <option value="{{ $m }}">{{ $m }} Bulan</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="request_change_room" value="1" x-model="changeRoom" class="w-5 h-5 rounded border-gray-300 text-[#222831] focus:ring-[#222831]">
                                <span class="text-sm font-bold text-gray-700">Saya ingin pindah kamar</span>
                            </label>

                            <div x-show="changeRoom" class="mt-4" x-transition>
                                <label class="block text-xs font-bold text-gray-500 mb-1">Pilih Kamar Baru</label>
                                <select name="new_kost_id" x-on:change="
                                        const opt = $event.target.selectedOptions[0];
                                        if (opt && opt.dataset && opt.dataset.price) {
                                            pricePerMonth = Number(opt.dataset.price);
                                        } else {
                                            pricePerMonth = originalPrice;
                                        }
                                    " class="w-full rounded-lg border-gray-300 text-sm focus:ring-[#222831]">
                                    <option value="">-- Pilih Kamar Kosong --</option>
                                    <template x-for="r in availableRooms" :key="r.id">
                                        <option :value="r.id" :data-price="r.price" x-text="'Kamar ' + r.room_number + ' (Rp ' + new Intl.NumberFormat('id-ID').format(r.price) + ')' "></option>
                                    </template>
                                </select>
                                <p class="text-[10px] text-gray-400 mt-1">*Harga sewa akan menyesuaikan kamar baru.</p>
                            </div>

                            <input type="hidden" name="current_kost_id" value="{{ $latestContract->room->id }}">
                        </div>

                        <div class="flex justify-between items-center bg-[#222831] text-[#DFD0B8] p-4 rounded-xl shadow-sm">
                            <span class="text-sm">Estimasi Biaya</span>
                            <span class="font-bold text-lg font-mono" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(duration * pricePerMonth)"></span>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Upload Bukti Transfer (DP)</label>
                            <input type="file" name="bukti_pembayaran" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-[#DFD0B8] file:text-[#222831] hover:file:bg-[#d4c2a3] border border-gray-300 rounded-xl">
                        </div>
                    </div>

                    <div class="mt-8 flex gap-3">
                        <button type="button" @click="extendModal = false" class="flex-1 py-3 rounded-xl border border-gray-300 text-gray-700 font-bold hover:bg-gray-50 transition">Batal</button>
                        <button type="submit" class="flex-1 py-3 rounded-xl bg-[#222831] text-[#DFD0B8] font-bold hover:shadow-lg transition">Kirim Pengajuan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

   <div x-show="contactModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
    <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="contactModal = false"></div>

    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl p-6">

            <h3 class="text-xl font-serif font-bold text-[#222831] mb-6 border-b border-gray-100 pb-3">Update Kontak Darurat</h3>

            <form action="{{ route('user.contract.update-info') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Nama Kerabat</label>
                        <input type="text" name="name" value="{{ $user->emergency_contact['name'] ?? '' }}" class="w-full rounded-xl border-gray-300 focus:border-[#222831] focus:ring-[#222831]/20" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Hubungan</label>
                        <input type="text" name="relation" value="{{ $user->emergency_contact['relation'] ?? '' }}" class="w-full rounded-xl border-gray-300 focus:border-[#222831] focus:ring-[#222831]/20" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Nomor Telepon</label>
                        <input type="text" name="phone" value="{{ $user->emergency_contact['phone'] ?? '' }}" class="w-full rounded-xl border-gray-300 focus:border-[#222831] focus:ring-[#222831]/20" required>
                    </div>
                </div>

                <div class="mt-6 flex gap-3">
                    <button type="button" @click="contactModal = false" class="flex-1 py-2.5 rounded-xl border border-gray-300 text-gray-700 font-bold hover:bg-gray-50">Batal</button>
                    <button type="submit" class="flex-1 py-2.5 rounded-xl bg-[#222831] text-[#DFD0B8] font-bold hover:shadow-lg">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

</div>
@endsection
