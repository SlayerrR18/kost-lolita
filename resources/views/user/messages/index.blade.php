@extends('layouts.user-layout')

@section('title', 'Hubungi Admin')

@section('content')
<div x-data="userChatApp()" class="min-h-screen bg-gray-50 py-4 md:py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="bg-white rounded-3xl shadow-xl overflow-hidden flex flex-col h-[80vh] md:h-[85vh] border border-gray-100">

            <div class="bg-[#222831] p-4 md:p-5 flex items-center justify-between shadow-md z-10">
                <div class="flex items-center gap-4">
                    <div class="relative">
                        <div class="w-12 h-12 bg-white/10 rounded-full flex items-center justify-center text-[#DFD0B8] border border-white/20">
                            <i class="fa-solid fa-headset text-xl"></i>
                        </div>
                        <span class="absolute bottom-0 right-0 w-3.5 h-3.5 bg-green-500 border-2 border-[#222831] rounded-full"></span>
                    </div>
                    <div>
                        <h2 class="font-bold text-lg text-white font-serif">Admin Pengelola</h2>
                        <p class="text-xs text-gray-400">Siap membantu keluhan & pertanyaan Anda</p>
                    </div>
                </div>

                <button @click="window.location.reload()" class="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center text-gray-400 hover:text-white hover:bg-white/10 transition">
                    <i class="fa-solid fa-rotate-right text-xs"></i>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto p-4 md:p-6 space-y-4 bg-[#f0f2f5]" id="msgArea" x-ref="msgContainer">

                @if($messages->count() > 0)
                    <div class="flex justify-center my-2">
                        <span class="bg-gray-200 text-gray-500 text-[10px] px-3 py-1 rounded-full font-bold uppercase tracking-wider shadow-sm">
                            Riwayat Percakapan
                        </span>
                    </div>
                @endif

                @forelse($messages as $m)
                    <div class="flex {{ $m->sender_id == auth()->id() ? 'justify-end' : 'justify-start' }} group">

                        @if($m->sender_id != auth()->id())
                            <div class="w-8 h-8 rounded-full bg-gray-300 flex items-center justify-center text-gray-500 text-xs mr-2 self-end mb-1">
                                <i class="fa-solid fa-user-shield"></i>
                            </div>
                        @endif

                        <div class="max-w-[80%] md:max-w-[70%]">
                            <div class="relative px-4 py-2.5 text-sm shadow-sm
                                {{ $m->sender_id == auth()->id()
                                    ? 'bg-[#222831] text-[#DFD0B8] rounded-2xl rounded-tr-none'
                                    : 'bg-white text-gray-800 rounded-2xl rounded-tl-none border border-gray-100' }}">

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

                                <div class="text-[10px] mt-1 text-right flex items-center justify-end gap-1
                                    {{ $m->sender_id == auth()->id() ? 'text-[#DFD0B8]/60' : 'text-gray-400' }}">
                                    {{ $m->created_at->format('H:i') }}
                                    @if($m->sender_id == auth()->id())
                                        <i class="fa-solid fa-check-double"></i>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="h-full flex flex-col items-center justify-center text-gray-400 opacity-60">
                        <i class="fa-regular fa-comments text-6xl mb-4"></i>
                        <p class="text-sm">Belum ada pesan. Sapa admin sekarang!</p>
                    </div>
                @endforelse
            </div>

            <div class="bg-white p-3 md:p-4 border-t border-gray-100 relative">

                <div x-show="filePreview" class="absolute bottom-full left-4 mb-2 p-2 bg-white rounded-xl shadow-lg border border-gray-100 flex items-center gap-3 w-fit"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     style="display: none;">
                    <div class="w-12 h-12 bg-gray-100 rounded-lg overflow-hidden border border-gray-200">
                        <img :src="filePreview" class="w-full h-full object-cover">
                    </div>
                    <div class="text-xs pr-2">
                        <p class="font-bold text-gray-700">Lampiran Gambar</p>
                        <button @click="clearFile" class="text-red-500 hover:text-red-700 font-bold mt-1">Batal</button>
                    </div>
                </div>

                <form id="userChatForm" @submit.prevent="sendMessage" class="flex items-end gap-2">
                    <button type="button" @click="$refs.fileInput.click()" class="p-3 text-gray-400 hover:text-[#222831] hover:bg-gray-50 rounded-full transition-colors">
                        <i class="fa-solid fa-paperclip text-lg"></i>
                    </button>
                    <input type="file" x-ref="fileInput" name="attachment" class="hidden" accept="image/*" @change="handleFileSelect">

                    <div class="flex-1 bg-gray-100 rounded-2xl px-4 py-3 border border-transparent focus-within:border-gray-300 focus-within:bg-white transition-all">
                        <input type="text" x-model="messageInput" name="content"
                               class="w-full bg-transparent border-none focus:ring-0 text-sm text-gray-800 placeholder-gray-400"
                               placeholder="Ketik pesan..." autocomplete="off">
                    </div>

                    <button type="submit"
                            class="p-3 bg-[#222831] text-[#DFD0B8] rounded-full shadow-md hover:bg-black hover:scale-105 hover:shadow-lg transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                            :disabled="!messageInput && !filePreview">
                        <i class="fa-solid fa-paper-plane text-lg translate-x-0.5 translate-y-0.5"></i>
                    </button>
                </form>
            </div>

        </div>
    </div>

    <div x-show="lightboxOpen" style="display: none;" class="fixed inset-0 z-[999] overflow-hidden" x-cloak>
        <div class="fixed inset-0 bg-black/90 backdrop-blur-sm transition-opacity" @click="lightboxOpen = false"></div>
        <div class="flex min-h-full items-center justify-center p-4 relative pointer-events-none">
            <div class="relative max-w-3xl w-full pointer-events-auto" x-show="lightboxOpen" x-transition.scale>
                <button @click="lightboxOpen = false" class="absolute -top-12 right-0 text-white hover:text-gray-300 text-2xl">
                    <i class="fa-solid fa-xmark"></i>
                </button>
                <img :src="lightboxSrc" class="w-full h-auto max-h-[80vh] object-contain rounded-lg shadow-2xl bg-black/50">
            </div>
        </div>
    </div>

</div>

<script>
    function userChatApp() {
        return {
            messageInput: '',
            filePreview: null,
            lightboxOpen: false,
            lightboxSrc: '',

            init() {
                this.scrollToBottom();
            },

            scrollToBottom() {
                const container = this.$refs.msgContainer;
                if (container) {
                    setTimeout(() => {
                        container.scrollTop = container.scrollHeight;
                    }, 100);
                }
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

                // Tambahkan recipient_id (Admin ID)
                // Pastikan variabel $adminId dikirim dari Controller
                fd.append('recipient_id', '{{ $adminId }}');

                // Reset UI segera (Optimistic UI)
                const tempMsg = this.messageInput;
                this.messageInput = '';
                this.clearFile();

                try {
                    const res = await fetch('{{ route("messages.store") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: fd
                    });

                    const data = await res.json();

                    if (data.success) {
                        window.location.reload();
                    } else {
                        alert('Gagal mengirim pesan.');
                        this.messageInput = tempMsg; // Kembalikan teks jika gagal
                    }
                } catch (error) {
                    console.error(error);
                    alert('Terjadi kesalahan koneksi.');
                    this.messageInput = tempMsg;
                }
            }
        }
    }
</script>
@endsection
