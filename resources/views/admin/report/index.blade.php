@extends('layouts.main')

@section('content')
<div class="chat-container">
    <div class="chat-sidebar">
        <div class="chat-sidebar-header">
            <h4>Daftar Percakapan</h4>
        </div>
        <div class="chat-conversations">
            @foreach($conversations as $conversation)
                <a href="{{ route('messages.index', ['user_id' => $conversation->id]) }}"
                   class="chat-conversation-item {{ $selectedUserId == $conversation->id ? 'active' : '' }}">
                    <div class="conversation-info">
                        <h6>{{ $conversation->name }}</h6>
                        <small>{{ $conversation->email }}</small>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    <div class="chat-content">
        @if($selectedUserId)
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
                    <input type="hidden" name="user_id" value="{{ $selectedUserId }}">
                    <div class="input-group">
                        <button type="button" class="btn btn-attachment" onclick="document.getElementById('attachment').click()">
                            <i data-feather="paperclip"></i>
                        </button>
                        <input type="file" id="attachment" name="attachment" class="d-none">
                        <input type="text" class="form-control" id="messageInput" placeholder="Tulis pesan...">
                        <button type="submit" class="btn btn-send">
                            <i data-feather="send"></i>
                        </button>
                    </div>
                </form>
            </div>
        @else
            <div class="chat-placeholder">
                <div class="text-center text-muted">
                    <i data-feather="message-square" class="mb-3" style="width: 48px; height: 48px;"></i>
                    <h5>Pilih percakapan untuk memulai chat</h5>
                </div>
            </div>
        @endif
    </div>
</div>

@push('css')
<style>
.chat-container {
    display: flex;
    height: calc(100vh - 100px);
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 2px 12px rgba(0,0,0,0.1);
}

.chat-sidebar {
    width: 300px;
    border-right: 1px solid #e5e7eb;
    display: flex;
    flex-direction: column;
}

.chat-sidebar-header {
    padding: 1rem;
    border-bottom: 1px solid #e5e7eb;
}

.chat-conversations {
    overflow-y: auto;
    flex: 1;
}

.chat-conversation-item {
    display: block;
    padding: 1rem;
    border-bottom: 1px solid #e5e7eb;
    color: inherit;
    text-decoration: none;
    transition: all 0.2s;
}

.chat-conversation-item:hover {
    background: #f9fafb;
}

.chat-conversation-item.active {
    background: #f3f4f6;
}

.chat-content {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.chat-placeholder {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
}

.chat-messages {
    flex: 1;
    overflow-y: auto;
    padding: 1rem;
}

.message {
    margin-bottom: 1rem;
    display: flex;
}

.message.sent {
    justify-content: flex-end;
}

.message-content {
    max-width: 70%;
    padding: 0.75rem 1rem;
    border-radius: 16px;
    position: relative;
}

.message.sent .message-content {
    background: #1a7f5a;
    color: white;
}

.message.received .message-content {
    background: #f3f4f6;
    color: #1f2937;
}

.message-time {
    font-size: 0.75rem;
    opacity: 0.7;
    margin-top: 0.25rem;
}

.chat-input {
    padding: 1rem;
    border-top: 1px solid #e5e7eb;
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

        const formData = new FormData();
        formData.append('content', messageInput.value);
        // Use selectedUserId instead of userId
        formData.append('recipient_id', '{{ $selectedUserId }}');

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
                attachment.value = '';
                appendMessage(data.message);
                messageContainer.scrollTop = messageContainer.scrollHeight;
            }
        } catch (error) {
            console.error('Error:', error);
        }
    });

    function appendMessage(message) {
        const div = document.createElement('div');
        div.className = `message ${message.user_id === {{ Auth::id() }} ? 'sent' : 'received'}`;
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
                <div class="message-time">${new Date(message.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</div>
            </div>
        `;
        messageContainer.appendChild(div);
        feather.replace();
    }

    // Auto-scroll to bottom on load
    if (messageContainer) {
        messageContainer.scrollTop = messageContainer.scrollHeight;
    }
});
</script>
@endpush
@endsection
