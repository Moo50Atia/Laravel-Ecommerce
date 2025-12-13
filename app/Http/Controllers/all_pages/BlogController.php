<?php

namespace App\Http\Controllers\all_pages;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\BlogRequest;
use App\Models\Blog;
use Illuminate\Http\Request;
use App\Models\BlogReview;
use App\Services\ReviewManagementService;

class BlogController extends Controller
{ 
    protected $reviewManagementService;

    public function __construct(ReviewManagementService $reviewManagementService)
    {
        $this->reviewManagementService = $reviewManagementService;
    }
    public function index(): \Illuminate\Contracts\View\View
    {
        
$blogs = Blog::withAvg("reviews", "rate")->orderBy("created_at")->where("is_published" , true)->paginate(10);

$special_blogs = Blog::withAvg("reviews", "rate")->orderBy("reviews_avg_rate", "desc")->where("is_published" , true)->get();

        return view('public.blogs.index', compact('blogs' , "special_blogs"));
    }

    public function create(): \Illuminate\Contracts\View\View
    {
        return view('public.blogs.create');
    }

    public function store(BlogRequest $request): \Illuminate\Http\RedirectResponse
    { 
            $data = $request->validated();
            $data['author_id'] = Auth::user()->id;
            // $data['is_published'] = $request->has('is_published');
            // $data['published_at'] = $request->has('is_published') ? now() : null;
            unset($data['featured_image']); // لو بتخزنها في جدول images

            $blog = Blog::create($data);


        if ($request->hasFile('featured_image')) {
        $path = $request->file('featured_image')->store('blogs', 'public');

        // 3. أنشئ image مربوطة بالمقال
        $blog->image()->create([
            'url' => $path,
        ]);
    }
        
        return redirect()->route('blogs.index')->with('success', 'Created successfully');
    }

    public function show(Blog $blog): \Illuminate\Contracts\View\View
    {
        return view('public.blogs.show', compact('blog'));
    }

    public function edit(Blog $blog): \Illuminate\Contracts\View\View
    {
        return view('public.blogs.edit', compact('blog'));
    }

    public function update(BlogRequest $request, Blog $blog): \Illuminate\Http\RedirectResponse
    { 
            $data = $request->validated();
            $data['author_id'] = Auth::user()->id;
            $data['is_published'] = $request->has('is_published');
            $data['published_at'] = $request->has('is_published') ? now() : null;
            unset($data['featured_image']); // لو بتخزنها في جدول images


        $blog->update($data);
        if ($request->hasFile('featured_image')) {
        $path = $request->file('featured_image')->store('blogs', 'public');

        // 3. أنشئ image مربوطة بالمقال
        $blog->image()->update([
            'url' => $path,
        ]);
    }
        
        return redirect()->route('blogs.index')->with('success', 'Updated successfully');
    }

    public function destroy(Blog $blog): \Illuminate\Http\RedirectResponse
    {
        $blog->delete();
        return redirect()->route('blogs.index')->with('success', 'Deleted successfully');
    }

    public function rate(Request $request, Blog $blog): \Illuminate\Http\RedirectResponse
    {
        $result = $this->reviewManagementService->createOrUpdateBlogReview($request, $blog);
        
        return redirect()->back()->with('success', $result['message']);
    }

    public function destroyReview(BlogReview $review): \Illuminate\Http\RedirectResponse
    {
        $result = $this->reviewManagementService->deleteBlogReview($review);
        
        if ($result['success']) {
            return redirect()->back()->with('success', $result['message']);
        } else {
            return redirect()->back()->with('error', $result['message']);
        }
    }
}



