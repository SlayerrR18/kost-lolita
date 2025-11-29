@extends('layouts.admin-layout')

@section('title', 'Edit Penghuni - ' . $user->name)

@section('content')
<div class="py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('admin.tenants.index') }}" class="w-10 h-10 flex items-center justify-center rounded-full bg-white border border-gray-200 text-gray-500 hover:bg-[#222831] hover:text-[#DFD0B8] transition-colors shadow-sm">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Manajemen Penghuni</span>
                <h1 class="text-2xl font-serif font-bold text-[#222831]">Edit Profil Penghuni</h1>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <form action="{{ route('admin.tenants.update', $user) }}" method="POST" class="p-8">
                @csrf
                @method('PATCH')

                <div class="space-y-8">

                    <div>
                        <h3 class="text-lg font-bold text-[#222831] border-b border-gray-100 pb-2 mb-4">
                            <i class="fa-regular fa-id-card mr-2 text-[#DFD0B8]"></i> Data Akun
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                        <i class="fa-regular fa-user"></i>
                                    </span>
                                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                                           class="pl-10 block w-full rounded-xl border-gray-200 shadow-sm focus:border-[#222831] focus:ring focus:ring-[#222831]/20 transition-all placeholder-gray-300">
                                </div>
                                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Alamat Email</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                        <i class="fa-regular fa-envelope"></i>
                                    </span>
                                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                                           class="pl-10 block w-full rounded-xl border-gray-200 shadow-sm focus:border-[#222831] focus:ring focus:ring-[#222831]/20 transition-all placeholder-gray-300">
                                </div>
                                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <div x-data="{ showPass: false }">
                        <h3 class="text-lg font-bold text-[#222831] border-b border-gray-100 pb-2 mb-4">
                            <i class="fa-solid fa-shield-halved mr-2 text-[#DFD0B8]"></i> Keamanan
                        </h3>

                        <div class="bg-yellow-50 border border-yellow-100 rounded-xl p-4 mb-4 flex gap-3 items-start">
                            <i class="fa-solid fa-circle-info text-yellow-600 mt-0.5"></i>
                            <p class="text-sm text-yellow-800">
                                Kosongkan kolom password di bawah jika Anda tidak ingin mengubah kata sandi penghuni.
                            </p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">Password Baru</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                        <i class="fa-solid fa-lock"></i>
                                    </span>
                                    <input :type="showPass ? 'text' : 'password'" name="password" id="password" placeholder="••••••••"
                                           class="pl-10 pr-10 block w-full rounded-xl border-gray-200 shadow-sm focus:border-[#222831] focus:ring focus:ring-[#222831]/20 transition-all placeholder-gray-300">

                                    <button type="button" @click="showPass = !showPass" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                                        <i class="fa-solid" :class="showPass ? 'fa-eye-slash' : 'fa-eye'"></i>
                                    </button>
                                </div>
                                @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-1">Konfirmasi Password</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                        <i class="fa-solid fa-check-double"></i>
                                    </span>
                                    <input :type="showPass ? 'text' : 'password'" name="password_confirmation" id="password_confirmation" placeholder="••••••••"
                                           class="pl-10 block w-full rounded-xl border-gray-200 shadow-sm focus:border-[#222831] focus:ring focus:ring-[#222831]/20 transition-all placeholder-gray-300">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="mt-10 pt-6 border-t border-gray-100 flex items-center justify-end gap-3">
                    <a href="{{ route('admin.tenants.index') }}" class="px-6 py-2.5 rounded-xl border border-gray-200 text-gray-600 font-medium hover:bg-gray-50 transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-[#222831] text-[#DFD0B8] font-bold shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex items-center gap-2">
                        <i class="fa-solid fa-floppy-disk"></i>
                        Simpan Perubahan
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
