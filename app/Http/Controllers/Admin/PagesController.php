<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pages;
use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function index(){
        $pages = Pages::all();
        return view('adminDash.pages.index',compact('pages'));
    }

    public function create(){
        return view('adminDash.pages.create');
    }

    public function store(Request $request){
        $request->validate([
            'page_name' => 'required|string',
            'english_description'=>'required|string'
        ]);
        Pages::create([
            'page_name'=> $request->page_name,
            'english_description'=> $request->english_description,
            'bangla_description'=> $request->bangla_description,
        ]);
        return back()->with('success', 'Page Create Successfull!');
    }

    public function status(Request $request)
    {
        $page = Pages::find($request->id);

        if (!$page) {
            return response()->json(['success' => false]);
        }

        // Only allow 0 and 1
        $page->status = $request->status == 1 ? 1 : 0;
        $page->save();

        return response()->json([
            'success' => true,
            'status' => $page->status
        ]);
    }


    public function edit($id){
        $pageData = Pages::where('id',$id)->first();
        return view('adminDash.pages.edit',compact('pageData'));
    }

public function destroy($id)
    {
        $page = Pages::where('id',$id)->first();
        try {
            // পেজ ডিলিট করার চেষ্টা
            $page->delete();

            // সফলভাবে ডিলিট হলে JSON রেসপন্স পাঠানো
            return response()->json([
                'success' => true,
                'message' => 'পেজটি সফলভাবে ডিলিট করা হয়েছে।'
            ], 200);

        } catch (\Exception $e) {
            // ডিলিট করার সময় কোনো ত্রুটি হলে
            // ত্রুটি বার্তা লগ করতে পারেন: Log::error($e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'পেজ ডিলিট করার সময় একটি ত্রুটি হয়েছে: ' . $e->getMessage()
            ], 500); // 500 Internal Server Error
        }
    }

}
