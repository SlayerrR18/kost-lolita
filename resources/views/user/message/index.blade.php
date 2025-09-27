@extends('layouts.user')

@push('css')
<style>
    :root{
        --primary:#1a7f5a; --primary-2:#16c79a; --bg:#f8fafc; --ink:#0f172a; --muted:#64748b; --line:#e2e8f0; --card:#ffffff;
        --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.05);
        --shadow-lg: 0 8px 24px rgba(0, 0, 0, 0.1);
        --radius-sm: 8px;
        --radius-md: 12px;
        --radius-lg: 24px;
        --danger: #dc2626;
        --success: #16a34a;
    }
    body { font-family: 'Poppins', sans-serif; }

    /* === Chat Layout === */
    .chat-container{
        min-height: calc(100vh - 80px);
        background:var(--bg);
        padding:2rem;
    }
    .chat-shell{
        height:100%;
        display:grid;
        grid-template-columns: 1fr;
        gap: 1rem;
    }

    .chat-main{
        background:var(--card);
        border-radius:var(--radius-lg);
        overflow:hidden;
        display:flex;
        flex-direction:column;
        box-shadow:var(--shadow-md);
    }

    /* === Chat Main (Stream) === */
    .main-header{
        padding:1rem;
        border-bottom:1px solid var(--line);
        display:flex;
        justify-content:space-between;
        align-items:center;
    }
    .main-header .who{
        display:flex;
        align-items:center;
        gap:10px;
    }
    .main-header .who .name{
        font-weight:700;
    }
    .main-header .who .status{
        font-size:.8rem;
        color:var(--muted);
    }

    .message-stream{
        flex:1;
        overflow-y:auto;
        padding:20px;
        display:flex;
        flex-direction:column;
        gap:10px;
        background:linear-gradient(180deg,#fafafa, #fff);
    }
    .message-separator{
        align-self:center;
        font-size:.75rem;
        color:#6b7280;
        background:#eef2f7;
        border-radius:999px;
        padding:4px 10px;
        margin:10px 0;
    }

    .message-row{
        display:flex;
        gap:10px;
    }
    .message-row.me{
        justify-content:flex-end;
    }
    .message-bubble{
        max-width:min(640px,80%);
        padding:10px 14px;
        border-radius:var(--radius-md);
        box-shadow:0 2px 8px rgba(0,0,0,.05);
        position:relative;
        font-size:.95rem;
    }
    .message-row.me .message-bubble{
        background:linear-gradient(135deg,var(--primary),var(--primary-2));
        color:#fff;
        border-bottom-right-radius:6px;
    }
    .message-row.you .message-bubble{
        background:var(--bg);
        color:var(--ink);
        border-bottom-left-radius:6px;
    }
    .message-time{
        font-size:.72rem;
        opacity:.8;
        margin-top:4px;
        text-align:right;
    }
    .message-attachment a{
        font-size:.85rem;
        display:inline-flex;
        gap:6px;
        align-items:center;
        margin-top:6px;
    }
    .message-attachment img {
        max-width: 200px;
        height: auto;
        border-radius: var(--radius-sm);
        margin-top: 8px;
        display: block;
    }

    /* === Composer === */
    .chat-composer{
        padding:12px;
        border-top:1px solid var(--line);
        background:var(--card);
    }
    .composer-inputbar{
        background:var(--bg);
        border-radius:var(--radius-md);
        padding:8px;
        display:flex;
        gap:8px;
        align-items:center;
    }
    .composer-inputbar input{
        border:none;
        background:transparent;
        outline:0;
        flex:1;
        font-size:1rem;
    }
    .icon-button{
        width:40px;
        height:40px;
        border-radius:10px;
        display:grid;
        place-items:center;
        background:transparent;
        border:none;
        color:var(--primary);
    }
    .icon-button:hover{
        background:#e8f5ef;
    }
    .attachment-preview{
        display:flex;
        gap:8px;
        flex-wrap:wrap;
        margin-top:8px;
        padding:4px;
    }
    .attachment-preview .chip{
        background:#eef2f7;
        padding:6px 8px;
        border-radius:8px;
        font-size:.8rem;
        display:flex;
        gap:6px;
        align-items:center;
    }

    /* === Responsive === */
    @media (max-width: 992px){
        .chat-shell{
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="chat-container">
    <div class="chat-shell">
        {{-- Main Chat Area --}}
        <section class="chat-main">
            <div class="main-header">
                <div class="who">
                    <i data-feather="user" style="width:34px;height:34px; color:var(--primary)"></i>
                    <div>
                        <div class="name">Admin Kost Lolita</div>
                        <div class="status">Percakapan pribadi</div>
                    </div>
                </div>
            </div>

            <div id="messageContainer" class="message-stream">
                @php $lastDate = null; @endphp
                @foreach($messages as $m)
                    @php $d = $m->created_at->toDateString(); @endphp
                    @if($lastDate !== $d)
                        <div class="message-separator">{{ \Carbon\Carbon::parse($d)->translatedFormat('d F Y') }}</div>
                        @php $lastDate = $d; @endphp
                    @endif
                    <div class="message-row {{ $m->user_id === auth()->id() ? 'me' : 'you' }}">
                        <div class="message-bubble">
                            <div class="text">{{ $m->content }}</div>
                            @if($m->attachment)
                                <div class="message-attachment">
                                    @php
                                        $fileExtension = pathinfo($m->attachment, PATHINFO_EXTENSION);
                                        $isImage = in_array(strtolower($fileExtension), ['jpg', 'jpeg', 'png', 'gif']);
                                    @endphp
                                    @if($isImage)
                                        <a href="{{ asset('storage/'.$m->attachment) }}" target="_blank">
                                            <img src="{{ asset('storage/'.$m->attachment) }}" alt="Lampiran" class="img-fluid">
                                        </a>
                                    @else
                                        <a href="{{ asset('storage/'.$m->attachment) }}" target="_blank">
                                            <i data-feather="paperclip"></i>
                                            Lampiran
                                        </a>
                                    @endif
                                </div>
                            @endif
                            <div class="message-time">{{ $m->created_at->format('H:i') }}</div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="chat-composer">
                <form id="messageForm">
                    @csrf
                    <div class="composer-inputbar">
                        <button type="button" class="icon-button" onclick="document.getElementById('attachment').click()">
                            <i data-feather="paperclip"></i>
                        </button>
                        <input type="file" id="attachment" name="attachment" class="d-none" accept="image/*,application/pdf">
                        <input type="text" id="messageInput" name="content" placeholder="Tulis pesan..." autocomplete="off">
                        <button class="icon-button" id="sendBtn" title="Kirim">
                            <i data-feather="send"></i>
                        </button>
                    </div>
                    <div class="attachment-preview" id="filePreview" hidden></div>
                </form>
            </div>
        </section>
    </div>
</div>
@endsection

@push('js')
<script>
(function(){
    // Initial setup
    const container = document.getElementById('messageContainer');
    if (container) container.scrollTop = container.scrollHeight;

    // Form and input elements
    const form = document.getElementById('messageForm');
    const input = document.getElementById('messageInput');
    const file = document.getElementById('attachment');
    const chipBox = document.getElementById('filePreview');
    const btn = document.getElementById('sendBtn');

    // File attachment logic
    if (file && chipBox) {
        file.addEventListener('change', () => {
            chipBox.innerHTML = '';
            if (file.files[0]) {
                chipBox.hidden = false;
                const fileType = file.files[0].type;
                const isImage = fileType.startsWith('image/');
                const fileName = file.files[0].name;

                let icon = isImage ? 'image' : 'file';

                chipBox.innerHTML = `<div class="chip"><i data-feather="${icon}"></i> ${fileName} <a style="margin-left:6px;cursor:pointer" onclick="clearAttachment()">×</a></div>`;
                feather.replace();
            } else {
                chipBox.hidden = true;
            }
        });
    }

    window.clearAttachment = function(){
        if (file) file.value = '';
        if (chipBox) {
            chipBox.hidden = true;
            chipBox.innerHTML = '';
        }
        feather.replace();
    }

    // Send message logic
    if (form && btn) {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            if (!input.value.trim() && !(file && file.files[0])) return;

            btn.disabled = true;
            const fd = new FormData(form);
            fd.append('content', input.value.trim() || '(lampiran)');
            fd.append('recipient_id', '{{ $adminId }}');

            const res = await fetch('{{ route("messages.store") }}', {
                method:'POST',
                headers:{'X-Requested-With':'XMLHttpRequest', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                body: fd
            });

            const data = await res.json();

            if (data.success) {
                input.value = '';
                clearAttachment();
                appendMessage(data.message);
                if (container) container.scrollTop = container.scrollHeight;
            } else {
                alert(data.message || 'Gagal mengirim pesan');
            }
            btn.disabled = false;
        });
    }

    function appendMessage(m){
        const date = new Date(m.created_at);
        const isMe = m.user_id === {{ auth()->id() }};
        const row = document.createElement('div');
        row.className = `message-row ${isMe ? 'me':'you'}`;

        let attachmentHtml = '';
        if (m.attachment) {
            const isImage = m.attachment.match(/\.(jpeg|jpg|png|gif)$/i);
            const attachmentUrl = `/storage/${m.attachment}`;
            if (isImage) {
                attachmentHtml = `<div class="message-attachment"><a href="${attachmentUrl}" target="_blank"><img src="${attachmentUrl}" alt="Lampiran"></a></div>`;
            } else {
                attachmentHtml = `<div class="message-attachment"><a href="${attachmentUrl}" target="_blank"><i data-feather="paperclip"></i> Lampiran</a></div>`;
            }
        }

        row.innerHTML = `
            <div class="message-bubble">
                <div class="text">${escapeHtml(m.content)}</div>
                ${attachmentHtml}
                <div class="message-time">${date.toLocaleTimeString('id-ID',{hour:'2-digit',minute:'2-digit'})}</div>
            </div>`;

        if (container) container.appendChild(row);
        feather.replace();
    }

    function escapeHtml(s){
        return s.replace(/[&<>"']/g, m => ({ '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;' }[m]));
    }

    // Polling logic (if needed)
    @if(isset($adminId) && $adminId)
    let lastMessageId = {{ $messages->last()?->id ?? 0 }};
    setInterval(async () => {
        try {
            const res = await fetch(`{{ route('messages.index') }}?user_id={{ $adminId }}&since_id=${lastMessageId}`, {
                headers: {'X-Requested-With':'XMLHttpRequest', 'Accept':'application/json'}
            });
            const newMessages = await res.json();
            if (newMessages.length > 0) {
                newMessages.forEach(m => appendMessage(m));
                lastMessageId = newMessages[newMessages.length - 1].id;
                if (container) container.scrollTop = container.scrollHeight;
            }
        } catch(e) {
            console.error('Polling failed:', e);
        }
    }, 5000); // Poll every 5 seconds
    @endif
})();
</script>
@endpush
