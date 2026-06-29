<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\FeatureActivation;
use App\Models\FraudCheck;
use App\Models\GeneralWebSettings;
use App\Models\Logs;
use App\Models\Orders;
use App\Models\OrderDetails;
use App\Models\Product;
use App\Models\Thana;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Services\SteadfastService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Exception;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Support\Facades\DB;

class OrderManageController extends Controller
{
    /**
     * Checks if the authenticated admin has permission to view/manage the given order.
     */
    protected function checkOrderAccess(Orders $order)
    {
        $user = auth()->guard('admin')->user();
        if (!$user) {
            abort(403, 'Unauthorized.');
        }

        // Master admin bypasses all checks
        if ($user->role_id === 'admin') {
            return;
        }

        // Check manage_order permission
        if ($user->hasPermission('manage_order')) {
            return;
        }

        // Check specific status permissions
        $status = $order->delivery_status;
        $permission = match ($status) {
            'new' => 'hold_order',
            'pending' => 'pending_order',
            'approved' => 'approved_order',
            'packaging' => 'packaging_order',
            'in_courier', 'unknown', 'in_review', 'hold',
            'unknown_approval_pending', 'cancelled_approval_pending',
            'partial_delivered_approval_pending', 'delivered_approval_pending' => 'shipment_order',
            'delivered', 'partial_delivered' => 'delivered_order',
            'cancel', 'cancelled' => 'canceled_order',
            'returned' => 'return_order',
            default => null
        };

        if (!$permission || !$user->hasPermission($permission)) {
            abort(403, 'You do not have permission to access this order.');
        }
    }

