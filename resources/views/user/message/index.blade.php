@extends('layouts.user')

@push('css')
<style>
:root{ --brand:#1a7f5a; --brand2:#16c79a; --bg:#f8fafc; --ink:#0f172a; --muted:#64748b; --line:#e5e7eb; }
.chat-user{ min-height:calc(100vh - 80px); background:var(--bg); padding:16px; }
.wrap{ height:100%; background:#fff; border-radius:16px; overflow:hidden; box-shadow:0 6px 16px rgba(0,0,0,.06); display:flex; flex-direction:column; }
.top{ padding:14px 16px; border-bottom:1px solid var(--line); display:flex; justify-content:space-between; align-items:center;}
.stream{ flex:1; overflow-y:auto; padding:20px; display:flex; flex-direction:column; gap:10px; background:linear-gradient(180deg,#fafafa, #fff); }
.separator{ align-self:center; font-size:.75rem; color:#6b7280; background:#eef2f7; border-radius:999px; padding:4px 10px; margin:10px 0; }
.row{ display:flex; gap:10px; }
.row.me{ justify-content:flex-end; }
.bubble{ max-width:min(640px,80%); padding:10px 14px; border-radius:16px; box-shadow:0 2px 8px rgba(0,0,0,.05); }
.me .bubble{ background:linear-gradient(135deg,var(--brand),var(--brand2)); color:#fff; border-bottom-right-radius:6px; }
.you .bubble{ background:#f3f4f6; color:#0f172a; border-bottom-left-radius:6px; }
.time{ font-size:.72rem; opacity:.8; margin-top:4px; text-align:right; }
.composer{ padding:12px; border-top:1px solid var(--line); background:#fff; }
.inputbar{ background:#f3f4f6; border-radius:12px; padding:8px; display:flex; gap:8px; align-items:center; }
.inputbar input{ border:none; background:transparent; outline:0; flex:1; font-size:1rem; }
.iconbtn{ width:40px; height:40px; border-radius:10px; display:grid; place-items:center; background:transparent; border:none; color:var(--brand); }
.iconbtn:hover{ background:#e8f5ef; }
.preview{ display:flex; gap:8px; margin-top:8px; flex-wrap:wrap; }
.preview .chip{ background:#eef2f7; padding:6px 8px; border-radius:8px; font-size:.8rem; display:flex; gap:6px; align-items:center; }
</style>
@endpush

@section('content')
<div class="chat-user">
  <div class="wrap">
    <div class="top"><div class="left"><strong>Pesan</strong></div></div>

    <div class="stream" id="messageContainer">
      @php $lastDate = null; @endphp
      @foreach($messages as $m)
        @php $d = $m->created_at->toDateString(); @endphp
        @if($lastDate !== $d)
          <div class="separator">{{ \Carbon\Carbon::parse($d)->translatedFormat('d F Y') }}</div>
          @php $lastDate = $d; @endphp
        @endif
        <div class="row {{ $m->user_id === auth()->id() ? 'me' : 'you' }}">
          <div class="bubble">
            <div class="text">{{ $m->content }}</div>
            @if($m->attachment)
              <div class="attach"><a href="{{ asset('storage/'.$m->attachment) }}" target="_blank"><i data-feather="paperclip"></i> Lampiran</a></div>
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
          <button class="iconbtn" id="sendBtn"><i data-feather="send"></i></button>
        </div>
        <div class="preview" id="filePreview" hidden></div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('js')
<script>
(function(){
  feather.replace();
  const container = document.getElementById('messageContainer');
  container.scrollTop = container.scrollHeight;

  const form = document.getElementById('messageForm');
  const input = document.getElementById('messageInput');
  const file = document.getElementById('attachment');
  const chipBox = document.getElementById('filePreview');
  const btn = document.getElementById('sendBtn');

  file.addEventListener('change', ()=>{
    chipBox.innerHTML='';
    if (file.files[0]){ chipBox.hidden=false;
      chipBox.innerHTML=`<div class="chip"><i data-feather="paperclip"></i> ${file.files[0].name}
      <a style="margin-left:6px;cursor:pointer" onclick="document.getElementById('attachment').value='';document.getElementById('filePreview').hidden=true;document.getElementById('filePreview').innerHTML=''">×</a></div>`;
      feather.replace();
    } else { chipBox.hidden=true; }
  });

  form.addEventListener('submit', async (e)=>{
    e.preventDefault();
    if (!input.value.trim() && !(file && file.files[0])) return;

    btn.disabled = true;
    const fd = new FormData();
    fd.append('content', input.value.trim() || '(lampiran)');
    fd.append('recipient_id', '{{ $adminId }}');
    if (file && file.files[0]) fd.append('attachment', file.files[0]);

    const res = await fetch('{{ route("messages.store") }}', {
      method:'POST', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}, body: fd
    });
    const data = await res.json();
    if (data.success){ input.value=''; file.value=''; chipBox.hidden=true; chipBox.innerHTML=''; appendMessage(data.message); container.scrollTop = container.scrollHeight; }
    else alert(data.message || 'Gagal mengirim pesan');
    btn.disabled = false;
  });

  function appendMessage(m){
    const date = new Date(m.created_at);
    const row = document.createElement('div');
    row.className = `row ${m.user_id === {{ auth()->id() }} ? 'me':'you'}`;
    row.innerHTML = `
      <div class="bubble">
        <div class="text">${escapeHtml(m.content)}</div>
        ${m.attachment? `<div class="attach"><a href="/storage/${m.attachment}" target="_blank"><i data-feather='paperclip'></i> Lampiran</a></div>`:''}
        <div class="time">${date.toLocaleTimeString('id-ID',{hour:'2-digit',minute:'2-digit'})}</div>
      </div>`;
    container.appendChild(row);
    feather.replace();
  }
  function escapeHtml(s){ return s.replace(/[&<>"']/g,m=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m])); }
})();
</script>
@endpush
