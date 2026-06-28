@extends('layouts.Backend.master')
@section('title')
    CUSTOMER CHAT
@endsection
@section('content')
    <style>
        /* WhatsApp replica admin console */
        .wa-chat-wrapper {
            display: flex;
            height: calc(100vh - 160px);
            min-height: 550px;
            background-color: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.04);
        }

        /* LEFT SIDEBAR PANEL */
        .wa-sidebar {
            width: 360px;
            border-right: 1px solid #e9ecef;
            display: flex;
            flex-direction: column;
            background-color: #ffffff;
            flex-shrink: 0;
        }

        .wa-sidebar-header {
            padding: 15px 20px;
            background-color: #f0f2f5;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #e9ecef;
        }

        .wa-sidebar-header h5 {
            margin: 0;
            font-weight: 700;
            color: #111b21;
        }

        .wa-search-container {
            padding: 10px 15px;
            background-color: #fff;
            border-bottom: 1px solid #f0f2f5;
        }

        .wa-search-box {
            display: flex;
            align-items: center;
            background-color: #f0f2f5;
            padding: 6px 12px;
            border-radius: 8px;
        }

        .wa-search-box i {
            color: #667781;
            margin-right: 10px;
            font-size: 15px;
        }

        .wa-search-box input {
            border: none;
            background: none;
            width: 100%;
            outline: none;
            font-size: 14px;
            color: #111b21;
        }

        .wa-user-list {
            flex: 1;
            overflow-y: auto;
            background-color: #ffffff;
        }

        .wa-user-item {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            cursor: pointer;
            border-bottom: 1px solid #f8f9fa;
            transition: background-color 0.2s;
        }

        .wa-user-item:hover {
            background-color: #f0f2f5;
        }

        .wa-user-item.active {
            background-color: #eaebeb;
        }

        .wa-user-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 14px;
            background-color: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: #495057;
            flex-shrink: 0;
        }

        .wa-user-info {
            flex: 1;
            min-width: 0;
        }

        .wa-user-meta {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            margin-bottom: 3px;
        }

        .wa-user-name {
            font-size: 15px;
            font-weight: 600;
            color: #111b21;
            margin: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .wa-msg-time {
            font-size: 12px;
            color: #667781;
        }

        .wa-user-last-msg {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .wa-msg-snippet {
            font-size: 13.5px;
            color: #667781;
            margin: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            padding-right: 10px;
        }

        .wa-unread-badge {
            background-color: #25d366;
            color: #ffffff;
            font-size: 11px;
            font-weight: bold;
            min-width: 19px;
            height: 19px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 5px;
            flex-shrink: 0;
        }

        /* RIGHT CHAT AREA */
        .wa-chat-area {
            flex: 1;
            display: flex;
            flex-direction: column;
            background-color: #efeae2;
            background-image: url('https://user-images.githubusercontent.com/15075759/28719144-86dc0f70-73b1-11e7-911d-60d70fcded21.png');
            background-repeat: repeat;
            position: relative;
        }

        /* Splash Screen (Default - no selected chat) */
        .wa-splash {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            background-color: #f8f9fa;
            text-align: center;
            padding: 40px;
            color: #667781;
        }

        .wa-splash i {
            font-size: 80px;
            color: #cbd5e1;
            margin-bottom: 20px;
        }

        .wa-splash h4 {
            font-weight: 300;
            color: #41525d;
            margin-bottom: 10px;
        }

        /* Chat Header */
        .wa-chat-header {
            display: flex;
            align-items: center;
            padding: 10px 20px;
            background-color: #f0f2f5;
            border-bottom: 1px solid #e9ecef;
            z-index: 5;
        }

        .wa-chat-header-info {
            flex: 1;
        }

        .wa-chat-header-name {
            font-size: 15.5px;
            font-weight: 600;
            color: #111b21;
            margin-bottom: 1px;
        }

        .wa-chat-header-status {
            font-size: 12px;
            color: #667781;
        }

        /* Message Thread */
        .wa-messages-thread {
            flex: 1;
            overflow-y: auto;
            padding: 20px 30px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .wa-bubble {
            max-width: 60%;
            padding: 7px 11px;
            border-radius: 8px;
            font-size: 14.5px;
            line-height: 1.4;
            position: relative;
            box-shadow: 0 1px 1px rgba(0,0,0,0.08);
            word-wrap: break-word;
        }

        .wa-bubble.sender-admin {
            background-color: #d9fdd3;
            align-self: flex-end;
            border-top-right-radius: 0;
            color: #111b21;
        }

        .wa-bubble.sender-user {
            background-color: #ffffff;
            align-self: flex-start;
            border-top-left-radius: 0;
            color: #111b21;
        }

        .wa-bubble-meta {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 4px;
            font-size: 11px;
            color: #667781;
            margin-top: 3px;
        }

        .wa-bubble-meta .ticks {
            font-size: 13px;
        }

        .wa-bubble-meta .ticks.read {
            color: #53bdeb;
        }

        .wa-bubble-meta .ticks.unread {
            color: #8696a0;
        }

        /* Attachment Container in bubbles */
        .wa-attachment-box {
            margin-bottom: 6px;
            border-radius: 6px;
            overflow: hidden;
            background-color: rgba(0,0,0,0.02);
        }

        .wa-attachment-img {
            max-width: 100%;
            max-height: 250px;
            display: block;
            object-fit: cover;
        }

        .wa-attachment-file {
            display: flex;
            align-items: center;
            padding: 8px 12px;
            gap: 10px;
            background: rgba(0,0,0,0.04);
            border-radius: 4px;
            text-decoration: none;
            color: #111b21;
        }

        .wa-attachment-file i {
            font-size: 22px;
            color: #008069;
        }

        /* Input area */
        .wa-input-panel {
            background-color: #f0f2f5;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-top: 1px solid #e9ecef;
            z-index: 5;
        }

        .wa-input-panel form {
            display: flex;
            width: 100%;
            align-items: center;
            gap: 12px;
            margin: 0;
        }

        .wa-field {
            flex: 1;
            background-color: #ffffff;
            border: none;
            outline: none;
            border-radius: 20px;
            padding: 9px 18px;
            font-size: 14.5px;
            box-shadow: 0 1px 1px rgba(0,0,0,0.05);
            height: 40px;
        }

        .wa-icon-btn {
            background: none;
            border: none;
            color: #54656f;
            cursor: pointer;
            font-size: 21px;
            padding: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.2s;
        }

        .wa-icon-btn:hover {
            color: #008069;
        }

        .wa-circle-send {
            background-color: #008069;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: none;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .wa-circle-send:hover {
            background-color: #006e5a;
        }

        /* Preview Panel */
        .wa-preview-bar {
            background-color: #e2e8f0;
            padding: 8px 25px;
            display: none;
            align-items: center;
            justify-content: space-between;
            font-size: 13.5px;
            color: #475569;
            border-top: 1px solid #cbd5e1;
            z-index: 6;
        }

        .wa-preview-bar span {
            display: flex;
            align-items: center;
            gap: 8px;
        }
    </style>

    <div class="wa-chat-wrapper">
        <!-- Sidebar: User List -->
        <div class="wa-sidebar">
            <div class="wa-sidebar-header">
                <h5>Chats</h5>
            </div>
            <div class="wa-search-container">
                <div class="wa-search-box">
                    <i class="fa fa-search"></i>
                    <input type="text" id="waSearchInput" placeholder="Search or start new chat" autocomplete="off">
                </div>
            </div>
            <div class="wa-user-list" id="waUserList">
                <!-- User rows will be loaded dynamically -->
                <div class="text-center my-5 text-muted">
                    <div class="spinner-border spinner-border-sm text-primary mb-2" role="status"></div>
                    <div>Loading conversations...</div>
                </div>
            </div>
        </div>

        <!-- Chat Main Area -->
        <div class="wa-chat-area">
            <!-- Default Splash Screen -->
            <div class="wa-splash" id="waSplashScreen">
                <i class="fa-regular fa-comments"></i>
                <h4>WhatsApp Support Console</h4>
                <p class="text-muted" style="max-width: 400px; font-size:14px;">
                    Select a customer from the left sidebar to start messaging. You can view their full message history, send replies, and attach files or screenshots.
                </p>
            </div>

            <!-- Active Chat Box -->
            <div id="waActiveChatBox" style="display: none; flex-direction: column; height: 100%;">
                <!-- Chat Header -->
                <div class="wa-chat-header">
                    <div class="wa-user-avatar" id="activeChatAvatar">U</div>
                    <div class="wa-chat-header-info">
                        <div class="wa-chat-header-name" id="activeChatName">Customer Name</div>
                        <div class="wa-chat-header-status"><span class="wa-unread-badge" style="display: inline-block; width: 6px; height: 6px; padding:0; min-width:0; background-color: #25d366; margin-right:5px; vertical-align: middle;"></span>Customer User</div>
                    </div>
                </div>

                <!-- Messages Thread -->
                <div class="wa-messages-thread" id="waMessagesThread">
                    <!-- Messages populated via AJAX -->
                </div>

                <!-- File upload preview -->
                <div class="wa-preview-bar" id="waPreviewBar">
                    <span><i class="fa fa-paperclip"></i> Attachment: <strong id="waFileName">file.png</strong></span>
                    <button type="button" class="btn btn-sm btn-link text-danger" id="waCancelFileBtn"><i class="fa fa-times"></i> Remove</button>
                </div>

                <!-- Chat Input Panel -->
                <div class="wa-input-panel">
                    <form id="waChatForm" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="user_id" id="waSelectedUserId">
                        <input type="file" id="waFileInput" name="file" style="display: none;">
                        
                        <button type="button" class="wa-icon-btn" id="waAttachBtn" title="Attach file or image">
                            <i class="fa fa-paperclip"></i>
                        </button>
                        
                        <input type="text" id="waMessageInput" name="message" class="wa-field" placeholder="Type a message" autocomplete="off">
                        
                        <button type="submit" class="wa-circle-send" id="waSendBtn">
                            <i class="fa-solid fa-paper-plane" style="font-size: 14px;"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            const waUserList = $('#waUserList');
            const waSearchInput = $('#waSearchInput');
            const waSplashScreen = $('#waSplashScreen');
            const waActiveChatBox = $('#waActiveChatBox');
            const activeChatName = $('#activeChatName');
            const activeChatAvatar = $('#activeChatAvatar');
            const waMessagesThread = $('#waMessagesThread');
            const waChatForm = $('#waChatForm');
            const waSelectedUserId = $('#waSelectedUserId');
            const waFileInput = $('#waFileInput');
            const waAttachBtn = $('#waAttachBtn');
            const waMessageInput = $('#waMessageInput');
            const waPreviewBar = $('#waPreviewBar');
            const waFileName = $('#waFileName');
            const waCancelFileBtn = $('#waCancelFileBtn');

            let currentSelectedUserId = "{{ $selectedUserId ?? '' }}";
            let lastMessageId = 0;
            let listPollInterval;
            let threadPollInterval;

            // Trigger file click
            waAttachBtn.click(function() {
                waFileInput.click();
            });

            // Show preview of selected file
            waFileInput.change(function(e) {
                if (e.target.files && e.target.files.length > 0) {
                    const file = e.target.files[0];
                    waFileName.text(file.name);
                    waPreviewBar.css('display', 'flex');
                }
            });

            // Cancel upload
            waCancelFileBtn.click(function() {
                waFileInput.val('');
                waPreviewBar.hide();
            });

            // Scroll thread to bottom
            function scrollThreadToBottom() {
                waMessagesThread.scrollTop(waMessagesThread[0].scrollHeight);
            }

            // Load contact list
            function loadUserList() {
                const searchVal = waSearchInput.val().trim();
                $.ajax({
                    url: "{{ route('admin.chat.users') }}",
                    method: 'GET',
                    data: { search: searchVal },
                    success: function(response) {
                        if (response.success) {
                            let html = '';
                            if (response.users.length === 0) {
                                html = '<div class="text-center my-5 text-muted small">No active conversations found</div>';
                            } else {
                                response.users.forEach(function(user) {
                                    const isActive = user.id == currentSelectedUserId;
                                    const timeStr = user.last_message_time ? formatRelativeTime(user.last_message_time) : '';
                                    const unreadBadge = user.unread_count > 0 
                                        ? `<span class="wa-unread-badge">${user.unread_count}</span>` 
                                        : '';
                                    
                                    // Initials for avatar
                                    const initials = user.name.split(' ').map(n => n[0]).slice(0, 2).join('').toUpperCase();
                                    const avatarHtml = user.avatar 
                                        ? `<img src="${user.avatar}" class="wa-user-avatar" alt="Avatar">`
                                        : `<div class="wa-user-avatar">${initials}</div>`;

                                    html += `
                                        <div class="wa-user-item ${isActive ? 'active' : ''}" data-id="${user.id}" data-name="${escapeHtml(user.name)}" data-initials="${initials}" data-avatar="${user.avatar || ''}">
                                            ${avatarHtml}
                                            <div class="wa-user-info">
                                                <div class="wa-user-meta">
                                                    <h6 class="wa-user-name">${escapeHtml(user.name)}</h6>
                                                    <span class="wa-msg-time">${timeStr}</span>
                                                </div>
                                                <div class="wa-user-last-msg">
                                                    <p class="wa-msg-snippet">${escapeHtml(user.last_message)}</p>
                                                    ${unreadBadge}
                                                </div>
                                            </div>
                                        </div>`;
                                });
                            }
                            waUserList.html(html);

                            // Bind item click
                            $('.wa-user-item').click(function() {
                                const userId = $(this).data('id');
                                const userName = $(this).data('name');
                                const initials = $(this).data('initials');
                                const avatar = $(this).data('avatar');
                                selectUserChat(userId, userName, initials, avatar);
                            });
                        }
                    },
                    error: function(err) {
                        console.error("Error loading chat user list", err);
                    }
                });
            }

            // Select a chat
            function selectUserChat(userId, name, initials, avatar) {
                currentSelectedUserId = userId;
                waSelectedUserId.val(userId);
                
                // Toggle active class in list
                $('.wa-user-item').removeClass('active');
                $(`.wa-user-item[data-id="${userId}"]`).addClass('active');

                // Setup header
                activeChatName.text(name);
                if (avatar) {
                    activeChatAvatar.replaceWith(`<img src="${avatar}" class="wa-user-avatar" id="activeChatAvatar" alt="Avatar">`);
                } else {
                    $('#activeChatAvatar').replaceWith(`<div class="wa-user-avatar" id="activeChatAvatar">${initials}</div>`);
                }

                // Show thread, hide splash
                waSplashScreen.hide();
                waActiveChatBox.css('display', 'flex');

                lastMessageId = 0;
                
                // Load thread messages immediately
                loadMessagesThread();

                // Setup or restart thread polling
                clearInterval(threadPollInterval);
                threadPollInterval = setInterval(loadMessagesThread, 3000);
            }

            // Load thread messages
            function loadMessagesThread() {
                if (!currentSelectedUserId) return;
                
                $.ajax({
                    url: `/admin/chat/messages/${currentSelectedUserId}`,
                    method: 'GET',
                    success: function(response) {
                        if (response.success) {
                            let html = '';
                            let newLastId = 0;

                            response.messages.forEach(function(msg) {
                                newLastId = msg.id;
                                const isAdmin = msg.sender_type === 'admin';
                                const timeStr = formatTime(msg.created_at);
                                const isReadTick = msg.is_read 
                                    ? '<i class="las la-check-double text-info"></i>' 
                                    : '<i class="las la-check"></i>';

                                html += `<div class="wa-bubble sender-${msg.sender_type}">`;
                                
                                // File Attachment
                                if (msg.file_path) {
                                    const fullUrl = '/' + msg.file_path;
                                    if (msg.file_type === 'image') {
                                        html += `
                                            <div class="wa-attachment-box">
                                                <a href="${fullUrl}" target="_blank">
                                                    <img src="${fullUrl}" class="wa-attachment-img" alt="Attachment">
                                                </a>
                                            </div>`;
                                    } else {
                                        html += `
                                            <div class="wa-attachment-box">
                                                <a href="${fullUrl}" target="_blank" class="wa-attachment-file">
                                                    <i class="fa-regular fa-file-pdf"></i>
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
                                    <div class="wa-bubble-meta">
                                        <span>${timeStr}</span>
                                        ${isAdmin ? `<span class="ticks">${isReadTick}</span>` : ''}
                                    </div>
                                </div>`;
                            });

                            waMessagesThread.html(html);

                            // Scroll to bottom if new messages loaded
                            if (newLastId !== lastMessageId) {
                                lastMessageId = newLastId;
                                scrollThreadToBottom();
                                // Refresh contact list to clear unread bubble
                                loadUserList();
                            }
                        }
                    },
                    error: function(err) {
                        console.error("Error loading chat messages thread", err);
                    }
                });
            }

            // Send message
            waChatForm.submit(function(e) {
                e.preventDefault();
                const msgText = waMessageInput.val().trim();
                const fileSelected = waFileInput[0].files.length > 0;

                if (!msgText && !fileSelected) return;

                const formData = new FormData(this);
                
                // Clear input fields immediately for response feel
                waMessageInput.val('');
                waFileInput.val('');
                waPreviewBar.hide();

                $.ajax({
                    url: "{{ route('admin.chat.send') }}",
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            loadMessagesThread();
                            loadUserList(); // trigger immediate list bubble updates
                        }
                    },
                    error: function(err) {
                        alert("Error sending response message.");
                        console.error(err);
                    }
                });
            });

            // Search filtering helper
            let searchTimeout;
            waSearchInput.on('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(loadUserList, 400); // Debounce input
            });

            // Initial configurations
            loadUserList();
            listPollInterval = setInterval(loadUserList, 4000);

            // Check if user was selected from tickets page
            if (currentSelectedUserId) {
                // Fetch details of user to open conversation immediately
                $.ajax({
                    url: "{{ route('admin.chat.users') }}",
                    method: 'GET',
                    success: function(response) {
                        if (response.success) {
                            const matchedUser = response.users.find(u => u.id == currentSelectedUserId);
                            if (matchedUser) {
                                const initials = matchedUser.name.split(' ').map(n => n[0]).slice(0, 2).join('').toUpperCase();
                                selectUserChat(matchedUser.id, matchedUser.name, initials, matchedUser.avatar);
                            }
                        }
                    }
                });
            }

            // Pause polling when tab is inactive to save system load
            $(window).focus(function() {
                if (!listPollInterval) {
                    loadUserList();
                    listPollInterval = setInterval(loadUserList, 4000);
                }
                if (currentSelectedUserId && !threadPollInterval) {
                    loadMessagesThread();
                    threadPollInterval = setInterval(loadMessagesThread, 3000);
                }
            });

            $(window).blur(function() {
                clearInterval(listPollInterval);
                clearInterval(threadPollInterval);
                listPollInterval = null;
                threadPollInterval = null;
            });

            // Helpers: relative time for contact list
            function formatRelativeTime(isoStr) {
                const date = new Date(isoStr);
                const now = new Date();
                const diffTime = Math.abs(now - date);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                
                if (diffTime < 60000) { // under a minute
                    return 'Just now';
                }
                if (diffTime < 3600000) { // under an hour
                    const mins = Math.floor(diffTime / 60000);
                    return mins + 'm ago';
                }
                if (date.toDateString() === now.toDateString()) {
                    return formatTime(isoStr);
                }
                if (diffDays <= 2) {
                    return 'Yesterday';
                }
                return date.toLocaleDateString(undefined, {month: 'short', day: 'numeric'});
            }

            // Helper format time
            function formatTime(isoStr) {
                const date = new Date(isoStr);
                let hours = date.getHours();
                let minutes = date.getMinutes();
                const ampm = hours >= 12 ? 'PM' : 'AM';
                hours = hours % 12;
                hours = hours ? hours : 12;
                minutes = minutes < 10 ? '0' + minutes : minutes;
                return hours + ':' + minutes + ' ' + ampm;
            }

            // Helper to escape HTML characters
            function escapeHtml(text) {
                if (!text) return '';
                return text
                    .replace(/&/g, "&amp;")
                    .replace(/</g, "&lt;")
                    .replace(/>/g, "&gt;")
                    .replace(/"/g, "&quot;")
                    .replace(/'/g, "&#039;");
            }
        });
    </script>
@endsection
