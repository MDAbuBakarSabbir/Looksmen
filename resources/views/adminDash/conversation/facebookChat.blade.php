@extends('layouts.Backend.master')

@section('title','Facebook & Instagram Conversations')
@section('content')
<!-- Import modern fonts -->
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
    /* Design Variables */
    :root {
        --fb-blue: #0084ff;
        --fb-gradient-start: #006aff;
        --fb-gradient-end: #00b2ff;
        --fb-hover: #f2f3f5;
        --fb-active: #e7f3ff;
        --fb-border: #e4e6eb;
        --fb-bg-secondary: #f0f2f5;
        --fb-text-primary: #050505;
        --fb-text-secondary: #65676b;
        --fb-bubble-in: #e4e6eb;
        --fb-bubble-out-text: #ffffff;
        
        /* Instagram styling tokens */
        --ig-gradient: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);
        --ig-bubble-out: linear-gradient(135deg, #3897f0, #bc1888);
        --ig-bubble-in: #efefef;
        --ig-accent: #bc1888;
    }

    .fb-chat-wrapper {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background: #f0f2f5;
        padding: 15px 0;
    }

    .fb-container {
        display: flex;
        flex-direction: column;
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        border: 1px solid var(--fb-border);
        height: 82vh;
    }

    /* Tab Switcher Headers */
    .fb-tabs-header {
        background: #ffffff;
        border-bottom: 1px solid var(--fb-border);
        display: flex;
        padding: 12px 24px;
        align-items: center;
        gap: 16px;
        z-index: 20;
    }

    .fb-tab-btn {
        padding: 10px 20px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 14px;
        border: none;
        background: var(--fb-bg-secondary);
        color: var(--fb-text-secondary);
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.25s ease;
    }

    .fb-tab-btn:hover {
        background: var(--fb-border);
        color: var(--fb-text-primary);
    }

    .fb-tab-btn.active {
        background: var(--fb-active);
        color: var(--fb-blue);
    }
    
    .fb-tab-btn.ig-tab.active {
        background: #fdf0f5;
        color: var(--ig-accent);
    }

    .fb-tab-content {
        flex: 1;
        display: none;
        overflow: hidden;
    }

    .fb-tab-content.active {
        display: flex;
    }

    /* Sidebars */
    .msger-sidebar {
        width: 360px;
        border-right: 1px solid var(--fb-border);
        display: flex;
        flex-direction: column;
        background: #ffffff;
        flex-shrink: 0;
    }

    .msger-sidebar-header {
        padding: 20px 24px;
        display: flex;
        flex-direction: column;
        gap: 12px;
        border-bottom: 1px solid var(--fb-border);
    }

    .msger-title {
        font-size: 22px;
        font-weight: 800;
        color: var(--fb-text-primary);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .msger-title i {
        background: linear-gradient(135deg, var(--fb-gradient-start), var(--fb-gradient-end));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .ig-title-style i {
        background: var(--ig-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .msger-search {
        position: relative;
    }

    .msger-search input {
        width: 100%;
        padding: 10px 16px 10px 42px;
        background: var(--fb-bg-secondary);
        border: 1px solid transparent;
        border-radius: 20px;
        font-size: 14px;
        outline: none;
        transition: all 0.3s ease;
    }

    .msger-search input:focus {
        background: #ffffff;
        border-color: var(--fb-blue);
        box-shadow: 0 0 0 3px rgba(0, 132, 255, 0.15);
    }
    
    .ig-search input:focus {
        border-color: var(--ig-accent);
        box-shadow: 0 0 0 3px rgba(220, 39, 67, 0.15);
    }

    .msger-search i {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--fb-text-secondary);
    }

    .msger-list {
        overflow-y: auto;
        flex: 1;
        margin: 0;
        padding: 8px 0;
        list-style: none;
    }

    .msger-item {
        padding: 12px 20px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 14px;
        transition: all 0.2s ease;
        position: relative;
    }

    .msger-item:hover {
        background: var(--fb-hover);
    }

    .msger-item.active {
        background: var(--fb-active);
    }

    .msger-item.active.ig-active-bg {
        background: #faf0f5;
    }

    /* Avatars */
    .avatar-frame {
        position: relative;
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: var(--fb-bg-secondary);
        display: flex;
        justify-content: center;
        align-items: center;
        font-weight: 700;
        color: var(--fb-blue);
        font-size: 18px;
        border: 1px solid var(--fb-border);
        flex-shrink: 0;
        text-transform: uppercase;
    }
    
    /* Instagram story active ring styling */
    .avatar-frame.ig-story-active {
        border: 2px solid transparent;
        background-image: linear-gradient(#fff, #fff), var(--ig-gradient);
        background-origin: border-box;
        background-clip: content-box, border-box;
        color: var(--ig-accent);
    }

    .avatar-badge {
        position: absolute;
        bottom: 1px;
        right: 1px;
        width: 13px;
        height: 13px;
        background: #31a24c;
        border: 2px solid #ffffff;
        border-radius: 50%;
    }

    .msger-details {
        flex: 1;
        min-width: 0;
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .msger-meta-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .msger-name {
        font-weight: 600;
        font-size: 15px;
        color: var(--fb-text-primary);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .msger-time {
        font-size: 12px;
        color: var(--fb-text-secondary);
    }

    .msger-preview-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 8px;
    }

    .msger-preview {
        font-size: 13px;
        color: var(--fb-text-secondary);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        flex: 1;
    }

    .unread-indicator {
        width: 9px;
        height: 9px;
        background: var(--fb-blue);
        border-radius: 50%;
        flex-shrink: 0;
    }
    
    .ig-unread {
        background: var(--ig-accent);
    }

    /* Messenger Chat Frame */
    .msger-chat {
        flex: 1;
        display: flex;
        flex-direction: column;
        background: #ffffff;
    }

    .msger-chat-header {
        padding: 16px 24px;
        border-bottom: 1px solid var(--fb-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        z-index: 10;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    }

    .msger-header-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .msger-header-name {
        font-weight: 700;
        font-size: 16px;
        color: var(--fb-text-primary);
    }

    .msger-header-status {
        font-size: 12px;
        color: var(--fb-text-secondary);
    }

    .msger-header-actions {
        display: flex;
        align-items: center;
        gap: 16px;
        color: var(--fb-blue);
        font-size: 18px;
    }

    .msger-header-actions i {
        cursor: pointer;
        padding: 8px;
        border-radius: 50%;
        transition: background 0.2s;
    }

    .msger-header-actions i:hover {
        background: var(--fb-bg-secondary);
    }
    
    .ig-header-theme {
        color: var(--ig-accent);
    }

    .msger-chat-messages {
        flex: 1;
        padding: 24px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    /* Messenger Message Bubbles */
    .msger-bubble {
        max-width: 60%;
        padding: 10px 16px;
        border-radius: 18px;
        position: relative;
        font-size: 14.5px;
        line-height: 1.4;
        word-wrap: break-word;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
        animation: bubbleSlideUp 0.2s ease-out;
    }

    @keyframes bubbleSlideUp {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .msger-bubble.inbound {
        background: var(--fb-bubble-in);
        align-self: flex-start;
        color: var(--fb-text-primary);
        border-top-left-radius: 4px;
    }

    .msger-bubble.outbound {
        background: linear-gradient(135deg, var(--fb-gradient-start), var(--fb-gradient-end));
        align-self: flex-end;
        color: var(--fb-bubble-out-text);
        border-top-right-radius: 4px;
    }
    
    /* Instagram specific bubble style */
    .msger-bubble.ig-inbound {
        background: var(--ig-bubble-in);
        align-self: flex-start;
        color: var(--fb-text-primary);
        border-top-left-radius: 4px;
    }
    
    .msger-bubble.ig-outbound {
        background: var(--ig-bubble-out);
        align-self: flex-end;
        color: #ffffff;
        border-top-right-radius: 4px;
    }

    /* Send/View indicators */
    .msger-status-row {
        align-self: flex-end;
        display: flex;
        justify-content: flex-end;
        margin-top: -12px;
        margin-bottom: 4px;
        padding-right: 2px;
    }

    .msger-indicator {
        width: 16px;
        height: 16px;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 8px;
        flex-shrink: 0;
    }

    .msger-indicator.sent {
        border: 1px solid var(--fb-text-secondary);
        color: var(--fb-text-secondary);
    }

    .msger-indicator.delivered {
        background: var(--fb-text-secondary);
        color: #ffffff;
    }

    .msger-indicator.read-avatar {
        width: 15px;
        height: 15px;
        background: #e3f2fd;
        border: 1px solid var(--fb-border);
        color: var(--fb-blue);
        font-size: 8px;
        font-weight: 700;
        text-transform: uppercase;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    /* IG Seen Status Text style */
    .ig-seen-text {
        align-self: flex-end;
        font-size: 11px;
        color: var(--fb-text-secondary);
        margin-top: -10px;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    
    .ig-seen-text .seen-avatar {
        width: 14px;
        height: 14px;
        border-radius: 50%;
        background: #fce4ec;
        color: var(--ig-accent);
        font-size: 7px;
        display: flex;
        justify-content: center;
        align-items: center;
        font-weight: 700;
    }

    .msger-chat-input-area {
        padding: 16px 24px;
        display: flex;
        gap: 16px;
        align-items: center;
        border-top: 1px solid var(--fb-border);
        background: #ffffff;
    }

    .msger-chat-input-area i {
        font-size: 20px;
        color: var(--fb-blue);
        cursor: pointer;
        padding: 8px;
        border-radius: 50%;
        transition: background 0.2s;
    }

    .msger-chat-input-area i:hover {
        background: var(--fb-bg-secondary);
    }
    
    .ig-input-icons i {
        color: var(--fb-text-primary);
    }
    
    .ig-input-icons i:hover {
        color: var(--ig-accent);
    }

    .msger-input-wrapper {
        flex: 1;
        position: relative;
    }

    .msger-input-wrapper input {
        width: 100%;
        padding: 12px 42px 12px 20px;
        background: var(--fb-bg-secondary);
        border: 1px solid transparent;
        border-radius: 24px;
        outline: none;
        font-size: 15px;
        transition: all 0.3s ease;
    }

    .msger-input-wrapper input:focus {
        background: #ffffff;
        border-color: var(--fb-border);
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
    }

    .msger-input-wrapper .emoji-trigger {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--fb-blue);
        cursor: pointer;
        font-size: 18px;
    }
    
    .msger-input-wrapper .ig-emoji-color {
        color: var(--fb-text-secondary);
    }

    .msger-send-btn {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: transparent;
        border: none;
        display: flex;
        justify-content: center;
        align-items: center;
        color: var(--fb-blue);
        cursor: pointer;
        font-size: 20px;
        transition: background 0.2s;
    }

    .msger-send-btn:hover {
        background: var(--fb-bg-secondary);
    }

    /* Comments Moderation View Styles */
    .comments-view {
        flex: 1;
        display: flex;
        overflow: hidden;
    }

    .posts-sidebar {
        width: 320px;
        border-right: 1px solid var(--fb-border);
        background: #ffffff;
        display: flex;
        flex-direction: column;
        flex-shrink: 0;
    }

    .posts-sidebar-title {
        padding: 20px 24px;
        font-size: 18px;
        font-weight: 700;
        color: var(--fb-text-primary);
        border-bottom: 1px solid var(--fb-border);
        margin: 0;
    }

    .posts-list {
        overflow-y: auto;
        flex: 1;
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .post-item {
        padding: 16px 20px;
        cursor: pointer;
        display: flex;
        gap: 12px;
        border-bottom: 1px solid var(--fb-border);
        transition: background 0.2s;
    }

    .post-item:hover {
        background: var(--fb-hover);
    }

    .post-item.active {
        background: var(--fb-active);
    }

    .post-thumbnail {
        width: 60px;
        height: 60px;
        border-radius: 8px;
        background: var(--fb-bg-secondary);
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 20px;
        color: var(--fb-text-secondary);
        flex-shrink: 0;
    }

    .post-details {
        flex: 1;
        min-width: 0;
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .post-text {
        font-size: 13.5px;
        font-weight: 500;
        color: var(--fb-text-primary);
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .post-stats {
        font-size: 11px;
        color: var(--fb-text-secondary);
        display: flex;
        gap: 8px;
    }

    /* Comments Moderation Panel */
    .comments-panel {
        flex: 1;
        display: flex;
        flex-direction: column;
        background: #ffffff;
    }

    .comments-panel-header {
        padding: 18px 24px;
        border-bottom: 1px solid var(--fb-border);
        font-weight: 700;
        font-size: 16px;
        color: var(--fb-text-primary);
    }

    .comments-thread-area {
        flex: 1;
        padding: 24px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    /* Nested Comment Cards */
    .comment-card {
        display: flex;
        gap: 12px;
    }

    .comment-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: var(--fb-bg-secondary);
        display: flex;
        justify-content: center;
        align-items: center;
        font-weight: 700;
        color: var(--fb-blue);
        font-size: 14px;
        flex-shrink: 0;
    }

    .comment-bubble-wrapper {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .comment-bubble {
        background: var(--fb-bg-secondary);
        padding: 10px 14px;
        border-radius: 18px;
        display: inline-block;
        max-width: 80%;
    }

    .comment-user-name {
        font-weight: 700;
        font-size: 13.5px;
        color: var(--fb-text-primary);
        margin-bottom: 2px;
    }

    .comment-text {
        font-size: 13.5px;
        color: var(--fb-text-primary);
        line-height: 1.4;
    }

    .comment-actions {
        display: flex;
        gap: 16px;
        font-size: 11.5px;
        color: var(--fb-text-secondary);
        padding-left: 8px;
    }

    .comment-actions span {
        cursor: pointer;
        font-weight: 600;
    }

    .comment-actions span:hover {
        text-decoration: underline;
    }

    .reply-thread {
        margin-left: 48px;
        display: flex;
        flex-direction: column;
        gap: 16px;
        margin-top: 12px;
        border-left: 2px solid var(--fb-border);
        padding-left: 16px;
    }

    /* Comment Box Footer */
    .comment-footer-input {
        padding: 16px 24px;
        border-top: 1px solid var(--fb-border);
        display: flex;
        gap: 12px;
        align-items: center;
    }

    .comment-footer-input input {
        flex: 1;
        padding: 10px 18px;
        background: var(--fb-bg-secondary);
        border: 1px solid transparent;
        border-radius: 20px;
        outline: none;
        font-size: 14px;
    }

    .comment-footer-input input:focus {
        background: #ffffff;
        border-color: var(--fb-blue);
    }

    /* Empty states */
    .fb-empty-state {
        flex: 1;
        display: flex;
        justify-content: center;
        align-items: center;
        color: var(--fb-text-secondary);
        flex-direction: column;
        background: #f8f9fa;
        text-align: center;
        padding: 40px;
    }

    .fb-empty-state-icon {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: #e7f3ff;
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 20px;
        color: var(--fb-blue);
    }
    
    .ig-empty-icon {
        background: #fdf0f5;
        color: var(--ig-accent);
    }

    .fb-empty-state-icon i {
        font-size: 44px;
    }
</style>

<div class="page-wrapper fb-chat-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="fb-container">
                    
                    <!-- Tab Headers -->
                    <div class="fb-tabs-header">
                        <button class="fb-tab-btn active" id="tab-btn-messenger" onclick="switchTab('messenger')">
                            <i class="fab fa-facebook-messenger"></i> Messenger
                        </button>
                        <button class="fb-tab-btn" id="tab-btn-comments" onclick="switchTab('comments')">
                            <i class="fab fa-facebook"></i> Post Comments
                        </button>
                        <button class="fb-tab-btn ig-tab" id="tab-btn-instagram" onclick="switchTab('instagram')">
                            <i class="fab fa-instagram"></i> Instagram DMs
                        </button>
                    </div>

                    <!-- TAB 1: MESSENGER CHAT -->
                    <div class="fb-tab-content active" id="tab-messenger">
                        <!-- Sidebar -->
                        <div class="msger-sidebar">
                            <div class="msger-sidebar-header">
                                <h4 class="msger-title">
                                    <i class="fab fa-facebook-messenger"></i> Messenger
                                </h4>
                                <div class="msger-search">
                                    <i class="fa fa-search"></i>
                                    <input type="text" id="contact-search" placeholder="Search Messenger..." autocomplete="off">
                                </div>
                            </div>
                            <ul class="msger-list" id="contact-list">
                                <!-- Loaded dynamically via JS -->
                            </ul>
                        </div>

                        <!-- Chat Area -->
                        <div class="msger-chat" id="chat-frame" style="display: none;">
                            <div class="msger-chat-header">
                                <div class="msger-header-info">
                                    <div class="avatar-frame" id="active-avatar">W</div>
                                    <div>
                                        <div class="msger-header-name" id="active-contact-name">Active User</div>
                                        <div class="msger-header-status" id="active-status">Active now</div>
                                    </div>
                                </div>
                                <div class="msger-header-actions">
                                    <i class="fa fa-phone"></i>
                                    <i class="fa fa-video"></i>
                                    <i class="fa fa-circle-info"></i>
                                </div>
                            </div>
                            <div class="msger-chat-messages" id="chat-messages">
                                <!-- Dynamically loaded -->
                            </div>
                            <div class="msger-chat-input-area">
                                <i class="fa fa-plus-circle"></i>
                                <i class="fa fa-image"></i>
                                <i class="fa fa-microphone"></i>
                                <div class="msger-input-wrapper">
                                    <input type="text" id="message-input" placeholder="Aa" autocomplete="off" onkeypress="handleKeyPress(event)">
                                    <span class="emoji-trigger"><i class="far fa-smile"></i></span>
                                </div>
                                <button class="msger-send-btn" id="send-btn" onclick="sendMessage()"><i class="fa fa-paper-plane"></i></button>
                            </div>
                        </div>

                        <!-- Empty State -->
                        <div class="fb-empty-state" id="messenger-empty-state">
                            <div class="fb-empty-state-icon">
                                <i class="fab fa-facebook-messenger"></i>
                            </div>
                            <h3>Messenger Inbox</h3>
                            <p class="text-muted">Select a customer conversation from the list to start messaging in real-time.</p>
                        </div>
                    </div>

                    <!-- TAB 2: COMMENTS VIEW -->
                    <div class="fb-tab-content" id="tab-comments">
                        <div class="comments-view">
                            <!-- Left Sidebar: Post List -->
                            <div class="posts-sidebar">
                                <h4 class="posts-sidebar-title">Facebook Posts</h4>
                                <ul class="posts-list" id="posts-list">
                                    <!-- Loaded via JS -->
                                </ul>
                            </div>

                            <!-- Right Content: Thread Moderation -->
                            <div class="comments-panel" id="comments-panel" style="display: none;">
                                <div class="comments-panel-header" id="active-post-title">
                                    Comments Thread
                                </div>
                                <div class="comments-thread-area" id="comments-thread">
                                    <!-- Comments go here -->
                                </div>
                                <div class="comment-footer-input">
                                    <input type="text" id="comment-input" placeholder="Write a public reply..." autocomplete="off">
                                    <button class="btn btn-primary btn-sm rounded-pill" onclick="postComment()"><i class="fa fa-reply"></i> Reply</button>
                                </div>
                            </div>

                            <!-- Comments Empty State -->
                            <div class="fb-empty-state" id="comments-empty-state" style="flex: 1;">
                                <div class="fb-empty-state-icon">
                                    <i class="fab fa-facebook"></i>
                                </div>
                                <h3>Post Comments Moderation</h3>
                                <p class="text-muted text-center">Select a Facebook Page post from the left sidebar to review and reply to customer comments.</p>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 3: INSTAGRAM DIRECT MESSAGES -->
                    <div class="fb-tab-content" id="tab-instagram">
                        <!-- Instagram Sidebar -->
                        <div class="msger-sidebar">
                            <div class="msger-sidebar-header">
                                <h4 class="msger-title ig-title-style">
                                    <i class="fab fa-instagram"></i> Instagram DMs
                                </h4>
                                <div class="msger-search ig-search">
                                    <i class="fa fa-search"></i>
                                    <input type="text" id="ig-contact-search" placeholder="Search DMs..." autocomplete="off">
                                </div>
                            </div>
                            <ul class="msger-list" id="ig-contact-list">
                                <!-- Loaded dynamically via JS -->
                            </ul>
                        </div>

                        <!-- Instagram Chat Pane -->
                        <div class="msger-chat" id="ig-chat-frame" style="display: none;">
                            <div class="msger-chat-header">
                                <div class="msger-header-info">
                                    <div class="avatar-frame ig-story-active" id="ig-active-avatar">IG</div>
                                    <div>
                                        <div class="msger-header-name" id="ig-active-contact-name">Instagram User</div>
                                        <div class="msger-header-status" id="ig-active-status">Active now</div>
                                    </div>
                                </div>
                                <div class="msger-header-actions ig-header-theme">
                                    <i class="fa fa-phone"></i>
                                    <i class="fa fa-video"></i>
                                    <i class="fa fa-circle-info"></i>
                                </div>
                            </div>
                            <div class="msger-chat-messages" id="ig-chat-messages">
                                <!-- Loaded dynamically -->
                            </div>
                            <div class="msger-chat-input-area">
                                <div class="ig-input-icons" style="display: flex; gap: 8px;">
                                    <i class="fa fa-camera"></i>
                                    <i class="fa fa-image"></i>
                                </div>
                                <div class="msger-input-wrapper">
                                    <input type="text" id="ig-message-input" placeholder="Message..." autocomplete="off" onkeypress="handleIgKeyPress(event)">
                                    <span class="emoji-trigger ig-emoji-color"><i class="far fa-smile"></i></span>
                                </div>
                                <div class="ig-input-icons" style="display: flex; gap: 4px; align-items: center;">
                                    <button class="msger-send-btn" id="ig-send-btn" onclick="sendIgMessage()"><i class="fa fa-paper-plane" style="color: var(--ig-accent);"></i></button>
                                    <i class="far fa-heart" id="ig-heart-btn" onclick="sendIgHeart()" style="font-size: 20px;"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Instagram Empty State -->
                        <div class="fb-empty-state" id="ig-empty-state">
                            <div class="fb-empty-state-icon ig-empty-icon">
                                <i class="fab fa-instagram"></i>
                            </div>
                            <h3>Instagram Direct</h3>
                            <p class="text-muted">Select an Instagram conversation from the list to start messaging.</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    let currentContactId = null;
    let currentPostId = null;
    let currentIgContactId = null;

    // Switch Tabs helper
    function switchTab(tabName) {
        $('.fb-tab-btn').removeClass('active');
        $('.fb-tab-content').removeClass('active');

        if(tabName === 'messenger') {
            $('#tab-btn-messenger').addClass('active');
            $('#tab-messenger').addClass('active');
        } else if(tabName === 'comments') {
            $('#tab-btn-comments').addClass('active');
            $('#tab-comments').addClass('active');
            loadPosts(); 
        } else if(tabName === 'instagram') {
            $('#tab-btn-instagram').addClass('active');
            $('#tab-instagram').addClass('active');
            renderIgContacts(mockIgContacts); // Load Instagram contacts
        }
    }

    // Helper: Generate initials
    function getInitials(name) {
        if (!name) return 'U';
        let parts = name.trim().split(' ');
        let initials = '';
        for (let i = 0; i < Math.min(parts.length, 2); i++) {
            if (parts[i].length > 0) initials += parts[i][0];
        }
        return initials.toUpperCase() || 'U';
    }

    // MOCK DATA - Messenger
    const mockContacts = [
        { id: 101, name: "Tanvir Rahman", phone: "Messenger Chat", unread: 2, last_msg: "Hi, is this product still available?", time: "2:31 PM", active: true },
        { id: 102, name: "Nabila Karim", phone: "Messenger Chat", unread: 0, last_msg: "Thank you so much! Received the delivery.", time: "11:15 AM", active: false },
        { id: 103, name: "Sakib Al Hasan", phone: "Messenger Chat", unread: 0, last_msg: "Can I get this in Blue color?", time: "Yesterday", active: true }
    ];

    const mockMessages = {
        101: [
            { id: 1, body: "Hello, welcome to Looksmen! How can we help you today?", direction: "outbound", status: "read", created_at: "2:28 PM" },
            { id: 2, body: "Hi, is this product still available?", direction: "inbound", status: "received", created_at: "2:31 PM" }
        ],
        102: [
            { id: 3, body: "Hi, receives the parcel. Thank you so much! Received the delivery.", direction: "inbound", status: "received", created_at: "11:15 AM" }
        ],
        103: [
            { id: 4, body: "Can I get this in Blue color?", direction: "inbound", status: "received", created_at: "Yesterday" }
        ]
    };

    // MOCK DATA - Instagram
    const mockIgContacts = [
        { id: 401, name: "sumaiya_islam", unread: 1, last_msg: "Loved your story! Is that shirt still in stock?", time: "1h ago", active: true, story: true },
        { id: 402, name: "raihan_ahmed22", unread: 0, last_msg: "Do you ship to Chittagong?", time: "4h ago", active: false, story: true },
        { id: 403, name: "lamia_farha", unread: 0, last_msg: "❤️ Sent a heart", time: "2 days ago", active: false, story: false }
    ];

    const mockIgMessages = {
        401: [
            { id: 11, body: "Hi sumaiya! Yes, that premium shirt is in stock. You can place your order.", direction: "ig-outbound", status: "seen", created_at: "Yesterday" },
            { id: 12, body: "Loved your story! Is that shirt still in stock?", direction: "ig-inbound", status: "received", created_at: "1h ago" }
        ],
        402: [
            { id: 13, body: "Do you ship to Chittagong?", direction: "ig-inbound", status: "received", created_at: "4h ago" }
        ],
        403: [
            { id: 14, body: "❤️", direction: "ig-inbound", status: "received", created_at: "2 days ago" }
        ]
    };

    // MOCK DATA - Facebook Comments
    const mockPosts = [
        { id: 201, text: "Check out our new premium denim shirts collection! 👕 Now live on website.", comments_count: 5, time: "Yesterday" },
        { id: 202, text: "Looksmen Eid Super Sale! Get up to 40% discount on all winter outfits.", comments_count: 12, time: "3 days ago" }
    ];

    const mockComments = {
        201: [
            { id: 301, user: "Abir Hossain", text: "Price detail please?", time: "5h ago", replies: [] },
            { id: 302, user: "Sultana Yasmin", text: "Do you have XL sizes?", time: "3h ago", replies: [
                { user: "Looksmen Support", text: "Yes Sultana! XL size is available. You can place your order now.", time: "2h ago" }
            ]}
        ],
        202: [
            { id: 303, user: "Maruf Khan", text: "Is delivery free in Dhaka?", time: "2 days ago", replies: [] }
        ]
    };

    // Document Init
    $(document).ready(function() {
        fetchContacts();

        // Listen for new messages globally to update the contact list
        window.Echo.channel('facebook-contacts')
            .listen('.facebook.message.new', (e) => {
                fetchContacts();
            });

        // Sidebar search filter (Messenger)
        $('#contact-search').on('input', function() {
            let val = $(this).val().toLowerCase();
            $('#contact-list .msger-item').each(function() {
                let name = $(this).find('.msger-name').text().toLowerCase();
                if (name.includes(val)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });

        // Sidebar search filter (Instagram)
        $('#ig-contact-search').on('input', function() {
            let val = $(this).val().toLowerCase();
            $('#ig-contact-list .msger-item').each(function() {
                let name = $(this).find('.msger-name').text().toLowerCase();
                if (name.includes(val)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });

        // Hybrid Fallback Polling for Facebook Messenger (similar to WhatsApp)
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

    // FETCH: Real Facebook Contacts from Database
    function fetchContacts() {
        $.get("{{ route('conversation.facebook.contacts') }}", function(data) {
            let html = '';
            let searchVal = $('#contact-search').val().toLowerCase();
            if(data.length === 0) {
                html = '<div class="text-center p-5 text-muted">No conversations yet</div>';
            } else {
                data.forEach(function(contact) {
                    let activeClass = (contact.id === currentContactId) ? 'active' : '';
                    let badgeClass = (contact.unread_count > 0) ? '' : 'd-none';
                    let name = contact.name || 'Facebook User';
                    let initials = getInitials(name);
                    
                    let isVisible = name.toLowerCase().includes(searchVal) || contact.sender_id.toLowerCase().includes(searchVal);
                    let displayStyle = isVisible ? '' : 'style="display: none;"';

                    html += `
                        <li class="msger-item ${activeClass}" ${displayStyle} onclick="openChat(${contact.id}, '${name}', true)">
                            <div class="avatar-frame">${initials}</div>
                            <div class="msger-details">
                                <div class="msger-meta-info">
                                    <span class="msger-name">${name}</span>
                                    <span class="msger-time">${contact.last_message_at ? new Date(contact.last_message_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) : ''}</span>
                                </div>
                                <div class="msger-preview-wrapper">
                                    <span class="msger-preview">ID: ${contact.sender_id}</span>
                                    <span class="unread-indicator ${badgeClass}">${contact.unread_count}</span>
                                </div>
                            </div>
                        </li>
                    `;
                });
            }
            $('#contact-list').html(html);
        }).fail(function() {
            $('#contact-list').html('<div class="text-center p-4 text-danger">Failed to load contacts.</div>');
        });
    }

    // OPEN: Facebook Chat Box
    function openChat(id, name, active) {
        // Leave old channel if any
        if (currentContactId) {
            window.Echo.leave('facebook-chat.' + currentContactId);
        }

        currentContactId = id;
        $('#messenger-empty-state').hide();
        $('#chat-frame').css('display', 'flex');
        $('#active-contact-name').text(name);
        $('#active-avatar').text(getInitials(name));
        $('#active-status').text(active ? "Active now" : "Offline");

        $('.msger-item').removeClass('active');
        fetchContacts(); // Update active class state

        fetchMessages(id, true);

        // Listen for real-time messages on specific chat channel
        window.Echo.channel('facebook-chat.' + id)
            .listen('.facebook.message.new', (e) => {
                appendMessage(e.message);
                fetchContacts(); // Clear unread count
            });
    }

    // FETCH: Real Facebook Messages from Database
    function fetchMessages(contactId, scrollToBottom) {
        $.get("{{ url('admin/conversation/facebook/messages') }}/" + contactId, function(data) {
            let html = '';
            data.messages.forEach(function(msg) {
                let time = new Date(msg.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                let bubbleClass = msg.direction === 'outbound' ? 'outbound' : 'inbound';
                
                html += `
                    <div class="msger-bubble ${bubbleClass}" data-message-id="${msg.message_id || ''}">
                        ${msg.body}
                    </div>
                `;

                if (msg.direction === 'outbound') {
                    let statusHtml = '';
                    if (msg.status === 'sent') {
                        statusHtml = `<div class="msger-indicator sent"><i class="fa fa-check"></i></div>`;
                    } else if (msg.status === 'delivered') {
                        statusHtml = `<div class="msger-indicator delivered"><i class="fa fa-check"></i></div>`;
                    } else if (msg.status === 'read') {
                        let initials = getInitials($('#active-contact-name').text());
                        statusHtml = `<div class="msger-indicator read-avatar">${initials}</div>`;
                    }
                    html += `<div class="msger-status-row">${statusHtml}</div>`;
                }
            });

            let chatBox = $('#chat-messages');
            let isAtBottom = chatBox.prop("scrollHeight") - chatBox.scrollTop() === chatBox.outerHeight();

            chatBox.html(html);

            if (scrollToBottom || isAtBottom) {
                chatBox.scrollTop(chatBox.prop("scrollHeight"));
            }
        });
    }

    // APPEND: Dynamically add message bubble in UI (real-time)
    function appendMessage(msg) {
        if (msg.message_id) {
            let existing = $(`[data-message-id="${msg.message_id}"]`);
            if (existing.length) {
                // If it exists, update checkmarks status
                let statusHtml = '';
                if (msg.status === 'sent') {
                    statusHtml = `<div class="msger-indicator sent"><i class="fa fa-check"></i></div>`;
                } else if (msg.status === 'delivered') {
                    statusHtml = `<div class="msger-indicator delivered"><i class="fa fa-check"></i></div>`;
                } else if (msg.status === 'read') {
                    let initials = getInitials($('#active-contact-name').text());
                    statusHtml = `<div class="msger-indicator read-avatar">${initials}</div>`;
                }
                existing.next('.msger-status-row').html(statusHtml);
                return;
            }
        }

        let bubbleClass = msg.direction === 'outbound' ? 'outbound' : 'inbound';
        let html = `
            <div class="msger-bubble ${bubbleClass}" data-message-id="${msg.message_id || ''}">
                ${msg.body}
            </div>
        `;

        if (msg.direction === 'outbound') {
            let statusHtml = '';
            if (msg.status === 'sent') {
                statusHtml = `<div class="msger-indicator sent"><i class="fa fa-check"></i></div>`;
            } else if (msg.status === 'delivered') {
                statusHtml = `<div class="msger-indicator delivered"><i class="fa fa-check"></i></div>`;
            } else if (msg.status === 'read') {
                let initials = getInitials($('#active-contact-name').text());
                statusHtml = `<div class="msger-indicator read-avatar">${initials}</div>`;
            }
            html += `<div class="msger-status-row">${statusHtml}</div>`;
        }

        let chatBox = $('#chat-messages');
        let isAtBottom = chatBox.prop("scrollHeight") - chatBox.scrollTop() === chatBox.outerHeight();

        chatBox.append(html);

        if (isAtBottom) {
            chatBox.scrollTop(chatBox.prop("scrollHeight"));
        }
    }

    // SEND: Facebook Messenger Message through API
    function sendMessage() {
        let input = $('#message-input');
        let val = input.val().trim();
        if(!val || !currentContactId) return;

        input.val('');

        $.post("{{ route('conversation.facebook.send') }}", {
            _token: "{{ csrf_token() }}",
            contact_id: currentContactId,
            message: val
        }, function(data) {
            if (data.success) {
                appendMessage(data.message);
                fetchContacts(); // Re-order contacts
            }
        }).fail(function(xhr) {
            let err = xhr.responseJSON ? xhr.responseJSON.error : 'Failed to send message.';
            alert("Error: " + err);
        });
    }

    // RENDER: Instagram Contacts
    function renderIgContacts(contacts) {
        let html = '';
        contacts.forEach(function(c) {
            let initials = getInitials(c.name);
            // Story active ring indicator
            let storyClass = c.story ? 'ig-story-active' : '';
            let activeClass = (c.id === currentIgContactId) ? 'active ig-active-bg' : '';
            let unreadClass = (c.unread > 0) ? 'unread-indicator ig-unread' : 'd-none';

            html += `
                <li class="msger-item ${activeClass}" onclick="openIgChat(${c.id}, '${c.name}')">
                    <div class="avatar-frame ${storyClass}">${initials}</div>
                    <div class="msger-details">
                        <div class="msger-meta-info">
                            <span class="msger-name">${c.name}</span>
                            <span class="msger-time">${c.time}</span>
                        </div>
                        <div class="msger-preview-wrapper">
                            <span class="msger-preview">${c.last_msg}</span>
                            <span class="${unreadClass}"></span>
                        </div>
                    </div>
                </li>
            `;
        });
        $('#ig-contact-list').html(html);
    }

    // OPEN: Instagram Chat Box
    function openIgChat(id, name) {
        currentIgContactId = id;
        $('#ig-empty-state').hide();
        $('#ig-chat-frame').css('display', 'flex');
        $('#ig-active-contact-name').text(name);
        $('#ig-active-avatar').text(getInitials(name));
        
        let contact = mockIgContacts.find(c => c.id === id);
        if (contact) {
            contact.unread = 0;
            contact.story = false;
        }

        renderIgContacts(mockIgContacts); 
        renderIgMessages(id);
    }

    // RENDER: Instagram Messages (Seen indicator style)
    function renderIgMessages(contactId) {
        let messages = mockIgMessages[contactId] || [];
        let html = '';

        messages.forEach(function(msg, index) {
            let bubbleType = (msg.direction === 'ig-outbound') ? 'ig-outbound' : 'ig-inbound';
            html += `
                <div class="msger-bubble ${bubbleType}">
                    ${msg.body}
                </div>
            `;

            if (msg.direction === 'ig-outbound') {
                let statusHtml = '';
                if (msg.status === 'seen') {
                    let initials = getInitials($('#ig-active-contact-name').text());
                    statusHtml = `<div class="ig-seen-text">Seen <span class="seen-avatar">${initials}</span></div>`;
                }
                html += statusHtml;
            }
        });

        $('#ig-chat-messages').html(html);
        let chatBox = $('#ig-chat-messages');
        chatBox.scrollTop(chatBox.prop("scrollHeight"));
    }

    // SEND: Instagram Message
    function handleIgKeyPress(e) {
        if(e.key === 'Enter') {
            sendIgMessage();
        }
    }

    function sendIgMessage() {
        let input = $('#ig-message-input');
        let val = input.val().trim();
        if(!val || !currentIgContactId) return;

        input.val('');

        mockIgMessages[currentIgContactId].push({
            id: Date.now(),
            body: val,
            direction: 'ig-outbound',
            status: 'sent',
            created_at: new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})
        });

        let contact = mockIgContacts.find(c => c.id === currentIgContactId);
        if (contact) {
            contact.last_msg = val;
            contact.time = "Just now";
        }

        renderIgMessages(currentIgContactId);
        renderIgContacts(mockIgContacts);

        setTimeout(() => {
            let msgs = mockIgMessages[currentIgContactId];
            if(msgs && msgs.length > 0) {
                msgs[msgs.length - 1].status = 'seen';
                renderIgMessages(currentIgContactId);
            }
        }, 2500);
    }

    // SEND: Instagram Heart Reaction
    function sendIgHeart() {
        if(!currentIgContactId) return;

        mockIgMessages[currentIgContactId].push({
            id: Date.now(),
            body: "❤️",
            direction: 'ig-outbound',
            status: 'sent',
            created_at: new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})
        });

        let contact = mockIgContacts.find(c => c.id === currentIgContactId);
        if (contact) {
            contact.last_msg = "❤️ Sent a heart";
            contact.time = "Just now";
        }

        renderIgMessages(currentIgContactId);
        renderIgContacts(mockIgContacts);

        setTimeout(() => {
            let msgs = mockIgMessages[currentIgContactId];
            if(msgs && msgs.length > 0) {
                msgs[msgs.length - 1].status = 'seen';
                renderIgMessages(currentIgContactId);
            }
        }, 2500);
    }

    // TAB 2: POSTS & COMMENTS FUNCTIONS
    function loadPosts() {
        let html = '';
        mockPosts.forEach(function(post) {
            let activeClass = (post.id === currentPostId) ? 'active' : '';
            html += `
                <li class="post-item ${activeClass}" onclick="openComments(${post.id})">
                    <div class="post-thumbnail"><i class="fab fa-facebook"></i></div>
                    <div class="post-details">
                        <span class="post-text">${post.text}</span>
                        <div class="post-stats">
                            <span><i class="far fa-comment"></i> ${post.comments_count}</span>
                            <span>${post.time}</span>
                        </div>
                    </div>
                </li>
            `;
        });
        $('#posts-list').html(html);
    }

    function openComments(postId) {
        currentPostId = postId;
        $('#comments-empty-state').hide();
        $('#comments-panel').show();

        let post = mockPosts.find(p => p.id === postId);
        $('#active-post-title').html(`<i class="fab fa-facebook"></i> Post: "${post.text}"`);

        $('.post-item').removeClass('active');
        loadPosts(); 

        renderCommentsList(postId);
    }

    function renderCommentsList(postId) {
        let comments = mockComments[postId] || [];
        let html = '';

        comments.forEach(function(comment) {
            let initials = getInitials(comment.user);
            html += `
                <div class="comment-card">
                    <div class="comment-avatar">${initials}</div>
                    <div class="comment-bubble-wrapper">
                        <div class="comment-bubble">
                            <div class="comment-user-name">${comment.user}</div>
                            <div class="comment-text">${comment.text}</div>
                        </div>
                        <div class="comment-actions">
                            <span>Like</span>
                            <span onclick="focusReply('${comment.user}')">Reply</span>
                            <span class="text-muted">${comment.time}</span>
                        </div>
            `;

            if (comment.replies && comment.replies.length > 0) {
                html += `<div class="reply-thread">`;
                comment.replies.forEach(function(reply) {
                    let rInitials = getInitials(reply.user);
                    html += `
                        <div class="comment-card">
                            <div class="comment-avatar">${rInitials}</div>
                            <div class="comment-bubble-wrapper">
                                <div class="comment-bubble">
                                    <div class="comment-user-name">${reply.user}</div>
                                    <div class="comment-text">${reply.text}</div>
                                </div>
                                <div class="comment-actions">
                                    <span>Like</span>
                                    <span class="text-muted">${reply.time}</span>
                                </div>
                            </div>
                        </div>
                    `;
                });
                html += `</div>`;
            }

            html += `</div></div>`; 
        });

        $('#comments-thread').html(html);
    }

    function focusReply(userName) {
        $('#comment-input').val(`@${userName} `).focus();
    }

    function postComment() {
        let input = $('#comment-input');
        let val = input.val().trim();
        if(!val || !currentPostId) return;

        input.val('');

        if (mockComments[currentPostId] && mockComments[currentPostId].length > 0) {
            mockComments[currentPostId][0].replies.push({
                user: "Looksmen Support",
                text: val,
                time: "Just now"
            });
            renderCommentsList(currentPostId);
        }
    }
</script>
@endsection
