@extends('layouts.user-layout')

@section('title', 'Pengaturan Akun')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 pb-24">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-8">
            <a href="{{ route('user.contract.index') }}" class="inline-flex items-center text-sm font-bold text-gray-500 hover:text-[#222831] mb-2 transition">
                <i class="fa-solid fa-arrow-left mr-2"></i> Kembali
            </a>
            <h1 class="text-3xl font-serif font-bold text-[#222831]">Pengaturan Akun</h1>
            <p class="text-gray-500 text-sm mt-1">Perbarui informasi profil dan keamanan akun Anda.</p>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-green-100 border border-green-200 text-green-800 px-4 py-3 rounded-xl flex items-center gap-2 shadow-sm"
                 x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)">
                <i class="fa-solid fa-circle-check"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <div class="lg:col-span-1 space-y-6">

                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 text-center relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-full h-24 bg-gradient-to-r from-[#222831] to-[#393E46]"></div>

                        <div class="relative z-10 -mt-8 mb-4 inline-block group">
                            <img class="w-32 h-32 rounded-full border-4 border-white shadow-md object-cover bg-white"
                                 src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=DFD0B8&color=222831&size=128"
                                 alt="{{ $user->name }}">

                            <div class="absolute bottom-0 right-0 bg-white rounded-full p-1.5 shadow-sm border border-gray-100 cursor-pointer hover:bg-gray-50" title="Ganti Foto">
                                <i class="fa-solid fa-camera text-gray-500 text-sm"></i>
                            </div>
                        </div>

                        <h2 class="text-xl font-bold text-[#222831]">{{ $user->name }}</h2>
                        <p class="text-xs text-gray-500 mb-6">Penghuni Kost Lolita</p>

                        <div class="space-y-4 text-left">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nama Lengkap</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                        <i class="fa-solid fa-user"></i>
                                    </span>
                                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                           class="w-full pl-10 rounded-xl border-gray-300 focus:border-[#222831] focus:ring focus:ring-[#222831]/20 transition py-2.5 text-sm">
                                </div>
                                @error('name') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Alamat Email</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                        <i class="fa-solid fa-envelope"></i>
                                    </span>
                                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                           class="w-full pl-10 rounded-xl border-gray-300 focus:border-[#222831] focus:ring focus:ring-[#222831]/20 transition py-2.5 text-sm">
                                </div>
                                @error('email') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>

                </div>

                <div class="lg:col-span-2 space-y-6">

                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
                        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100">
                            <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center text-red-500">
                                <i class="fa-solid fa-lock"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-[#222831]">Ganti Password</h3>
                                <p class="text-xs text-gray-500">Kosongkan jika tidak ingin mengubah password.</p>
                            </div>
                        </div>

                        <div class="space-y-5">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Password Saat Ini</label>
                                <input type="password" name="current_password" placeholder="Masukkan password lama..."
                                       class="w-full rounded-xl border-gray-300 focus:border-[#222831] focus:ring focus:ring-[#222831]/20 py-2.5">
                                @error('current_password') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Password Baru</label>
                                    <input type="password" name="password" placeholder="Minimal 8 karakter"
                                           class="w-full rounded-xl border-gray-300 focus:border-[#222831] focus:ring focus:ring-[#222831]/20 py-2.5">
                                    @error('password') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Konfirmasi Password Baru</label>
                                    <input type="password" name="password_confirmation" placeholder="Ulangi password baru"
                                           class="w-full rounded-xl border-gray-300 focus:border-[#222831] focus:ring focus:ring-[#222831]/20 py-2.5">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="fixed bottom-6 left-0 right-0 px-4 flex justify-center z-40 pointer-events-none">
                <div class="bg-[#222831] text-white px-6 py-3 rounded-full shadow-2xl flex items-center gap-4 pointer-events-auto transform hover:scale-105 transition-all">
                    <div class="h-4 w-px bg-gray-600"></div>
                    <button type="submit" class="font-bold hover:text-[#DFD0B8] transition flex items-center gap-2">
                        <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
                    </button>
                </div>
            </div>

        </form>
    </div>
</div>
@endsection
