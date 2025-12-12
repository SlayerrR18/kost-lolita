@extends('layouts.admin-layout')

@section('title', 'Pesan Masuk')

@section('content')
<div class="h-[calc(100vh-100px)] -m-6 flex bg-white" x-data="chatApp()">

    <aside class="w-full md:w-80 lg:w-96 border-r border-gray-200 flex flex-col bg-white"
           :class="{'hidden md:flex': mobileChatOpen, 'flex': !mobileChatOpen}">

        <div class="p-4 border-b border-gray-100 bg-gray-50/50">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-serif font-bold text-[#222831]">Pesan</h2>
                <button @click="newChatModal = true" class="w-8 h-8 rounded-full bg-[#222831] text-[#DFD0B8] flex items-center justify-center hover:bg-black transition shadow-sm" title="Chat Baru">
                    <i class="fa-solid fa-plus"></i>
                </button>
            </div>

            <div class="relative">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                <input type="text" x-model="search" placeholder="Cari nama penghuni..."
                       class="w-full pl-9 pr-4 py-2 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#222831] focus:border-transparent transition">
            </div>
        </div>

        <div class="flex-1 overflow-y-auto custom-scrollbar p-2 space-y-1">
            @foreach($conversations as $u)
                <a href="{{ route('messages.index', ['user_id' => $u->id]) }}"
                   class="flex items-center gap-3 p-3 rounded-xl transition-all hover:bg-gray-50 group relative
                   {{ (string)$selectedUserId === (string)$u->id ? 'bg-[#222831]/5 border border-[#222831]/10' : '' }}"
                   x-show="matchesSearch('{{ strtolower($u->name) }}')">

                    <div class="relative shrink-0">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($u->name) }}&background=random&color=fff"
                             class="w-12 h-12 rounded-full object-cover border border-gray-100 shadow-sm" alt="">
                        @if($u->unread_count > 0)
                            <div class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full border-2 border-white">
                                {{ $u->unread_count }}
                            </div>
                        @endif
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-center mb-0.5">
                            <h4 class="font-bold text-sm text-gray-800 truncate {{ (string)$selectedUserId === (string)$u->id ? 'text-[#222831]' : '' }}">
                                {{ $u->name }}
                            </h4>
                            @if($u->last_message)
                                <span class="text-[10px] text-gray-400">{{ $u->last_message->created_at->format('H:i') }}</span>
                            @endif
                        </div>
                        <p class="text-xs text-gray-500 truncate group-hover:text-gray-700">
                            @if($u->last_message && $u->last_message->sender_id == auth()->id())
                                <i class="fa-solid fa-check text-[10px] mr-1"></i>
                            @endif
                            {{ $u->last_message ? Str::limit($u->last_message->content ?: '📷 Mengirim foto', 30) : 'Mulai percakapan baru' }}
                        </p>
                    </div>
                </a>
            @endforeach

            <div x-show="filteredCount() === 0" class="text-center py-8 text-gray-400 text-sm" style="display: none;">
                Tidak ada penghuni ditemukan.
            </div>
        </div>
    </aside>

    <main class="flex-1 flex flex-col bg-[#f0f2f5] relative"
          :class="{'flex': mobileChatOpen, 'hidden md:flex': !mobileChatOpen}">

        @if($selectedUser)
            <div class="bg-white border-b border-gray-200 px-6 py-3 flex items-center justify-between shadow-sm z-10">
                <div class="flex items-center gap-3">
                    <a href="{{ route('messages.index') }}" class="md:hidden text-gray-500 hover:text-[#222831]">
                        <i class="fa-solid fa-arrow-left text-lg"></i>
                    </a>

                    <img src="https://ui-avatars.com/api/?name={{ urlencode($selectedUser->name) }}&background=222831&color=DFD0B8"
                         class="w-10 h-10 rounded-full border border-gray-100" alt="">
                    <div>
                        <h3 class="font-bold text-gray-800 leading-tight">{{ $selectedUser->name }}</h3>
                        <p class="text-xs text-green-600 flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Penghuni Kost
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto p-6 space-y-4" id="chatStream">
                <div class="flex justify-center my-4">
                    <span class="bg-gray-200 text-gray-600 text-[10px] px-3 py-1 rounded-full font-bold uppercase tracking-wider">
                        Hari Ini
                    </span>
                </div>

                @foreach($messages as $m)
                    <div class="flex {{ $m->sender_id == auth()->id() ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-[75%] {{ $m->sender_id == auth()->id() ? 'order-1' : 'order-2' }}">

                            <div class="relative px-4 py-2 rounded-2xl shadow-sm text-sm
                                {{ $m->sender_id == auth()->id()
                                    ? 'bg-[#222831] text-[#DFD0B8] rounded-tr-none'
                                    : 'bg-white text-gray-800 border border-gray-100 rounded-tl-none' }}">

                                @if($m->attachment)
                                    <div class="mb-2 -mx-2 -mt-2">
                                        <img src="{{ asset('storage/' . $m->attachment) }}"
                                             class="rounded-lg max-h-60 w-auto object-cover cursor-pointer hover:opacity-90 transition"
                                             @click="openLightbox('{{ asset('storage/' . $m->attachment) }}')">
                                    </div>
                                @endif

                                @if($m->content)
                                    <p class="whitespace-pre-line leading-relaxed">{{ $m->content }}</p>
                                @endif

                                <div class="text-[10px] mt-1 text-right {{ $m->sender_id == auth()->id() ? 'text-[#DFD0B8]/60' : 'text-gray-400' }}">
                                    {{ $m->created_at->format('H:i') }}
                                    @if($m->sender_id == auth()->id())
                                        <i class="fa-solid fa-check ml-1"></i>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="bg-white p-4 border-t border-gray-200">
                <div x-show="filePreview" class="flex items-center gap-3 mb-3 bg-gray-50 p-2 rounded-lg w-fit border border-gray-200" style="display: none;">
                    <div class="w-12 h-12 bg-gray-200 rounded overflow-hidden">
                        <img :src="filePreview" class="w-full h-full object-cover">
                    </div>
                    <div class="text-xs">
                        <p class="font-bold text-gray-700">Lampiran Gambar</p>
                        <button @click="clearFile" class="text-red-500 hover:underline">Hapus</button>
                    </div>
                </div>

                <form id="messageForm" class="flex items-end gap-2" @submit.prevent="sendMessage">
                    @csrf
                    <input type="hidden" name="recipient_id" value="{{ $selectedUserId }}">

                    <button type="button" @click="$refs.fileInput.click()" class="p-3 text-gray-400 hover:text-[#222831] hover:bg-gray-100 rounded-full transition">
                        <i class="fa-solid fa-paperclip text-lg"></i>
                    </button>
                    <input type="file" x-ref="fileInput" name="attachment" class="hidden" accept="image/*" @change="handleFileSelect">

                    <div class="flex-1 bg-gray-100 rounded-2xl px-4 py-3 border border-transparent focus-within:border-gray-300 focus-within:bg-white transition">
                        <input type="text" x-model="messageInput" name="content"
                               class="w-full bg-transparent border-none focus:ring-0 text-sm text-gray-800 placeholder-gray-400"
                               placeholder="Ketik pesan balasan..." autocomplete="off">
                    </div>

                    <button type="submit"
                            class="p-3 bg-[#222831] text-[#DFD0B8] rounded-full shadow-lg hover:bg-black hover:scale-105 transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                            :disabled="!messageInput && !filePreview">
                        <i class="fa-solid fa-paper-plane"></i>
                    </button>
                </form>
            </div>

        @else
            <div class="h-full flex flex-col items-center justify-center text-center p-8 bg-[#f8fafc]">
                <div class="w-32 h-32 bg-gray-100 rounded-full flex items-center justify-center mb-6">
                    <i class="fa-regular fa-comments text-5xl text-gray-300"></i>
                </div>
                <h3 class="text-2xl font-serif font-bold text-[#222831]">Pusat Pesan Admin</h3>
                <p class="text-gray-500 max-w-md mt-2">Pilih penghuni dari daftar di sebelah kiri untuk mulai membaca pesan atau mengirim pengumuman.</p>
            </div>
        @endif

    </main>

    <div x-show="lightboxOpen" style="display: none;" class="fixed inset-0 z-50 overflow-hidden" x-cloak>
        <div class="absolute inset-0 bg-black/90 backdrop-blur-sm transition-opacity" @click="lightboxOpen = false"></div>
        <div class="absolute inset-0 flex items-center justify-center p-4 pointer-events-none">
            <div class="relative max-w-4xl w-full pointer-events-auto" x-show="lightboxOpen" x-transition.scale>
                <button @click="lightboxOpen = false" class="absolute -top-12 right-0 text-white hover:text-gray-300 text-2xl">
                    <i class="fa-solid fa-xmark"></i>
                </button>
                <img :src="lightboxSrc" class="w-full h-auto max-h-[80vh] object-contain rounded-lg shadow-2xl bg-black">
            </div>
        </div>
    </div>

    <div x-show="newChatModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="newChatModal = false"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl p-6" @click.stop>
                <h3 class="text-lg font-bold text-[#222831] mb-4">Mulai Percakapan Baru</h3>
                <div class="relative mb-4">
                    <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" x-model="userSearch" placeholder="Cari nama penghuni..."
                           class="w-full pl-9 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-[#222831]">
                </div>
                <div class="max-h-60 overflow-y-auto space-y-1 custom-scrollbar">
                    @foreach($users as $user)
                        <a href="{{ route('messages.index', ['user_id' => $user->id]) }}"
                           class="flex items-center gap-3 p-2 hover:bg-gray-50 rounded-lg"
                           x-show="'{{ strtolower($user->name) }}'.includes(userSearch.toLowerCase())">
                            <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold text-gray-600">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-800">{{ $user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $user->email }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    function chatApp() {
        return {
            search: '',
            userSearch: '',
            messageInput: '',
            filePreview: null,
            lightboxOpen: false,
            lightboxSrc: '',
            newChatModal: false,
            mobileChatOpen: {{ $selectedUser ? 'true' : 'false' }},

            init() {
                this.scrollToBottom();
            },

            matchesSearch(name) {
                return name.includes(this.search.toLowerCase());
            },

            filteredCount() {
                // Helper logic bisa ditambahkan jika ingin menghitung hasil search
                return 1;
            },

            scrollToBottom() {
                const stream = document.getElementById('chatStream');
                if (stream) stream.scrollTop = stream.scrollHeight;
            },

            handleFileSelect(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.filePreview = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            },

            clearFile() {
                this.filePreview = null;
                this.$refs.fileInput.value = null;
            },

            openLightbox(src) {
                this.lightboxSrc = src;
                this.lightboxOpen = true;
            },

            async sendMessage(e) {
                const form = e.target;
                const fd = new FormData(form);

                // Optimistic UI Update (Opsional: bisa tambah bubble palsu dulu)
                // Disini kita reload simple dulu
                try {
                    const res = await fetch('{{ route("messages.store") }}', {
                        method: 'POST',
                        headers: { 'X-Requested-With': 'XMLHttpRequest' },
                        body: fd
                    });

                    if (res.ok) {
                        window.location.reload(); // Reload untuk menampilkan pesan baru
                    } else {
                        alert('Gagal mengirim pesan.');
                    }
                } catch (error) {
                    console.error(error);
                }
            }
        }
    }
</script>
@endsection
