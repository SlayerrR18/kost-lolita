@extends('layouts.main')

@push('css')
<style>
    /* === Palet & Umum (diselaraskan dengan desain sebelumnya) === */
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
        min-height: calc(100vh - 80px); /* 80px for layout margin */
        padding: 2rem;
        background: var(--bg);
    }
    .chat-shell{
        height: 100%;
        display: grid;
        grid-template-columns: 320px 1fr;
        gap: 1rem;
    }

    .chat-sidebar{
        background:var(--card);
        border-radius:var(--radius-lg);
        overflow:hidden;
        display:flex;
        flex-direction:column;
        box-shadow:var(--shadow-md);
    }
    .chat-main{
        background:var(--card);
        border-radius:var(--radius-lg);
        overflow:hidden;
        display:flex;
        flex-direction:column;
        box-shadow:var(--shadow-md);
    }

    /* === Chat Sidebar (List Percakapan) === */
    .sidebar-header{
        padding:1rem;
        border-bottom:1px solid var(--line);
        display:flex;
        align-items:center;
        gap:8px;
        justify-content:space-between;
    }
    .search-input-group{
        display:flex;
        gap:8px;
        background:var(--bg);
        border-radius:var(--radius-md);
        padding:6px 12px;
        flex:1;
        align-items:center;
    }
    .search-input-group input{
        border:none;
        background:transparent;
        outline:0;
        width:100%;
        font-size:.95rem;
        color:var(--ink);
    }
    .conversations-list{
        overflow-y:auto;
        padding:8px;
        flex:1;
    }
    .conversation-item{
        display:flex;
        gap:12px;
        padding:12px;
        border:1px solid transparent;
        border-radius:var(--radius-md);
        align-items:center;
        background:transparent;
        color:var(--ink);
        text-decoration:none;
        transition:all .2s ease;
        margin-bottom:4px;
    }
    .conversation-item:hover{
        background:var(--bg);
        transform:translateY(-1px);
    }
    .conversation-item.active{
        background:var(--primary);
        color:#fff;
    }
    .conversation-item.active:hover{
        background:var(--primary-2);
        color:#fff;
    }
    .avatar{
        width:38px;
        height:38px;
        border-radius:50%;
        background:#e2e8f0;
        display:grid;
        place-items:center;
        font-weight:700;
        flex-shrink:0;
    }
    .meta{
        flex:1;
        min-width:0;
    }
    .meta h6{
        margin:0;
        font-size:.95rem;
        white-space:nowrap;
        overflow:hidden;
        text-overflow:ellipsis;
    }
    .meta p{
        margin:0;
        color:var(--muted);
        font-size:.8rem;
        white-space:nowrap;
        overflow:hidden;
        text-overflow:ellipsis;
    }
    .conversation-item.active .meta p{
        color:rgba(255,255,255,.8);
    }
    .unread-badge{
        background:var(--danger);
        color:#fff;
        border-radius:10px;
        padding:2px 8px;
        font-size:.75rem;
        font-weight:700;
        flex-shrink:0;
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
        max-width:min(680px,80%);
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
        .chat-sidebar{
            order:2;
            height: 300px; /* Lebar untuk mobile */
        }
        .chat-main{
            order:1;
        }
    }
</style>
@endpush

