<x-guest-layout>
    <head>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
    </head>

    <div class="px-4 py-2">

        <div class="text-center mb-8">
            <a href="/" class="inline-block group mb-4">
                <div class="w-20 h-20 bg-white rounded-2xl flex items-center justify-center shadow-lg border border-gray-100 mx-auto transform transition-transform duration-300 group-hover:rotate-6 group-hover:scale-105">
                    <img src="{{ asset('img/Logo.png') }}" alt="Logo" class="h-12 w-auto">
                </div>
            </a>
            <h2 class="text-3xl font-serif font-bold text-[#222831]">Buat Akun</h2>
            <p class="text-gray-500 text-sm mt-2">Bergabunglah dengan komunitas Kost Lolita.</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf

            <div>
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-1.5 ml-1">Nama Lengkap</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fa-regular fa-user text-gray-400 group-focus-within:text-[#222831] transition-colors"></i>
                    </div>
                    <input id="name"
                           class="pl-11 block w-full rounded-xl border-gray-200 bg-gray-50 text-gray-800 shadow-sm focus:border-[#222831] focus:ring focus:ring-[#222831]/10 focus:bg-white transition-all duration-200 py-3"
                           type="text"
                           name="name"
                           :value="old('name')"
                           placeholder="John Doe"
                           required autofocus autocomplete="name" />
                </div>
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5 ml-1">Email Address</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fa-regular fa-envelope text-gray-400 group-focus-within:text-[#222831] transition-colors"></i>
                    </div>
                    <input id="email"
                           class="pl-11 block w-full rounded-xl border-gray-200 bg-gray-50 text-gray-800 shadow-sm focus:border-[#222831] focus:ring focus:ring-[#222831]/10 focus:bg-white transition-all duration-200 py-3"
                           type="email"
                           name="email"
                           :value="old('email')"
                           placeholder="nama@email.com"
                           required autocomplete="username" />
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div x-data="{ show: false }">
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5 ml-1">Password</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fa-solid fa-lock text-gray-400 group-focus-within:text-[#222831] transition-colors"></i>
                    </div>

                    <input id="password"
                           class="pl-11 pr-11 block w-full rounded-xl border-gray-200 bg-gray-50 text-gray-800 shadow-sm focus:border-[#222831] focus:ring focus:ring-[#222831]/10 focus:bg-white transition-all duration-200 py-3"
                           :type="show ? 'text' : 'password'"
                           name="password"
                           placeholder="••••••••"
                           required autocomplete="new-password" />

                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none cursor-pointer">
                        <i class="fa-solid" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div x-data="{ showConfirm: false }">
                <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-1.5 ml-1">Konfirmasi Password</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fa-solid fa-shield-halved text-gray-400 group-focus-within:text-[#222831] transition-colors"></i>
                    </div>

                    <input id="password_confirmation"
                           class="pl-11 pr-11 block w-full rounded-xl border-gray-200 bg-gray-50 text-gray-800 shadow-sm focus:border-[#222831] focus:ring focus:ring-[#222831]/10 focus:bg-white transition-all duration-200 py-3"
                           :type="showConfirm ? 'text' : 'password'"
                           name="password_confirmation"
                           placeholder="••••••••"
                           required autocomplete="new-password" />

                    <button type="button" @click="showConfirm = !showConfirm" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none cursor-pointer">
                        <i class="fa-solid" :class="showConfirm ? 'fa-eye-slash' : 'fa-eye'"></i>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full flex justify-center items-center gap-3 bg-[#222831] text-[#DFD0B8] py-3.5 px-4 rounded-xl font-bold text-sm uppercase tracking-wider shadow-lg hover:shadow-xl hover:-translate-y-0.5 hover:bg-gray-800 transition-all duration-300">
                    <span>Daftar Sekarang</span>
                    <i class="fa-solid fa-user-plus"></i>
                </button>
            </div>
        </form>

        <div class="mt-8 pt-6 border-t border-gray-100 text-center">
            <p class="text-sm text-gray-500">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="font-bold text-[#222831] hover:text-[#DFD0B8] transition-colors ml-1">
                    Masuk disini
                </a>
            </p>
        </div>
    </div>
</x-guest-layout>
