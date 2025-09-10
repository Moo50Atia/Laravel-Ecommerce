<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Blog::with(['author', 'reviews'])->ForAdmin($user);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where('title', 'like', "%{$search}%");
        }

        // Status filter
        if ($request->filled('status')) {
            $status = $request->get('status');
            if ($status === 'published') {
                $query->where('is_published', true);
            } elseif ($status === 'draft') {
                $query->where('is_published', false);
            }
        }

        // Author filter
        if ($request->filled('author')) {
            $author = $request->get('author');
            $query->whereHas('author', function($q) use ($author) {
                $q->where('name', 'like', "%{$author}%");
            });
        }

        $blogs = $query->latest()->paginate(15)->withQueryString();

        // Get all unique authors for the filter dropdown (filtered by admin's city)
        $authors = Blog::with('author')
            ->ForAdmin($user)
            ->whereHas('author')
            ->get()
            ->pluck('author.name')
            ->unique()
            ->filter()
            ->values();

        // Get statistics for all blogs (filtered by admin's city)
        $allBlogs = Blog::query()->ForAdmin($user);
        $totalBlogs = $allBlogs->count();
        $publishedBlogs = (clone $allBlogs)->where('is_published', true)->count();
        $draftBlogs = (clone $allBlogs)->where('is_published', false)->count();
        $totalReviews = (clone $allBlogs)->with('reviews')->get()->sum(function($blog) { 
            return $blog->reviews->count(); 
        });

        return view('admin.manage-blog', compact('blogs', 'authors', 'totalBlogs', 'publishedBlogs', 'draftBlogs', 'totalReviews'));
    }

    public function create()
    {
        return view('admin.blogs.create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'nullable|string|max:500',
            'content' => 'required|string',
            'author_id' => 'nullable',
            'is_published' => 'boolean',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $blog = Blog::create([
            'title' => $request->get('title'),
            'short_description' => $request->get('short_description'),
            'content' => $request->get('content'),
            'author_id' => $request->get('author_id') ?: $user->id, // Use current user as author if not specified
            'is_published' => $request->has('is_published'),
            'published_at' => $request->has('is_published') ? now() : null,
        ]);

        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            $imagePath = $request->file('cover_image')->store('blog-covers', 'public');
            
            Image::create([
                'url' => $imagePath,
                'type' => 'cover',
                'imageable_type' => Blog::class,
                'imageable_id' => $blog->id
            ]);
        }

        return redirect()->route('admin.blogs.index')->with('success', 'تم إنشاء المدونة بنجاح');
    }

    public function show(Blog $blog)
    {
        // Check if the blog is accessible to the current admin
        $user = Auth::user();
        $accessibleBlog = Blog::where('id', $blog->id)->ForAdmin($user)->first();
        
        if (!$accessibleBlog && $user->role === 'admin') {
            abort(403, 'Unauthorized access');
        }
        
        return view('public.blogs.show', compact('blog'));
    }

    public function edit(Blog $blog)
    {
        // Check if the blog is accessible to the current admin
        $user = Auth::user();
        $accessibleBlog = Blog::where('id', $blog->id)->ForAdmin($user)->first();
        
        if (!$accessibleBlog && $user->role === 'admin') {
            abort(403, 'Unauthorized access');
        }
        
        return view('public.blogs.edit', compact('blog'));
    }

    public function update(Request $request, Blog $blog)
    {
        // Check if the blog is accessible to the current admin
        $user = Auth::user();
        $accessibleBlog = Blog::where('id', $blog->id)->ForAdmin($user)->first();
        
        if (!$accessibleBlog && $user->role === 'admin') {
            abort(403, 'Unauthorized access');
        }
        
        $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'nullable|string|max:500',
            'content' => 'required|string',
            'author_id' => 'nullable|exists:users,id',
            'is_published' => 'boolean',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $blog->update([
            'title' => $request->get('title'),
            'short_description' => $request->get('short_description'),
            'content' => $request->get('content'),
            'author_id' => $request->get('author_id') ?: $user->id, // Use current user as author if not specified
            'is_published' => $request->has('is_published'),
            'published_at' => $request->has('is_published') ? now() : null,
        ]);

        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            // Delete old cover image if exists
            if ($blog->coverImage) {
                $blog->coverImage->delete();
            }
            
            $imagePath = $request->file('cover_image')->store('blog-covers', 'public');
            
            Image::create([
                'url' => $imagePath,
                'type' => 'cover',
                'imageable_type' => Blog::class,
                'imageable_id' => $blog->id
            ]);
        }

        return redirect()->route('admin.blogs.index')->with('success', 'تم تحديث المدونة بنجاح');
    }

    public function destroy(Blog $blog)
    {
        // Check if the blog is accessible to the current admin
        $user = Auth::user();
        $accessibleBlog = Blog::where('id', $blog->id)->ForAdmin($user)->first();
        
        if (!$accessibleBlog && $user->role === 'admin') {
            abort(403, 'Unauthorized access');
        }
        
        $blog->delete();
        return redirect()->route('admin.blogs.index')->with('success', 'تم حذف المدونة بنجاح');
    }
}
