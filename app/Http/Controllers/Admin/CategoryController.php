<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Http\Requests\Admin\CategoryRequest;

class CategoryController extends Controller
{
    protected $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }
    public function index(Request $request)
    {
        // Use repository for category filtering and pagination
        $categories = $this->categoryRepository->getForAdmin([
            'search' => $request->get('search'),
            'status' => $request->get('status'),
            'parent_id' => $request->get('parent_id'),
            'has_products' => $request->get('has_products'),
            'per_page' => 15
        ]);

        // Get statistics using repository
        $statistics = $this->categoryRepository->getStatistics();

        // Get parent categories for filter dropdown
        $parentCategories = $this->categoryRepository->getParentCategories();

        return view('admin.manage-categories', compact('categories', 'statistics', 'parentCategories'));
    }

    public function create()
    {
        // Get parent categories for dropdown
        $parentCategories = $this->categoryRepository->getParentCategories();
        return view('admin.categories.create', compact('parentCategories'));
    }

    public function store(CategoryRequest $request)
    {
        $validated = $request->validated();

        // Use repository to create category
        $this->categoryRepository->create($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully');
    }

    public function show(Category $category)
    {
        // Load relationships using repository
        $category = $this->categoryRepository->find($category->id);
        return view('admin.categories.show', compact('category'));
    }

    public function edit(Category $category)
    {
        // Get parent categories for dropdown
        $parentCategories = $this->categoryRepository->getParentCategories();
        return view('admin.categories.edit', compact('category', 'parentCategories'));
    }

    public function update(CategoryRequest $request, Category $category)
    {
        $validated = $request->validated();

        // Use repository to update category
        $this->categoryRepository->update($category->id, $validated);

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully');
    }

    public function destroy(Category $category)
    {
        // Use repository to delete category
        $this->categoryRepository->delete($category->id);
        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully');
    }
}
