<?php

namespace App\Http\Controllers;

use App\Http\Requests\Profile\ProfileAddressUpdateRequest;
use App\Http\Requests\Profile\UpdatePersonalInfoRequest;
use App\Http\Requests\Profile\UpdateVendorInfoRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\UserAddress;
use App\Models\Vendor;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        
        return view('profile.edit', [
            'user' => $request->user(),
            
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function updateAddress(ProfileAddressUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        UserAddress::updateOrCreate(
            ["user_id" => $user->id], // الشرط
            [ // القيم اللي هتتحدث
                "address_line1" => $request->address_line1,
                "address_line2" => $request->address_line2,
                "city" => $request->city,
                "state" => $request->state,
                "country" => $request->country,
                "postal_code" => $request->postal_code,
            ]
        );

        return Redirect::route('profile.edit')->with('status', 'profile-updated')->with('form_data', $request->all());
    }

    public function updatePersonal(UpdatePersonalInfoRequest $request): RedirectResponse
    {
        $user = $request->user();

        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated')->with('form_data', $request->all());
    }

    public function updateVendor(UpdateVendorInfoRequest $request): RedirectResponse
    {
        $user = $request->user();

        Vendor::updateOrCreate(
            ["user_id" => $user->id],
            [
                "store_name" => $request->store_name,
                "description" => $request->description,
                "commission_rate" => $request->commission_rate,
                "user_id" => $request->user()->id,
                "slug" => Str::slug($request->store_name),
            ]
        );

        return Redirect::route('profile.edit')->with('status', 'profile-updated')->with('form_data', $request->all());
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
