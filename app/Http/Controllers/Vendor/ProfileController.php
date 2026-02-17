<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function settings()
    {
        $vendor = Auth::user()->vendor;
        return view('vendor.settings', compact('vendor'));
    }

    public function update(Request $request)
    {
        $vendor = Auth::user()->vendor;

        $validated = $request->validate([
            'store_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'store_email' => 'required|email|max:255',
            'store_phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        $vendor->update($validated);

        return redirect()->route('vendor.settings')->with('success', 'Store settings updated successfully');
    }
}
