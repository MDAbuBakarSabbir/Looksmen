@extends('layouts.Backend.master')
@section('title')
    AI & LIVE CUSTOMER CHAT
@endsection
@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');

    .chat-wrapper {
        font-family: 'Plus Jakarta Sans', sans-serif !important;
        background: #f8fafc;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        border: 1px solid #e2e8f0;
        overflow: hidden;
        min-height: 720px;
        display: flex;
    }

    /* Sidebar list */
    .chat-threads-sidebar {
        width: 340px;
        background: #ffffff;
        border-right: 1px solid #e2e8f0;
        display: flex;
        flex-direction: column;
        flex-shrink: 0;
    }
    .threads-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #e2e8f0;
        background: #ffffff;
    }
    .threads-list {
        flex: 1;
        overflow-y: auto;
        padding: 0.5rem;
    }
    .thread-card {
        padding: 1rem 1.1rem;
        border-radius: 12px;
        margin-bottom: 0.4rem;
        cursor: pointer;
        transition: all 0.2s ease;
        border: 1px solid transparent;
    }
    .thread-card:hover {
        background: #f1f5f9;
    }
    .thread-card.active {
        background: #e0e7ff;
        border-color: #c7d2fe;
    }
    .badge-transferred {
        background: #fee2e2;
        color: #dc2626;
        font-size: 0.72rem;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 6px;
    }
    .badge-ai {
        background: #e0e7ff;
        color: #4f46e5;
        font-size: 0.72rem;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 6px;
    }

    /* Main Chat Stream */
    .chat-main-area {
        flex: 1;
        min-width: 0;
        display: flex;
        flex-direction: column;
        background: #f8fafc;
    }
    .chat-main-header {
        padding: 1.1rem 1.75rem;
        background: #ffffff;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .chat-messages-container {
        flex: 1;
        padding: 1.5rem;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 1rem;
        max-height: 520px;
        word-break: break-word;
    }

    .bubble-row {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        max-width: 80%;
    }
    .bubble-row.row-user {
        margin-right: auto;
        align-self: flex-start;
    }
    .bubble-row.row-ai, .bubble-row.row-admin {
        margin-left: auto;
        align-self: flex-end;
    }

    .bubble-content {
        padding: 0.85rem 1.1rem;
        border-radius: 16px;
        font-size: 0.92rem;
        line-height: 1.5;
        box-shadow: 0 2px 5px rgba(0,0,0,0.03);
        word-break: break-word;
        overflow-wrap: break-word;
    }
    .bubble-row.row-user .bubble-content {
        background: #ffffff;
        color: #1e293b;
        border-top-left-radius: 4px;
        border: 1px solid #e2e8f0;
    }
    .bubble-row.row-ai .bubble-content {
        background: #e0e7ff;
        color: #3730a3;
        border-top-right-radius: 4px;
        border: 1px solid #c7d2fe;
    }
    .bubble-row.row-admin .bubble-content {
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        color: #ffffff;
        border-top-right-radius: 4px;
    }

    .chat-footer-composer {
        padding: 1rem 1.5rem;
        background: #ffffff;
        border-top: 1px solid #e2e8f0;
    }
    .input-composer {
        border-radius: 12px;
        border: 1.5px solid #cbd5e1;
        padding: 0.75rem 1rem;
        font-size: 0.92rem;
        background: #f8fafc;
    }
    .input-composer:focus {
        background: #ffffff;
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
    }
    .btn-composer-send {
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        color: white;
        border-radius: 12px;
        padding: 0.75rem 1.5rem;
        font-weight: 700;
        border: none;
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        flex-shrink: 0;
    }
    .quick-reply-chip {
        font-size: 12px !important;
        border-radius: 20px !important;
        padding: 5px 14px !important;
        font-weight: 600 !important;
        transition: all 0.2s ease !important;
        flex-shrink: 0;
        cursor: pointer;
        border: 1.5px solid #e2e8f0 !important;
        background: #ffffff !important;
        color: #334155 !important;
    }
    .quick-reply-chip:hover {
        background: #eff6ff !important;
        border-color: #6366f1 !important;
        color: #4f46e5 !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.15);
    }
</style>

