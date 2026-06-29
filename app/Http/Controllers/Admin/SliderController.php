<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

use function Symfony\Component\Clock\now;

class SliderController extends Controller
{
    public function index()
    {
        $sliders = Slider::latest()->get();

        return view('adminDash.slider&banner.slider.index', compact('sliders'));
    }

    public function create()
    {
        return view('adminDash.slider&banner.slider.create');
    }

    public function store(Request $request)
    {
        $manager = new ImageManager(new Driver);
        $request->validate([
            'image' => 'required|image',
        ]);
        if ($request->hasFile('image')) {
            $newname = Str::random(5).'.'.$request->file('image')->getClientOriginalExtension();
            $image = $manager->decode($request->file('image'));
            $image->save(base_path('public/adminDash/uploads/slider&banner/'.$newname));
        }

        $sliders = new Slider;
        $sliders->image = $newname;
        $sliders->url = $request->url;
        $sliders->created_at = now();
        $sliders->save();

        return response()->json([
            'success' => true,
            'data' => $sliders,
            'message' => 'Slider Added successfully!',
        ]);
        // return back()->with('success', 'Create successfull');
    }

    public function status(Request $request)
    {
        $slider = Slider::find($request->id);

        if (! $slider) {
            return response()->json(['success' => false]);
        }

        // Only allow 0 and 1
        $slider->status = $request->status == 1 ? 1 : 0;
        $slider->save();

        return response()->json([
            'success' => true,
            'status' => $slider->status,
        ]);
    }

    public function edit($id)
    {
        $slider = Slider::findOrFail($id);

        return view('adminDash.slider&banner.slider.edit', compact('slider'));
    }

    public function update(Request $request, $id)
    {
        $slider = Slider::findOrFail($id);

        $request->validate([
            'image' => 'nullable|image',
        ]);

        if ($request->hasFile('image')) {
            $oldPath = base_path('public/adminDash/uploads/slider&banner/'.$slider->image);
            if ($slider->image && file_exists($oldPath) && is_file($oldPath)) {
                unlink($oldPath);
            }

            $manager = new ImageManager(new Driver);
            $newname = Str::random(5).'.'.$request->file('image')->getClientOriginalExtension();
            $image = $manager->decode($request->file('image'));
            $image->save(base_path('public/adminDash/uploads/slider&banner/'.$newname));
            $slider->image = $newname;
        }

        $slider->url = $request->url;
        $slider->save();

        return redirect()->route('slider.index')->with('success', 'Slider Updated successfully!');
    }

    public function destroy($id)
    {
        $slider = Slider::findOrFail($id);

        $oldPath = base_path('public/adminDash/uploads/slider&banner/'.$slider->image);
        if ($slider->image && file_exists($oldPath) && is_file($oldPath)) {
            unlink($oldPath);
        }

        $slider->delete();

        return redirect()->route('slider.index')->with('success', 'Slider Deleted successfully!');
    }
}
