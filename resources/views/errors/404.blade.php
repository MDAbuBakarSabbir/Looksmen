@php
    use App\Models\GeneralWebSettings;
    use Illuminate\Support\Facades\Cache;

    $webConfig = [];
    try {
        $webConfig = Cache::remember('global_webconfig_pluck', 3600, function () {
            return GeneralWebSettings::pluck('value', 'name')->toArray();
        });
    } catch (\Exception $e) {
        $webConfig = [];
    }

    $webName = $webConfig['web_name'] ?? 'LOOKSMEN';
    $logoFileName = $webConfig['web_logo'] ?? 'Logo.png';
    $webLogo = asset('adminDash/assets/img/layouts/' . $logoFileName);
    $faviconFileName = $webConfig['web_favicon'] ?? null;
    $webFavicon = $faviconFileName ? asset('adminDash/assets/img/layouts/' . $faviconFileName) : null;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>404 - Page Not Found | {{ $webName }}</title>
    @if ($webFavicon)
        <link rel="icon" href="{{ $webFavicon }}" type="image/png">
    @endif

    <!-- Google Fonts & LineAwesome Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">

    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #d946ef 100%);
            --accent-color: #6366f1;
            --bg-dark: #090d16;
            --card-bg: rgba(17, 24, 39, 0.7);
            --border-color: rgba(255, 255, 255, 0.1);
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-dark);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
            position: relative;
        }

        /* Ambient Glow Backgrounds */
        .ambient-glow-1 {
            position: absolute;
            top: -10%;
            left: 20%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.25) 0%, rgba(0, 0, 0, 0) 70%);
            filter: blur(60px);
            z-index: 0;
            pointer-events: none;
            animation: pulseGlow 8s infinite alternate ease-in-out;
        }

        .ambient-glow-2 {
            position: absolute;
            bottom: -10%;
            right: 15%;
            width: 550px;
            height: 550px;
            background: radial-gradient(circle, rgba(217, 70, 239, 0.2) 0%, rgba(0, 0, 0, 0) 70%);
            filter: blur(70px);
            z-index: 0;
            pointer-events: none;
            animation: pulseGlow 10s infinite alternate-reverse ease-in-out;
        }

        @keyframes pulseGlow {
            0% { transform: scale(1) translate(0, 0); opacity: 0.7; }
            100% { transform: scale(1.15) translate(30px, 20px); opacity: 1; }
        }

        /* Header Navigation */
        .site-header {
            position: relative;
            z-index: 10;
            padding: 20px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            background: rgba(9, 13, 22, 0.6);
        }

        .header-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .brand-logo-link {
            display: flex;
            align-items: center;
            text-decoration: none;
            transition: transform 0.3s ease;
        }

        .brand-logo-link:hover {
            transform: translateY(-2px);
        }

        .brand-logo-img {
            max-height: 48px;
            width: auto;
            object-fit: contain;
            filter: drop-shadow(0 4px 10px rgba(0, 0, 0, 0.3));
        }

        .header-nav {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .nav-link-btn {
            color: var(--text-muted);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.95rem;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .nav-link-btn:hover {
            color: #ffffff;
        }

        /* Main Error Section */
        .main-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px 20px;
            position: relative;
            z-index: 1;
        }

        .error-card {
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--border-color);
            border-radius: 32px;
            padding: 60px 40px;
            max-width: 680px;
            width: 100%;
            text-align: center;
            box-shadow: 0 30px 60px -15px rgba(0, 0, 0, 0.6),
                        inset 0 1px 0 rgba(255, 255, 255, 0.1);
            position: relative;
            overflow: hidden;
            animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* 404 Visual Graphic */
        .error-code-wrapper {
            position: relative;
            display: inline-block;
            margin-bottom: 24px;
        }

        .error-code {
            font-size: clamp(6rem, 15vw, 10rem);
            font-weight: 800;
            line-height: 1;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -4px;
            user-select: none;
            position: relative;
            text-shadow: 0 20px 40px rgba(99, 102, 241, 0.2);
        }

        .error-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(99, 102, 241, 0.15);
            border: 1px solid rgba(99, 102, 241, 0.3);
            color: #a5b4fc;
            padding: 8px 18px;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 20px;
        }

        .error-badge i {
            font-size: 1.1rem;
            color: #c084fc;
        }

        .error-title {
            font-size: clamp(1.75rem, 4vw, 2.5rem);
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 16px;
            line-height: 1.25;
        }

        .error-description {
            color: var(--text-muted);
            font-size: 1.05rem;
            line-height: 1.6;
            margin-bottom: 40px;
            max-width: 520px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Action Buttons */
        .btn-group {
            display: flex;
            gap: 16px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 14px 32px;
            border-radius: 14px;
            font-size: 1rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
        }

        .btn-primary {
            background: var(--primary-gradient);
            color: #ffffff;
            box-shadow: 0 10px 25px -5px rgba(99, 102, 241, 0.4);
            border: none;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px -5px rgba(124, 58, 237, 0.6);
            color: #ffffff;
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.05);
            color: #f1f5f9;
            border: 1px solid var(--border-color);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.25);
            color: #ffffff;
            transform: translateY(-3px);
        }

        /* Quick Links Footer */
        .quick-links {
            margin-top: 40px;
            padding-top: 30px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            display: flex;
            justify-content: center;
            gap: 24px;
            flex-wrap: wrap;
        }

        .quick-link-item {
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.2s ease;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .quick-link-item:hover {
            color: #a5b4fc;
        }

        /* Footer Copyright */
        .site-footer {
            text-align: center;
            padding: 24px;
            color: #64748b;
            font-size: 0.875rem;
            border-top: 1px solid rgba(255, 255, 255, 0.04);
            position: relative;
            z-index: 10;
        }

        @media (max-width: 576px) {
            .error-card {
                padding: 40px 24px;
            }
            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>

    <div class="ambient-glow-1"></div>
    <div class="ambient-glow-2"></div>

    <!-- Header containing Logo -->
    <header class="site-header">
        <div class="header-container">
            <a href="{{ url('/') }}" class="brand-logo-link" title="{{ $webName }}">
                <img src="{{ $webLogo }}" alt="{{ $webName }}" class="brand-logo-img" onerror="this.onerror=null; this.src='{{ asset('frontend/assets/img/logo.png') }}';">
            </a>
            <nav class="header-nav">
                <a href="{{ url('/') }}" class="nav-link-btn">
                    <i class="las la-home"></i> Home
                </a>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-container">
        <div class="error-card">
            <div class="error-badge">
                <i class="las la-exclamation-triangle"></i> Error 404
            </div>

            <div class="error-code-wrapper">
                <div class="error-code">404</div>
            </div>

            <h1 class="error-title">Page Not Found</h1>
            <p class="error-description">
                Oops! The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.
            </p>

            <div class="btn-group">
                <a href="{{ url('/') }}" class="btn btn-primary">
                    <i class="las la-arrow-left"></i> Back to Homepage
                </a>
                <button onclick="window.history.back()" class="btn btn-secondary">
                    <i class="las la-history"></i> Previous Page
                </button>
            </div>

            <div class="quick-links">
                <a href="{{ url('/') }}" class="quick-link-item">
                    <i class="las la-store"></i> Shop Store
                </a>
                @if(Route::has('cart'))
                <a href="{{ route('cart') }}" class="quick-link-item">
                    <i class="las la-shopping-cart"></i> View Cart
                </a>
                @endif
                @if(Route::has('dashboard'))
                <a href="{{ route('dashboard') }}" class="quick-link-item">
                    <i class="las la-user"></i> My Account
                </a>
                @endif
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="site-footer">
        &copy; {{ date('Y') }} {{ $webName }}. All Rights Reserved.
    </footer>

</body>
</html>

