<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Str;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => ['regex:/^01[0-9]{9}$/'],
            'role' => ['required', 'string', 'in:user,vendor']
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'role' => $request->role,
        ]);
        
        // If the user registered as a vendor, create a vendor record
        if ($request->role === 'vendor') {
            // Create a basic vendor profile that they can complete later
            $user->vendor()->create([
                'store_name' => $user->name . "'s Store",
                'slug' => Str::slug($user->name . "'s Store"),
                'email' => $user->email,
                'phone' => $user->phone,
            ]);
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('index', absolute: false));
    }
}