    public function filter(Request $request)
    {
        $status = $request->status;
        $user = auth()->guard('admin')->user();
        
        if (empty($status) && !$user->hasPermission('manage_order')) {
            abort(403, 'Unauthorized.');
        }
        if ($status === 'pending' && !$user->hasPermission('pending_order')) {
            abort(403, 'Unauthorized.');
        }
        if ($status === 'new' && !$user->hasPermission('hold_order')) {
            abort(403, 'Unauthorized.');
        }
        if ($status === 'approved' && !$user->hasPermission('approved_order')) {
            abort(403, 'Unauthorized.');
        }
        if ($status === 'packaging' && !$user->hasPermission('packaging_order')) {
            abort(403, 'Unauthorized.');
        }
        if ($status === 'in_courier' && !$user->hasPermission('shipment_order')) {
            abort(403, 'Unauthorized.');
        }
        if (in_array($status, ['delivered', 'partial_delivered']) && !$user->hasPermission('delivered_order')) {
            abort(403, 'Unauthorized.');
        }
        if (in_array($status, ['cancel', 'cancelled']) && !$user->hasPermission('canceled_order')) {
            abort(403, 'Unauthorized.');
        }
        if ($status === 'returned' && !$user->hasPermission('return_order')) {
            abort(403, 'Unauthorized.');
        }

        $query = Orders::query();

        // STATUS
        if ($request->status) {
            $query->where('delivery_status', $request->status);
        }

        // SEARCH
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('id', 'like', "%{$request->search}%")
                    ->orWhere('phone', 'like', "%{$request->search}%");
            });
        }

        // DATE RANGE
        if ($request->from && $request->to) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->from)->startOfDay(),
                Carbon::parse($request->to)->endOfDay()
            ]);
        }

        // DAYS FILTER
        if ($request->days) {
            match ($request->days) {
                'today' => $query->whereDate('created_at', today()),
                'yesterday' => $query->whereDate('created_at', today()->subDay()),
                '7days' => $query->where('created_at', '>=', now()->subDays(7)),
                '30days' => $query->where('created_at', '>=', now()->subDays(30)),
                'this_year' => $query->whereYear('created_at', now()->year),
                'last_year' => $query->whereYear('created_at', now()->subYear()->year),
                default => null
            };
        }

        // ADMIN FILTER
        if ($request->admin_id) {
            $query->where('updated_by', $request->admin_id);
        }

        $orders = $query->latest()->get();

        return view('adminDash.orders.extends.order_rows', compact('orders'))->render();
    }

    public function updateStatus(Request $request)
    {
        $order = Orders::find($request->id);

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Order not found!']);
        }

        $this->checkOrderAccess($order);

        // Auto courier entry on status changed to in-courier
        if (in_array($request->status, ['incourier', 'in_courier'])) {
            if (!$order->consignment_id) {
                $courierRes = $this->placeCourierOrderInternal($order);
                if ($courierRes['status'] === 'error') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Courier booking failed: ' . $courierRes['message']
                    ]);
                }
            } else {
                $order->delivery_status = 'incourier';
                $order->save();
            }
        } else {
            $order->delivery_status = $request->status;
            $order->updated_by = auth()->id();
            $order->save();
        }

        $logs = new Logs();
        $logs->user_id = auth()->id();
        $logs->order_id = $request->id;
        $logs->action_type = 'status_update';
        $logs->details = 'Order status changed to ' . $order->delivery_status;
        $logs->order_status = $order->delivery_status;
        $logs->save();

        if ($order->delivery_status == 'delivered') {
            try {
                $affiliateController = new \App\Http\Controllers\Admin\affiliate\AffiliateController();
                $affiliateController->processAffiliatePoints($order);
            } catch (\Exception $e) {
                \Log::error('Affiliate status processing error: ' . $e->getMessage());
            }
            $this->processOrderPoints($order);
        }

        $badgeClass = ($order->delivery_status == 'cancel') ? 'bg-danger' : 'bg-info';
        $statusText = ucfirst($order->delivery_status);
        $view = view('adminDash.orders.extends.buttons', compact('order'))->render();

        return response()->json([
            'success' => true,
            'status' => $order->delivery_status,
            'status_text' => $statusText,
            'badge_class' => $badgeClass,
            'order_id' => $order->id,
            'new_dropdown' => $view,
            'consignment_id' => $order->consignment_id,
            'tracking_code' => $order->tracking_code
        ]);
    }

    public function bulkUpdate(Request $request)
    {
        if ($request->status == 'delivered') {
            $orders = Orders::whereIn('id', $request->ids)->get();
            foreach ($orders as $order) {
                $order->delivery_status = $request->status;
                $order->save();
                try {
                    $affiliateController = new \App\Http\Controllers\Admin\affiliate\AffiliateController();
                    $affiliateController->processAffiliatePoints($order);
                } catch (\Exception $e) {
                    \Log::error('Affiliate bulk processing error: ' . $e->getMessage());
                }
                $this->processOrderPoints($order);
            }
        } else {
            Orders::whereIn('id', $request->ids)
                ->update(['delivery_status' => $request->status]);
        }

        return response()->json(['success' => true]);
    }



    public function statusCount()
    {
        return response()->json([
            'new'        => Orders::where('delivery_status', 'new')->count(),
            'pending'    => Orders::where('delivery_status', 'pending')->count(),
            'approved'   => Orders::where('delivery_status', 'approved')->count(),
            'packaging'  => Orders::where('delivery_status', 'packaging')->count(),
            'in_courier' => Orders::where('delivery_status', 'in_courier')->count(),
            'delivered'  => Orders::where('delivery_status', 'delivered')->count(),
            'canceled'   => Orders::where('delivery_status', 'canceled')->count(),
            'returned'   => Orders::where('delivery_status', 'returned')->count(),
        ]);
    }



















    public function index()
    {
        if (!auth()->guard('admin')->user()->hasPermission('manage_order')) {
            abort(403, 'You do not have permission to manage orders.');
        }
        $countorders = Orders::with('admin')->latest()->get();
        return view('adminDash.orders.all', compact('countorders'));
    }
    public function new()
    {
        if (!auth()->guard('admin')->user()->hasPermission('hold_order')) {
            abort(403, 'You do not have permission to view hold orders.');
        }
        $countorders = Orders::all();
        $orders = Orders::where('delivery_status', 'new')->latest()->get();
        return view('adminDash.orders.all', compact('orders', 'countorders'));
    }
    public function pending()
    {
        if (!auth()->guard('admin')->user()->hasPermission('pending_order')) {
            abort(403, 'You do not have permission to view pending orders.');
        }
        $countorders = Orders::all();
        $orders = Orders::where('delivery_status', 'pending')->latest()->get();
        return view('adminDash.orders.all', compact('orders', 'countorders'));
    }
    public function approved()
    {
        if (!auth()->guard('admin')->user()->hasPermission('approved_order')) {
            abort(403, 'You do not have permission to view approved orders.');
        }
        $countorders = Orders::all();
        $orders = Orders::where('delivery_status', 'approved')->latest()->get();
        return view('adminDash.orders.all', compact('orders', 'countorders'));
    }
    public function packaging()
    {
        if (!auth()->guard('admin')->user()->hasPermission('packaging_order')) {
            abort(403, 'You do not have permission to view packaging orders.');
        }
        $countorders = Orders::all();
        $orders = Orders::where('delivery_status', 'packaging')->latest()->get();
        return view('adminDash.orders.all', compact('orders', 'countorders'));
    }
    public function incourier()
    {
        if (!auth()->guard('admin')->user()->hasPermission('shipment_order')) {
            abort(403, 'You do not have permission to view in-courier orders.');
        }
        $countorders = Orders::all();
        $orders = Orders::whereIn('delivery_status', [
            'in_courier', 'unknown', 'in_review', 'hold',
            'unknown_approval_pending', 'cancelled_approval_pending',
            'partial_delivered_approval_pending', 'delivered_approval_pending', 'pending'
        ])->latest()->get();
        return view('adminDash.orders.all', compact('orders', 'countorders'));
    }
    public function delivered()
    {
        if (!auth()->guard('admin')->user()->hasPermission('delivered_order')) {
            abort(403, 'You do not have permission to view delivered orders.');
        }
        $countorders = Orders::all();
        $orders = Orders::whereIn('delivery_status', ['delivered', 'partial_delivered'])->latest()->get();
        return view('adminDash.orders.all', compact('orders', 'countorders'));
    }
    public function canceled()
    {
        if (!auth()->guard('admin')->user()->hasPermission('canceled_order')) {
            abort(403, 'You do not have permission to view canceled orders.');
        }
        $countorders = Orders::all();
        $orders = Orders::whereIn('delivery_status', ['cancel', 'cancelled'])->latest()->get();
        return view('adminDash.orders.all', compact('orders', 'countorders'));
    }
    public function returned()
    {
        if (!auth()->guard('admin')->user()->hasPermission('return_order')) {
            abort(403, 'You do not have permission to view returned orders.');
        }
        $countorders = Orders::all();
        $orders = Orders::where('delivery_status', 'returned')->latest()->get();
        return view('adminDash.orders.all', compact('orders', 'countorders'));
    }


    public function getUpazilas($districtId)
    {
        $upazilas = Thana::where('district_id', $districtId)
            ->where('status', '1')
            ->get();

        return response()->json($upazilas);
    }

    public function searchProducts(Request $request)
    {
        $term = $request->term;

        // নাম অথবা কোড দিয়ে সার্চ করা (শুধুমাত্র একটিভ প্রোডাক্ট এবং রিলেশনস)
        $products = Product::with(['firstImage', 'productAttributes.attribute', 'productColors.color'])
            ->where('status', '1')
            ->where(function ($q) use ($term) {
                $q->where('title', 'LIKE', '%' . $term . '%')
                  ->orWhere('code', 'LIKE', '%' . $term . '%');
            })
            ->limit(10)
            ->get();

        // প্রতিটি প্রোডাক্টের জন্য সঠিক থাম্বনেইল পাথ রিজলভ করে পাঠানো
        $products->each(function($product) {
            $imageSrc = asset('favicon.png');
            if ($product->firstImage) {
                $imgName = $product->firstImage->image;
                if (file_exists(public_path('adminDash/uploads/products/' . $imgName))) {
                    $imageSrc = asset('adminDash/uploads/products/' . $imgName);
                } elseif (file_exists(public_path('adminDash/images/product/' . $imgName))) {
                    $imageSrc = asset('adminDash/images/product/' . $imgName);
                } else {
                    $imageSrc = asset('adminDash/uploads/products/' . $imgName);
                }
            }
            $product->first_image_url = $imageSrc;
        });

        return response()->json($products);
    }
    // আপনার অর্ডারের মডেল

    // ... OrderController এর ভেতরে একটি মেথড ...

    protected $steadfast;

    public function __construct(SteadfastService $steadfast)
    {
        $this->steadfast = $steadfast;
    }

    // Helper: Place Order into Active Courier
    protected function placeCourierOrderInternal(Orders $order)
    {
        if ($order->consignment_id) {
            return [
                'status' => 'success',
                'message' => 'Order is already booked in courier.',
                'consignment_id' => $order->consignment_id,
                'tracking_code' => $order->tracking_code
            ];
        }

        $activeCourier = \App\Models\CourierApi::where('status', '1')->first();
        if (!$activeCourier) {
            return [
                'status' => 'error',
                'message' => 'No active courier API configured.'
            ];
        }

        if ($activeCourier->courier_name === 'steadfast') {
            $payload = [
                'invoice' => $order->order_id,
                'recipient_name' => $order->name,
                'recipient_phone' => $order->phone,
                'recipient_address' => $order->address,
                'cod_amount' => (float)$order->grand_total,
                'note' => $order->comments,
            ];

            $response = $this->steadfast->createOrder($payload);

            if (!isset($response['status']) || $response['status'] != 200) {
                $errorMsg = $response['message'] ?? 'Steadfast API error';
                return [
                    'status' => 'error',
                    'message' => 'Steadfast: ' . $errorMsg,
                    'api_response' => $response
                ];
            }

            $order->consignment_id = $response['consignment']['consignment_id'];
            $order->tracking_code = $response['consignment']['tracking_code'] ?? null;
            $order->delivery_status = 'incourier';
            $order->courier_updated_by = auth()->id();
            $order->courier_popup_shown = 0;
            $order->save();

            return [
                'status' => 'success',
                'message' => 'Successfully booked in Steadfast Courier.',
                'consignment_id' => $order->consignment_id,
                'tracking_code' => $order->tracking_code
            ];
        }

        return [
            'status' => 'error',
            'message' => 'Active courier ' . ucfirst($activeCourier->courier_name) . ' API integration not fully implemented.'
        ];
    }

    // AJAX: Manually Place Order in Courier
    public function placeCourierOrder($id)
    {
        $order = Orders::find($id);
        if (!$order) {
            return response()->json(['status' => 'error', 'message' => 'Order Not Found']);
        }

        $this->checkOrderAccess($order);

        $result = $this->placeCourierOrderInternal($order);

        if ($result['status'] === 'success') {
            return response()->json([
                'status' => 'success',
                'message' => $result['message'],
                'consignment_id' => $result['consignment_id'],
                'tracking_code' => $result['tracking_code']
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => $result['message'],
            'api_response' => $result['api_response'] ?? null
        ]);
    }

    // AJAX: Keep route compatibility
    public function placeSteadfastOrder($id)
    {
        return $this->placeCourierOrder($id);
    }

    // Mark popup seen
    public function popupSeen($id)
    {
        $order = Orders::find($id);
        if ($order) {
            $order->courier_popup_shown = 1;
            $order->save();
        }
        return response()->json(['status' => 'ok']);
    }

    // Cron: Update Status from Steadfast
    public function updateStatuses()
    {
        $activeCourier = \App\Models\CourierApi::where('status', '1')->first();
        if (!$activeCourier) {
            return "No active courier configuration.";
        }

        if ($activeCourier->courier_name === 'steadfast') {
            $orders = Orders::whereNotNull('consignment_id')
                ->whereNotIn('delivery_status', ['delivered', 'cancel', 'cancelled', 'returned'])
                ->get();

            $updatedCount = 0;
            foreach ($orders as $order) {
                $res = $this->steadfast->checkStatusByInvoice($order->order_id);
                if (isset($res['status']) && $res['status'] == 200 && isset($res['delivery_status'])) {
                    $newStatus = $res['delivery_status'];
                    if ($newStatus === 'cancelled') {
                        $newStatus = 'cancel';
                    }
                    if ($order->delivery_status !== $newStatus) {
                        $order->delivery_status = $newStatus;
                        $order->save();
                        $updatedCount++;

                        try {
                            $logs = new Logs();
                            $logs->user_id = 0; // system
                            $logs->order_id = $order->id;
                            $logs->action_type = 'status_update';
                            $logs->details = 'System auto-synced courier status to ' . $newStatus;
                            $logs->order_status = $newStatus;
                            $logs->save();
                        } catch (\Exception $e) {}
                    }
                }
            }
            return "Status Updated: {$updatedCount} orders updated.";
        }

        return "Active courier {$activeCourier->courier_name} auto-sync not implemented.";
    }

    // AJAX: Track Courier Order Details
    public function trackCourierOrder($id)
    {
        $order = Orders::find($id);
        if (!$order) {
            return response()->json(['status' => 'error', 'message' => 'Order Not Found']);
        }

        $this->checkOrderAccess($order);

        if (!$order->consignment_id) {
            return response()->json(['status' => 'error', 'message' => 'This order is not booked in any courier yet.']);
        }

        $activeCourier = \App\Models\CourierApi::where('status', '1')->first();
        if (!$activeCourier) {
            return response()->json(['status' => 'error', 'message' => 'No active courier configuration found.']);
        }

        if ($activeCourier->courier_name === 'steadfast') {
            $response = $this->steadfast->checkStatusByTrackingCode($order->tracking_code ?? $order->consignment_id);
            if (isset($response['status']) && $response['status'] == 200) {
                return response()->json([
                    'status' => 'success',
                    'courier' => 'Steadfast',
                    'consignment_id' => $order->consignment_id,
                    'tracking_code' => $order->tracking_code,
                    'delivery_status' => $response['delivery_status'] ?? 'unknown',
                    'raw_response' => $response
                ]);
            }
            
            $response = $this->steadfast->checkStatusByInvoice($order->order_id);
            if (isset($response['status']) && $response['status'] == 200) {
                return response()->json([
                    'status' => 'success',
                    'courier' => 'Steadfast',
                    'consignment_id' => $order->consignment_id,
                    'tracking_code' => $order->tracking_code,
                    'delivery_status' => $response['delivery_status'] ?? 'unknown',
                    'raw_response' => $response
                ]);
            }

            return response()->json(['status' => 'error', 'message' => 'Failed to retrieve tracking data from Steadfast API.', 'response' => $response]);
        }

        return response()->json(['status' => 'error', 'message' => 'Tracking for ' . ucfirst($activeCourier->courier_name) . ' is not implemented yet.']);
    }


    public function checkNewOrders(Request $request)
    {

        $seenOrderIds = session('seen_order_ids', []);

        // ২. নতুন (এবং অদেখা) অর্ডারগুলো খুঁজে বের করা
        $newOrders = Orders::where('created_at', '>', Carbon::now()->subHour(1)) // গত ১ ঘণ্টার মধ্যে তৈরি হয়েছে
            ->where('delivery_status', 'new') // শুধু পেন্ডিং অর্ডার দেখাবে
            ->whereNotIn('id', $seenOrderIds) // যা সেশনে দেখা হয়েছে তা বাদ দেওয়া
            ->get();

        $newOrderCount = $newOrders->count();

        // ৩. যদি নতুন অর্ডার পাওয়া যায়, তাহলে সেগুলোর ID সেশনে যুক্ত করা
        if ($newOrderCount > 0) {
            $newOrderIds = $newOrders->pluck('id')->toArray();

            // নতুন ID গুলো বিদ্যমান ID গুলোর সাথে যুক্ত করা
            $updatedSeenIds = array_unique(array_merge($seenOrderIds, $newOrderIds));

            // সেশন আপডেট করা
            session(['seen_order_ids' => $updatedSeenIds]);
        }

        // ৪. নতুন অর্ডারের সংখ্যা JSON ফরম্যাটে পাঠানো
        return response()->json([
            'new_count' => $newOrderCount
        ]);
    }








    public function create(Request $request)
    {
        $districts = District::where('status', '1')->get();
        $thanas = Thana::where('status', '1')->get();
        $incompleteOrder = null;
        if ($request->has('incomplete_id')) {
            $incompleteOrder = \App\Models\IncompleteOrders::find($request->incomplete_id);
        }
        return view('adminDash.orders.create', compact('districts', 'thanas', 'incompleteOrder'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'phone' => 'required|string',
            'address' => 'required|string',
            'product_ids' => 'required|array',
            'product_ids.*' => 'required|exists:products,id',
            'quantities' => 'required|array',
            'prices' => 'required|array',
        ]);

        try {
            DB::beginTransaction();

            $order = new Orders();
            $order->user_id = 0; // Created by admin
            $order->name = $request->name;
            $order->phone = $request->phone;
            $order->address = $request->address;
            $order->comments = $request->comments;
            $order->note = $request->note; // Customer note
            
            $order->delivery_status = $request->delivery_status ?? 'new';
            $order->payment_type = $request->payment_type ?? 'Cash On Delivery';
            $order->payment_status = 'pending';

            // Resolve district and thana names if IDs are provided
            if ($request->district_id) {
                $district = District::find($request->district_id);
                if ($district) {
                    $order->district = $district->name;
                }
            }
            if ($request->upazila_id) {
                $thana = Thana::find($request->upazila_id);
                if ($thana) {
                    $order->thana = $thana->name;
                }
            }

            $order->save(); // Save to generate the ID

            // Process order items
            $productIds = $request->product_ids ?? [];
            $sizes = $request->sizes ?? [];
            $colors = $request->colors ?? [];
            $quantities = $request->quantities ?? [];
            $prices = $request->prices ?? [];

            $totalAmount = 0;
            
            // Group the products by ID, size, and color to prevent duplicates
            $groupedProducts = [];
            for ($i = 0; $i < count($productIds); $i++) {
                if (empty($productIds[$i])) continue;
                
                $pId = $productIds[$i];
                $pSize = $sizes[$i] ?? 'N/A';
                $pColor = $colors[$i] ?? 'N/A';
                $pQty = (int)($quantities[$i] ?? 1);
                $pPrice = (float)($prices[$i] ?? 0);
                
                $key = $pId . '|' . $pSize . '|' . $pColor;
                
                if (isset($groupedProducts[$key])) {
                    $groupedProducts[$key]['qty'] += $pQty;
                } else {
                    $groupedProducts[$key] = [
                        'id' => $pId,
                        'size' => $pSize,
                        'color' => $pColor,
                        'qty' => $pQty,
                        'price' => $pPrice,
                    ];
                }
            }

            foreach ($groupedProducts as $item) {
                $detail = new OrderDetails();
                $detail->order_id = $order->id;
                $detail->product_id = $item['id'];
                $detail->product_attribute = $item['size'];
                $detail->product_colour = $item['color'];
                
                $detail->product_qty = $item['qty'];
                $detail->unit_price = $item['price'];
                $detail->total_price = $item['qty'] * $item['price'];
                $detail->save();

                $totalAmount += $detail->total_price;
            }

            // Update financials
            $order->total_amount = $totalAmount;
            $order->admin_discount = (float)($request->admin_discount ?? 0);
            $order->coupon_discount = (float)($request->coupon_discount ?? 0);
            $order->delivery_charge = (float)($request->delivery_charge ?? 0);
            $order->paid_amount = (float)($request->paid_amount ?? 0);

            $grandTotalCalculated = $totalAmount - $order->admin_discount - $order->coupon_discount + $order->delivery_charge;
            $order->grand_total = max(0, $grandTotalCalculated - $order->paid_amount);

            $order->created_by = auth()->id();
            $order->save();

            if ($request->has('incomplete_id')) {
                \App\Models\IncompleteOrders::where('id', $request->incomplete_id)->delete();
            }

            // Log order creation
            $logs = new Logs();
            $logs->user_id = auth()->id();
            $logs->order_id = $order->id;
            $logs->action_type = 'order_creation';
            $logs->details = 'New order created by admin: ' . (auth()->user()?->name ?? 'System');
            $logs->order_status = $order->delivery_status;
            $logs->save();

            DB::commit();
            return redirect()->route('admin.order-show', $order->id)->with('success', 'Order created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $districts = District::where('status', '1')->get();
        $thanas = Thana::where('status', '1')->get();
        $order = Orders::with([
            'orderDetails.orderProduct.firstImage',
            'orderDetails.orderProduct.productAttributes.attribute',
            'orderDetails.orderProduct.productColors.color'
        ])->findOrFail($id);

        $this->checkOrderAccess($order);

        return view('adminDash.orders.edit', compact('order', 'districts', 'thanas'));
    }



    public function getCourierHistory(Request $request)
    {
        $request->validate([
            'phone' => 'required|digits:11'
        ]);

        $webConfig = GeneralWebSettings::first()->pluck('value', 'name', 'status')->toArray();
        $apiKey = $webConfig['fraud_check_api_key'];
        $endpoint = $webConfig['fraud_check_api_url'] ?? 'https://api.bdcourier.com/courier-check';

        $response = Http::timeout(30)->withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
        ])->post($endpoint, [
            'phone' => $request->phone
        ]);

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch courier history'
            ], 400);
        }

        $data = $response->json()['courierData'] ?? null;

        if (!$data || !isset($data['summary'])) {
            return response()->json([
                'success' => false,
                'message' => 'No courier data found'
            ]);
        }

        return response()->json([
            'success' => true,
            'summary' => $data['summary'],
            'details' => collect($data)->except('summary')->values()
        ]);
    }

    public function show($id)
    {
        $order = Orders::where('id', $id)->first();
        if (!$order) {
            abort(404);
        }

        $this->checkOrderAccess($order);

        $orderLogs = Logs::with('user')->where('order_id', $id)->latest()->get();
        $phone = $order->phone;
        
        $features = FeatureActivation::all();
        $featuresConfig = $features->pluck('status', 'name')->toArray();

        $response = null;

        // শুধুমাত্র fraud_check_api সক্রিয় থাকলেই এপিআই কল করা হবে
        if (isset($featuresConfig['fraud_check_api']) && $featuresConfig['fraud_check_api'] == '1') {
            $fraudCheck = FraudCheck::first();
            if ($fraudCheck) {
                $apiKey = $fraudCheck->api_key;
                $endpoint = $fraudCheck->base_url;

                if ($apiKey && $endpoint) {
                    try {
                        // ব্যালেন্স চেক করার মতো ফ্রড চেক ডেটাও ১ ঘণ্টার জন্য ক্যাশ করা হচ্ছে (৩৬০০ সেকেন্ড)
                        $response = cache()->remember('fraud_check_' . $phone, 3600, function () use ($apiKey, $endpoint, $phone) {
                            $apiResponse = Http::withHeaders([
                                'Authorization' => 'Bearer ' . $apiKey,
                                'Content-Type' => 'application/json',
                            ])
                            ->timeout(3) // দীর্ঘক্ষণ পেজ লোডিং আটকানোর জন্য ৩ সেকেন্ড টাইমআউট
                            ->post($endpoint, [
                                'phone' => $phone,
                            ]);

                            if ($apiResponse->successful()) {
                                return $apiResponse->json();
                            }
                            return 'API failed to return status.';
                        });
                    } catch (\Exception $e) {
                        Log::error('Fraud Check API Call Exception: ' . $e->getMessage());
                        $response = 'Internal server error while fetching status.';
                    }
                }
            }
        }

        return view('adminDash.orders.show', compact('order', 'response', 'orderLogs'));
    }


    public function invoice($id)
    {
        $order = Orders::where('id', $id)->get()->first();
        if (!$order) {
            abort(404);
        }
        $this->checkOrderAccess($order);
        return view('adminDash.orders.invoice', compact('order'));
    }

    public function refreshCourierHistory($id)
    {
        $order = Orders::findOrFail($id);
        $this->checkOrderAccess($order);

        $phone = preg_replace('/[^0-9]/', '', $order->phone);
        if (strlen($phone) !== 11) {
            return response()->json(['status' => 'error', 'message' => 'Invalid phone number format. Must be 11 digits.']);
        }

        $fraudCheck = FraudCheck::first();
        if (!$fraudCheck || empty($fraudCheck->api_key) || empty($fraudCheck->base_url)) {
            return response()->json(['status' => 'error', 'message' => 'BD Courier API credentials are not configured.']);
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $fraudCheck->api_key,
            ])->timeout(5)->post($fraudCheck->base_url, [
                'phone' => $phone
            ]);

            if ($response->successful()) {
                $resData = $response->json();
                if (isset($resData['status']) && $resData['status'] == 'success') {
                    $order->courier_history = json_encode($resData);
                    $order->timestamps = false;
                    $order->save();
                    $order->timestamps = true;

                    // Clear laravel cache key
                    cache()->forget('fraud_check_' . $phone);

                    return response()->json([
                        'status' => 'success',
                        'message' => 'Courier history updated successfully!'
                    ]);
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => $resData['message'] ?? 'API responded with an error.'
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to reach BD Courier API (HTTP Status: ' . $response->status() . ')'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Server error: ' . $e->getMessage()
            ]);
        }
    }
    public function update(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'name' => 'required|string',
            'phone' => 'required|string',
            'address' => 'required|string',
        ]);

        $order = Orders::findOrFail($request->order_id);
        $this->checkOrderAccess($order);

        try {
            DB::beginTransaction();

            // 1. Update customer information
            $order->name = $request->name;
            $order->phone = $request->phone;
            $order->address = $request->address;
            $order->comments = $request->comments;
            $order->note = $request->note; // Customer note
            
            if ($request->has('delivery_status')) {
                // If status changed, log it
                if ($order->delivery_status !== $request->delivery_status) {
                    $logs = new Logs();
                    $logs->user_id = auth()->id();
                    $logs->order_id = $order->id;
                    $logs->action_type = 'status_update';
                    $logs->details = 'Order status changed from ' . $order->delivery_status . ' to ' . $request->delivery_status . ' via Order Edit';
                    $logs->order_status = $request->delivery_status;
                    $logs->save();
                    
                    $order->delivery_status = $request->delivery_status;
                }
            }

            // Update district and thana if IDs are provided
            if ($request->district_id) {
                $district = District::find($request->district_id);
                if ($district) {
                    $order->district = $district->name;
                }
            }
            if ($request->upazila_id) {
                $thana = Thana::find($request->upazila_id);
                if ($thana) {
                    $order->thana = $thana->name;
                }
            }

            // 2. Process order items / details
            // First, delete old order details
            OrderDetails::where('order_id', $order->id)->delete();

            // Insert new order details
            $productIds = $request->product_ids ?? [];
            $sizes = $request->sizes ?? [];
            $colors = $request->colors ?? [];
            $quantities = $request->quantities ?? [];
            $prices = $request->prices ?? [];

            $totalAmount = 0;

            // Group the products by ID, size, and color to prevent duplicates
            $groupedProducts = [];
            for ($i = 0; $i < count($productIds); $i++) {
                if (empty($productIds[$i])) continue;
                
                $pId = $productIds[$i];
                $pSize = $sizes[$i] ?? 'N/A';
                $pColor = $colors[$i] ?? 'N/A';
                $pQty = (int)($quantities[$i] ?? 1);
                $pPrice = (float)($prices[$i] ?? 0);
                
                $key = $pId . '|' . $pSize . '|' . $pColor;
                
                if (isset($groupedProducts[$key])) {
                    $groupedProducts[$key]['qty'] += $pQty;
                } else {
                    $groupedProducts[$key] = [
                        'id' => $pId,
                        'size' => $pSize,
                        'color' => $pColor,
                        'qty' => $pQty,
                        'price' => $pPrice,
                    ];
                }
            }

            foreach ($groupedProducts as $item) {
                $detail = new OrderDetails();
                $detail->order_id = $order->id;
                $detail->product_id = $item['id'];
                $detail->product_attribute = $item['size'];
                $detail->product_colour = $item['color'];
                
                $detail->product_qty = $item['qty'];
                $detail->unit_price = $item['price'];
                $detail->total_price = $item['qty'] * $item['price'];
                $detail->save();

                $totalAmount += $detail->total_price;
            }

            // 3. Update order financials
            $order->total_amount = $totalAmount;
            $order->admin_discount = (float)($request->admin_discount ?? 0);
            $order->coupon_discount = (float)($request->coupon_discount ?? 0);
            $order->delivery_charge = (float)($request->delivery_charge ?? 0);
            $order->paid_amount = (float)($request->paid_amount ?? 0);

            // Grand Total = Total - Admin Discount - Coupon Discount + Delivery Charge
            // COD/Due = Grand Total - Paid Amount
            $grandTotalCalculated = $totalAmount - $order->admin_discount - $order->coupon_discount + $order->delivery_charge;
            $order->grand_total = max(0, $grandTotalCalculated - $order->paid_amount);

            $order->updated_by = auth()->id();
            $order->save();

            if ($request->delivery_status == 'delivered') {
                try {
                    $affiliateController = new \App\Http\Controllers\Admin\affiliate\AffiliateController();
                    $affiliateController->processAffiliatePoints($order);
                } catch (\Exception $e) {
                    \Log::error('Affiliate edit processing error: ' . $e->getMessage());
                }
                $this->processOrderPoints($order);
            }

            DB::commit();
            return redirect()->route('admin.order-show', $order->id)->with('success', 'Order updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage())->withInput();
        }
    }


    public function destroy(Request $request)
    {
        //
    }

    /**
     * BDCourier API থেকে ফোন নম্বর ব্যবহার করে লাইভ স্ট্যাটাস নিয়ে আসে।
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function orderSearch(Request $request)
    {
        $user = auth()->guard('admin')->user();
        if (!$user || (
            !$user->hasPermission('manage_order') &&
            !$user->hasPermission('pending_order') &&
            !$user->hasPermission('hold_order') &&
            !$user->hasPermission('approved_order') &&
            !$user->hasPermission('packaging_order') &&
            !$user->hasPermission('shipment_order') &&
            !$user->hasPermission('delivered_order') &&
            !$user->hasPermission('canceled_order') &&
            !$user->hasPermission('return_order') &&
            !$user->hasPermission('incomplete_order')
        )) {
            abort(403, 'Unauthorized.');
        }

        $output = '';

        // Ajax থেকে আসা 'search' প্যারামিটার দিয়ে অনুসন্ধান
        $searchTerm = $request->search;

        // কোয়েরি: id অথবা phone দিয়ে সার্চ করবে
        $orders = Orders::where('id', 'Like', '%' . $searchTerm . '%')
            ->orWhere('phone', 'Like', '%' . $searchTerm . '%')
            ->get();

        // যদি কোনো অর্ডার না পাওয়া যায়
        if ($orders->isEmpty()) {
            $output .= '<tr><td colspan="8" class="text-center text-danger">No Order found.</td></tr>';
            return response($output);
        }

        // ফলাফলগুলো HTML আকারে তৈরি করা
        foreach ($orders as $order) {
            // **সংশোধন ১:** delivery_status কন্ডিশন (ternary operator) ব্র্যাকেট দিয়ে ঠিক করা হলো
            $statusDisplay = ($order->delivery_status == 'new')
                ? '<span class="text-danger">New Order</span>'
                : '<span class="text-success">' . ucfirst($order->delivery_status) . '</span>';

            $output .=
                '<tr>
            <td scope="row">' . '<input type="checkbox" class="order-check">' . '</td>
            <td class="text-dark font-weight-bold">' . $order->name . '<br>' .
                $order->phone . '<br>' .
                $order->address . '<br>
            </td>
            <td class="text-dark font-weight-bold">
                <img style="height: 100px; width: 100px" src="' . asset('favicon.png') . '" alt=""><br>' .
                'Drop Shouder T-Shirt' . '<br>' .
                'Size : XL ' . ' Colour :' . ' White' .
                '</td>
            <td class="text-dark font-weight-bold">LM-' . $order->id . '</td>
            <td class="text-dark font-weight-bold">
                Total: ' . $order->total_amount . ' BDT <br>
                Paid: ' . $order->paid_amount . ' BDT <br>
                Due: ' . $order->grand_total . ' BDT <br>
            </td>
            <td class="text-dark font-weight-bold">
                Status: ' . $statusDisplay . '<br>
                Created at: ' . $order->created_at . '<br>
                Created by: ' . $order->created_by . '<br>
            </td>
            <td class="text-dark font-weight-bold">' . $order->comments . '</td>
            <td class="text-dark font-weight-bold">
                <a href="' . route('admin.order-show', $order->id) . '"><i class="fa-solid fa-eye"></i></a>
                <a href="' . route('admin.order-edit', $order->id) . '"><i class="fa-solid fa-pen-to-square"></i></a>
                <a href="' . route('admin.order-destroy', $order->id) . '"><i class="fa-solid fa-trash"></i></a>
            </td>
        </tr>';
        }
        return response($output);
    }



    public function live()
    {
        $countorders = Orders::all();
        return view('adminDash.orders.live', compact('countorders'));
    }

    public function filterByStatus(Request $request)
    {
        $status = $request->input('delivery_status');

        // 1. Filter the orders
        $orders = Orders::query();

        if ($status && $status !== 'all') {
            $orders->where('delivery_status', $status);
        }

        $filteredOrders = $orders->get();

        // 2. Render the Blade partial for the table rows
        // It's best practice to move the table row loop into a dedicated partial view
        $html = view('adminDash.orders.extends.order_rows', ['countorders' => $filteredOrders])->render();

        // 3. Return the response
        return response()->json([
            'html' => $html
        ]);
    }

    public function orderAutocomplete(Request $request)
    {
        $user = auth()->guard('admin')->user();
        if (!$user || (
            !$user->hasPermission('manage_order') &&
            !$user->hasPermission('pending_order') &&
            !$user->hasPermission('hold_order') &&
            !$user->hasPermission('approved_order') &&
            !$user->hasPermission('packaging_order') &&
            !$user->hasPermission('shipment_order') &&
            !$user->hasPermission('delivered_order') &&
            !$user->hasPermission('canceled_order') &&
            !$user->hasPermission('return_order') &&
            !$user->hasPermission('incomplete_order')
        )) {
            return response()->json([]);
        }

        $term = $request->input('query');
        if (empty($term)) {
            return response()->json([]);
        }

        $orders = Orders::where('id', 'like', "%{$term}%")
            ->orWhere('phone', 'like', "%{$term}%")
            ->orWhere('name', 'like', "%{$term}%")
            ->limit(10)
            ->get(['id', 'name', 'phone']);

        $suggestions = $orders->map(function ($order) {
            return [
                'id' => $order->id,
                'name' => $order->name,
                'phone' => $order->phone,
                'label' => $order->name . ' - LM ' . $order->id,
                'url' => route('admin.order-show', $order->id)
            ];
        });

        return response()->json($suggestions);
    }

    private function processOrderPoints(Orders $order)
    {
        try {
            $features = FeatureActivation::pluck('status', 'name')->toArray();
            if (!isset($features['point_system']) || $features['point_system'] !== '1') {
                return;
            }

            if (!$order->user_id) {
                return;
            }

            $user = \App\Models\User::find($order->user_id);
            if (!$user) {
                return;
            }

            // Check if points were already processed/earned for this order
            $already_earned = \App\Models\PointTransaction::where('order_id', $order->id)
                ->where('type', 'earn')
                ->exists();
            if ($already_earned) {
                return;
            }

            $points_per_taka = (float) (GeneralWebSettings::where('name', 'points_per_taka')->first()->value ?? 0.1);
            $total_points_earned = 0;

            foreach ($order->orderDetails as $detail) {
                $product = $detail->orderProduct;
                if ($product) {
                    if ($product->points > 0) {
                        $total_points_earned += $detail->product_qty * $product->points;
                    } else {
                        $total_points_earned += floor($detail->total_price * $points_per_taka);
                    }
                }
            }

            if ($total_points_earned > 0) {
                $user->points += $total_points_earned;
                $user->save();

                \App\Models\PointTransaction::create([
                    'user_id' => $user->id,
                    'points' => $total_points_earned,
                    'type' => 'earn',
                    'order_id' => $order->id,
                    'details' => "Earned {$total_points_earned} points from order #LM-{$order->id}."
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error processing order points: ' . $e->getMessage());
        }
    }
}
