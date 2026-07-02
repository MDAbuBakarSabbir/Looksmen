@extends('layouts.Backend.master')

@section('title','Whatsapp Conversation')
@section('content')
<style>
    .chat-container { display: flex; height: 75vh; background: #fff; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); overflow: hidden; }
    .chat-sidebar { width: 300px; border-right: 1px solid #ddd; display: flex; flex-direction: column; }
    .chat-sidebar-header { padding: 15px; background: #f8f9fa; border-bottom: 1px solid #ddd; font-weight: bold; }
    .contact-list { overflow-y: auto; flex: 1; margin: 0; padding: 0; list-style: none; }
    .contact-item { padding: 15px; border-bottom: 1px solid #f1f1f1; cursor: pointer; display: flex; justify-content: space-between; align-items: center; transition: background 0.2s; }
    .contact-item:hover, .contact-item.active { background: #eef2f5; }
    .contact-info { display: flex; flex-direction: column; }
    .contact-name { font-weight: bold; font-size: 14px; color: #333; }
    .contact-phone { font-size: 12px; color: #777; }
    .unread-badge { background: #25d366; color: white; padding: 2px 8px; border-radius: 12px; font-size: 11px; font-weight: bold; display: none; }
    .unread-badge.show { display: block; }
    
    .chat-main { flex: 1; display: flex; flex-direction: column; background: #e5ddd5; }
    .chat-header { padding: 15px; background: #f8f9fa; border-bottom: 1px solid #ddd; font-weight: bold; display: flex; align-items: center; }
    .chat-messages { flex: 1; padding: 20px; overflow-y: auto; display: flex; flex-direction: column; gap: 10px; }
    .message { max-width: 70%; padding: 10px 15px; border-radius: 8px; position: relative; font-size: 14px; line-height: 1.4; word-wrap: break-word; }
    .message.inbound { background: #fff; align-self: flex-start; border-top-left-radius: 0; }
    .message.outbound { background: #dcf8c6; align-self: flex-end; border-top-right-radius: 0; }
    .message-time { font-size: 10px; color: #888; text-align: right; margin-top: 5px; display: block; }
    
    .chat-input-area { padding: 15px; background: #f0f0f0; display: flex; gap: 10px; align-items: center; border-top: 1px solid #ddd; }
    .chat-input-area input { flex: 1; padding: 12px 15px; border: none; border-radius: 20px; outline: none; }
    .chat-input-area button { background: #25d366; color: white; border: none; width: 45px; height: 45px; border-radius: 50%; cursor: pointer; display: flex; justify-content: center; align-items: center; transition: 0.2s; }
    .chat-input-area button:hover { background: #128c7e; }
    .chat-input-area button i { font-size: 18px; }
    .empty-state { flex: 1; display: flex; justify-content: center; align-items: center; color: #888; font-size: 18px; flex-direction: column; }
    .empty-state i { font-size: 50px; margin-bottom: 15px; color: #ccc; }
</style>

<div class="page-wrapper">
    <div class="page-titles">
        <h4>Whatsapp Conversation</h4>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="chat-container">
                    
                    <!-- Sidebar: Contacts -->
                    <div class="chat-sidebar">
                        <div class="chat-sidebar-header">
                            WhatsApp Chats
                        </div>
                        <ul class="contact-list" id="contact-list">
                            <!-- Contacts injected via JS -->
                            <div class="text-center p-4 text-muted">Loading...</div>
                        </ul>
                    </div>

                    <!-- Main Chat Area -->
                    <div class="chat-main" id="chat-main" style="display: none;">
                        <div class="chat-header">
                            <span id="active-contact-name">Select a contact</span>
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
                        <i class="fab fa-whatsapp"></i>
                        Select a conversation to start messaging
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

    // Load contacts on page load
    $(document).ready(function() {
        fetchContacts();
        
        // Listen for new messages globally to update the contact list
        window.Echo.channel('whatsapp-contacts')
            .listen('NewWhatsAppMessage', (e) => {
                fetchContacts();
            });
    });

    function fetchContacts() {
        $.get("{{ route('conversation.whatsapp.contacts') }}", function(data) {
            let html = '';
            if(data.length === 0) {
                html = '<div class="text-center p-4 text-muted">No conversations yet</div>';
            } else {
                data.forEach(function(contact) {
                    let activeClass = (contact.id === currentContactId) ? 'active' : '';
                    let badgeClass = (contact.unread_count > 0) ? 'show' : '';
                    let name = contact.name || contact.phone_number;
                    html += `
                        <li class="contact-item ${activeClass}" onclick="openChat(${contact.id}, '${name}')">
                            <div class="contact-info">
                                <span class="contact-name">${name}</span>
                                <span class="contact-phone">${contact.phone_number}</span>
                            </div>
                            <span class="unread-badge ${badgeClass}">${contact.unread_count}</span>
                        </li>
                    `;
                });
            }
            $('#contact-list').html(html);
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
        $('#contact-list .contact-item').removeClass('active');
        fetchContacts(); // Update active class immediately
        fetchMessages(contactId, true);

        // Listen for messages on the specific chat channel
        window.Echo.channel('whatsapp-chat.' + contactId)
            .listen('NewWhatsAppMessage', (e) => {
                appendMessage(e.message);
                fetchContacts(); // Clear unread counts for this contact
            });
    }

    function fetchMessages(contactId, scrollToBottom) {
        $.get("{{ url('admin/conversation/whatsapp/messages') }}/" + contactId, function(data) {
            let html = '';
            data.messages.forEach(function(msg) {
                let time = new Date(msg.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                html += `
                    <div class="message ${msg.direction}">
                        ${msg.body}
                        <span class="message-time">${time}</span>
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
        let time = new Date(msg.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
        let html = `
            <div class="message ${msg.direction}">
                ${msg.body}
                <span class="message-time">${time}</span>
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
                // We don't fetch messages here, it will be injected by the server event or we can append it directly!
                // Assuming you're echoing your own messages from Reverb, but usually you don't.
                // We'll just fetch again to be simple and safe, or append directly.
                fetchMessages(currentContactId, true);
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