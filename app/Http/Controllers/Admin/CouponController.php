<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Coupon;
use Illuminate\Http\Request;
use App\Repositories\Contracts\CouponRepositoryInterface;
use App\Http\Requests\Admin\CouponRequest;

class CouponController extends Controller
{
    protected $couponRepository;

    public function __construct(CouponRepositoryInterface $couponRepository)
    {
        $this->couponRepository = $couponRepository;
    }
    public function index(Request $request)
    {
        // Use repository for coupon filtering and pagination
        $coupons = $this->couponRepository->getForAdmin([
            'search' => $request->get('search'),
            'status' => $request->get('status'),
            'type' => $request->get('type'),
            'expired' => $request->get('expired'),
            'min_discount' => $request->get('min_discount'),
            'max_discount' => $request->get('max_discount'),
            'per_page' => 15
        ]);

        // Get statistics using repository
        $statistics = $this->couponRepository->getStatistics();

        return view('admin.manage-coupons', compact('coupons', 'statistics'));
    }

    public function create()
    {
        return view('admin.coupons.create');
    }

    public function store(CouponRequest $request)
    {
        $validated = $request->validated();

        // Use repository to create coupon
        $this->couponRepository->create($validated);

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon created successfully');
    }

    public function show(Coupon $coupon)
    {
        // Load relationships using repository
        $coupon = $this->couponRepository->find($coupon->id);
        return view('admin.coupons.show', compact('coupon'));
    }

    public function edit(Coupon $coupon)
    {
        return view('admin.coupons.edit', compact('coupon'));
    }

    public function update(CouponRequest $request, Coupon $coupon)
    {
        $validated = $request->validated();

        // Use repository to update coupon
        $this->couponRepository->update($coupon->id, $validated);

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon updated successfully');
    }

    public function destroy(Coupon $coupon)
    {
        // Use repository to delete coupon
        $this->couponRepository->delete($coupon->id);
        return redirect()->route('admin.coupons.index')->with('success', 'Coupon deleted successfully');
    }
}
