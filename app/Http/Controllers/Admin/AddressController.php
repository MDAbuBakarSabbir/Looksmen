<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Thana;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function address(Request $request){
        $district_query = District::query();
        if ($request->filled('district_search')) {
            $district_query->where('name', 'LIKE', '%' . $request->district_search . '%');
        }
        $districts = $district_query->paginate(15, ['*'], 'district_page');

        $thana_query = Thana::with('district');
        if ($request->filled('thana_search')) {
            $thana_query->where('name', 'LIKE', '%' . $request->thana_search . '%')
                        ->orWhereHas('district', function($q) use ($request) {
                            $q->where('name', 'LIKE', '%' . $request->thana_search . '%');
                        });
        }
        $thanas = $thana_query->paginate(15, ['*'], 'thana_page');

        // If AJAX request for District list/search
        if ($request->ajax() && $request->ajax_type == 'district') {
            return view('adminDash.settings.address.district', compact('districts'))->render();
        }

        // If AJAX request for Thana list/search
        if ($request->ajax() && $request->ajax_type == 'thana') {
            return view('adminDash.settings.address.thana', compact('thanas'))->render();
        }

        return view('adminDash.settings.address', compact('districts', 'thanas'));
    }



    public function districtstore(Request $request){
        $validated = $request->validate([
            'city_name' => 'required|string|max:255',
            'delivery_charge' => 'required|numeric|min:0',
        ]);

        $district = new District();
        $district->name = $validated['city_name'];
        $district->delivery_charge = $validated['delivery_charge'];
        $district->status = 1;
        $district->save();

        return redirect()->back()->with('success', 'District added successfully!');
    }
    public function districtstatus(Request $request){
        $district = District::find($request->id);

        if (!$district) {
            return response()->json(['success' => false]);
        }

        // Only allow 0 and 1
        $district->status = $request->status == 1 ? 1 : 0;
        $district->save();

        return response()->json([
            'success' => true,
            'status' => $district->status
        ]);
    }
    public function thanastore(Request $request){
        $validated = $request->validate([
            'district_id' => 'required|exists:districts,id',
            'city_name' => 'required|string|max:255',
        ]);

        $thana = new Thana();
        $thana->district_id = $validated['district_id'];
        $thana->name = $validated['city_name'];
        $thana->status = 1;
        $thana->save();

        return redirect()->back()->with('success', 'Thana added successfully!');
    }
    public function thanastatus(Request $request){
        $thana = Thana::find($request->id);

        if (!$thana) {
            return response()->json(['success' => false]);
        }

        // Only allow 0 and 1
        $thana->status = $request->status == 1 ? 1 : 0;
        $thana->save();

        return response()->json([
            'success' => true,
            'status' => $thana->status
        ]);
    }

    public function districtupdate(Request $request, $id){
        $validated = $request->validate([
            'city_name' => 'required|string|max:255',
            'delivery_charge' => 'required|numeric|min:0',
        ]);

        $district = District::findOrFail($id);
        $district->name = $validated['city_name'];
        $district->delivery_charge = $validated['delivery_charge'];
        $district->save();

        return redirect()->back()->with('success', 'District updated successfully!');
    }

    public function districtdestroy($id){
        $district = District::findOrFail($id);
        
        // Also delete child thanas to maintain db integrity
        $district->thanas()->delete(); 
        
        $district->delete();

        return redirect()->back()->with('success', 'District deleted successfully!');
    }

    public function thanaupdate(Request $request, $id){
        $validated = $request->validate([
            'district_id' => 'required|exists:districts,id',
            'city_name' => 'required|string|max:255',
        ]);

        $thana = Thana::findOrFail($id);
        $thana->district_id = $validated['district_id'];
        $thana->name = $validated['city_name'];
        $thana->save();

        return redirect()->back()->with('success', 'Thana updated successfully!');
    }

    public function thanadestroy($id){
        $thana = Thana::findOrFail($id);
        $thana->delete();

        return redirect()->back()->with('success', 'Thana deleted successfully!');
    }


}
