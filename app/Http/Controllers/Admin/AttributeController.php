<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AttributeValues;
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
        $attributeValues = AttributeValues::where('attribute_id',$id)->get();
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
        $request->validate([
            'value' => 'required',
        ]);

        $value = AttributeValues::create([
            'attribute_id'=>$id,
            'value'=>$request->value,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $value,
                'message' => 'Attribute value added successfully'
            ]);
        }

        return back()->with('success','success');
    }
    public function edit($id)
    {
        $attribute = Attribute::findOrFail($id);
        return view('adminDash.attribute.edit', compact('attribute'));
    }
    public function update(Request $request, $id)
    {
        $attribute = Attribute::findOrFail($id);
        $request->validate([
            'attribute_name' => 'required',
        ]);
        $attribute->update([
            'name' => $request->attribute_name,
        ]);
        return redirect()->route('attribute.index')->with('success', 'Attribute updated successfully');
    }
    public function destroy($id)
    {
        $attribute = Attribute::findOrFail($id);
        AttributeValues::where('attribute_id', $id)->delete();
        $attribute->delete();
        return back()->with('success', 'Attribute deleted successfully');
    }
    public function valueDestroy($id)
    {
        $value = AttributeValues::findOrFail($id);
        $value->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Attribute value deleted successfully'
            ]);
        }

        return back()->with('success', 'Attribute value deleted successfully');
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
