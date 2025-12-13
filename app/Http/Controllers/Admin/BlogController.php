<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CreateBlogRequest;
use App\Models\User;
use App\Repositories\Contracts\BlogRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;

class BlogController extends Controller
{
    protected $blogRepository;
    protected $userRepository;

    public function __construct(
        BlogRepositoryInterface $blogRepository,
        UserRepositoryInterface $userRepository
    ) {
        $this->blogRepository = $blogRepository;
        $this->userRepository = $userRepository;
    }
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Use repository for blog filtering and pagination
        $blogs = $this->blogRepository->getForAdmin($user, [
            'search' => $request->get('search'),
            'status' => $request->get('status'),
            'author' => $request->get('author'),
            'date_from' => $request->get('date_from'),
            'date_to' => $request->get('date_to'),
            'per_page' => 15
        ]);

        // Get statistics using repository
        $statistics = $this->blogRepository->getAdminStatistics($user);

        // Get authors for filter dropdown using repository
        $authors = $this->blogRepository->getAuthorsForAdmin($user)
            ->pluck('name')
            ->unique()
            ->filter()
            ->values();

        return view('admin.manage-blog', compact('blogs', 'authors', 'statistics'));
    }

    public function create()
    {
        // Get authors using repository
        $authors = $this->userRepository->getByRole('admin')
            ->merge($this->userRepository->getByRole('vendor'));
        
        return view('admin.blogs.create', compact('authors'));
    }

    public function store(CreateBlogRequest $request)
    {
        $user = Auth::user();
        
        // Use repository to create blog
        $blog = $this->blogRepository->create([
            'title' => $request->get('title'),
            'short_description' => $request->get('short_description'),
            'content' => $request->get('content'),
            'author_id' => $request->get('author_id') ?: $user->id,
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

    public function update(CreateBlogRequest $request, Blog $blog)
    {
        // Check if the blog is accessible to the current admin
        $user = Auth::user();
        $accessibleBlog = $this->blogRepository->find($blog->id);
        
        if (!$accessibleBlog && $user->role === 'admin') {
            abort(403, 'Unauthorized access');
        }
        
        // Use repository to update blog
        $this->blogRepository->update($blog->id, [
            'title' => $request->get('title'),
            'short_description' => $request->get('short_description'),
            'content' => $request->get('content'),
            'author_id' => $request->get('author_id') ?: $user->id,
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
        $accessibleBlog = $this->blogRepository->find($blog->id);
        
        if (!$accessibleBlog && $user->role === 'admin') {
            abort(403, 'Unauthorized access');
        }
        
        // Use repository to delete blog
        $this->blogRepository->delete($blog->id);
        return redirect()->route('admin.blogs.index')->with('success', 'تم حذف المدونة بنجاح');
    }
}