@section('content')
<div class="chat-container">
    <div class="chat-shell">
        {{-- Sidebar Percakapan --}}
        <aside class="chat-sidebar">
            <div class="sidebar-header">
                <div class="search-input-group">
                    <i data-feather="search"></i>
                    <input type="text" id="convSearch" placeholder="Cari penghuni...">
                </div>
                <button class="icon-button" title="Pesan baru" onclick="showNewChatModal()">
                    <i data-feather="plus"></i>
                </button>
            </div>
            <div class="conversations-list" id="convList">
                @foreach($conversations as $u)
                    @php
                        $active = (string)$selectedUserId === (string)$u->id;
                        $initial = strtoupper(mb_substr($u->name,0,1));
                        $snippet = $u->last_message?->content ?? '';
                        $isAttachment = $u->last_message?->attachment;
                    @endphp
                    <a class="conversation-item {{ $active?'active':'' }}" href="{{ route('messages.index',['user_id'=>$u->id]) }}">
                        <div class="avatar">{{ $initial }}</div>
                        <div class="meta">
                            <h6>{{ $u->name }}</h6>
                            <p>
                                @if($isAttachment)<i data-feather="paperclip" class="me-1"></i>@endif
                                {{ Str::limit($snippet, 30) }}
                            </p>
                        </div>
                        @if($u->unread_count > 0 && !$active)
                            <span class="unread-badge">{{ $u->unread_count }}</span>
                        @endif
                    </a>
                @endforeach
            </div>
        </aside>

        {{-- Main Chat Area --}}
        <section class="chat-main">
            @if($selectedUserId)
                <div class="main-header">
                    <div class="who">
                        <div class="avatar" style="width:34px;height:34px">{{ strtoupper(mb_substr($messages->first()?->user?->name ?? 'U',0,1)) }}</div>
                        <div>
                            <div class="name">{{ $messages->first()?->user?->name ?? 'Penghuni' }}</div>
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
                        <div class="message-row {{ $m->user_id === auth()->id() ? 'me':'you' }}">
                            <div class="message-bubble">
                                <div class="text">{{ $m->content }}</div>
                                @if($m->attachment)
                                    <div class="message-attachment">
                                        <a href="{{ asset('storage/'.$m->attachment) }}" target="_blank">
                                            <img src="{{ asset('storage/'.$m->attachment) }}" alt="Lampiran" class="img-fluid">
                                        </a>
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
            @else
                <div class="main-header"><div class="who"><div class="name">Pilih percakapan di kiri</div></div></div>
                <div class="message-stream" style="justify-content:center;align-items:center;color:#94a3b8">
                    <i data-feather="message-square" style="width:56px;height:56px;margin-bottom:8px"></i>
                    <div>Belum ada percakapan yang dipilih</div>
                </div>
            @endif
        </section>
    </div>
</div>

{{-- Modal chat baru --}}
<div class="modal fade" id="newChatModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pesan Baru</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Pilih Penghuni</label>
                    <select class="form-select" id="userSelect">
                        <option value="">Pilih...</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}">{{ $u->name }} — {{ $u->email }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Pesan</label>
                    <textarea class="form-control" id="newMessageContent" rows="3" placeholder="Halo..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-success" onclick="startNewChat()">Kirim</button>
            </div>
        </div>
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
            fd.append('recipient_id', '{{ $selectedUserId }}');

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
    @if(isset($selectedUserId) && $selectedUserId)
    let lastMessageId = {{ $messages->last()?->id ?? 0 }};
    setInterval(async () => {
        try {
            const res = await fetch(`{{ route('messages.index') }}?user_id={{ $selectedUserId }}&since_id=${lastMessageId}`, {
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

    window.showNewChatModal = function(){
        const modal = new bootstrap.Modal(document.getElementById('newChatModal'));
        modal.show();
        feather.replace();
    }

    window.startNewChat = async function(){
        const uid = document.getElementById('userSelect').value;
        const text = document.getElementById('newMessageContent').value.trim();
        if(!uid || !text){ alert('Pilih penghuni & isi pesan'); return; }

        const formModal = document.getElementById('newChatModal').querySelector('form');
        const fd = new FormData();
        fd.append('content', text);
        fd.append('recipient_id', uid);
        fd.append('_token', '{{ csrf_token() }}');

        const res = await fetch('{{ route("messages.store") }}', {
            method:'POST',
            body: fd
        });
        const data = await res.json();

        if (data.success) {
            window.location = '{{ route("messages.index") }}?user_id='+uid;
        } else {
            alert(data.message || 'Gagal mengirim pesan');
        }
    }

    // Polling untuk unread count di sidebar admin
    @if(Auth::user()->role === 'admin')
    setInterval(async () => {
        try {
            const res = await fetch('{{ route('messages.index') }}', { headers: {'X-Requested-With':'XMLHttpRequest', 'Accept':'application/json'} });
            const data = await res.json();
            const convs = data.conversations;

            convs.forEach(conv => {
                const item = document.querySelector(`.conversation-item[href*="user_id=${conv.id}"]`);
                if (item) {
                    const badge = item.querySelector('.unread-badge');
                    if (conv.unread_count > 0) {
                        if (badge) {
                            badge.textContent = conv.unread_count;
                        } else {
                            item.innerHTML += `<span class="unread-badge">${conv.unread_count}</span>`;
                        }
                    } else if (badge) {
                        badge.remove();
                    }
                }
            });
        } catch(e) {
            console.error('Failed to update unread count:', e);
        }
    }, 15000); // Every 15 seconds
    @endif

})();
</script>
@endpush
