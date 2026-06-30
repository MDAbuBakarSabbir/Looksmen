<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admins;
use App\Models\Roles;
use Illuminate\Http\Request;

class AdminsController extends Controller
{
    public function index()
    {
        $admins = Admins::all();
        return view('adminDash.admins.adminList',compact('admins'));
    }
    public function search(Request $request)
    {
        $term = $request->search;
        $admins = Admins::where(function($query) use ($term) {
            $query->where('name', 'like', "%{$term}%")
                  ->orWhere('id', 'like', "%{$term}%")
                  ->orWhere('email', 'like', "%{$term}%")
                  ->orWhere('number', 'like', "%{$term}%");
        })->get();

        return view('adminDash.admins.extends.admin_rows', compact('admins'))->render();
    }
    public function status(Request $request)
    {

        $admin = Admins::find($request->id);

        if (!$admin) {
            return response()->json(['success' => false]);
        }

        $admin->status = $request->status == 1 ? 1 : 0;
        $admin->save();

        return response()->json([
            'success' => true,
            'status' => $admin->status
        ]);
    }

    public function bulkStatus(Request $request)
    {
        $ids = $request->input('ids', []);
        $status = $request->status == 1 ? 1 : 0;
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No admins selected']);
        }
        Admins::whereIn('id', $ids)->update(['status' => $status]);
        return response()->json(['success' => true, 'message' => count($ids) . ' admins updated']);
    }

    public function create()
    {
        $roles = Roles::all();
        if ($roles->isEmpty()) {
            Roles::create(['role' => 'admin', 'permission_id' => json_encode([]), 'status' => 1]);
            Roles::create(['role' => 'manager', 'permission_id' => json_encode([]), 'status' => 1]);
            Roles::create(['role' => 'editor', 'permission_id' => json_encode([]), 'status' => 1]);
            $roles = Roles::all();
        }
        return view('adminDash.admins.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins,email',
            'number' => 'required|string|max:20|unique:admins,number',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|string|max:50',
        ]);

        Admins::create([
            'name' => $request->name,
            'email' => $request->email,
            'number' => $request->number,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role_id' => $request->role_id,
            'status' => 1,
        ]);

        return redirect()->route('admin.index')->with('success', 'Admin employee created successfully!');
    }

    public function role()
    {

        $roles = Roles::all();
        return view('adminDash.admins.roles',compact('roles'));
    }
    public function permission($id)
    {

        $admin = Admins::findOrFail($id);
        return view('adminDash.admins.permissions',compact('admin'));
    }

    public function updatePermission(Request $request, $id)
    {
        $admin = Admins::findOrFail($id);
        $permissions = $request->input('permissions', []);
        $admin->permission_id = json_encode($permissions);
        $admin->save();

        return redirect()->route('admin.index')->with('success', 'Admin permissions updated successfully!');
    }

    public function profile()
    {
        $admin = auth()->guard('admin')->user();
        return view('adminDash.admins.profile', compact('admin'));
    }

    public function profileUpdate(Request $request)
    {
        $admin = auth()->guard('admin')->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins,email,' . $admin->id,
            'number' => 'required|string|max:20|unique:admins,number,' . $admin->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->number = $request->number;

        if ($request->filled('password')) {
            $admin->password = \Illuminate\Support\Facades\Hash::make($request->password);
        }

        $admin->save();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Admin profile updated successfully!'
            ]);
        }

        return back()->with('success', 'Admin profile updated successfully!');
    }
}
