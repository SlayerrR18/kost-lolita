@extends('layouts.main')

@push('css')
<style>
:root{
  --brand:#1a7f5a; --brand2:#16c79a; --bg:#f8fafc; --ink:#0f172a; --muted:#64748b; --line:#e5e7eb; --card:#ffffff;
}
.chat{
  height:calc(100vh - 80px); background:var(--bg); padding:16px;
}
.shell{
  height:100%; display:grid; grid-template-columns: 320px 1fr; gap:16px;
}
.aside{
  background:var(--card); border-radius:16px; overflow:hidden; display:flex; flex-direction:column; box-shadow:0 6px 16px rgba(0,0,0,.06);
}
.head{
  padding:14px 16px; border-bottom:1px solid var(--line); display:flex; align-items:center; gap:8px; justify-content:space-between;
}
.search{
  display:flex; gap:8px; background:#f3f4f6; border-radius:10px; padding:6px 10px;
}
.search input{ border:none; background:transparent; outline:0; width:100%; font-size:.95rem; color:var(--ink); }
.list{ overflow-y:auto; padding:10px; }
.item{
  display:flex; gap:12px; padding:12px; border:1px solid transparent; border-radius:12px; align-items:center; background:#fff; cursor:pointer;
}
.item:hover{ border-color:#dbe5de; box-shadow:0 4px 12px rgba(0,0,0,.04); transform:translateY(-1px); }
.item.active{ background:linear-gradient(135deg,var(--brand),var(--brand2)); color:#fff; }
.avatar{ width:38px; height:38px; border-radius:50%; background:#e2e8f0; display:grid; place-items:center; font-weight:700; }
.meta{ flex:1; min-width:0; }
.meta h6{ margin:0; font-size:.95rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.meta p{ margin:0; color:var(--muted); font-size:.8rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.badge{ background:#ef4444; color:#fff; border-radius:10px; padding:2px 8px; font-size:.75rem; font-weight:700; }

.main{
  background:var(--card); border-radius:16px; overflow:hidden; display:flex; flex-direction:column; box-shadow:0 6px 16px rgba(0,0,0,.06);
}
.top{
  padding:14px 16px; border-bottom:1px solid var(--line); display:flex; justify-content:space-between; align-items:center;
}
.top .who{ display:flex; align-items:center; gap:10px; }
.top .who .name{ font-weight:700; }
.top .who .status{ font-size:.8rem; color:var(--muted); }

.stream{
  flex:1; overflow-y:auto; padding:20px; display:flex; flex-direction:column; gap:10px; background:linear-gradient(180deg,#fafafa, #fff);
}
.separator{
  align-self:center; font-size:.75rem; color:#6b7280; background:#eef2f7; border-radius:999px; padding:4px 10px; margin:10px 0;
}

.row{ display:flex; gap:10px; }
.row.me{ justify-content:flex-end; }
.bubble{
  max-width:min(680px,80%); padding:10px 14px; border-radius:16px; box-shadow:0 2px 8px rgba(0,0,0,.05); position:relative; font-size:.95rem;
}
.me .bubble{ background:linear-gradient(135deg,var(--brand),var(--brand2)); color:#fff; border-bottom-right-radius:6px; }
.you .bubble{ background:#f3f4f6; color:#0f172a; border-bottom-left-radius:6px; }
.time{ font-size:.72rem; opacity:.8; margin-top:4px; text-align:right; }
.attach a{ font-size:.85rem; display:inline-flex; gap:6px; align-items:center; margin-top:6px; }

.composer{
  padding:12px; border-top:1px solid var(--line); background:#fff;
}
.inputbar{
  background:#f3f4f6; border-radius:12px; padding:8px; display:flex; gap:8px; align-items:center;
}
.inputbar input{ border:none; background:transparent; outline:0; flex:1; font-size:1rem; }
.iconbtn{
  width:40px; height:40px; border-radius:10px; display:grid; place-items:center; background:transparent; border:none; color:var(--brand);
}
.iconbtn:hover{ background:#e8f5ef; }
.preview{ display:flex; gap:8px; flex-wrap:wrap; margin-top:8px; }
.preview .chip{
  background:#eef2f7; padding:6px 8px; border-radius:8px; font-size:.8rem; display:flex; gap:6px; align-items:center;
}
@media (max-width: 992px){
  .shell{ grid-template-columns: 1fr; }
  .aside{ order:2; }
  .main{ order:1; }
}
</style>
@endpush

@section('content')
<div class="chat">
  <div class="shell">
    {{-- Sidebar --}}
    <aside class="aside">
      <div class="head">
        <div class="search" style="flex:1">
          <i data-feather="search"></i>
          <input type="text" id="convSearch" placeholder="Cari penghuni...">
        </div>
        <button class="iconbtn" title="Chat baru" onclick="showNewChatModal()"><i data-feather="plus"></i></button>
      </div>
      <div class="list" id="convList">
        @foreach($conversations as $u)
          @php
            $active = (string)$selectedUserId === (string)$u->id;
            $initial = strtoupper(mb_substr($u->name,0,1));
            $snippet = $u->last_message?->content ?? '';
          @endphp
          <a class="item {{ $active?'active':'' }}" href="{{ route('messages.index',['user_id'=>$u->id]) }}">
            <div class="avatar">{{ $initial }}</div>
            <div class="meta">
              <h6>{{ $u->name }}</h6>
              <p>{{ $snippet }}</p>
            </div>
            @if($u->unread_count>0 && !$active)
              <span class="badge">{{ $u->unread_count }}</span>
            @endif
          </a>
        @endforeach
      </div>
    </aside>

    {{-- Main --}}
    <section class="main">
      @if($selectedUserId)
        <div class="top">
          <div class="who">
            <div class="avatar" style="width:34px;height:34px">{{ strtoupper(mb_substr($messages->first()?->user?->name ?? 'U',0,1)) }}</div>
            <div>
              <div class="name">{{ $messages->first()?->user?->name ?? 'Penghuni' }}</div>
              <div class="status">Percakapan pribadi</div>
            </div>
          </div>
          <div class="kiri" style="color:#6b7280">{{ $messages->last()?->created_at?->diffForHumans() }}</div>
        </div>

        <div id="messageContainer" class="stream">
          @php $lastDate = null; @endphp
          @foreach($messages as $m)
            @php
              $d = $m->created_at->toDateString();
            @endphp
            @if($lastDate !== $d)
              <div class="separator">{{ \Carbon\Carbon::parse($d)->translatedFormat('d F Y') }}</div>
              @php $lastDate = $d; @endphp
            @endif
            <div class="row {{ $m->user_id === auth()->id() ? 'me':'you' }}">
              <div class="bubble">
                <div class="text">{{ $m->content }}</div>
                @if($m->attachment)
                  <div class="attach">
                    <a href="{{ asset('storage/'.$m->attachment) }}" target="_blank"><i data-feather="paperclip"></i> Lampiran</a>
                  </div>
                @endif
                <div class="time">{{ $m->created_at->format('H:i') }}</div>
              </div>
            </div>
          @endforeach
        </div>

        <div class="composer">
          <form id="messageForm">
            @csrf
            <div class="inputbar">
              <button type="button" class="iconbtn" onclick="document.getElementById('attachment').click()"><i data-feather="paperclip"></i></button>
              <input type="file" id="attachment" name="attachment" class="d-none" accept="image/*,application/pdf">
              <input type="text" id="messageInput" placeholder="Tulis pesan..." autocomplete="off">
              <button class="iconbtn" id="sendBtn" title="Kirim"><i data-feather="send"></i></button>
            </div>
            <div class="preview" id="filePreview" hidden></div>
          </form>
        </div>
      @else
        <div class="top"><div class="who"><div class="name">Pilih percakapan di kiri</div></div></div>
        <div class="stream" style="justify-content:center;align-items:center;color:#94a3b8">
          <i data-feather="message-square" style="width:56px;height:56px;margin-bottom:8px"></i>
          <div>Belum ada percakapan yang dipilih</div>
        </div>
      @endif
    </section>
  </div>
</div>

{{-- Modal chat baru --}}
<div class="modal fade" id="newChatModal" tabindex="-1">
  <div class="modal-dialog"><div class="modal-content">
    <div class="modal-header"><h5 class="modal-title">Pesan Baru</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
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
  </div></div>
</div>
@endsection

@push('js')
<script>
(function(){
  feather.replace();

  const container = document.getElementById('messageContainer');
  if (container) container.scrollTop = container.scrollHeight;

  // search on sidebar
  const search = document.getElementById('convSearch');
  const list = document.getElementById('convList');
  if (search && list){
    search.addEventListener('input', ()=>{
      const q = search.value.toLowerCase();
      list.querySelectorAll('.item').forEach(el=>{
        const name = el.querySelector('.meta h6')?.textContent.toLowerCase() || '';
        const mail = el.querySelector('.meta p')?.textContent.toLowerCase() || '';
        el.style.display = (name.includes(q) || mail.includes(q)) ? '' : 'none';
      });
    });
  }

  // attachment preview chip
  const file = document.getElementById('attachment');
  const chipBox = document.getElementById('filePreview');
  if (file && chipBox){
    file.addEventListener('change', ()=>{
      chipBox.innerHTML = '';
      if (file.files[0]){
        chipBox.hidden = false;
        const chip = document.createElement('div');
        chip.className = 'chip';
        chip.innerHTML = `<i data-feather="paperclip"></i> ${file.files[0].name}
                          <a style="margin-left:6px;cursor:pointer" onclick="clearAttachment()">×</a>`;
        chipBox.appendChild(chip);
        feather.replace();
      }else{
        chipBox.hidden = true;
      }
    });
  }
  window.clearAttachment = function(){
    file.value = ''; chipBox.hidden = true; chipBox.innerHTML = '';
  }

  // send
  const form = document.getElementById('messageForm');
  const input = document.getElementById('messageInput');
  const btn = document.getElementById('sendBtn');
  if (form){
    form.addEventListener('submit', async (e)=>{
      e.preventDefault();
      if (!input.value.trim() && !(file && file.files[0])) return;

      btn.disabled = true;
      const fd = new FormData();
      fd.append('content', input.value.trim() || '(lampiran)');
      fd.append('recipient_id', '{{ $selectedUserId }}');
      if (file && file.files[0]) fd.append('attachment', file.files[0]);

      const res = await fetch('{{ route("messages.store") }}', {
        method:'POST',
        headers:{'X-CSRF-TOKEN': '{{ csrf_token() }}'},
        body: fd
      });
      const data = await res.json();

      if (data.success){
        input.value = ''; clearAttachment();
        appendMessage(data.message);
        container.scrollTop = container.scrollHeight;
      } else {
        alert(data.message || 'Gagal mengirim pesan');
      }
      btn.disabled = false;
    });
  }

  function appendMessage(m){
    const date = new Date(m.created_at);
    const row = document.createElement('div');
    row.className = `row ${m.user_id === {{ auth()->id() }} ? 'me':'you'}`;
    row.innerHTML = `
      <div class="bubble">
        <div class="text">${escapeHtml(m.content)}</div>
        ${m.attachment? `<div class="attach"><a href="/storage/${m.attachment}" target="_blank">
          <i data-feather='paperclip'></i> Lampiran</a></div>`:''}
        <div class="time">${date.toLocaleTimeString('id-ID',{hour:'2-digit',minute:'2-digit'})}</div>
      </div>`;
    container.appendChild(row);
    feather.replace();
  }

  // simple polling setiap 10s (bisa ganti ke Pusher kalau mau)
  @if($selectedUserId)
  setInterval(async ()=>{
    const res = await fetch(`{{ route('messages.index') }}?user_id={{ $selectedUserId }}`, {headers:{'X-Requested-With':'XMLHttpRequest'}});
    // kalau mau, implementasikan route khusus JSON untuk efisiensi.
  }, 10000);
  @endif

  function escapeHtml(s){ return s.replace(/[&<>"']/g, m=>({ '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;' }[m])); }

})();
function showNewChatModal(){ new bootstrap.Modal(document.getElementById('newChatModal')).show(); }
async function startNewChat(){
  const uid = document.getElementById('userSelect').value;
  const text = document.getElementById('newMessageContent').value.trim();
  if(!uid || !text){ alert('Pilih penghuni & isi pesan'); return; }
  const res = await fetch('{{ route("messages.store") }}', {
    method:'POST',
    headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
    body: JSON.stringify({ recipient_id: uid, content: text })
  });
  const data = await res.json();
  if (data.success){
    window.location = '{{ route("messages.index") }}?user_id='+uid;
  } else alert(data.message || 'Gagal');
}
</script>
@endpush
