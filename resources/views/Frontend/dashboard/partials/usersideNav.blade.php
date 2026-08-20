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
    <div class="user-sidenav-header position-relative">
        <button type="button" class="btn p-0 text-white position-absolute d-xl-none" style="top: 12px; right: 15px; font-size: 28px; line-height: 1; opacity: 0.9; background: none; border: none; outline: none; cursor: pointer; z-index: 10;" data-toggle="class-toggle" data-backdrop="static" data-target=".aiz-mobile-side-nav" title="Close Menu">
            <span aria-hidden="true">×</span>
        </button>
        @php
            $navUserAvatar = Auth::user()->profile_pic && file_exists(public_path('Uploads/' . Auth::user()->profile_pic))
                ? asset('Uploads/' . Auth::user()->profile_pic)
                : asset('frontend/assets/img/avatar-place.png');
        @endphp
        <img src="{{ $navUserAvatar }}" onerror="this.onerror=null;this.src='data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCA4MCA4MCI+PGNpcmNsZSBjeD0iNDAiIGN5PSI0MCIgcj0iNDAiIGZpbGw9IiNlMmU4ZjAiLz48Y2lyY2xlIGN4PSI0MCIgY3k9IjMwIiByPSIxNSIgZmlsbD0iIzk0YTNiOCIvPjxwYXRoIGQ9Ik0xMCA3MWMwLTE2LjU3IDEzLjQzLTMwIDMwLTMwczMwIDEzLjQzIDMwIDMwIiBmaWxsPSIjOTRhM2I4Ii8+PC9zdmc+';">
        <h4 class="h5 mb-1 fw-600 text-white">{{ Auth::user()->name }}</h4>
        <div class="opacity-70 fs-14">{{ Auth::user()->email }}</div>
    </div>
    @php
        $settings = \App\Models\GeneralWebSettings::pluck('value', 'name')->toArray();
        $featuresConfig = \Illuminate\Support\Facades\Cache::rememberForever('feature_activations_map', function () {
            return \App\Models\FeatureActivation::pluck('status', 'name')->toArray();
        });
        $emailVerificationFeature = ($featuresConfig['email_verification'] ?? '0') === '1';
        $verificationTemplateActive = ($settings['verification_mail_active'] ?? '0') === '1';
        $otpTemplateActive = ($settings['otp_mail_active'] ?? '0') === '1';

        $isUnverifiedNav = $emailVerificationFeature && ($verificationTemplateActive || $otpTemplateActive) && Auth::check() && !Auth::user()->hasVerifiedEmail();
    @endphp

    @if($isUnverifiedNav)
        <div class="p-4 text-center">
            <div class="p-3 rounded-lg text-left mb-3" style="background: #fffbeb; border: 1px solid #fef3c7; color: #92400e; font-size: 0.88rem; line-height: 1.5;">
                <i class="las la-exclamation-triangle font-weight-bold mr-1 text-warning"></i>
                <strong>Email Not Verified:</strong> Please complete email verification to access your dashboard and account features.
            </div>
            <a href="{{ route('verification.notice') }}" class="btn btn-primary btn-block rounded-pill font-weight-bold py-2" style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); border: none;">
                <i class="las la-shield-alt mr-1"></i> Verify Email Now
            </a>
            <form method="POST" action="{{ route('logout') }}" id="logout-form-nav" class="mt-3">
                @csrf
                <button type="submit" class="btn btn-link text-danger btn-sm p-0">
                    <i class="las la-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>
    @else
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
            <a href="{{ route('toReview') }}" class="user-nav-link {{ Route::currentRouteName() == 'toReview' ? 'active' : '' }}">
                <i class="las la-star-half-alt"></i> To Review
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
        @if(addon_is_activated('conversations'))
        <li>
            <a href="{{ route('conversation') }}" class="user-nav-link {{ Route::currentRouteName() == 'conversation' ? 'active' : '' }}">
                <i class="las la-comment"></i> Conversations
            </a>
        </li>
        @endif
        @if(addon_is_activated('wallet_system'))
        <li>
            <a href="{{ route('myWallet') }}" class="user-nav-link {{ Route::currentRouteName() == 'myWallet' ? 'active' : '' }}">
                <i class="las la-dollar-sign"></i> My Wallet
            </a>
        </li>
        @endif
        @if(addon_is_activated('conversations'))
        <li>
            <a href="{{ route('supportTicket') }}" class="user-nav-link {{ Route::currentRouteName() == 'supportTicket' ? 'active' : '' }}">
                <i class="las la-atom"></i> Support Ticket
            </a>
        </li>
        @endif
        <li>
            <a href="{{ route('profile.edit') }}" class="user-nav-link {{ Route::currentRouteName() == 'profile.edit' ? 'active' : '' }}">
                <i class="las la-user"></i> Manage Profile
            </a>
        </li>
        
        @if (addon_is_activated('affiliate_system'))
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
        @endif
        
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
    @endif
</div>
