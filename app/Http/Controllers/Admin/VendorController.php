<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\CreateVendorRequest;
use App\Http\Requests\Admin\UpdateVendorRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Contracts\VendorRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;

class VendorController extends Controller
{
    protected $vendorRepository;
    protected $userRepository;

    public function __construct(
        VendorRepositoryInterface $vendorRepository,
        UserRepositoryInterface $userRepository
    ) {
        $this->vendorRepository = $vendorRepository;
        $this->userRepository = $userRepository;
    }
    public function index(Request $request)
    {
        $user = Auth::user();

        // Use repository for vendor filtering and pagination
        $vendors = $this->vendorRepository->getForAdmin($user, [
            'status' => $request->get('status'),
            'search' => $request->get('search'),
            'min_rating' => $request->get('min_rating'),
            'max_rating' => $request->get('max_rating'),
            'has_products' => $request->get('has_products'),
            'has_orders' => $request->get('has_orders'),
            'per_page' => 10
        ]);

        // Get statistics using repository
        $statistics = $this->vendorRepository->getStatistics();

        return view('admin.manage-vendors', compact('vendors', 'statistics'));
    }

    public function create()
    {
        return view('admin.vendors.create');
    }

    public function store(CreateVendorRequest $request)
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated) {
            // Use repository to create user
            $user = $this->userRepository->create([
                'name' => $validated['user_name'],
                'email' => $validated['user_email'],
                'phone' => $validated['user_phone'],
                'password' => Hash::make($validated['password']),
                'role' => 'vendor',
                'status' => 'active',
            ]);

            // Use repository to create vendor
            $this->vendorRepository->create([
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
        $vendor->load(['user', 'products', 'orders']);
        return view('admin.vendors.show', compact('vendor'));
    }

    public function edit(Vendor $vendor)
    {
        $vendor->load('user');
        return view('admin.vendors.edit', compact('vendor'));
    }

    public function update(UpdateVendorRequest $request, Vendor $vendor)
    {
        $validated = $request->validated();

        // Use repository to update vendor
        $this->vendorRepository->update($vendor->id, [
            'store_name' => $validated['store_name'],
            'slug' => Str::slug($validated['store_name']),
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'description' => $validated['description'] ?? null,
            'commission_rate' => $validated['commission_rate'] ?? 0,
        ]);

        if (isset($validated['status'])) {
            // Use repository to update user status
            $this->userRepository->update($vendor->user_id, ['status' => $validated['status']]);
        }

        return redirect()->route('admin.vendors.index')
            ->with('success', 'Vendor updated successfully.');
    }

    public function destroy(Vendor $vendor)
    {
        DB::transaction(function () use ($vendor) {
            // Use repository to delete vendor and user
            $this->vendorRepository->delete($vendor->id);
            $this->userRepository->delete($vendor->user_id);
        });

        return redirect()->route('admin.vendors.index')
            ->with('success', 'Vendor deleted successfully.');
    }

    public function approve(Vendor $vendor)
    {
        // Use repository to update user status
        $this->userRepository->update($vendor->user_id, ['status' => 'active']);
        return redirect()->route('admin.vendors.index')
            ->with('success', 'Vendor approved successfully.');
    }

    public function suspend(Vendor $vendor)
    {
        // Use repository to update user status
        $this->userRepository->update($vendor->user_id, ['status' => 'suspended']);
        return redirect()->route('admin.vendors.index')
            ->with('success', 'Vendor suspended successfully.');
    }
}
