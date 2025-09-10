<x-app-layout>

<div class="max-w-4xl mx-auto mt-10 p-6 bg-white rounded shadow" data-aos="fade-up">
    <h2 class="text-2xl font-bold mb-6">Create User</h2>

    @if ($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="mr-3">
                    <h3 class="text-sm font-medium text-red-800">هناك أخطاء في النموذج</h3>
                    <div class="mt-2 text-sm text-red-700">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-4">
            <label class="block mb-1">Name</label>
            <input type="text" name="name" class="w-full border rounded px-3 py-2" value="{{ old('name') }}" required>
        </div>

        <div class="mb-4">
            <label class="block mb-1">Email</label>
            <input type="email" name="email" class="w-full border rounded px-3 py-2" value="{{ old('email') }}" required>
        </div>

        <div class="mb-4">
            <label class="block mb-1">Email Verified At</label>
            <input type="datetime-local" name="email_verified_at" class="w-full border rounded px-3 py-2" value="{{ old('email_verified_at', now()->format('Y-m-d\TH:i')) }}">
        </div>

        <div class="mb-4">
            <label class="block mb-1">Password</label>
            <input type="password" name="password" class="w-full border rounded px-3 py-2" required>
        </div>

        <div class="mb-4">
            <label class="block mb-1">Phone</label>
            <input type="text" name="phone" class="w-full border rounded px-3 py-2" value="{{ old('phone') }}">
        </div>

        <div class="mb-4">
            <label class="block mb-1">Avatar</label>
            <input type="file" name="avatar" accept="image/*" class="w-full border rounded px-3 py-2">
            @error('avatar')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block mb-1">Role</label>
            <select name="role" class="w-full border rounded px-3 py-2">
                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="vendor" {{ old('role') === 'vendor' ? 'selected' : '' }}>Vendor</option>
                <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>User</option>
            </select>
        </div>

        <div class="mb-4">
            <label class="block mb-2">Status</label>
            <div class="flex items-center gap-6">
                <label class="flex items-center gap-2">
                    <input type="radio" name="status" value="active" class="accent-green-600" {{ old('status', 'active') === 'active' ? 'checked' : '' }}>
                    <span>Active</span>
                </label>
                <label class="flex items-center gap-2">
                    <input type="radio" name="status" value="suspended" class="accent-red-600" {{ old('status') === 'suspended' ? 'checked' : '' }}>
                    <span>Suspended</span>
                </label>
                <label class="flex items-center gap-2">
                    <input type="radio" name="status" value="banned" class="accent-red-600" {{ old('status') === 'banned' ? 'checked' : '' }}>
                    <span>Banned</span>
                </label>
            </div>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">Create</button>
    </form>
</div>
@if (session('error'))
    <div style="background-color: #fee2e2; color: #b91c1c; border: 1px solid #fca5a5; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
        <strong>⚠️ حصل خطأ:</strong>
        <p>{{ session('error') }}</p>
    </div>
@endif


</x-app-layout>
