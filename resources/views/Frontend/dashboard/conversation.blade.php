@extends('layouts.Frontend.master')
@section('title')
    CONVERSATION WITH SUPPORT
@endsection
@section('content')
    <style>
        /* WhatsApp-like UI styles */
        .chat-container {
            display: flex;
            flex-direction: column;
            height: 600px;
            background-color: #efeae2;
            background-image: url('https://user-images.githubusercontent.com/15075759/28719144-86dc0f70-73b1-11e7-911d-60d70fcded21.png');
            background-repeat: repeat;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }

        .chat-header {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            background-color: #f0f2f5;
            border-bottom: 1px solid #e2e8f0;
            z-index: 10;
        }

        .chat-header img {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 15px;
            border: 2px solid #fff;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .chat-header .status-indicator {
            font-size: 13px;
            color: #667781;
        }

        .chat-header .status-dot {
            width: 8px;
            height: 8px;
            background-color: #25d366;
            border-radius: 50%;
            display: inline-block;
            margin-right: 5px;
        }

        .chat-messages-area {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            background-color: transparent;
        }

        .chat-bubble {
            max-width: 65%;
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 14.5px;
            line-height: 1.4;
            position: relative;
            box-shadow: 0 1px 1px rgba(0,0,0,0.08);
            word-wrap: break-word;
        }

        .chat-bubble.user {
            align-self: flex-end;
            background-color: #d9fdd3;
            color: #111b21;
            border-top-right-radius: 0;
        }

        .chat-bubble.admin {
            align-self: flex-start;
            background-color: #ffffff;
            color: #111b21;
            border-top-left-radius: 0;
        }

        .chat-bubble-meta {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 4px;
            font-size: 11px;
            color: #667781;
            margin-top: 4px;
            text-align: right;
        }

        .chat-bubble-meta .ticks {
            font-size: 14px;
        }

        .chat-bubble-meta .ticks.read {
            color: #53bdeb;
        }

        .chat-bubble-meta .ticks.unread {
            color: #8696a0;
        }

        .chat-bubble .attachment-container {
            margin-bottom: 6px;
            border-radius: 6px;
            overflow: hidden;
            background-color: rgba(0,0,0,0.03);
            border: 1px solid rgba(0,0,0,0.05);
        }

        .chat-bubble .attachment-img {
            max-width: 100%;
            max-height: 250px;
            display: block;
            object-fit: cover;
        }

        .chat-bubble .attachment-file {
            display: flex;
            align-items: center;
            padding: 10px;
            gap: 10px;
            background: rgba(0,0,0,0.04);
            border-radius: 4px;
            text-decoration: none;
            color: #111b21;
        }

        .chat-bubble .attachment-file i {
            font-size: 24px;
            color: #008069;
        }

        .chat-input-area {
            padding: 10px 20px;
            background-color: #f0f2f5;
            display: flex;
            align-items: center;
            border-top: 1px solid #e2e8f0;
        }

        .chat-input-area form {
            display: flex;
            width: 100%;
            align-items: center;
            gap: 12px;
            margin: 0;
        }

        .chat-input-field {
            flex: 1;
            background-color: #ffffff;
            border: none;
            outline: none;
            border-radius: 20px;
            padding: 9px 18px;
            font-size: 14.5px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
            resize: none;
            height: 40px;
        }

        .chat-btn {
            background: none;
            border: none;
            color: #54656f;
            cursor: pointer;
            font-size: 20px;
            padding: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.2s, transform 0.1s;
        }

        .chat-btn:hover {
            color: #008069;
        }

        .chat-btn:active {
            transform: scale(0.95);
        }

        .chat-send-btn {
            background-color: #008069;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .chat-send-btn:hover {
            background-color: #006e5a;
            color: white;
        }

        /* Preview container for selected file upload */
        .upload-preview-bar {
            background-color: #e2e8f0;
            padding: 8px 20px;
            display: none;
            align-items: center;
            justify-content: space-between;
            font-size: 13.5px;
            color: #475569;
            border-top: 1px solid #cbd5e1;
        }

        .upload-preview-bar span {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .upload-preview-bar i {
            font-size: 18px;
            color: #008069;
        }
    </style>

    <section class="py-5">
        <div class="container">
            <div class="d-flex align-items-start">

                @include('Frontend.dashboard.partials.usersideNav')

                <div class="aiz-user-panel" style="flex: 1; margin-left: 20px;">
                    <div class="chat-container">
                        <!-- Chat Header -->
                        <div class="chat-header">
                            <img src="https://www.store.looksmen.com/public/assets/img/avatar-place.png" onerror="this.src='https://www.w3schools.com/howto/img_avatar.png';" alt="Support">
                            <div>
                                <h6 class="mb-0 font-weight-bold text-dark">Customer Support Team</h6>
                                <div class="status-indicator">
                                    <span class="status-dot"></span>Online Support
                                </div>
                            </div>
                        </div>

                        <!-- Chat Messages Area -->
                        <div class="chat-messages-area" id="chatMessagesArea">
                            <div class="text-center my-4 text-muted">
                                <span class="bg-white px-3 py-1 rounded-pill small shadow-sm">
                                    All chats are securely handled by our support staff
                                </span>
                            </div>
                            <div class="d-flex justify-content-center my-5" id="chatLoading">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                        </div>

                        <!-- Upload Preview Bar -->
                        <div class="upload-preview-bar" id="uploadPreviewBar">
                            <span><i class="las la-paperclip"></i> Selected Attachment: <strong id="previewFileName">filename.ext</strong></span>
                            <button type="button" class="btn btn-sm btn-link text-danger" id="cancelUploadBtn"><i class="las la-times"></i> Remove</button>
                        </div>

                        <!-- Chat Input Area -->
                        <div class="chat-input-area">
                            <form id="chatForm" enctype="multipart/form-data">
                                @csrf
                                <input type="file" id="chatFileInput" name="file" style="display: none;">
                                <button type="button" class="chat-btn" id="attachFileBtn" title="Attach file or image">
                                    <i class="las la-paperclip"></i>
                                </button>
                                
                                <input type="text" id="chatMessageInput" name="message" class="chat-input-field" placeholder="Type a message" autocomplete="off">
                                
                                <button type="submit" class="chat-btn chat-send-btn" id="sendBtn">
                                    <i class="las la-paper-plane" style="font-size: 16px;"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            const chatMessagesArea = $('#chatMessagesArea');
            const chatLoading = $('#chatLoading');
            const chatForm = $('#chatForm');
            const chatFileInput = $('#chatFileInput');
            const attachFileBtn = $('#attachFileBtn');
            const chatMessageInput = $('#chatMessageInput');
            const uploadPreviewBar = $('#uploadPreviewBar');
            const previewFileName = $('#previewFileName');
            const cancelUploadBtn = $('#cancelUploadBtn');

            let lastMessageId = 0;
            let pollingInterval;

            // Check for pre-filled message in URL
            const urlParams = new URLSearchParams(window.location.search);
            const prefilledMsg = urlParams.get('message');
            if (prefilledMsg) {
                chatMessageInput.val(prefilledMsg);
                // Clean the URL so that reloading doesn't keep adding the text
                const cleanUrl = window.location.pathname;
                window.history.replaceState({}, document.title, cleanUrl);
            }

            // Trigger file click
            attachFileBtn.click(function() {
                chatFileInput.click();
            });

            // Show preview of selected file
            chatFileInput.change(function(e) {
                if (e.target.files && e.target.files.length > 0) {
                    const file = e.target.files[0];
                    previewFileName.text(file.name + ' (' + formatBytes(file.size) + ')');
                    uploadPreviewBar.css('display', 'flex');
                }
            });

            // Cancel upload
            cancelUploadBtn.click(function() {
                chatFileInput.val('');
                uploadPreviewBar.hide();
            });

            // Helper to format bytes
            function formatBytes(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            }

            // Scroll chat to bottom
            function scrollToBottom() {
                chatMessagesArea.scrollTop(chatMessagesArea[0].scrollHeight);
            }

            // Fetch and render messages
            function loadMessages() {
                $.ajax({
                    url: "{{ route('conversation.messages') }}",
                    method: 'GET',
                    success: function(response) {
                        chatLoading.hide();
                        if (response.success) {
                            let html = '';
                            let newLastId = 0;

                            response.messages.forEach(function(msg) {
                                newLastId = msg.id;
                                const isUser = msg.sender_type === 'user';
                                const timeStr = formatTime(msg.created_at);
                                const isReadTick = msg.is_read 
                                    ? '<i class="las la-check-double text-info"></i>' 
                                    : '<i class="las la-check"></i>';

                                html += `<div class="chat-bubble ${isUser ? 'user' : 'admin'}">`;
                                
                                // File Attachment
                                if (msg.file_path) {
                                    const fullUrl = '/' + msg.file_path;
                                    if (msg.file_type === 'image') {
                                        html += `
                                            <div class="attachment-container">
                                                <a href="${fullUrl}" target="_blank">
                                                    <img src="${fullUrl}" class="attachment-img" alt="Attachment">
                                                </a>
                                            </div>`;
                                    } else {
                                        html += `
                                            <div class="attachment-container">
                                                <a href="${fullUrl}" target="_blank" class="attachment-file">
                                                    <i class="las la-file-alt"></i>
                                                    <span style="font-size:13px; font-weight:600; text-overflow:ellipsis; overflow:hidden; white-space:nowrap; max-width:180px;">
                                                        ${msg.file_name}
                                                    </span>
                                                </a>
                                            </div>`;
                                    }
                                }

                                // Message Text
                                if (msg.message) {
                                    html += `<div>${escapeHtml(msg.message)}</div>`;
                                }

                                // Meta (Time, Ticks)
                                html += `
                                    <div class="chat-bubble-meta">
                                        <span>${timeStr}</span>
                                        ${isUser ? `<span class="ticks">${isReadTick}</span>` : ''}
                                    </div>
                                </div>`;
                            });

                            chatMessagesArea.html(html);

                            // Scroll to bottom if new message arrived
                            if (newLastId !== lastMessageId) {
                                lastMessageId = newLastId;
                                scrollToBottom();
                            }
                        }
                    },
                    error: function(err) {
                        console.error("Error loading chat messages", err);
                    }
                });
            }

            // Helper to format time (e.g. 10:24 AM)
            function formatTime(isoStr) {
                const date = new Date(isoStr);
                let hours = date.getHours();
                let minutes = date.getMinutes();
                const ampm = hours >= 12 ? 'PM' : 'AM';
                hours = hours % 12;
                hours = hours ? hours : 12; // the hour '0' should be '12'
                minutes = minutes < 10 ? '0' + minutes : minutes;
                return hours + ':' + minutes + ' ' + ampm;
            }

            // Helper to escape HTML to prevent XSS
            function escapeHtml(text) {
                if(!text) return '';
                return text
                    .replace(/&/g, "&amp;")
                    .replace(/</g, "&lt;")
                    .replace(/>/g, "&gt;")
                    .replace(/"/g, "&quot;")
                    .replace(/'/g, "&#039;");
            }

            // Send message
            chatForm.submit(function(e) {
                e.preventDefault();
                const msgText = chatMessageInput.val().trim();
                const fileSelected = chatFileInput[0].files.length > 0;

                if (!msgText && !fileSelected) return;

                const formData = new FormData(this);
                
                // Clear input fields immediately for responsiveness
                chatMessageInput.val('');
                chatFileInput.val('');
                uploadPreviewBar.hide();

                $.ajax({
                    url: "{{ route('conversation.send') }}",
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            loadMessages();
                        }
                    },
                    error: function(err) {
                        alert("Error sending message. Please try again.");
                        console.error(err);
                    }
                });
            });

            // Initial load
            loadMessages();

            // Set up polling (every 3 seconds)
            pollingInterval = setInterval(loadMessages, 3000);

            // Pause polling when tab is not focused to save client/server resources
            $(window).focus(function() {
                if (!pollingInterval) {
                    loadMessages();
                    pollingInterval = setInterval(loadMessages, 3000);
                }
            });

            $(window).blur(function() {
                clearInterval(pollingInterval);
                pollingInterval = null;
            });
        });
    </script>
@endsection


