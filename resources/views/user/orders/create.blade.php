@extends('layouts.order')

@section('title', 'Booking Kamar - Kost Lolita')

@section('content')
<div x-data="{
    duration: {{ old('rent_duration', 1) }},
    pricePerMonth: {{ $room->price }},
    formatRupiah(value) {
        return new Intl.NumberFormat('id-ID').format(value);
    },
    get total() {
        return this.duration * this.pricePerMonth;
    }
}" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="flex items-center gap-4 mb-8">
        <a href="{{ url()->previous() }}" class="w-10 h-10 flex items-center justify-center rounded-full bg-white border border-gray-200 text-gray-500 hover:bg-[#222831] hover:text-[#DFD0B8] transition-colors shadow-sm">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-3xl font-serif font-bold text-[#222831]">Konfirmasi Pesanan</h1>
            <p class="text-sm text-gray-500">Lengkapi data diri dan pembayaran untuk mengamankan kamar.</p>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-6 rounded-xl bg-green-50 border border-green-200 p-4 flex items-center gap-3 text-green-800">
            <i class="fa-solid fa-circle-check text-xl"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <form action="{{ route('user.orders.store', $room) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

            <div class="lg:col-span-2 space-y-6">

                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 sm:p-8">
                    <div class="flex items-center gap-3 mb-6 border-b border-gray-100 pb-4">
                        <div class="w-10 h-10 rounded-full bg-[#DFD0B8]/20 flex items-center justify-center text-[#222831]">
                            <i class="fa-regular fa-user text-lg"></i>
                        </div>
                        <h2 class="text-xl font-bold text-[#222831]">Data Penyewa</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Email Akun</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400"><i class="fa-solid fa-envelope"></i></span>
                                <input type="email" value="{{ $user->email }}" disabled class="pl-10 w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-500 cursor-not-allowed">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
                            <input type="text" name="full_name" value="{{ old('full_name', $user->name) }}" class="w-full border-gray-200 rounded-xl px-4 py-3 focus:border-[#222831] focus:ring focus:ring-[#222831]/20 transition-all" required placeholder="Sesuai KTP">
                            @error('full_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor WhatsApp</label>
                            <input type="text" name="phone" value="{{ old('phone') }}" class="w-full border-gray-200 rounded-xl px-4 py-3 focus:border-[#222831] focus:ring focus:ring-[#222831]/20 transition-all" required placeholder="0812...">
                            @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat Asal</label>
                            <textarea name="address" rows="2" class="w-full border-gray-200 rounded-xl px-4 py-3 focus:border-[#222831] focus:ring focus:ring-[#222831]/20 transition-all" required placeholder="Alamat lengkap sesuai KTP">{{ old('address') }}</textarea>
                            @error('address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Identitas (KTP)</label>
                            <div class="flex gap-4 items-start">
                                <div class="flex-1">
                                    <input type="text" name="id_number" value="{{ old('id_number') }}" class="w-full border-gray-200 rounded-xl px-4 py-3 focus:border-[#222831] focus:ring focus:ring-[#222831]/20 transition-all mb-2" required placeholder="NIK / Nomor KTP">
                                    @error('id_number') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div class="flex-1">
                                    <label class="flex items-center gap-2 px-4 py-3 bg-white border border-dashed border-gray-300 rounded-xl cursor-pointer hover:bg-gray-50 hover:border-[#222831] transition-all">
                                        <i class="fa-solid fa-camera text-gray-400"></i>
                                        <span class="text-sm text-gray-500 truncate">Upload Foto KTP</span>
                                        <input type="file" name="id_photo" class="hidden" accept="image/*" required onchange="this.previousElementSibling.innerText = this.files[0].name">
                                    </label>
                                    @error('id_photo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 sm:p-8">
                    <div class="flex items-center gap-3 mb-6 border-b border-gray-100 pb-4">
                        <div class="w-10 h-10 rounded-full bg-[#DFD0B8]/20 flex items-center justify-center text-[#222831]">
                            <i class="fa-regular fa-calendar-check text-lg"></i>
                        </div>
                        <h2 class="text-xl font-bold text-[#222831]">Durasi & Tanggal</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Mulai Tanggal</label>
                            <input type="date" name="start_date" value="{{ old('start_date') }}" class="w-full border-gray-200 rounded-xl px-4 py-3 focus:border-[#222831] focus:ring focus:ring-[#222831]/20 transition-all" required>
                            @error('start_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Durasi Sewa (Bulan)</label>
                            <div>
                                <select name="rent_duration" x-model.number="duration" class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:border-[#222831] focus:ring focus:ring-[#222831]/20 transition-all" required>
                                    @for ($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ old('rent_duration', 1) == $i ? 'selected' : '' }}>{{ $i }} Bulan</option>
                                    @endfor
                                </select>
                            </div>
                            @error('rent_duration') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 sm:p-8">
                    <div class="flex items-center gap-3 mb-6 border-b border-gray-100 pb-4">
                        <div class="w-10 h-10 rounded-full bg-[#DFD0B8]/20 flex items-center justify-center text-[#222831]">
                            <i class="fa-solid fa-wallet text-lg"></i>
                        </div>
                        <h2 class="text-xl font-bold text-[#222831]">Bukti Pembayaran</h2>
                    </div>

                    <div class="bg-gray-50 rounded-xl p-5 mb-5 border border-dashed border-gray-300 text-center">
                        <p class="text-sm text-gray-500 mb-2">Silakan transfer Total Pembayaran ke:</p>
                        <div class="flex items-center justify-center gap-3 mb-2">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/5/5c/Bank_Central_Asia.svg" alt="BCA" class="h-6">
                            <span class="font-bold text-lg text-[#222831]">7065037157</span>
                        </div>
                        <p class="text-xs text-gray-400">a.n. Yoseph Zosimus Sakera</p>
                    </div>

                    <label class="block text-sm font-semibold text-gray-700 mb-2">Upload Bukti Transfer</label>
                    <input type="file" name="transfer_proof" class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-[#222831] file:text-[#DFD0B8] hover:file:bg-gray-800 transition-all cursor-pointer border border-gray-200 rounded-xl" required accept="image/*">
                    @error('transfer_proof') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

            </div>

            <div class="lg:col-span-1">
                <div class="bg-white rounded-3xl shadow-lg border border-gray-100 p-6 sticky top-24">
                    <h3 class="text-lg font-bold text-[#222831] mb-4">Ringkasan Pesanan</h3>

                    <div class="flex gap-4 mb-4">
                        @if($room->photos && count($room->photos) > 0)
                            <img src="{{ Storage::url($room->photos[0]) }}" class="w-20 h-20 rounded-xl object-cover border border-gray-100">
                        @else
                            <div class="w-20 h-20 rounded-xl bg-gray-200 flex items-center justify-center text-gray-400">
                                <i class="fa-solid fa-image"></i>
                            </div>
                        @endif
                        <div>
                            <h4 class="font-bold text-[#222831]">Kamar {{ $room->room_number }}</h4>
                            <div class="flex flex-wrap gap-1 mt-1">
                                @foreach(array_slice($room->facilities ?? [], 0, 2) as $f)
                                    <span class="text-[10px] bg-gray-100 px-2 py-0.5 rounded text-gray-600">{{ $f }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <hr class="border-dashed border-gray-200 my-4">

                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between text-gray-500">
                            <span>Harga Sewa</span>
                            <span>Rp {{ number_format($room->price, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-gray-500">
                            <span>Durasi</span>
                            <span><span x-text="duration"></span> Bulan</span>
                        </div>
                        <div class="flex justify-between text-gray-500">
                            <span>Biaya Admin</span>
                            <span class="text-green-600">Gratis</span>
                        </div>
                    </div>

                    <hr class="border-gray-200 my-4">

                    <div class="flex justify-between items-center mb-6">
                        <span class="font-bold text-gray-700">Total Bayar</span>
                        <span class="font-bold text-2xl text-[#222831]">
                            Rp <span x-text="formatRupiah(total)"></span>
                        </span>
                    </div>

                    <button type="submit" class="w-full bg-[#222831] text-[#DFD0B8] py-4 rounded-xl font-bold text-lg shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex justify-center items-center gap-2">
                        <span>Bayar Sekarang</span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </button>

                    <p class="text-xs text-center text-gray-400 mt-4">
                        <i class="fa-solid fa-lock"></i> Data Anda aman & terenkripsi.
                    </p>
                </div>
            </div>

        </div>
    </form>
</div>
@endsection
