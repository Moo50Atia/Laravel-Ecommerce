{{-- <div class="container">
<h2>blogs List</h2>
<a href="{{ route('blogs.create') }}" class="btn btn-primary mb-3">Create blogs</a>
<table class="table">
    <thead>
        <tr><th>title</th><th>slug</th><th>rate</th><th>content</th><th>author_id</th><th>featured_image</th><th>meta_title</th><th>meta_description</th><th>is_published</th><th>published_at</th></tr>
    </thead>
    <tbody>
        @foreach ($blogs as $item)
                <tr>
                    <td>{{$item->title}}</td>
<td>{{$item->slug}}</td>
<td>{{$item->rate}}</td>
<td>{{$item->content}}</td>
<td>{{$item->author_id}}</td>
<td>{{$item->featured_image}}</td>
<td>{{$item->meta_title}}</td>
<td>{{$item->meta_description}}</td>
<td>{{$item->is_published}}</td>
<td>{{$item->published_at}}</td>
<td>
                        <a href="{{ route('blogs.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('blogs.destroy', $item->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div> --}}

@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" data-aos="fade-up">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Blogs List</h2>

    <div class="flex justify-end mb-4">
        <a href="{{ route('blogs.create') }}"
           class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
            Create Blog
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
            <thead class="bg-gray-100 text-gray-700 text-sm uppercase text-left">
                <tr>
                    <th class="px-4 py-3">Title</th>
                    <th class="px-4 py-3">Slug</th>
                    <th class="px-4 py-3">Rate</th>
                    <th class="px-4 py-3">Content</th>
                    <th class="px-4 py-3">Author</th>
                    <th class="px-4 py-3">Image</th>
                    <th class="px-4 py-3">Meta Title</th>
                    <th class="px-4 py-3">Meta Desc</th>
                    <th class="px-4 py-3">Published?</th>
                    <th class="px-4 py-3">Published At</th>
                    <th class="px-4 py-3">Actions</th>
                </tr>
            </thead>
            <tbody class="text-gray-700 text-sm">
                @foreach ($blogs as $item)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $item->title }}</td>
                        <td class="px-4 py-2">{{ $item->slug }}</td>
                        <td class="px-4 py-2">{{ $item->rate }}</td>
                        <td class="px-4 py-2 truncate max-w-xs">{{ Str::limit($item->content, 50) }}</td>
                        <td class="px-4 py-2">{{ $item->author_id }}</td>
                        <td class="px-4 py-2">{{ $item->featured_image }}</td>
                        <td class="px-4 py-2">{{ $item->meta_title }}</td>
                        <td class="px-4 py-2">{{ $item->meta_description }}</td>
                        <td class="px-4 py-2">
                            <span class="px-2 py-1 rounded text-white text-xs
                                {{ $item->is_published ? 'bg-green-500' : 'bg-red-500' }}">
                                {{ $item->is_published ? 'Yes' : 'No' }}
                            </span>
                        </td>
                        <td class="px-4 py-2">{{ $item->published_at }}</td>
                        <td class="px-4 py-2 flex gap-2">
                            <a href="{{ route('blogs.edit', $item->id) }}"
                               class="text-white bg-yellow-500 hover:bg-yellow-600 px-3 py-1 rounded text-sm">
                                Edit
                            </a>
                            <form action="{{ route('blogs.destroy', $item->id) }}" method="POST"
                                  onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="text-white bg-red-600 hover:bg-red-700 px-3 py-1 rounded text-sm">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection