@extends('layouts.user')

@section('content')
<div class="chat-wrapper">
    <div class="chat-content">
        <div class="chat-header">
            <h1>Masukan Laporan Anda</h1>
        </div>

        <div class="chat-messages" id="messageContainer">
            @foreach($messages as $message)
                <div class="message {{ $message->user_id === auth()->id() ? 'sent' : 'received' }}">
                    <div class="message-content">
                        <div class="message-text">{{ $message->content }}</div>
                        @if($message->attachment)
                            <div class="message-attachment">
                                <a href="{{ asset('storage/' . $message->attachment) }}" target="_blank">
                                    <i data-feather="paperclip"></i> Attachment
                                </a>
                            </div>
                        @endif
                        <div class="message-time">
                            {{ $message->created_at->format('H:i') }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="chat-input">
            <form id="messageForm" class="message-form">
                @csrf
                <div class="input-group">
                    <button type="button" class="btn btn-attachment" onclick="document.getElementById('attachment').click()">
                        <i data-feather="paperclip"></i>
                    </button>
                    <input type="file" id="attachment" name="attachment" class="d-none">
                    <input type="text" class="form-control" id="messageInput" name="content" placeholder="Tulis pesan...">
                    <button type="submit" class="btn btn-send">
                        <i data-feather="send"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('css')
<style>
.chat-wrapper {
    width: 100%;
    height: calc(100vh - 100px);
    display: flex;
    background: #f6f8fa;
}
.chat-content {
    flex: 1;
    display: flex;
    flex-direction: column;
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.1);
    margin: 24px;
    overflow: hidden;
}
.chat-header {
    padding: 1rem 2rem;
    border-bottom: 1px solid #e5e7eb;
    background: #fff;
}
.chat-messages {
    flex: 1;
    overflow-y: auto;
    padding: 2rem;
    background: #f6f8fa;
}
.message {
    margin-bottom: 1rem;
    display: flex;
}
.message.sent {
    justify-content: flex-end;
}
.message-content {
    max-width: 60%;
    padding: 0.75rem 1rem;
    border-radius: 16px;
    position: relative;
}
.message.sent .message-content {
    background: #1a7f5a;
    color: white;
    border-bottom-right-radius: 4px;
}
.message.received .message-content {
    background: #f3f4f6;
    color: #1f2937;
    border-bottom-left-radius: 4px;
}
.message-time {
    font-size: 0.75rem;
    opacity: 0.7;
    margin-top: 0.25rem;
}
.chat-input {
    padding: 1rem 2rem;
    border-top: 1px solid #e5e7eb;
    background: #fff;
}
.message-form {
    display: flex;
}
.input-group {
    width: 100%;
    display: flex;
    gap: 0.5rem;
}
.btn-attachment, .btn-send {
    background: none;
    border: none;
    color: #1a7f5a;
    padding: 0.5rem;
}
.btn-attachment:hover, .btn-send:hover {
    color: #156c4a;
}
</style>
@endpush

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const messageForm = document.getElementById('messageForm');
    const messageInput = document.getElementById('messageInput');
    const messageContainer = document.getElementById('messageContainer');
    const attachment = document.getElementById('attachment');

    messageForm.addEventListener('submit', async function(e) {
        e.preventDefault();

        if (!messageInput.value.trim()) {
            return;
        }

        const submitBtn = this.querySelector('.btn-send');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i data-feather="loader"></i>';
        feather.replace();

        const formData = new FormData();
        formData.append('content', messageInput.value.trim());
        formData.append('recipient_id', '{{ $adminId }}');

        if (attachment.files[0]) {
            formData.append('attachment', attachment.files[0]);
        }

        try {
            const response = await fetch('{{ route("messages.store") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                messageInput.value = '';
                if (attachment) {
                    attachment.value = '';
                }
                appendMessage(data.message);
                messageContainer.scrollTop = messageContainer.scrollHeight;
            } else {
                alert(data.message);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mengirim pesan');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i data-feather="send"></i>';
            feather.replace();
        }
    });

    function appendMessage(message) {
        const div = document.createElement('div');
        const isCurrentUser = message.user_id === {{ Auth::id() }};
        div.className = `message ${isCurrentUser ? 'sent' : 'received'}`;

        const time = new Date(message.created_at).toLocaleTimeString('id-ID', {
            hour: '2-digit',
            minute: '2-digit'
        });

        div.innerHTML = `
            <div class="message-content">
                <div class="message-text">${message.content}</div>
                ${message.attachment ? `
                    <div class="message-attachment">
                        <a href="/storage/${message.attachment}" target="_blank">
                            <i data-feather="paperclip"></i> Attachment
                        </a>
                    </div>
                ` : ''}
                <div class="message-time">${time}</div>
            </div>
        `;
        messageContainer.appendChild(div);
        feather.replace();
    }

    // Auto-scroll to bottom on load
    messageContainer.scrollTop = messageContainer.scrollHeight;
});
</script>
@endpush
@endsection
