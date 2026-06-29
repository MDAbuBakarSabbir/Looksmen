<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Color;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    public function index()
    {
        $colors = Color::all();
        return view('adminDash.colour.index', compact('colors'));
    }
    public function create()
    {
        return view('adminDash.category.main.create');
    }
    public function joma(Request $request)
    {
        $request->validate([
            'color_name' => 'required',
            'color_code' => 'required',
        ]);
        Color::create([
            'color_name' => $request->color_name,
            'color_code' => $request->color_code,
            'created_at' => now(),
        ]);
        return back();
    }
    public function edit($id)
    {
        $color = Color::findOrFail($id);
        return view('adminDash.colour.edit', compact('color'));
    }

    public function update(Request $request, $id)
    {
        $color = Color::findOrFail($id);
        $request->validate([
            'color_name' => 'required',
            'color_code' => 'required',
        ]);
        $color->update([
            'color_name' => $request->color_name,
            'color_code' => $request->color_code,
            'updated_at' => now(),
        ]);
        return redirect()->route('color.index')->with('success', 'Color updated successfully');
    }

    public function destroy($id)
    {
        $color = Color::findOrFail($id);
        $color->delete();
        return back()->with('success', 'Color deleted successfully');
    }
    // public function status($id) {
    //     $color = Color::where('id',$id)->first();
    //     if($color->status == '1'){
    //         Color::find($color->id)->update([
    //             'status'=>'0',
    //         ]);
    //     }else{
    //         Color::find($color->id)->update([
    //                 'status'=>'1',
    //         ]);
    //     }
    //     return redirect()->back();
    // }
    public function status(Request $request)
    {
        $color = Color::find($request->id);

        if (!$color) {
            return response()->json(['success' => false]);
        }

        // Only allow 0 and 1
        $color->status = $request->status == 1 ? 1 : 0;
        $color->save();

        return response()->json([
            'success' => true,
            'status' => $color->status
        ]);
    }


    public function imgstore(Request $request)
    {
        return $request;
        // $request->validate([
        //     'images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        // ]);

        // foreach ($request->file('images') as $image) {
        //     $path = $image->store('uploads', 'public');
        //     // Save path to DB if needed
        // }

        // return back()->with('success', 'Images uploaded successfully!');
    }
}
