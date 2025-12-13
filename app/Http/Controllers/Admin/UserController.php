<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Database\QueryException;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\ImageUploadService;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserController extends Controller
{ 
    protected $imageUploadService;
    protected $userRepository;

    public function __construct(
        ImageUploadService $imageUploadService,
        UserRepositoryInterface $userRepository
    ) {
        $this->imageUploadService = $imageUploadService;
        $this->userRepository = $userRepository;
    }
    public function checkEmail(Request $request)
    {
        $exists = $this->userRepository->where('email', $request->email)->count() > 0;

        return response()->json([
            'exists' => $exists
        ]);
    }

    public function index(Request $request): \Illuminate\Contracts\View\View
    {
        $user = Auth::user();
        
        // Use repository for user filtering and pagination
        $users = $this->userRepository->getForAdmin([
            'search' => $request->get('search'),
            'role' => $request->get('role'),
            'status' => $request->get('status'),
            'verified' => $request->get('verified'),
            'has_vendor' => $request->get('has_vendor'),
            'per_page' => 15
        ]);

        // Get statistics using repository
        $statistics = $this->userRepository->getStatistics();

        // Get filter options using repository
        $roles = $this->userRepository->getByRole('admin')
            ->merge($this->userRepository->getByRole('vendor'))
            ->merge($this->userRepository->getByRole('customer'))
            ->pluck('role')
            ->unique()
            ->values();

        $statuses = $this->userRepository->getByStatus('active')
            ->merge($this->userRepository->getByStatus('inactive'))
            ->pluck('status')
            ->unique()
            ->values();

        return view('admin.manage-users', compact(
            'users',
            'statistics',
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
        // Handle avatar upload using service
        unset($data['avatar']);
        
        // Use repository to create user
        $user = $this->userRepository->create($data);
        
        if ($request->hasFile('avatar')) {
            $this->imageUploadService->uploadSingleImage($request->file('avatar'), $user, 'avatar');
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
            
        // Use repository to update user
        $this->userRepository->update($user->id, $data);
        
        // Handle avatar upload using service
        if ($request->hasFile('avatar')) {
            $this->imageUploadService->updateOrCreateImage($request->file('avatar'), $user, 'avatar');
        }
        return redirect()->route('admin.users.index')->with('success', 'Updated successfully');
    }

    public function destroy(User $user): \Illuminate\Http\RedirectResponse
    {
        // Use repository to delete user
        $this->userRepository->delete($user->id);
        return redirect()->route('admin.users.index')->with('success', 'Deleted successfully');
    }
}
