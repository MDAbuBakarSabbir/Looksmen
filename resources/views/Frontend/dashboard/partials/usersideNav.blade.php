<style>
    .user-sidenav {
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 10px 40px -10px rgba(0,0,0,0.08);
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }
    .user-sidenav-header {
        background: linear-gradient(135deg, var(--chk-primary, #6366f1) 0%, #a855f7 100%);
        padding: 30px 20px;
        text-align: center;
        color: white;
    }
    .user-sidenav-header img {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        border: 3px solid rgba(255,255,255,0.3);
        margin-bottom: 15px;
        object-fit: cover;
    }
    .user-nav-list {
        list-style: none;
        padding: 15px 0;
        margin: 0;
    }
    .user-nav-link {
        display: flex;
        align-items: center;
        padding: 12px 24px;
        color: #475569;
        font-weight: 500;
        transition: all 0.2s;
        text-decoration: none;
        border-left: 3px solid transparent;
        font-size: 0.95rem;
    }
    .user-nav-link:hover, .user-nav-link.active {
        background: #f8fafc;
        color: var(--chk-primary, #6366f1);
        border-left-color: var(--chk-primary, #6366f1);
        text-decoration: none;
    }
    .user-nav-link i {
        font-size: 1.3rem;
        margin-right: 12px;
        width: 24px;
        text-align: center;
    }
    .user-nav-divider {
        height: 1px;
        background: #e2e8f0;
        margin: 10px 20px;
    }
</style>

<div class="user-sidenav mb-4">
    <div class="user-sidenav-header">
        <img src="{{ asset('frontend/assets/img/avatar-place.png') }}" onerror="this.onerror=null;this.src='data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCA4MCA4MCI+PGNpcmNsZSBjeD0iNDAiIGN5PSI0MCIgcj0iNDAiIGZpbGw9IiNlMmU4ZjAiLz48Y2lyY2xlIGN4PSI0MCIgY3k9IjMwIiByPSIxNSIgZmlsbD0iIzk0YTNiOCIvPjxwYXRoIGQ9Ik0xMCA3MWMwLTE2LjU3IDEzLjQzLTMwIDMwLTMwczMwIDEzLjQzIDMwIDMwIiBmaWxsPSIjOTRhM2I4Ii8+PC9zdmc+';">
        <h4 class="h5 mb-1 fw-600 text-white">{{ Auth::user()->name }}</h4>
        <div class="opacity-70 fs-14">{{ Auth::user()->email }}</div>
    </div>
    <ul class="user-nav-list">
        <li>
            <a href="{{ route('dashboard') }}" class="user-nav-link {{ Route::currentRouteName() == 'dashboard' ? 'active' : '' }}">
                <i class="las la-home"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="{{ route('purchaseHistory') }}" class="user-nav-link {{ Route::currentRouteName() == 'purchaseHistory' ? 'active' : '' }}">
                <i class="las la-file-alt"></i> Purchase History
            </a>
        </li>
        <li>
            <a href="{{ route('wishlist') }}" class="user-nav-link {{ Route::currentRouteName() == 'wishlist' ? 'active' : '' }}">
                <i class="la la-heart-o"></i> Wishlist
            </a>
        </li>
        <li>
            <a href="{{ route('compare') }}" class="user-nav-link {{ Route::currentRouteName() == 'compare' ? 'active' : '' }}">
                <i class="la la-refresh"></i> Compare
            </a>
        </li>
        <li>
            <a href="#" class="user-nav-link">
                <i class="lab la-sketch"></i> Classified Products
            </a>
        </li>
        <li>
            <a href="{{ route('conversation') }}" class="user-nav-link {{ Route::currentRouteName() == 'conversation' ? 'active' : '' }}">
                <i class="las la-comment"></i> Conversations
            </a>
        </li>
        <li>
            <a href="{{ route('myWallet') }}" class="user-nav-link {{ Route::currentRouteName() == 'myWallet' ? 'active' : '' }}">
                <i class="las la-dollar-sign"></i> My Wallet
            </a>
        </li>
        <li>
            <a href="{{ route('supportTicket') }}" class="user-nav-link {{ Route::currentRouteName() == 'supportTicket' ? 'active' : '' }}">
                <i class="las la-atom"></i> Support Ticket
            </a>
        </li>
        <li>
            <a href="{{ route('profile.edit') }}" class="user-nav-link {{ Route::currentRouteName() == 'profile.edit' ? 'active' : '' }}">
                <i class="las la-user"></i> Manage Profile
            </a>
        </li>
        
        <li>
            @php
                $affiliate = Auth::user()->affiliate_user;
            @endphp
            @if ($affiliate && $affiliate->status == 1)
                <a href="{{ route('affiliate.user.index') }}" class="user-nav-link {{ str_starts_with(Route::currentRouteName(), 'affiliate.user') ? 'active' : '' }}">
                    <i class="las la-handshake"></i> Affiliate Dashboard
                </a>
            @else
                <a href="{{ route('affiliate.index') }}" class="user-nav-link {{ Route::currentRouteName() == 'affiliate.index' ? 'active' : '' }}">
                    <i class="las la-handshake"></i> Affiliate Partner
                </a>
            @endif
        </li>
        
        <div class="user-nav-divider"></div>
        
        <li>
            <form method="POST" action="{{ route('logout') }}" id="logout-form" class="m-0">
                @csrf
                <a href="#" onclick="document.getElementById('logout-form').submit();" class="user-nav-link text-danger">
                    <i class="las la-sign-out-alt"></i> Logout
                </a>
            </form>
        </li>
    </ul>
</div>
