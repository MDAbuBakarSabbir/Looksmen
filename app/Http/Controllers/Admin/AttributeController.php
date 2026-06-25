<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\attributeValues;
use Illuminate\Http\Request;

class AttributeController extends Controller
{
    public function index()
    {
        $attributes = Attribute::all();
        return view('adminDash.attribute.index',compact('attributes'));
        // return Attribute::find(1)->AttributeValues;
    }
    public function create($id)
    {
        $attribute = Attribute::where('id',$id)->first();
        $attributeValues = attributeValues::where('attribute_id',$id)->get();
        return view('adminDash.attribute.create',compact('attribute','attributeValues'));
    }
    public function store(Request $request)
    {
        Attribute::create([
            'name'=>$request->attribute_name,
            'status'=>'1',
        ]);
        return back()->with('success','success');
    }
    public function valuestore(Request $request,$id)
    {
        attributeValues::create([
            'attribute_id'=>$id,
            'value'=>$request->value,
        ]);
        return back()->with('success','success');
    }
    public function edit()
    {
        return view('adminDash.category.main.edit');
    }
    public function update()
    {

    }
    public function destroy()
    {

    }
     public function status(Request $request)
    {
        $attribute = Attribute::find($request->id);

        if (!$attribute) {
            return response()->json(['success' => false]);
        }

        // Only allow 0 and 1
        $attribute->status = $request->status == 1 ? 1 : 0;
        $attribute->save();

        return response()->json([
            'success' => true,
            'status' => $attribute->status
        ]);
    }
}
