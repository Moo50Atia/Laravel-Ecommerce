<x-app-layout>


  <div class="container mx-auto">
    <h2 class="text-3xl font-bold mb-6 text-center text-blue-800" data-aos="fade-down">Users List</h2>

    <div class="flex justify-end mb-4">
      <a href="{{ route('admin.users.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-md shadow-md transition" data-aos="fade-left">+ Create User</a>
    </div>

    <div class="overflow-x-auto shadow-lg rounded-lg bg-white" data-aos="fade-up" data-aos-delay="200">
      <table class="min-w-full text-sm text-left text-gray-700">
        <thead class="bg-gray-200 text-gray-600 uppercase text-xs">
          <tr>
            <th class="px-4 py-3">Name</th>
            <th class="px-4 py-3">Email</th>
            <th class="px-4 py-3">Verified At</th>
            <th class="px-4 py-3">Phone</th>
            <th class="px-4 py-3">Avatar</th>
            <th class="px-4 py-3">Role</th>
            <th class="px-4 py-3">Status</th>
            <th class="px-4 py-3 text-center">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($users as $item)
            <tr class="border-b hover:bg-gray-50 transition">
              <td class="px-4 py-3">{{ $item->name }}</td>
              <td class="px-4 py-3">{{ $item->email }}</td>
              <td class="px-4 py-3">{{ $item->email_verified_at ?? '-' }}</td>
              <td class="px-4 py-3">{{ $item->phone }}</td>
              <td class="px-4 py-3">
                @if ($item->avatar)
                  <div class="w-12 h-12 rounded-full overflow-hidden mx-auto">
                    <img src="{{ asset('storage/' . $item->avatar) }}" alt="Avatar" class="w-full h-full object-cover">
                  </div>
                @else
                  <div class="text-center text-gray-400">No Avatar</div>
                @endif
              </td>
              <td class="px-4 py-3">{{ $item->role }}</td>
              <td class="px-4 py-3">
                @if ($item->status == 'active')
                  <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Active</span>
                @else
                  <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs">Inactive</span>
                @endif
              </td>
              <td class="px-4 py-3 text-center">
                <a href="{{ route('admin.users.edit', $item->id) }}" class="text-yellow-600 hover:text-yellow-800 font-medium mr-2">Edit</a>
                <form action="{{ route('admin.users.destroy', $item->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure?')">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="text-red-600 hover:text-red-800 font-medium">Delete</button>
                </form>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

  </x-app-layout>