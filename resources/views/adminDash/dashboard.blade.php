@php
    use App\Models\Reviews;
    use App\Models\SupportTicket;
    use App\Models\SocialMedia;
    use Illuminate\Support\Facades\Http;

    $reviews = collect([]);
    $supportticket = collect([]);
    $socials = collect([]);

    if (class_exists('App\Models\Reviews')) {
        try {
            $reviews = Reviews::all();
        } catch (\Exception $e) {}
    }

    if (class_exists('App\Models\SupportTicket')) {
        try {
            $supportticket = SupportTicket::all();
        } catch (\Exception $e) {}
    }

    if (class_exists('App\Models\SocialMedia')) {
        try {
            $socials = SocialMedia::where('status', 1)->get();
        } catch (\Exception $e) {}
    }

    // Lookup individual social media records or define fallbacks
    $fb = $socials->where('social_icon', 'fa-facebook')->first();
    $ig = $socials->where('social_icon', 'fa-instagram')->first();
    $yt = $socials->where('social_icon', 'fa-youtube')->first();
    $tt = $socials->where('social_icon', 'fa-tiktok')->first();

    $fb_link = $fb ? $fb->social_link : 'https://www.facebook.com/looksmenstore';
    $fb_followers = $fb ? $fb->followers_count : '15K';
    $fb_likes = $fb ? $fb->secondary_count : '14K';

    $ig_link = $ig ? $ig->social_link : 'https://www.instagram.com/looksmenstore';
    $ig_followers = $ig ? $ig->followers_count : '119K';
    $ig_posts = $ig ? $ig->secondary_count : '89';

    $yt_link = $yt ? $yt->social_link : 'https://www.youtube.com/@looksmenstore';
    $yt_subscribers = $yt ? $yt->followers_count : '119K';
    $yt_videos = $yt ? $yt->secondary_count : '89';

    $tt_link = $tt ? $tt->social_link : 'https://www.tiktok.com/@looksmen.com';
    $tt_followers = $tt ? $tt->followers_count : '119K';
    $tt_posts = $tt ? $tt->secondary_count : '89';

    $pageId = '728349017033680'; // আপনার পেজের আইডি বা ইউজারনেম
    $accessToken = '4b6c433bdefd1ed2d4462d715c5822e5'; // আপনার পার্মানেন্ট এক্সেস টোকেন

    // Optional Facebook Graph API check as fallback if database has default 15K but API is working
    try {
        if (!$fb && $accessToken !== '4b6c433bdefd1ed2d4462d715c5822e5') {
            $response = Http::timeout(3)->get("https://graph.facebook.com/v18.0/{$pageId}", [
                'fields' => 'engagement,followers_count',
                'access_token' => $accessToken,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $fb_likes = $data['engagement']['count'] ?? '14K';
                $fb_followers = $data['followers_count'] ?? '15K';
            }
        }
    } catch (\Exception $e) {}

    // Calculate rates dynamically based on the orders list passed from controller
    $totalOrders = $orders->count();
    $newCount = $orders->where('delivery_status', 'new')->count();
    $pendingCount = $orders->where('delivery_status', 'pending')->count();
    $deliveredCount = $orders->where('delivery_status', 'delivered')->count();
    $cancelCount = $orders->whereIn('delivery_status', ['cancel', 'canceled'])->count();

    $confirmedCount = $totalOrders - $newCount - $cancelCount;

    $confirmationRate = $totalOrders > 0 ? ($confirmedCount / $totalOrders) * 100 : 0;
    $pendingRate = $totalOrders > 0 ? ($pendingCount / $totalOrders) * 100 : 0;
    $deliveryRate = $totalOrders > 0 ? ($deliveredCount / $totalOrders) * 100 : 0;
    $cancelRate = $totalOrders > 0 ? ($cancelCount / $totalOrders) * 100 : 0;
@endphp

@extends('layouts.AdminLays.master')
@section('title')
    DASHBOARD
@endsection

@section('content')

<style>
    /* Google Fonts for a modern look */
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap');

    :root {
        --primary-gradient: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
        --success-gradient: linear-gradient(135deg, #22c55e 0%, #10b981 100%);
        --warning-gradient: linear-gradient(135deg, #f59e0b 0%, #ea580c 100%);
        --danger-gradient: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        --info-gradient: linear-gradient(135deg, #0ea5e9 0%, #3b82f6 100%);
        
        --glass-bg: rgba(255, 255, 255, 0.95);
        --glass-border: rgba(255, 255, 255, 0.5);
        --glass-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.05);
        
        --text-main: #1e293b;
        --text-muted: #64748b;
    }

    body, .content-body {
        font-family: 'Outfit', sans-serif !important;
        background-color: #f8fafc;
    }

    /* Core Glass Card */
    .glass-card {
        background: var(--glass-bg);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid var(--glass-border);
        border-radius: 20px;
        box-shadow: var(--glass-shadow);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
        margin-bottom: 24px;
    }

    .glass-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 40px 0 rgba(31, 38, 135, 0.1);
    }

    /* Stat Widget Customization */
    .stat-widget-premium {
        padding: 24px;
        position: relative;
        overflow: hidden;
        border-radius: 20px;
        color: white;
        text-decoration: none;
        display: block;
        transition: all 0.4s ease;
    }

    .stat-widget-premium::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, transparent 60%);
        transform: scale(1);
        transition: transform 0.6s ease;
    }

    .stat-widget-premium:hover::before {
        transform: scale(1.5);
    }

    .stat-widget-premium .stat-text {
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 600;
        margin-bottom: 12px;
        opacity: 0.9;
    }

    .stat-widget-premium .stat-digit {
        font-size: 2.5rem;
        font-weight: 700;
        line-height: 1;
    }

    .stat-widget-premium img {
        height: 56px;
        filter: drop-shadow(0 4px 6px rgba(0,0,0,0.2));
        transition: transform 0.3s ease;
    }

    .stat-widget-premium:hover img {
        transform: scale(1.1) rotate(5deg);
    }

    /* Card Headers */
    .premium-header {
        background: transparent;
        border-bottom: 1px solid rgba(0,0,0,0.05);
        padding: 20px 24px;
    }

    .premium-header h4 {
        margin: 0;
        font-weight: 700;
        color: var(--text-main);
        font-size: 1.25rem;
    }

    /* Modern Table */
    .table-modern {
        border-collapse: separate;
        border-spacing: 0;
        width: 100%;
    }

    .table-modern th {
        background-color: #f8fafc;
        color: var(--text-muted);
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        padding: 16px 24px;
        border-bottom: 1px solid #e2e8f0;
    }

    .table-modern td {
        padding: 16px 24px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        color: var(--text-main);
        font-weight: 500;
        transition: background-color 0.2s;
    }

    .table-modern tbody tr:hover td {
        background-color: #f8fafc;
    }

    .table-modern tbody tr:last-child td {
        border-bottom: none;
    }

    /* Pill Badges */
    .badge-pill {
        padding: 6px 12px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: capitalize;
    }

    .badge-soft-success {
        background-color: #dcfce7;
        color: #166534;
    }

    .badge-soft-danger {
        background-color: #fee2e2;
        color: #991b1b;
    }

    .badge-soft-warning {
        background-color: #fef3c7;
        color: #92400e;
    }

    .badge-soft-info {
        background-color: #e0f2fe;
        color: #075985;
    }

    /* Product Images */
    .modern-pro-img {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        object-fit: cover;
        box-shadow: 0 4px 10px rgba(0,0,0,0.08);
        border: 2px solid white;
    }

    /* Chart Containers */
    .modern-chart-box {
        position: relative;
        height: 140px;
        margin: 20px 0;
        padding: 10px;
    }

    .modern-chart-label {
        margin-top: 15px;
        font-weight: 600;
        color: var(--text-muted);
        text-align: center;
        font-size: 0.85rem;
    }

    /* Feedback Circle */
    .feedback-circle {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: var(--primary-gradient);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 30px;
        box-shadow: 0 10px 25px rgba(99, 102, 241, 0.4);
        color: white;
        font-size: 2.5rem;
        position: relative;
    }

    .feedback-circle::after {
        content: '';
        position: absolute;
        width: 140px;
        height: 140px;
        border-radius: 50%;
        border: 2px dashed rgba(99, 102, 241, 0.3);
        animation: spin 20s linear infinite;
    }

    @keyframes spin {
        100% { transform: rotate(360deg); }
    }

    /* Social Widgets */
    .social-widget-modern {
        display: flex;
        align-items: center;
        padding: 20px;
    }

    .social-icon-wrapper {
        width: 60px;
        height: 60px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        color: white;
        margin-right: 20px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }

    .social-stats {
        flex: 1;
        display: flex;
        justify-content: space-between;
    }

    .social-stat-item {
        text-align: center;
    }

    .social-stat-item h4 {
        margin: 0 0 4px;
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text-main);
    }

    .social-stat-item p {
        margin: 0;
        font-size: 0.75rem;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
    }

    /* Specific Social Colors */
    .bg-fb { background: linear-gradient(135deg, #1877f2 0%, #0c5dc7 100%); }
    .bg-ig { background: linear-gradient(135deg, #f58529, #dd2a7b, #8134af, #515bd4); }
    .bg-yt { background: linear-gradient(135deg, #ff0000 0%, #cc0000 100%); }
    .bg-tt { background: linear-gradient(135deg, #000000 0%, #4a4a4a 100%); }
    
    a:hover {
        text-decoration: none;
    }
</style>

<!-- Top Statistics Row -->
<div class="row">
    <div class="col-lg-3 col-sm-6 mb-4">
        <a href="{{ route('order-new') }}" class="stat-widget-premium" style="background: var(--primary-gradient);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-text">New Orders</div>
                    <div class="stat-digit">{{ $orders->where('delivery_status', 'new')->count() }}</div>
                </div>
                <img src="{{ asset('adminDash/assets/img/orders/boxes.png') }}" alt="img">
            </div>
        </a>
    </div>
    
    <div class="col-lg-3 col-sm-6 mb-4">
        <a href="{{ route('order-pending') }}" class="stat-widget-premium" style="background: var(--warning-gradient);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-text">Pending Orders</div>
                    <div class="stat-digit">{{ $orders->where('delivery_status', 'pending')->count() }}</div>
                </div>
                <img src="{{ asset('adminDash/assets/img/orders/pending.png') }}" alt="img">
            </div>
        </a>
    </div>

    <div class="col-lg-3 col-sm-6 mb-4">
        <a href="{{ route('order-delivered') }}" class="stat-widget-premium" style="background: var(--success-gradient);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-text">Delivered</div>
                    <div class="stat-digit">{{ $orders->where('delivery_status', 'delivered')->count() }}</div>
                </div>
                <img src="{{ asset('adminDash/assets/img/orders/delivery.png') }}" alt="img">
            </div>
        </a>
    </div>

    <div class="col-lg-3 col-sm-6 mb-4">
        <a href="{{ route('order-index') }}" class="stat-widget-premium" style="background: var(--info-gradient);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-text">Support Tickets</div>
                    <div class="stat-digit">{{ $supportticket->count() }}</div>
                </div>
                <img src="{{ asset('adminDash/assets/img/layouts/support.png') }}" alt="img">
            </div>
        </a>
    </div>
</div>

<!-- Charts Row -->
<div class="row">
    <div class="col-12">
        <div class="glass-card">
            <div class="premium-header text-center">
                <h4>Total Platform Statistics</h4>
            </div>
            <div class="card-body">
                <div class="row d-flex justify-content-evenly pb-4">
                    <!-- Chart 1 -->
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="modern-chart-box">
                            <canvas id="confirmationChart"></canvas>
                        </div>
                        <div class="modern-chart-label">Order Confirmation Rate</div>
                    </div>
                    <!-- Chart 2 -->
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="modern-chart-box">
                            <canvas id="pendingChart"></canvas>
                        </div>
                        <div class="modern-chart-label">Delivery Pending Rate</div>
                    </div>
                    <!-- Chart 3 -->
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="modern-chart-box">
                            <canvas id="deliverySuccessChart"></canvas>
                        </div>
                        <div class="modern-chart-label">Delivery Success Rate</div>
                    </div>
                    <!-- Chart 4 -->
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="modern-chart-box">
                            <canvas id="cancelChart"></canvas>
                        </div>
                        <div class="modern-chart-label">Delivery Cancel Rate</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Feedback & Products Chart Row -->
<div class="row">
    <div class="col-xl-4 col-lg-5">
        <div class="glass-card h-100">
            <div class="premium-header text-center border-0">
                <h4>Customer Feedback</h4>
            </div>
            <div class="card-body text-center d-flex flex-column justify-content-center">
                <div class="feedback-circle">
                    {{ $reviews->count() }}
                </div>
                
                <div class="row mt-4 px-3">
                    <div class="col-6 border-right">
                        <h3 class="text-success fw-bold mb-1">99%</h3>
                        <p class="text-muted fw-semibold mb-0"><i class="ti-hand-point-up me-1"></i> Positive</p>
                    </div>
                    <div class="col-6">
                        <h3 class="text-danger fw-bold mb-1">1%</h3>
                        <p class="text-muted fw-semibold mb-0"><i class="ti-hand-point-down me-1"></i> Negative</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-8 col-lg-7">
        <div class="glass-card h-100">
            <div class="premium-header d-flex justify-content-between align-items-center">
                <h4>Product Sold Trends</h4>
                <div class="dropdown custom-dropdown">
                    <div data-toggle="dropdown" style="cursor: pointer;">
                        <i class="ti-more-alt text-muted"></i>
                    </div>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="#">Weekly</a>
                        <a class="dropdown-item" href="#">Monthly</a>
                        <a class="dropdown-item" href="#">Yearly</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="chart py-2" style="height: 300px;">
                    <canvas id="sold-product"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tables Row -->
<div class="row">
    <!-- Recent Orders -->
    <div class="col-lg-7">
        <div class="glass-card">
            <div class="premium-header">
                <h4>Recent Orders</h4>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table-modern">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer Info</th>
                                <th>Quantity</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                use App\Models\Orders;
                                $latestOrder = Orders::latest('id')->paginate(5);
                            @endphp
                            @forelse ($latestOrder as $order)
                                @php
                                    $badgeClass = 'badge-soft-info';
                                    if(in_array(strtolower($order->delivery_status), ['delivered', 'success'])) $badgeClass = 'badge-soft-success';
                                    if(in_array(strtolower($order->delivery_status), ['cancel', 'canceled'])) $badgeClass = 'badge-soft-danger';
                                    if(in_array(strtolower($order->delivery_status), ['pending', 'hold'])) $badgeClass = 'badge-soft-warning';
                                @endphp
                                <tr>
                                    <td class="fw-bold">#LM-{{ $order->id }}</td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold">{{$order->name}}</span>
                                            <span class="text-muted small">{{$order->phone}}</span>
                                        </div>
                                    </td>
                                    <td>1 pcs</td>
                                    <td>
                                        <span class="badge-pill {{ $badgeClass }}">
                                            {{ $order->delivery_status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">No recent orders found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Products -->
    <div class="col-lg-5">
        <div class="glass-card">
            <div class="premium-header">
                <h4>Top Selling Products</h4>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table-modern">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Details</th>
                                <th>Stock</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($topSellingProducts as $topSellingProduct)
                                <tr>
                                    <td>
                                        <img class="modern-pro-img" 
                                            src="{{ $topSellingProduct->firstImage ? asset('adminDash/uploads/products/' . $topSellingProduct->firstImage->image) : asset('frontEnd/assets/img/placeholder.jpg') }}" 
                                            alt="Product">
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold text-truncate" style="max-width: 150px;">{{ $topSellingProduct->title }}</span>
                                            <span class="text-muted small">Code: LM-{{ $topSellingProduct->code }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-bold {{ $topSellingProduct->stock < 10 ? 'text-danger' : 'text-success' }}">
                                            {{ $topSellingProduct->stock }} pcs
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted">No top products available.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Social Media Row -->
<div class="row">
    <div class="col-12">
        <div class="row">
            <!-- Facebook -->
            <div class="col-xl-3 col-lg-6 col-sm-6 mb-4">
                <a href="{{ $fb_link }}" target="_blank">
                    <div class="glass-card social-widget-modern mb-0">
                        <div class="social-icon-wrapper bg-fb">
                            <i class="fa-brands fa-facebook-f"></i>
                        </div>
                        <div class="social-stats">
                            <div class="social-stat-item">
                                <h4>{{ $fb_likes }}</h4>
                                <p>Likes</p>
                            </div>
                            <div class="social-stat-item text-right">
                                <h4>{{ $fb_followers }}</h4>
                                <p>Follows</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Instagram -->
            <div class="col-xl-3 col-lg-6 col-sm-6 mb-4">
                <a href="{{ $ig_link }}" target="_blank">
                    <div class="glass-card social-widget-modern mb-0">
                        <div class="social-icon-wrapper bg-ig">
                            <i class="fa-brands fa-instagram"></i>
                        </div>
                        <div class="social-stats">
                            <div class="social-stat-item">
                                <h4>{{ $ig_posts }}</h4>
                                <p>Posts</p>
                            </div>
                            <div class="social-stat-item text-right">
                                <h4>{{ $ig_followers }}</h4>
                                <p>Follows</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- YouTube -->
            <div class="col-xl-3 col-lg-6 col-sm-6 mb-4">
                <a href="{{ $yt_link }}" target="_blank">
                    <div class="glass-card social-widget-modern mb-0">
                        <div class="social-icon-wrapper bg-yt">
                            <i class="fa-brands fa-youtube"></i>
                        </div>
                        <div class="social-stats">
                            <div class="social-stat-item">
                                <h4>{{ $yt_videos }}</h4>
                                <p>Videos</p>
                            </div>
                            <div class="social-stat-item text-right">
                                <h4>{{ $yt_subscribers }}</h4>
                                <p>Subs</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- TikTok -->
            <div class="col-xl-3 col-lg-6 col-sm-6 mb-4">
                <a href="{{ $tt_link }}" target="_blank">
                    <div class="glass-card social-widget-modern mb-0">
                        <div class="social-icon-wrapper bg-tt">
                            <i class="fa-brands fa-tiktok"></i>
                        </div>
                        <div class="social-stats">
                            <div class="social-stat-item">
                                <h4>{{ $tt_posts }}</h4>
                                <p>Posts</p>
                            </div>
                            <div class="social-stat-item text-right">
                                <h4>{{ $tt_followers }}</h4>
                                <p>Follows</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Hidden Elements & FB Plugin -->
<div class="fb-page d-none" data-href="https://www.facebook.com/looksmenstore" data-tabs="" data-width=""
    data-height="" data-small-header="true" data-adapt-container-width="true" data-hide-cover="true"
    data-show-facepile="true">
    <blockquote cite="https://www.facebook.com/looksmenstore" class="fb-xfbml-parse-ignore">
        <a href="https://www.facebook.com/looksmenstore">𝐋𝐎𝐎𝐊𝐒𝐌𝐄𝐍</a>
    </blockquote>
</div>
<style>
    #hiddenNumber {
        display: none;
        font-weight: bold;
    }
</style>

@endsection

@section('script')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const centerTextPlugin = {
                id: "centerText",
                beforeDraw: function(chart) {
                    const chartInstance = chart.chart || chart;
                    const { width, height, ctx } = chartInstance;
                    const dataset = chartInstance.config.data.datasets[0];
                    const value = dataset.data[0];
                    const color = dataset.backgroundColor[0];

                    ctx.restore();
                    // Relative font scaling
                    const fontSize = (height / 120).toFixed(2);
                    ctx.font = "bold " + Math.max(14, Math.min(24, fontSize * 18)) + "px 'Outfit', sans-serif";
                    ctx.textBaseline = "middle";
                    ctx.textAlign = "center";
                    ctx.fillStyle = color;
                    
                    // Position text in the lower middle for the gauge style
                    const textY = height - (height * 0.15);
                    ctx.fillText(value.toFixed(1) + "%", width / 2, textY);
                    ctx.save();
                }
            };

            // Version safe registration
            const isV2 = Chart.version && Chart.version.indexOf('2.') === 0;
            if (isV2) {
                Chart.plugins.register(centerTextPlugin);
            } else {
                Chart.register(centerTextPlugin);
            }

            const chartData = [
                {
                    id: "confirmationChart",
                    color: "#6366f1", // Indigo
                    value: {{ $confirmationRate }},
                },
                {
                    id: "pendingChart",
                    color: "#f59e0b", // Yellow
                    value: {{ $pendingRate }},
                },
                {
                    id: "deliverySuccessChart",
                    color: "#10b981", // Green
                    value: {{ $deliveryRate }},
                },
                {
                    id: "cancelChart",
                    color: "#ef4444", // Red
                    value: {{ $cancelRate }},
                },
            ];

            chartData.forEach(function(chart) {
                const canvas = document.getElementById(chart.id);
                if (!canvas) return;
                const ctx = canvas.getContext("2d");

                new Chart(ctx, {
                    type: "doughnut",
                    data: {
                        datasets: [{
                            data: [chart.value, 100 - chart.value],
                            backgroundColor: [chart.color, "rgba(226, 232, 240, 0.5)"],
                            borderWidth: 0,
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        rotation: isV2 ? -0.5 * Math.PI : -90,
                        circumference: isV2 ? Math.PI : 180,
                        cutoutPercentage: 80,
                        cutout: "80%",
                        legend: {
                            display: false
                        },
                        tooltips: {
                            enabled: true,
                            backgroundColor: 'rgba(30, 41, 59, 0.9)',
                            titleFontFamily: "'Outfit', sans-serif",
                            bodyFontFamily: "'Outfit', sans-serif",
                            padding: 12,
                            cornerRadius: 8
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                enabled: true,
                                backgroundColor: 'rgba(30, 41, 59, 0.9)',
                                titleFont: { family: "'Outfit', sans-serif" },
                                bodyFont: { family: "'Outfit', sans-serif" },
                                padding: 12,
                                cornerRadius: 8
                            }
                        },
                        animation: {
                            duration: 1500,
                            easing: 'easeOutQuart'
                        }
                    }
                });
            });
        });
    </script>
    <script>
        @if(session('success'))
        Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,   
            timer: 3000,
            timerProgressBar: true,
            background: 'rgba(255, 255, 255, 0.95)',
            customClass: {
                popup: 'glass-card'
            }
        }).fire({
            icon: 'success',
            title: '{{ session('success') }}'
        });
        @endif
    </script>
@endsection
