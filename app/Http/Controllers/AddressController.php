<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Thana;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function Symfony\Component\Clock\now;

class AddressController extends Controller
{
    public function getThanasByDistrict($district_id)
    {
        // আপনার মডেল অনুযায়ী Thana বা City টেবিল থেকে ডাটা আনুন
        $thanas = Thana::where('district_id', $district_id)->get(['id', 'name']);
        return response()->json($thanas);
    }
    public function store(Request $request)
    {
        $request->validate([
            'address'  => 'required',
            'phone'    => 'required |min_digits:11',
            'district_id' => 'required',
            'thana_id' => 'required',
        ]);

        Address::create([
            'user_id'  => Auth::user()->id,
            'name'  => $request->name ?? Auth::user()->name,
            'address'  => $request->address,
            'phone'    => $request->phone,
            'district_id' => $request->district_id,
            'thana_id' => $request->thana_id,
            'created_at' => now(),
        ]);

        return back()->with('success', 'Address added successfully!');
    }

    // ২. এডিটের জন্য ডাটা পাঠানো (AJAX এর জন্য)
    public function edit($id)
    {
        $address = Address::where('user_id', Auth::id())->findOrFail($id);

        // এটি জাভাস্ক্রিপ্টকে ডাটা পাঠাবে
        return response()->json($address);
    }

    // ৩. অ্যাড্রেস আপডেট করা
    public function update(Request $request, $id)
    {
        $address = Address::where('user_id', Auth::id())->findOrFail($id);

        $address->update([
            'address'  => $request->address,
            'phone'    => $request->phone,
            'state_id' => $request->state_id,
        ]);

        return back()->with('success', 'Address updated successfully!');
    }
    public function set_default(Request $request)
    {
        $user_id = auth()->id();
        $address_id = $request->id;
        Address::where('user_id', $user_id)->update(['set_default' => 0]);

        $address = Address::where('user_id', $user_id)->findOrFail($address_id);
        $address->set_default = 1;
        $address->save();

        return response()->json([
            'status' => 'success',
            'message' => 'অ্যাড্রেসটি ডিফল্ট হিসেবে সেট করা হয়েছে।'
        ]);
    }
    public function destroy($id)
{
    $address = Address::where('user_id', auth()->id())->findOrFail($id);
    $address->delete();

    return response()->json([
        'status' => 'success',
        'message' => 'ঠিকানাটি সফলভাবে মুছে ফেলা হয়েছে।'
    ]);
}
}
