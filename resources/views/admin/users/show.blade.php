@extends('layouts.app')

@section('content')



  <div class="max-w-2xl mx-auto bg-white shadow-lg rounded-lg p-6" data-aos="zoom-in">
    <h2 class="text-2xl font-bold text-green-800 mb-6">User Details</h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
      @foreach (['name', 'email', 'email_verified_at', 'password', 'phone', 'role', 'status'] as $field)
        <div>
          <span class="font-semibold text-gray-700 capitalize">{{ str_replace('_', ' ', $field) }}:</span>
          <p class="text-gray-800 mt-1">
            {{ $user->$field ?? '-' }}
          </p>
        </div>
      @endforeach
    </div>

    <div class="mt-6">
      <h3 class="font-semibold text-gray-800 mb-2">Avatar</h3>
      @php $avatar = $user->images()->where('type','avatar')->first(); @endphp
      @if($avatar)
        <img src="{{ asset('storage/' . $avatar->url) }}" alt="avatar" class="w-24 h-24 rounded-full object-cover">
      @else
        <p class="text-gray-500 text-sm">No avatar uploaded</p>
      @endif
    </div>
  </div>
@endsection