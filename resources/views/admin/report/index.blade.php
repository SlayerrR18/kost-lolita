@extends('layouts.main')

@push('css')
<style>
/* Chat Variables */
:root {
    --primary: #1a7f5a;
    --primary-light: #16c79a;
    --gray-100: #f3f4f6;
    --gray-200: #e5e7eb;
    --gray-300: #d1d5db;
    --gray-600: #4b5563;
    --white: #ffffff;
}

/* Enhanced Chat Container */
.chat-container {
    display: flex;
    height: calc(100vh - 80px);
    background: var(--white);
    border-radius: 24px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    margin: 1rem;
}

/* Improved Sidebar */
.chat-sidebar {
    width: 320px;
    border-right: 1px solid var(--gray-200);
    display: flex;
    flex-direction: column;
    background: var(--gray-100);
}

.chat-sidebar-header {
    padding: 1.5rem;
    border-bottom: 1px solid var(--gray-200);
    background: var(--white);
}

.chat-sidebar-header h4 {
    color: var(--primary);
    font-size: 1.25rem;
    font-weight: 600;
    margin: 0;
}

.chat-conversations {
    overflow-y: auto;
    flex: 1;
    padding: 0.5rem;
}

/* Enhanced Conversation Items */
.chat-conversation-item {
    display: flex;
    align-items: center;
    padding: 1rem;
    margin-bottom: 0.5rem;
    border-radius: 12px;
    background: var(--white);
    transition: all 0.2s ease;
    border: 1px solid transparent;
}

.chat-conversation-item:hover {
    background: var(--white);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    border-color: var(--primary-light);
}

.chat-conversation-item.active {
    background: var(--primary);
}

.conversation-info {
    margin-left: 1rem;
}

.chat-conversation-item.active h6,
.chat-conversation-item.active small {
    color: var(--white);
}

.conversation-info h6 {
    margin: 0;
    font-weight: 600;
    color: var(--gray-600);
}

.conversation-info small {
    color: var(--gray-600);
    font-size: 0.85rem;
}

/* Improved Messages Area */
.chat-content {
    flex: 1;
    display: flex;
    flex-direction: column;
    background: var(--white);
}

.chat-messages {
    flex: 1;
    overflow-y: auto;
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

/* Enhanced Message Bubbles */
.message {
    display: flex;
    align-items: flex-end;
    gap: 1rem;
    max-width: 80%;
}

.message.sent {
    margin-left: auto;
}

.message-content {
    padding: 1rem 1.25rem;
    border-radius: 18px;
    position: relative;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.message.sent .message-content {
    background: linear-gradient(135deg, var(--primary), var(--primary-light));
    color: var(--white);
    border-bottom-right-radius: 4px;
}

.message.received .message-content {
    background: var(--gray-100);
    color: var(--gray-600);
    border-bottom-left-radius: 4px;
}

/* Enhanced Input Area */
.chat-input {
    padding: 1.25rem;
    background: var(--white);
    border-top: 1px solid var(--gray-200);
}

.message-form .input-group {
    background: var(--gray-100);
    border-radius: 12px;
    padding: 0.5rem;
    gap: 0.75rem;
}

.message-form input[type="text"] {
    border: none;
    background: transparent;
    padding: 0.5rem;
    font-size: 1rem;
    color: var(--gray-600);
}

.message-form input[type="text"]:focus {
    outline: none;
}

.btn-attachment, .btn-send {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    transition: all 0.2s ease;
}

.btn-attachment:hover, .btn-send:hover {
    background: var(--primary-light);
    color: var(--white);
}

/* New styles for buttons and modal */
.btn-new-chat {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--primary);
    color: var(--white);
    border: none;
    transition: all 0.2s ease;
}

.btn-new-chat:hover {
    background: var(--primary-light);
    transform: translateY(-2px);
}

.modal-content {
    border-radius: 16px;
    border: none;
}

.modal-header {
    background: var(--primary);
    color: var(--white);
    border-radius: 16px 16px 0 0;
}

.btn-close {
    filter: brightness(0) invert(1);
}

.form-select, .form-control {
    border-radius: 8px;
    border: 1px solid var(--gray-200);
    padding: 0.75rem;
}

.form-select:focus, .form-control:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 2px rgba(26, 127, 90, 0.1);
}

/* Responsive Design */
@media (max-width: 768px) {
    .chat-container {
        margin: 0;
        border-radius: 0;
        height: 100vh;
    }

    .chat-sidebar {
        width: 280px;
    }

    .message {
        max-width: 90%;
    }
}

@media (max-width: 576px) {
    .chat-sidebar {
        position: fixed;
        left: -100%;
        top: 0;
        bottom: 0;
        z-index: 1000;
        transition: all 0.3s ease;
    }

    .chat-sidebar.active {
        left: 0;
    }
}
</style>
@endpush

@section('content')
<div class="chat-container">
    <!-- Existing sidebar code with added user avatar -->
    <div class="chat-sidebar">
        <div class="chat-sidebar-header">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="d-flex align-items-center gap-2">
                    <img src="{{ asset('img/logo.png') }}" alt="Logo" width="32" height="32" class="rounded-circle">
                    Percakapan
                </h4>
                <button type="button" class="btn btn-new-chat" onclick="showNewChatModal()">
                    <i data-feather="plus"></i>
                </button>
            </div>
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

    <!-- Enhanced chat content -->
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

            <!-- Enhanced input form -->
            <div class="chat-input">
                <form id="messageForm" class="message-form">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ $selectedUserId }}">
                    <div class="input-group">
                        <button type="button" class="btn btn-attachment" onclick="document.getElementById('attachment').click()">
                            <i data-feather="paperclip"></i>
                        </button>
                        <input type="file" id="attachment" name="attachment" class="d-none" accept="image/*,application/pdf">
                        <input type="text" class="form-control" id="messageInput" placeholder="Ketik pesan..." autocomplete="off">
                        <button type="submit" class="btn btn-send">
                            <i data-feather="send"></i>
                        </button>
                    </div>
                </form>
            </div>
        @else
            <!-- Enhanced empty state -->
            <div class="chat-placeholder">
                <div class="text-center text-muted">
                    <i data-feather="message-square" class="mb-3" style="width: 64px; height: 64px;"></i>
                    <h5>Pilih percakapan untuk memulai chat</h5>
                    <p class="text-sm">Pilih salah satu percakapan dari daftar di sebelah kiri</p>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Add New Chat Modal -->
<div class="modal fade" id="newChatModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chat Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Pilih Penghuni</label>
                    <select class="form-select" id="userSelect">
                        <option value="">Pilih penghuni...</option>
                        @foreach($users as $user)
                            @if($user->role === 'user')
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Pesan</label>
                    <textarea class="form-control" id="newMessageContent" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="startNewChat()">Kirim</button>
            </div>
        </div>
    </div>
</div>

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

function showNewChatModal() {
    const modal = new bootstrap.Modal(document.getElementById('newChatModal'));
    modal.show();
}

async function startNewChat() {
    const userId = document.getElementById('userSelect').value;
    const content = document.getElementById('newMessageContent').value;

    if (!userId || !content) {
        alert('Silakan pilih penghuni dan isi pesan');
        return;
    }

    try {
        const response = await fetch('{{ route("messages.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                recipient_id: userId,
                content: content
            })
        });

        const data = await response.json();

        if (data.success) {
            // Perbaiki URL redirect
            window.location.href = '{{ route("messages.index") }}?user_id=' + userId;
        } else {
            alert('Gagal mengirim pesan: ' + data.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengirim pesan');
    }
}
</script>
@endpush
@endsection
