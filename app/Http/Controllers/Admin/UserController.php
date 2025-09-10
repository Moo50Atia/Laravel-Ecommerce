<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Database\QueryException;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{ 
    public function checkEmail(Request $request)
{
    $exists = User::where('email', $request->email)->exists();

    return response()->json([
        'exists' => $exists
    ]);
}

    public function index(Request $request): \Illuminate\Contracts\View\View
    {
        $user = Auth::user();
        $query = User::query()->ForAdmin($user);

        // Search by name/email/phone
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->get('role'));
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        // Filter by verification
        if ($request->filled('verified')) {
            $verified = $request->get('verified');
            if ($verified === 'yes') {
                $query->whereNotNull('email_verified_at');
            } elseif ($verified === 'no') {
                $query->whereNull('email_verified_at');
            }
        }

        // Filter by has vendor
        if ($request->filled('has_vendor')) {
            $hasVendor = $request->get('has_vendor');
            if ($hasVendor === 'yes') {
                $query->whereHas('vendor');
            } elseif ($hasVendor === 'no') {
                $query->whereDoesntHave('vendor');
            }
        }

        $users = $query->latest()->paginate(15)->withQueryString();

        // Statistics (across all users filtered by admin's city)
        $allUsers = User::query()->ForAdmin($user);
        $totalUsers = $allUsers->count();
        $activeUsers = (clone $allUsers)->where('status', 'active')->count();
        $inactiveUsers = (clone $allUsers)->where('status', 'suspended')->count();
        $verifiedUsers = (clone $allUsers)->whereNotNull('email_verified_at')->count();
        $vendorsCount = (clone $allUsers)->whereHas('vendor')->count();
        $adminsCount = (clone $allUsers)->where('role', 'admin')->count();

        // Distinct filter options (filtered by admin's city)
        $roles = (clone $allUsers)->whereNotNull('role')->distinct()->pluck('role')->values();
        $statuses = (clone $allUsers)->whereNotNull('status')->distinct()->pluck('status')->values();

        return view('admin.manage-users', compact(
            'users',
            'totalUsers',
            'activeUsers',
            'inactiveUsers',
            'verifiedUsers',
            'vendorsCount',
            'adminsCount',
            'roles',
            'statuses'
        ));
    }

    public function create(): \Illuminate\Contracts\View\View
    {
        return view('admin.users.create');
    }

    public function store(UserRequest $request): \Illuminate\Http\RedirectResponse
    {
        try{
        $data = $request->validated();
        // Default role if not provided
        if (empty($data['role'])) {
            $data['role'] = 'user';
        }
        // Normalize status from radio (accept 'active'/'suspended'/'banned')
        $data['status'] = in_array($request->input('status'), ['active','suspended','banned'], true)
            ? $request->input('status')
            : 'active';
        // Handle avatar upload via polymorphic images (type: avatar)
        unset($data['avatar']);
        $user = User::create($data);
        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->images()->create([
                'url' => $path,
                'type' => 'avatar',
            ]);
        }
        return redirect()->route('admin.users.index')->with('success', 'Created successfully');
        }
          catch (QueryException $e) {
        if ($e->getCode() == 23000) { // unique constraint violation
            return back()->with('error', 'This email already exists. Please use another one.');
        }

        return back()->with('error', 'Something went wrong. Please try again later.');
    }}
    

    public function show(User $user): \Illuminate\Contracts\View\View
    {
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user): \Illuminate\Contracts\View\View
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(UserRequest $request, User $user): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validated();
        // dd($request->input('password'));
        if ($request->input('password') === "" || $request->input("password") == null) {
            $data['password'] = $user->password;
        }
        // Default role if not provided (keep existing)
        if (empty($data['role'])) {
            $data['role'] = $user->role ?? 'user';
        }
        // Normalize status from radio (accept 'active'/'suspended'/'banned')
        $data['status'] = in_array($request->input('status'), ['active','suspended','banned'], true)
            ? $request->input('status')
            : ($user->status ?? 'active');
        $user->update($data);
        // Handle avatar upload via polymorphic images (type: avatar)
        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            // Update existing avatar if present, else create
            $existing = $user->images()->where('type', 'avatar')->first();
            if ($existing) {
                $existing->update(['url' => $path]);
            } else {
                $user->images()->create([
                    'url' => $path,
                    'type' => 'avatar',
                ]);
            }
        }
        return redirect()->route('admin.users.index')->with('success', 'Updated successfully');
    }

    public function destroy(User $user): \Illuminate\Http\RedirectResponse
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Deleted successfully');
    }
}
