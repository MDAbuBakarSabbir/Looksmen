@extends('layouts.Backend.master')

@section('title','WhatsApp Conversation')
@section('content')
<!-- Import modern fonts -->
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
    /* Styling variables */
    :root {
        --wa-teal: #00a884;
        --wa-teal-dark: #008069;
        --wa-bg: #efeae2;
        --wa-sidebar-bg: #ffffff;
        --wa-chat-bg: #eae6df;
        --wa-hover: #f5f6f6;
        --wa-active: #efeae2;
        --wa-text-primary: #111b21;
        --wa-text-secondary: #667781;
        --wa-bubble-in: #ffffff;
        --wa-bubble-out: #d9fdd3;
        --wa-border: #e9edef;
    }

    .chat-wrapper {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background: #f0f2f5;
        padding: 15px 0;
    }

    .chat-container {
        display: flex;
        height: 80vh;
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        border: 1px solid var(--wa-border);
    }

    /* Sidebar Styling */
    .chat-sidebar {
        width: 360px;
        border-right: 1px solid var(--wa-border);
        display: flex;
        flex-direction: column;
        background: var(--wa-sidebar-bg);
        flex-shrink: 0;
    }

    .chat-sidebar-header {
        padding: 20px 24px;
        background: #ffffff;
        border-bottom: 1px solid var(--wa-border);
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .chat-sidebar-title {
        font-size: 20px;
        font-weight: 700;
        color: var(--wa-text-primary);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .chat-sidebar-title i {
        color: var(--wa-teal);
    }

    .search-wrapper {
        position: relative;
    }

    .search-wrapper input {
        width: 100%;
        padding: 10px 16px 10px 42px;
        background: #f0f2f5;
        border: 1px solid transparent;
        border-radius: 20px;
        font-size: 14px;
        outline: none;
        transition: all 0.3s ease;
        color: var(--wa-text-primary);
    }

    .search-wrapper input:focus {
        background: #ffffff;
        border-color: var(--wa-teal);
        box-shadow: 0 0 0 3px rgba(0, 168, 132, 0.15);
    }

    .search-wrapper i {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--wa-text-secondary);
        font-size: 14px;
    }

    .contact-list {
        overflow-y: auto;
        flex: 1;
        margin: 0;
        padding: 8px 0;
        list-style: none;
    }

    .contact-item {
        padding: 14px 20px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 14px;
        transition: all 0.2s ease;
        position: relative;
    }

    .contact-item::after {
        content: '';
        position: absolute;
        bottom: 0;
        right: 20px;
        left: 78px;
        height: 1px;
        background: var(--wa-border);
    }

    .contact-item:last-child::after {
        display: none;
    }

    .contact-item:hover {
        background: var(--wa-hover);
    }

    .contact-item.active {
        background: var(--wa-active);
    }

    /* Avatars */
    .avatar-wrapper {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: #e1f5fe;
        color: #039be5;
        display: flex;
        justify-content: center;
        align-items: center;
        font-weight: 600;
        font-size: 16px;
        flex-shrink: 0;
        border: 1px solid rgba(0, 0, 0, 0.05);
        text-transform: uppercase;
    }

    .contact-item:nth-child(even) .avatar-wrapper {
        background: #efebe9;
        color: #8d6e63;
    }

    .contact-item:nth-child(3n) .avatar-wrapper {
        background: #e8f5e9;
        color: #2e7d32;
    }

    .contact-details {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 4px;
        min-width: 0;
    }

    .contact-header-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .contact-name {
        font-weight: 600;
        font-size: 15px;
        color: var(--wa-text-primary);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .contact-phone {
        font-size: 12px;
        color: var(--wa-text-secondary);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .unread-badge {
        background: var(--wa-teal);
        color: #ffffff;
        min-width: 20px;
        height: 20px;
        padding: 0 6px;
        border-radius: 10px;
        font-size: 11px;
        font-weight: 700;
        display: flex;
        justify-content: center;
        align-items: center;
        animation: scaleIn 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    @keyframes scaleIn {
        from { transform: scale(0); }
        to { transform: scale(1); }
    }

    /* Main Chat Panel */
    .chat-main {
        flex: 1;
        display: flex;
        flex-direction: column;
        background: var(--wa-chat-bg);
        position: relative;
    }

    /* Subtle WhatsApp background pattern using CSS */
    .chat-main::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        opacity: 0.06;
        background-image: radial-gradient(#128c7e 1px, transparent 0), radial-gradient(#128c7e 1px, transparent 0);
        background-size: 24px 24px;
        background-position: 0 0, 12px 12px;
        pointer-events: none;
    }

    .chat-header {
        padding: 16px 24px;
        background: #ffffff;
        border-bottom: 1px solid var(--wa-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        z-index: 10;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
    }

    .chat-header-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .chat-header-name {
        font-weight: 600;
        font-size: 16px;
        color: var(--wa-text-primary);
    }

    .chat-header-status {
        font-size: 12px;
        color: var(--wa-teal);
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .chat-messages {
        flex: 1;
        padding: 24px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 12px;
        z-index: 5;
    }

    /* Message Bubbles */
    .message {
        max-width: 65%;
        padding: 8px 14px 10px 14px;
        border-radius: 10px;
        position: relative;
        font-size: 14.5px;
        line-height: 1.5;
        word-wrap: break-word;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.08);
        animation: bubbleSlideIn 0.25s cubic-bezier(0.1, 0.8, 0.25, 1);
    }

    @keyframes bubbleSlideIn {
        from {
            opacity: 0;
            transform: translateY(8px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .message.inbound {
        background: var(--wa-bubble-in);
        align-self: flex-start;
        border-top-left-radius: 0;
        color: var(--wa-text-primary);
    }

    .message.outbound {
        background: var(--wa-bubble-out);
        align-self: flex-end;
        border-top-right-radius: 0;
        color: var(--wa-text-primary);
    }

    .message-meta {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 4px;
        margin-top: 4px;
    }

    .message-time {
        font-size: 10px;
        color: var(--wa-text-secondary);
        font-weight: 500;
    }

    .message-status {
        font-size: 11px;
        display: inline-flex;
    }

    .message-status.text-info i {
        color: #53bdeb !important;
    }

    .message-status.text-muted i {
        color: #8696a0 !important;
    }

    /* Chat Input Area */
    .chat-input-area {
        padding: 16px 24px;
        background: #f0f2f5;
        display: flex;
        gap: 12px;
        align-items: center;
        border-top: 1px solid var(--wa-border);
        z-index: 10;
    }

    .chat-input-area input {
        flex: 1;
        padding: 14px 20px;
        border: 1px solid transparent;
        border-radius: 30px;
        outline: none;
        background: #ffffff;
        font-size: 15px;
        transition: all 0.3s ease;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    }

    .chat-input-area input:focus {
        border-color: var(--wa-border);
        box-shadow: 0 3px 15px rgba(0, 0, 0, 0.05);
    }

    .chat-input-area button {
        background: var(--wa-teal);
        color: white;
        border: none;
        width: 48px;
        height: 48px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        justify-content: center;
        align-items: center;
        transition: all 0.25s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        box-shadow: 0 2px 8px rgba(0, 168, 132, 0.3);
    }

    .chat-input-area button:hover {
        background: var(--wa-teal-dark);
        transform: scale(1.05);
    }

    .chat-input-area button:active {
        transform: scale(0.95);
    }

    .chat-input-area button i {
        font-size: 18px;
        transform: rotate(0deg);
        transition: transform 0.2s;
    }

    /* Empty state */
    .empty-state {
        flex: 1;
        display: flex;
        justify-content: center;
        align-items: center;
        color: var(--wa-text-secondary);
        font-size: 16px;
        flex-direction: column;
        background: #f8f9fa;
        text-align: center;
        padding: 40px;
    }

    .empty-state-icon {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: #e8f5e9;
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 24px;
        color: var(--wa-teal);
        animation: pulse 2s infinite;
    }

    .empty-state-icon i {
        font-size: 56px;
    }

    @keyframes pulse {
        0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(0, 168, 132, 0.2); }
        70% { transform: scale(1.05); box-shadow: 0 0 0 15px rgba(0, 168, 132, 0); }
        100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(0, 168, 132, 0); }
    }

    .empty-state-title {
        font-size: 22px;
        font-weight: 700;
        color: var(--wa-text-primary);
        margin-bottom: 8px;
    }

    .empty-state-desc {
        max-width: 320px;
        font-size: 14px;
        line-height: 1.6;
    }
</style>

<div class="page-wrapper chat-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="chat-container">
                    
                    <!-- Sidebar: Contacts -->
                    <div class="chat-sidebar">
                        <div class="chat-sidebar-header">
                            <h4 class="chat-sidebar-title">
                                <i class="fab fa-whatsapp"></i> Chats
                            </h4>
                            <div class="search-wrapper">
                                <i class="fa fa-search"></i>
                                <input type="text" id="contact-search" placeholder="Search or start new chat..." autocomplete="off">
                            </div>
                        </div>
                        <ul class="contact-list" id="contact-list">
                            <!-- Contacts injected via JS -->
                            <div class="text-center p-5 text-muted">
                                <i class="fa fa-circle-notch fa-spin fa-2x mb-3 text-muted"></i>
                                <div>Loading conversations...</div>
                            </div>
                        </ul>
                    </div>

                    <!-- Main Chat Area -->
                    <div class="chat-main" id="chat-main" style="display: none;">
                        <div class="chat-header">
                            <div class="chat-header-info">
                                <div class="avatar-wrapper" id="active-avatar">W</div>
                                <div>
                                    <div class="chat-header-name" id="active-contact-name">Select a contact</div>
                                    <div class="chat-header-status"><i class="fa fa-circle"></i> Online</div>
                                </div>
                            </div>
                        </div>
                        <div class="chat-messages" id="chat-messages">
                            <!-- Messages injected via JS -->
                        </div>
                        <div class="chat-input-area">
                            <input type="text" id="message-input" placeholder="Type a message..." autocomplete="off" onkeypress="handleKeyPress(event)">
                            <button id="send-btn" onclick="sendMessage()"><i class="fa fa-paper-plane"></i></button>
                        </div>
                    </div>
                    
                    <!-- Empty State -->
                    <div class="empty-state" id="empty-state">
                        <div class="empty-state-icon">
                            <i class="fab fa-whatsapp"></i>
                        </div>
                        <h3 class="empty-state-title">Looksmen WhatsApp</h3>
                        <p class="empty-state-desc text-muted">Send and receive WhatsApp messages in real-time. Select a contact from the sidebar list to start conversing.</p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.16.1/dist/echo.iife.js"></script>
<script>
    let currentContactId = null;

    // Initialize Laravel Echo with Reverb
    window.Echo = new Echo({
        broadcaster: 'reverb',
        key: "{{ config('broadcasting.connections.reverb.key') }}",
        wsHost: window.location.hostname,
        wsPort: {{ config('broadcasting.connections.reverb.options.port', 8080) }},
        wssPort: {{ config('broadcasting.connections.reverb.options.port', 8080) }},
        forceTLS: (window.location.protocol === 'https:'),
        enabledTransports: ['ws', 'wss'],
    });

    // Helper: Generate clean initials from name
    function getInitials(name) {
        if (!name) return 'W';
        let parts = name.trim().split(' ');
        let initials = '';
        for (let i = 0; i < Math.min(parts.length, 2); i++) {
            if (parts[i].length > 0) initials += parts[i][0];
        }
        return initials.toUpperCase() || 'W';
    }

    // Helper: Generate message status HTML (gray/blue ticks)
    function getStatusHtml(msg) {
        if (msg.direction !== 'outbound') return '';
        
        let iconClass = 'fa-check';
        let statusClass = 'text-muted';
        
        if (msg.status === 'sent') {
            iconClass = 'fa-check';
            statusClass = 'text-muted';
        } else if (msg.status === 'delivered') {
            iconClass = 'fa-check-double';
            statusClass = 'text-muted';
        } else if (msg.status === 'read') {
            iconClass = 'fa-check-double';
            statusClass = 'text-info';
        } else if (msg.status === 'failed') {
            iconClass = 'fa-exclamation-circle';
            statusClass = 'text-danger';
        }
        
        return `<span class="message-status ${statusClass}"><i class="fa ${iconClass}"></i></span>`;
    }

    // Load contacts on page load
    $(document).ready(function() {
        fetchContacts();
        
        // Listen for new messages globally to update the contact list
        window.Echo.channel('whatsapp-contacts')
            .listen('.whatsapp.message.new', (e) => {
                fetchContacts();
            });

        // Client-side search filtering
        $('#contact-search').on('input', function() {
            let val = $(this).val().toLowerCase();
            $('.contact-item').each(function() {
                let name = $(this).find('.contact-name').text().toLowerCase();
                let phone = $(this).find('.contact-phone').text().toLowerCase();
                if (name.includes(val) || phone.includes(val)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });

        // Hybrid Fallback Polling: If WebSockets fail or disconnect, poll every 6 seconds
        setInterval(function() {
            let isConnected = false;
            try {
                if (window.Echo && window.Echo.connector && window.Echo.connector.pusher && window.Echo.connector.pusher.connection) {
                    isConnected = (window.Echo.connector.pusher.connection.state === 'connected');
                }
            } catch(e) {
                isConnected = false;
            }

            if (!isConnected) {
                fetchContacts();
                if (currentContactId) {
                    fetchMessages(currentContactId, false);
                }
            }
        }, 6000);
    });

    function fetchContacts() {
        $.get("{{ route('conversation.whatsapp.contacts') }}", function(data) {
            let html = '';
            let searchVal = $('#contact-search').val().toLowerCase();
            if(data.length === 0) {
                html = '<div class="text-center p-5 text-muted">No conversations yet</div>';
            } else {
                data.forEach(function(contact) {
                    let activeClass = (contact.id === currentContactId) ? 'active' : '';
                    let badgeClass = (contact.unread_count > 0) ? '' : 'd-none';
                    let name = contact.name || contact.phone_number;
                    let initials = getInitials(name);
                    
                    // Keep search filter state on update
                    let isVisible = name.toLowerCase().includes(searchVal) || contact.phone_number.toLowerCase().includes(searchVal);
                    let displayStyle = isVisible ? '' : 'style="display: none;"';

                    html += `
                        <li class="contact-item ${activeClass}" ${displayStyle} onclick="openChat(${contact.id}, '${name}')">
                            <div class="avatar-wrapper">${initials}</div>
                            <div class="contact-details">
                                <div class="contact-header-info">
                                    <span class="contact-name">${name}</span>
                                    <span class="unread-badge ${badgeClass}">${contact.unread_count}</span>
                                </div>
                                <span class="contact-phone">${contact.phone_number}</span>
                            </div>
                        </li>
                    `;
                });
            }
            $('#contact-list').html(html);
        }).fail(function(err) {
            $('#contact-list').html('<div class="text-center p-5 text-danger"><i class="fa fa-exclamation-circle fa-2x mb-2"></i><div>Failed to load contacts. Ensure migrations have run.</div></div>');
        });
    }

    function openChat(contactId, contactName) {
        // Leave old channel if any
        if (currentContactId) {
            window.Echo.leave('whatsapp-chat.' + currentContactId);
        }

        currentContactId = contactId;
        $('#empty-state').hide();
        $('#chat-main').css('display', 'flex');
        $('#active-contact-name').text(contactName);
        $('#active-avatar').text(getInitials(contactName));
        $('#contact-list .contact-item').removeClass('active');
        fetchContacts(); // Update active class immediately
        fetchMessages(contactId, true);

        // Listen for messages on the specific chat channel
        window.Echo.channel('whatsapp-chat.' + contactId)
            .listen('.whatsapp.message.new', (e) => {
                appendMessage(e.message);
                fetchContacts(); // Clear unread counts for this contact
            });
    }

    function fetchMessages(contactId, scrollToBottom) {
        $.get("{{ url('admin/conversation/whatsapp/messages') }}/" + contactId, function(data) {
            let html = '';
            data.messages.forEach(function(msg) {
                let time = new Date(msg.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                
                let bodyContent = msg.body;
                if (msg.type === 'image') {
                    if (msg.body && (msg.body.startsWith('http') || msg.body.startsWith('/'))) {
                        bodyContent = `<img src="${msg.body}" class="img-fluid rounded" style="max-width: 250px; cursor: pointer; display: block; border-radius: 8px; margin-bottom: 4px;" onclick="window.open('${msg.body}', '_blank')" />`;
                    }
                }

                html += `
                    <div class="message ${msg.direction}" data-message-id="${msg.message_id || ''}">
                        ${bodyContent}
                        <div class="message-meta">
                            <span class="message-time">${time}</span>
                            ${getStatusHtml(msg)}
                        </div>
                    </div>
                `;
            });
            let chatBox = $('#chat-messages');
            let isAtBottom = chatBox.prop("scrollHeight") - chatBox.scrollTop() === chatBox.outerHeight();
            
            chatBox.html(html);
            
            if(scrollToBottom || isAtBottom) {
                chatBox.scrollTop(chatBox.prop("scrollHeight"));
            }
        });
    }

    function appendMessage(msg) {
        // If message already exists (e.g. status update), update its checkmark status
        if (msg.message_id) {
            let existing = $(`[data-message-id="${msg.message_id}"]`);
            if (existing.length) {
                existing.find('.message-meta').html(`
                    <span class="message-time">${new Date(msg.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</span>
                    ${getStatusHtml(msg)}
                `);
                return;
            }
        }

        let time = new Date(msg.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
        
        let bodyContent = msg.body;
        if (msg.type === 'image') {
            if (msg.body && (msg.body.startsWith('http') || msg.body.startsWith('/'))) {
                bodyContent = `<img src="${msg.body}" class="img-fluid rounded" style="max-width: 250px; cursor: pointer; display: block; border-radius: 8px; margin-bottom: 4px;" onclick="window.open('${msg.body}', '_blank')" />`;
            }
        }

        let html = `
            <div class="message ${msg.direction}" data-message-id="${msg.message_id || ''}">
                ${bodyContent}
                <div class="message-meta">
                    <span class="message-time">${time}</span>
                    ${getStatusHtml(msg)}
                </div>
            </div>
        `;
        let chatBox = $('#chat-messages');
        let isAtBottom = chatBox.prop("scrollHeight") - chatBox.scrollTop() === chatBox.outerHeight();
        chatBox.append(html);
        if (isAtBottom) {
            chatBox.scrollTop(chatBox.prop("scrollHeight"));
        }
    }

    function handleKeyPress(e) {
        if(e.key === 'Enter') {
            sendMessage();
        }
    }

    function sendMessage() {
        let input = $('#message-input');
        let message = input.val().trim();
        let btn = $('#send-btn');
        
        if(!message || !currentContactId) return;
        
        input.val('');
        input.prop('disabled', true);
        btn.prop('disabled', true);
        
        $.ajax({
            url: "{{ route('conversation.whatsapp.send') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                contact_id: currentContactId,
                message: message
            },
            success: function(res) {
                input.prop('disabled', false).focus();
                btn.prop('disabled', false);
                // Immediately append the actual sent message returned by the server
                if (res.success && res.message) {
                    appendMessage(res.message);
                }
                fetchContacts(); // Update sidebar state
            },
            error: function(err) {
                input.prop('disabled', false).focus();
                btn.prop('disabled', false);
                alert("Failed to send message.");
                console.error(err);
            }
        });
    }
</script>
@endsection