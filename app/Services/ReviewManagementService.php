<?php

namespace App\Services;

use App\Models\ProductReview;
use App\Models\BlogReview;
use App\Models\Product;
use App\Models\Blog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ReviewManagementService
{
    /**
     * Create a product review
     */
    public function createProductReview(Request $request, Product $product): array
    {
        // Check if user is authenticated and has 'user' role
        if (!Auth::check() || Auth::user()->role !== 'user') {
            return [
                'success' => false,
                'message' => 'يجب تسجيل الدخول كعميل لإضافة تقييم'
            ];
        }

        // Check if user already reviewed this product
        $existingReview = ProductReview::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();

        if ($existingReview) {
            return [
                'success' => false,
                'message' => 'لقد قمت بتقييم هذا المنتج من قبل'
            ];
        }

        // Create the review
        ProductReview::create([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_approved' => true, // Auto-approve reviews for now
        ]);

        return [
            'success' => true,
            'message' => 'تم إضافة تقييمك بنجاح'
        ];
    }

    /**
     * Create or update a blog review/rating
     */
    public function createOrUpdateBlogReview(Request $request, Blog $blog): array
    {
        // Check if user already rated this blog
        $existingReview = $blog->reviews()->where('user_id', Auth::user()->id)->first();
        
        if ($existingReview) {
            // Update existing review
            $existingReview->update(['rate' => $request->rate]);
            $message = 'تم تحديث تقييمك بنجاح';
        } else {
            // Create new review
            $blog->reviews()->create([
                'user_id' => Auth::user()->id,
                'rate' => $request->rate
            ]);
            $message = 'تم إضافة تقييمك بنجاح';
        }

        return [
            'success' => true,
            'message' => $message
        ];
    }

    /**
     * Delete a blog review
     */
    public function deleteBlogReview(BlogReview $review): array
    {
        // Check if user is admin or the review owner
        if (Auth::user()->role !== 'admin' && Auth::user()->id !== $review->user_id) {
            return [
                'success' => false,
                'message' => 'غير مصرح لك بحذف هذا التقييم'
            ];
        }

        $review->delete();

        return [
            'success' => true,
            'message' => 'تم حذف التقييم بنجاح'
        ];
    }

    /**
     * Get product reviews with pagination
     */
    public function getProductReviews(Product $product, int $perPage = 10)
    {
        return $product->productReviews()
            ->with('user')
            ->where('is_approved', true)
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Get blog reviews with pagination
     */
    public function getBlogReviews(Blog $blog, int $perPage = 10)
    {
        return $blog->reviews()
            ->with('user')
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Calculate average rating for a product
     */
    public function calculateProductAverageRating(Product $product): float
    {
        return $product->productReviews()
            ->where('is_approved', true)
            ->avg('rating') ?? 0;
    }

    /**
     * Calculate average rating for a blog
     */
    public function calculateBlogAverageRating(Blog $blog): float
    {
        return $blog->reviews()->avg('rate') ?? 0;
    }

    /**
     * Get review statistics for a product
     */
    public function getProductReviewStats(Product $product): array
    {
        $reviews = $product->productReviews()->where('is_approved', true);
        
        return [
            'total_reviews' => $reviews->count(),
            'average_rating' => $reviews->avg('rating') ?? 0,
            'rating_distribution' => $this->getRatingDistribution($reviews->get())
        ];
    }

    /**
     * Get review statistics for a blog
     */
    public function getBlogReviewStats(Blog $blog): array
    {
        $reviews = $blog->reviews();
        
        return [
            'total_reviews' => $reviews->count(),
            'average_rating' => $reviews->avg('rate') ?? 0,
            'rating_distribution' => $this->getBlogRatingDistribution($reviews->get())
        ];
    }

    /**
     * Get rating distribution for products
     */
    private function getRatingDistribution($reviews): array
    {
        $distribution = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
        
        foreach ($reviews as $review) {
            $rating = (int) $review->rating;
            if (isset($distribution[$rating])) {
                $distribution[$rating]++;
            }
        }
        
        return $distribution;
    }

    /**
     * Get rating distribution for blogs
     */
    private function getBlogRatingDistribution($reviews): array
    {
        $distribution = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
        
        foreach ($reviews as $review) {
            $rating = (int) $review->rate;
            if (isset($distribution[$rating])) {
                $distribution[$rating]++;
            }
        }
        
        return $distribution;
    }

    /**
     * Approve or reject a review
     */
    public function updateReviewStatus(ProductReview $review, bool $approved): bool
    {
        return $review->update(['is_approved' => $approved]);
    }

    /**
     * Get pending reviews for moderation
     */
    public function getPendingReviews(int $perPage = 15)
    {
        return ProductReview::with(['user', 'product'])
            ->where('is_approved', false)
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Bulk approve reviews
     */
    public function bulkApproveReviews(array $reviewIds): int
    {
        return ProductReview::whereIn('id', $reviewIds)
            ->update(['is_approved' => true]);
    }

    /**
     * Bulk reject reviews
     */
    public function bulkRejectReviews(array $reviewIds): int
    {
        return ProductReview::whereIn('id', $reviewIds)
            ->update(['is_approved' => false]);
    }
}
