@php
    use App\Models\GeneralWebSettings;
    $webConfig = [];
    try {
        $webConfig = GeneralWebSettings::pluck('value', 'name')->toArray();
    } catch (\Exception $e) {
        // Fallback
    }
    $webName = $webConfig['web_name'] ?? 'LOOKSMEN';
    $webFavicon = isset($webConfig['web_favicon']) ? asset('adminDash/assets/img/layouts/' . $webConfig['web_favicon']) : null;
    $webLogo = isset($webConfig['web_logo']) ? asset('adminDash/assets/img/layouts/' . $webConfig['web_logo']) : null;
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $webName }}</title>
    @if ($webFavicon)
        <link rel="icon" href="{{ $webFavicon }}" type="image/png">
    @endif
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Outfit', sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            background: radial-gradient(circle at top right, #1e1b4b 0%, #0f172a 40%, #020617 100%);
            color: #f8fafc;
            overflow-x: hidden;
            position: relative;
        }

        /* Subtle animated background glow */
        body::before {
            content: '';
            position: absolute;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.15) 0%, transparent 70%);
            top: -200px;
            left: -200px;
            z-index: 0;
            pointer-events: none;
        }

        body::after {
            content: '';
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(236, 72, 153, 0.1) 0%, transparent 70%);
            bottom: -150px;
            right: -150px;
            z-index: 0;
            pointer-events: none;
        }

        .content-box {
            position: relative;
            z-index: 1;
            background: rgba(15, 23, 42, 0.45);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            padding: 50px 40px;
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            text-align: center;
            max-width: 620px;
            width: 90%;
            transition: all 0.3s ease;
        }

        .content-box:hover {
            border-color: rgba(255, 255, 255, 0.15);
            box-shadow: 0 30px 60px -10px rgba(99, 102, 241, 0.2);
        }

        .logo img {
            max-width: 200px;
            height: auto;
            margin-bottom: 25px;
            filter: drop-shadow(0 4px 12px rgba(0,0,0,0.3));
            transition: transform 0.3s ease;
        }

        .logo img:hover {
            transform: scale(1.05);
        }

        .logo h1 {
            font-size: 2.2em;
            font-weight: 800;
            background: linear-gradient(135deg, #ffffff 0%, #cbd5e1 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-top: 0;
            margin-bottom: 25px;
        }

        h2 {
            font-size: 2.4em;
            font-weight: 800;
            background: linear-gradient(135deg, #6366f1 0%, #a855f7 50%, #ec4899 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-top: 0;
            margin-bottom: 15px;
            letter-spacing: -0.02em;
        }

        p {
            font-size: 1.1em;
            line-height: 1.6;
            color: #94a3b8;
            margin-top: 0;
            margin-bottom: 20px;
            font-weight: 300;
        }

        /* Countdown Container styles */
        .countdown-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-top: 35px;
        }

        .countdown-box {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 16px;
            padding: 18px 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            box-shadow: inset 0 2px 4px rgba(255, 255, 255, 0.05);
            transition: transform 0.2s ease, border-color 0.2s ease;
        }

        .countdown-box:hover {
            transform: translateY(-5px);
            border-color: rgba(99, 102, 241, 0.3);
            background: rgba(99, 102, 241, 0.05);
        }

        .countdown-number {
            font-size: 2.2rem;
            font-weight: 800;
            color: #ffffff;
            line-height: 1;
            background: linear-gradient(to bottom, #ffffff 0%, #cbd5e1 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 8px;
        }

        .countdown-label {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #6366f1;
        }

        @media (max-width: 480px) {
            .content-box {
                padding: 40px 20px;
            }
            h2 {
                font-size: 1.8em;
            }
            .countdown-number {
                font-size: 1.6rem;
            }
            .countdown-label {
                font-size: 0.65rem;
            }
        }
    </style>
</head>

<body>
    <div class="content-box">
        <div class="logo">
            <a href="{{ url('/') }}">
                @if ($webLogo)
                    <img src="{{ $webLogo }}" alt="{{ $webName }}">
                @else
                    <h1>{{ $webName }}</h1>
                @endif
            </a>
        </div>
        <h2>Under Maintenance</h2>
        <p>We are currently working on some improvements to make your experience better.</p>
        <p>We'll be back soon!</p>

        <!-- Countdown Timer -->
        <div class="countdown-container" id="countdown-container">
            <div class="countdown-box">
                <span class="countdown-number" id="days">00</span>
                <span class="countdown-label">Days</span>
            </div>
            <div class="countdown-box">
                <span class="countdown-number" id="hours">00</span>
                <span class="countdown-label">Hours</span>
            </div>
            <div class="countdown-box">
                <span class="countdown-number" id="minutes">00</span>
                <span class="countdown-label">Minutes</span>
            </div>
            <div class="countdown-box">
                <span class="countdown-number" id="seconds">00</span>
                <span class="countdown-label">Seconds</span>
            </div>
        </div>
    </div>

    <script>
        // Set the date we're counting down to
        let countdownTarget = localStorage.getItem('maintenance_countdown_target');
        if (!countdownTarget) {
            // 7 days from now in milliseconds
            countdownTarget = new Date().getTime() + (7 * 24 * 60 * 60 * 1000);
            localStorage.setItem('maintenance_countdown_target', countdownTarget);
        } else {
            countdownTarget = parseInt(countdownTarget, 10);
            // If the target has already passed, reset it for demo purposes (so it's always running)
            if (countdownTarget < new Date().getTime()) {
                countdownTarget = new Date().getTime() + (7 * 24 * 60 * 60 * 1000);
                localStorage.setItem('maintenance_countdown_target', countdownTarget);
            }
        }

        function updateCountdown() {
            const now = new Date().getTime();
            const distance = countdownTarget - now;

            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            document.getElementById('days').innerText = String(days).padStart(2, '0');
            document.getElementById('hours').innerText = String(hours).padStart(2, '0');
            document.getElementById('minutes').innerText = String(minutes).padStart(2, '0');
            document.getElementById('seconds').innerText = String(seconds).padStart(2, '0');

            if (distance < 0) {
                clearInterval(x);
                document.getElementById('countdown-container').innerHTML = "<h3 style='grid-column: span 4; font-size: 1.5em; font-weight: 600; color: #a855f7;'>We are opening now!</h3>";
            }
        }

        // Update the count down every 1 second
        updateCountdown();
        const x = setInterval(updateCountdown, 1000);
    </script>
</body>

</html>
