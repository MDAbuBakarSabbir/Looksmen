<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WalletTransaction;
use App\Models\PointTransaction;
use App\Models\User;
use App\Models\GeneralWebSettings;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminWalletPointController extends Controller
{
    public function transactions()
    {
        $transactions = WalletTransaction::with('user')->latest()->paginate(15, ['*'], 'tx_page');
        $point_transactions = PointTransaction::with('user', 'order')->latest()->paginate(15, ['*'], 'point_page');
        $users = User::orderBy('name')->get();

        return view('AdminDash.wallet.index', compact('transactions', 'point_transactions', 'users'));
    }

    public function manualRecharges()
    {
        $pending_recharges = WalletTransaction::with('user')
            ->where('payment_method', 'manual_deposit')
            ->where('status', 'pending')
            ->latest()
            ->paginate(15);

        return view('AdminDash.wallet.manual_recharges', compact('pending_recharges'));
    }

    public function approveRecharge($id)
    {
        $transaction = WalletTransaction::findOrFail($id);
        if ($transaction->status !== 'pending') {
            flash('This transaction has already been processed.')->warning();
            return back();
        }

        try {
            DB::beginTransaction();

            $user = User::findOrFail($transaction->user_id);
            $user->wallet_balance += $transaction->amount;
            $user->save();

            $transaction->status = 'approved';
            $transaction->save();

            DB::commit();
            flash('Recharge request approved and balance credited successfully.')->success();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Admin approve recharge error: ' . $e->getMessage());
            flash('Error processing approval request: ' . $e->getMessage())->error();
        }

        return back();
    }

    public function rejectRecharge($id)
    {
        $transaction = WalletTransaction::findOrFail($id);
        if ($transaction->status !== 'pending') {
            flash('This transaction has already been processed.')->warning();
            return back();
        }

        $transaction->status = 'failed';
        $transaction->save();

        flash('Recharge request rejected successfully.')->success();
        return back();
    }

    public function adjustWallet(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:1',
            'action' => 'required|in:credit,debit',
            'note' => 'nullable|string|max:255'
        ]);

        $user = User::findOrFail($request->user_id);
        $amount = (float) $request->amount;

        if ($request->action === 'debit' && $user->wallet_balance < $amount) {
            flash('User has insufficient wallet balance for this deduction.')->error();
            return back();
        }

        try {
            DB::beginTransaction();

            if ($request->action === 'credit') {
                $user->wallet_balance += $amount;
            } else {
                $user->wallet_balance -= $amount;
            }
            $user->save();

            WalletTransaction::create([
                'user_id' => $user->id,
                'amount' => $amount,
                'payment_method' => 'admin_adjustment',
                'type' => $request->action,
                'status' => 'approved',
                'payment_details' => json_encode([
                    'note' => $request->note ?? 'Admin manual adjustment',
                    'adjusted_by' => auth()->guard('admin')->id()
                ])
            ]);

            DB::commit();
            flash('User wallet balance adjusted successfully!')->success();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Admin wallet adjustment error: ' . $e->getMessage());
            flash('Something went wrong during adjustment: ' . $e->getMessage())->error();
        }

        return back();
    }

    public function pointConfig()
    {
        $point_conversion_rate = GeneralWebSettings::where('name', 'point_conversion_rate')->first();
        $points_per_taka = GeneralWebSettings::where('name', 'points_per_taka')->first();

        return view('AdminDash.wallet.points_config', compact('point_conversion_rate', 'points_per_taka'));
    }

    public function pointConfigStore(Request $request)
    {
        $request->validate([
            'point_conversion_rate' => 'required|integer|min:1',
            'points_per_taka' => 'required|numeric|min:0'
        ]);

        GeneralWebSettings::updateOrCreate(
            ['name' => 'point_conversion_rate'],
            ['value' => $request->point_conversion_rate, 'status' => '1']
        );

        GeneralWebSettings::updateOrCreate(
            ['name' => 'points_per_taka'],
            ['value' => $request->points_per_taka, 'status' => '1']
        );

        flash('Point configuration updated successfully!')->success();
        return back();
    }
}
