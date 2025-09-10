<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class VendorController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status');
        $user = Auth::user();
        
        $query = Vendor::with('user')->ForAdmin($user);
        
        if ($status && in_array($status, ['active', 'inactive', 'suspended'])) {
            $query->whereHas('user', function($q) use ($status) {
                $q->where('status', $status);
            });
        }
        
        $vendors = $query->latest()->paginate(10);
        
        return view('admin.manage-vendors', compact('vendors', 'status'));
    }

    public function create()
    {
        return view('admin.vendors.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'store_name' => 'required|string|max:255|unique:vendors',
            'email' => 'required|email|unique:vendors',
            'phone' => 'required|string|max:20',
            'description' => 'nullable|string|max:1000',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
            'user_name' => 'required|string|max:255',
            'user_email' => 'required|email|unique:users',
            'user_phone' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ]);

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => $validated['user_name'],
                'email' => $validated['user_email'],
                'phone' => $validated['user_phone'],
                'password' => Hash::make($validated['password']),
                'role' => 'vendor',
                'status' => 'active',
            ]);

            $vendor = Vendor::create([
                'store_name' => $validated['store_name'],
                'slug' => Str::slug($validated['store_name']),
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'description' => $validated['description'] ?? null,
                'commission_rate' => $validated['commission_rate'] ?? 0,
                'user_id' => $user->id,
                'rating' => 0,
            ]);
        });

        return redirect()->route('admin.vendors.index')
            ->with('success', 'Vendor created successfully.');
    }

    public function show(Vendor $vendor)
    {
        // $vendor->with(['user', 'products', 'orders']);
        return view('admin.vendors.show', compact('vendor'));
    }

    public function edit(Vendor $vendor)
    {
        $vendor->load('user');
        return view('admin.vendors.edit', compact('vendor'));
    }

    public function update(Request $request, Vendor $vendor)
    {
        $validated = $request->validate([
            'store_name' => 'required|string|max:255|unique:vendors,store_name,' . $vendor->id,
            'email' => 'required|email|unique:vendors,email,' . $vendor->id,
            'phone' => 'required|string|max:20',
            'description' => 'nullable|string|max:1000',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
            'status' => 'required|in:active,inactive,suspended',
        ]);

        $vendor->update([
            'store_name' => $validated['store_name'],
            'slug' => Str::slug($validated['store_name']),
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'description' => $validated['description'] ?? null,
            'commission_rate' => $validated['commission_rate'] ?? 0,
        ]);

        if (isset($validated['status'])) {
            $vendor->user->update(['status' => $validated['status']]);
        }

        return redirect()->route('admin.vendors.index')
            ->with('success', 'Vendor updated successfully.');
    }

    public function destroy(Vendor $vendor)
    {
        DB::transaction(function () use ($vendor) {
            $vendor->delete();
            $vendor->user->delete();
        });

        return redirect()->route('admin.vendors.index')
            ->with('success', 'Vendor deleted successfully.');
    }

    public function approve(Vendor $vendor)
    {
        $vendor->user->update(['status' => 'active']);
        return redirect()->route('admin.vendors.index')
            ->with('success', 'Vendor approved successfully.');
    }

    public function suspend(Vendor $vendor)
    {
        $vendor->user->update(['status' => 'suspended']);
        return redirect()->route('admin.vendors.index')
            ->with('success', 'Vendor suspended successfully.');
    }
}
