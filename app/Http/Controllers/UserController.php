<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Orders;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display registered customers list.
     */
    public function regCustomer(Request $request)
    {
        $search = $request->search;
        $query = User::where('is_blocked', false);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $customers = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('adminDash.customers.registered', compact('customers', 'search'));
    }

    /**
     * Display guest/non-registered customers (extracted from orders with user_id = '0').
     */
    public function nonRegCustomer(Request $request)
    {
        $search = $request->search;

        // Group guest orders by phone and get total amount and count
        $query = Orders::select('name', 'phone', 'address', DB::raw('COUNT(id) as total_orders'), DB::raw('SUM(grand_total) as total_spent'))
            ->where('user_id', '0');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $customers = $query->groupBy('phone', 'name', 'address')
            ->orderBy('total_spent', 'desc')
            ->paginate(15);

        return view('adminDash.customers.non_registered', compact('customers', 'search'));
    }

    /**
     * Display blocked customers list.
     */
    public function customerBlock(Request $request)
    {
        $search = $request->search;
        $query = User::where('is_blocked', true);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $customers = $query->orderBy('updated_at', 'desc')->paginate(15);

        return view('adminDash.customers.blocked', compact('customers', 'search'));
    }

    /**
     * Toggle blocking status of a registered customer.
     */
    public function toggleBlock(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'is_blocked' => 'required|boolean'
        ]);

        $user->is_blocked = $request->is_blocked;
        $user->save();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $user->is_blocked ? 'Customer blocked successfully.' : 'Customer unblocked successfully.',
                'is_blocked' => $user->is_blocked
            ]);
        }

        return back()->with('success', $user->is_blocked ? 'Customer blocked.' : 'Customer unblocked.');
    }

    /* =========================================================================
     * IP BLOCKING METHODS
     * ======================================================================= */

    /**
     * Display blocked IPs list.
     */
    public function customeripBlock(Request $request)
    {
        $search = $request->search;
        $query = DB::table('blocked_ips');

        if ($search) {
            $query->where('ip_address', 'like', "%{$search}%")
                  ->orWhere('reason', 'like', "%{$search}%");
        }

        $blockedIps = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('adminDash.customers.ip_blocked', compact('blockedIps', 'search'));
    }

    /**
     * Block a new IP address.
     */
    public function storeIpBlock(Request $request)
    {
        $request->validate([
            'ip_address' => 'required|ip|unique:blocked_ips,ip_address',
            'reason' => 'nullable|string|max:255',
        ]);

        DB::table('blocked_ips')->insert([
            'ip_address' => $request->ip_address,
            'reason' => $request->reason,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'IP address blocked successfully.');
    }

    /**
     * Unblock a blocked IP address.
     */
    public function destroyIpBlock($id)
    {
        DB::table('blocked_ips')->where('id', $id)->delete();

        return back()->with('success', 'IP address unblocked successfully.');
    }
}