<div class="chat-wrapper">
    <!-- Sidebar -->
    <div class="chat-threads-sidebar">
        <div class="threads-header d-flex align-items-center justify-content-between">
            <div>
                <h4 class="font-weight-bold text-dark mb-1" style="font-size: 1.05rem;">
                    <i class="fa-solid fa-comments text-indigo mr-1"></i>Live & AI Chats
                </h4>
                <div class="small text-muted">Customer Conversations</div>
            </div>
            <a href="{{ route('admin.aiSupport.settings') }}" class="btn btn-sm btn-indigo text-white font-weight-bold rounded-pill px-2 py-1" style="font-size: 11px; background: linear-gradient(135deg, #6366f1, #4f46e5);" title="Train AI Knowledge Base">
                <i class="fa-solid fa-robot mr-1"></i> AI Training
            </a>
        </div>

        <div class="threads-list" id="threadsList">
            @forelse($sessions as $sess)
                <div class="thread-card {{ $loop->first ? 'active' : '' }}" onclick="loadChatSession('{{ $sess['session_id'] }}', this)">
                    <div class="d-flex align-items-center justify-content-between mb-1">
                        <span class="font-weight-bold text-dark" style="font-size: 0.92rem;">
                            <i class="fa-solid fa-user-circle text-secondary mr-1"></i>
                            {{ $sess['user'] ? $sess['user']->name : 'Guest Customer' }}
                        </span>
                        @if($sess['is_transferred'])
                            <span class="badge-transferred"><i class="fa-solid fa-headset mr-1"></i>Live Agent</span>
                        @else
                            <span class="badge-ai"><i class="fa-solid fa-robot mr-1"></i>AI Assistant</span>
                        @endif
                    </div>
                    <div class="text-truncate text-muted small mb-1">{{ $sess['latest_message'] }}</div>
                    <div class="d-flex justify-content-between align-items-center" style="font-size: 0.75rem; color: #94a3b8;">
                        <span>Session: {{ substr($sess['session_id'], -6) }}</span>
                        <span>{{ $sess['latest_time'] }}</span>
                    </div>
                </div>
            @empty
                <div class="text-center py-5 text-muted">
                    <i class="fa-solid fa-comments-question mb-2" style="font-size: 2.5rem; opacity: 0.4;"></i>
                    <p class="mb-0">No chat sessions found.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Main Chat Window -->
    <div class="chat-main-area">
        <div class="chat-main-header" id="chatHeader">
            @if($sessions->count() > 0)
                <div>
                    <h5 class="font-weight-bold text-dark mb-0" id="headerCustomerName">
                        {{ $sessions[0]['user'] ? $sessions[0]['user']->name : 'Guest Customer' }}
                    </h5>
                    <div class="small text-muted" id="headerSessionId">
                        Session: {{ $sessions[0]['session_id'] }}
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span id="headerTransferBadge">
                        @if($sessions[0]['is_transferred'])
                            <span class="badge badge-danger px-3 py-2" style="font-size: 0.85rem;"><i class="fa-solid fa-headset mr-1"></i>Transferred to Live Admin</span>
                        @else
                            <span class="badge badge-primary px-3 py-2" style="font-size: 0.85rem;"><i class="fa-solid fa-robot mr-1"></i>AI Automated Mode</span>
                        @endif
                    </span>
                    <button type="button" class="btn btn-sm btn-outline-danger font-weight-bold ml-2" onclick="closeAdminChat()" style="border-radius: 8px;">
                        <i class="fa-solid fa-circle-xmark mr-1"></i> Close & Clear Chat
                    </button>
                </div>
            @else
                <div class="text-muted">Select a conversation to view chat history.</div>
            @endif
        </div>

        <div class="chat-messages-container" id="chatMessagesBox">
            <div class="text-center py-5 text-muted">
                <i class="fa-solid fa-spinner fa-spin mr-2"></i> Loading conversation...
            </div>
        </div>

        @php
            $adminName = auth()->guard('admin')->user()->name ?? auth()->user()->name ?? 'Admin Support';
            $contactPhone = \App\Models\GeneralWebSettings::where('name', 'contact_phone')->value('value') ?? '+8801568482005';
        @endphp

        <div class="chat-footer-composer">
            <!-- Quick Replies Container -->
            <div class="quick-replies-container mb-3 p-3 rounded-lg" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px;">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="font-weight-bold text-dark fs-12 d-flex align-items-center gap-1">
                        <i class="fa-solid fa-bolt text-warning mr-1"></i> Quick Reply Templates
                    </span>
                    <span class="text-muted" style="font-size: 11px;">Click any chip to insert message</span>
                </div>

                <!-- Bangla Quick Replies Group -->
                <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                    <span class="badge badge-light border text-secondary font-weight-bold mr-1" style="font-size: 10px; padding: 5px 8px;">🇧🇩 বাংলা</span>
                    
                    <button type="button" class="quick-reply-chip"
                            onclick="insertQuickReply('হ্যালো! কাস্টমার সাপোর্টে আমি {{ $adminName }} বলছি। আপনাকে কীভাবে সাহায্য করতে পারি?')">
                        <i class="fa-solid fa-hand-wave text-primary mr-1"></i> স্বাগতম ({{ $adminName }})
                    </button>

                    <button type="button" class="quick-reply-chip"
                            onclick="insertQuickReply('আপনার অর্ডারটি সফলভাবে ভেরিফাই ও কনফার্ম করা হয়েছে! দ্রুত আপনার ঠিকানায় পার্সেল বানিয়ে পাঠিয়ে দেওয়া হবে।')">
                        <i class="fa-solid fa-circle-check text-success mr-1"></i> অর্ডার কনফার্ম
                    </button>

                    <button type="button" class="quick-reply-chip"
                            onclick="insertQuickReply('ঢাকার ভেতরে ২৪-৪৮ ঘন্টা এবং ঢাকার বাইরে ২-৪ দিনের মধ্যে ডেলিভারি দেওয়া হয়। পার্সেল পাঠালে ট্র্যাকিং এসএমএস পাবেন।')">
                        <i class="fa-solid fa-truck-fast text-info mr-1"></i> ডেলিভারি তথ্য
                    </button>

                    <button type="button" class="quick-reply-chip"
                            onclick="insertQuickReply('অনুগ্ৰহ করে আমাদের বিকাশ/নগদ নাম্বারে টাকা পাঠিয়ে আপনার Transaction ID (TRX ID) টি এখানে শেয়ার করুন।')">
                        <i class="fa-solid fa-credit-card text-warning mr-1"></i> পেমেন্ট তথ্য
                    </button>

                    <button type="button" class="quick-reply-chip"
                            onclick="insertQuickReply('জরুরি যেকোনো প্রয়োজনে আমাদের হেল্পলাইন নাম্বারে কল দিন: {{ $contactPhone }}')">
                        <i class="fa-solid fa-phone text-danger mr-1"></i> কল হেল্পলাইন
                    </button>

                    <button type="button" class="quick-reply-chip"
                            onclick="insertQuickReply('আমাদের সাথে যোগাযোগ করার জন্য ধন্যবাদ! আপনার দিনটি শুভ হোক।')">
                        <i class="fa-solid fa-hands-praying text-purple mr-1"></i> ধন্যবাদ
                    </button>
                </div>

                <!-- English Quick Replies Group -->
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <span class="badge badge-light border text-secondary font-weight-bold mr-1" style="font-size: 10px; padding: 5px 8px;">🇬🇧 EN</span>
                    
                    <button type="button" class="quick-reply-chip"
                            onclick="insertQuickReply('Hello! This is {{ $adminName }} from Customer Support. How can I assist you today?')">
                        <i class="fa-solid fa-hand-wave text-primary mr-1"></i> Welcome (English)
                    </button>

                    <button type="button" class="quick-reply-chip"
                            onclick="insertQuickReply('Your order has been verified and approved! We will dispatch it to your delivery address shortly.')">
                        <i class="fa-solid fa-circle-check text-success mr-1"></i> Order Approved (EN)
                    </button>

                    <button type="button" class="quick-reply-chip"
                            onclick="insertQuickReply('Delivery takes 24-48 hours in Dhaka & 2-4 days nationwide. You will receive a tracking SMS once dispatched.')">
                        <i class="fa-solid fa-truck-fast text-info mr-1"></i> Delivery Info (EN)
                    </button>
                </div>
            </div>

            <!-- Chat Input Form -->
            <form id="formAdminReply" class="d-flex align-items-center gap-2">
                @csrf
                <input type="hidden" id="activeSessionId" value="{{ $sessions->count() > 0 ? $sessions[0]['session_id'] : '' }}">
                <input type="text" id="adminReplyInput" class="form-control input-composer" placeholder="Type a message as Admin Support..." required autocomplete="off">
                <button type="submit" id="btnAdminSend" class="btn btn-composer-send">
                    <i class="fa-solid fa-paper-plane mr-1"></i> Send
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    let activeSessionId = "{{ $sessions->count() > 0 ? $sessions[0]['session_id'] : '' }}";
    let autoPollTimer = null;

    function insertQuickReply(text) {
        $('#adminReplyInput').val(text).focus();
    }

    function loadChatSession(sessionId, element) {
        activeSessionId = sessionId;
        $('#activeSessionId').val(sessionId);

        if (element) {
            $('.thread-card').removeClass('active');
            $(element).addClass('active');
        }

        $.get("{{ url('/admin/ai-support/messages') }}/" + sessionId, function(res) {
            if (res.success) {
                const custName = res.user ? res.user.name : 'Guest Customer';
                $('#headerCustomerName').text(custName);
                $('#headerSessionId').text('Session: ' + res.session_id);

                if (res.is_transferred) {
                    $('#headerTransferBadge').html('<span class="badge badge-danger px-3 py-2" style="font-size: 0.85rem;"><i class="fa-solid fa-headset mr-1"></i>Transferred to Live Admin</span>');
                } else {
                    $('#headerTransferBadge').html('<span class="badge badge-primary px-3 py-2" style="font-size: 0.85rem;"><i class="fa-solid fa-robot mr-1"></i>AI Automated Mode</span>');
                }

                let html = '';
                res.messages.forEach(function(msg) {
                    let senderClass = 'row-user';
                    let senderLabel = 'Customer';
                    if (msg.sender === 'ai') {
                        senderClass = 'row-ai';
                        senderLabel = 'AI Support';
                    } else if (msg.sender === 'admin') {
                        senderClass = 'row-admin';
                        senderLabel = 'Admin Agent';
                    }

                    html += `
                        <div class="bubble-row ${senderClass}">
                            <div class="bubble-content">
                                <div class="font-weight-bold small mb-1" style="opacity: 0.8;">${senderLabel}</div>
                                <div>${escapeHtml(msg.message).replace(/\n/g, '<br>')}</div>
                            </div>
                        </div>
                    `;
                });

                $('#chatMessagesBox').html(html);
                scrollToBottom();
            }
        });
    }

    function escapeHtml(text) {
        return text.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;");
    }

    function scrollToBottom() {
        const box = document.getElementById('chatMessagesBox');
        if (box) {
            box.scrollTop = box.scrollHeight;
        }
    }

    $(document).ready(function() {
        if (activeSessionId) {
            loadChatSession(activeSessionId, null);
        }

        $('#formAdminReply').on('submit', function(e) {
            e.preventDefault();
            const input = $('#adminReplyInput');
            const msg = input.val().trim();
            const sessId = $('#activeSessionId').val();

            if (!msg || !sessId) return;

            const btn = $('#btnAdminSend');
            btn.prop('disabled', true);

            $.post("{{ route('admin.aiSupport.reply') }}", {
                _token: '{{ csrf_token() }}',
                session_id: sessId,
                message: msg
            }, function(res) {
                btn.prop('disabled', false);
                if (res.success) {
                    input.val('');
                    loadChatSession(sessId, null);
                }
            }).fail(function() {
                btn.prop('disabled', false);
            });
        });

        // Polling every 5 seconds
        autoPollTimer = setInterval(function() {
            if (activeSessionId) {
                loadChatSession(activeSessionId, null);
            }
        }, 5000);
    });

    function closeAdminChat() {
        const sessId = $('#activeSessionId').val();
        if (!sessId) {
            Swal.fire({ icon: 'warning', title: 'Notice', text: 'No active chat session selected.' });
            return;
        }

        Swal.fire({
            title: 'Close & Clear Chat?',
            text: 'Are you sure you want to close this chat? All message history for this customer session will be deleted permanently.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Close & Clear!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post("{{ route('admin.aiSupport.close') }}", {
                    _token: '{{ csrf_token() }}',
                    session_id: sessId
                }, function(res) {
                    if (res.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Closed!',
                            text: res.message,
                            timer: 1500,
                            showConfirmButton: false
                        });
                        setTimeout(function() {
                            location.reload();
                        }, 1200);
                    }
                }).fail(function() {
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to close chat session.' });
                });
            }
        });
    }
</script>
@endsection
