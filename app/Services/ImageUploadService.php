<?php

namespace App\Services;

use App\Models\Image;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;

class ImageUploadService
{
    /**
     * Upload a single image and create image record
     */
    public function uploadSingleImage(UploadedFile $file, Model $model, string $type = 'default', string $disk = 'public'): Image
    {
        $path = $file->store($this->getStoragePath($type), $disk);
        
        return Image::create([
            'url' => $path,
            'type' => $type,
            'imageable_type' => get_class($model),
            'imageable_id' => $model->id
        ]);
    }

    /**
     * Upload multiple images and create image records
     */
    public function uploadMultipleImages(array $files, Model $model, string $type = 'default', string $disk = 'public'): array
    {
        $images = [];
        
        foreach ($files as $file) {
            $images[] = $this->uploadSingleImage($file, $model, $type, $disk);
        }
        
        return $images;
    }

    /**
     * Update existing image or create new one
     */
    public function updateOrCreateImage(UploadedFile $file, Model $model, string $type = 'default', string $disk = 'public'): Image
    {
        // Delete old image if exists
        $existingImage = $model->images()->where('type', $type)->first();
        if ($existingImage) {
            $this->deleteImage($existingImage, $disk);
        }
        
        return $this->uploadSingleImage($file, $model, $type, $disk);
    }

    /**
     * Delete image file and record
     */
    public function deleteImage(Image $image, string $disk = 'public'): bool
    {
        // Delete file from storage
        if (Storage::disk($disk)->exists($image->url)) {
            Storage::disk($disk)->delete($image->url);
        }
        
        // Delete image record
        return $image->delete();
    }

    /**
     * Delete all images for a model
     */
    public function deleteAllImages(Model $model, string $disk = 'public'): bool
    {
        $images = $model->images;
        $deleted = true;
        
        foreach ($images as $image) {
            $deleted = $deleted && $this->deleteImage($image, $disk);
        }
        
        return $deleted;
    }

    /**
     * Get storage path based on image type
     */
    private function getStoragePath(string $type): string
    {
        return match($type) {
            'avatar' => 'avatars',
            'card' => 'products',
            'detail' => 'product',
            'cover' => 'blog-covers',
            'product' => 'product-images',
            default => 'uploads'
        };
    }

    /**
     * Validate image file
     */
    public function validateImage(UploadedFile $file): bool
    {
        $allowedMimes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
        $maxSize = 2048; // 2MB in KB
        
        return in_array($file->getMimeType(), $allowedMimes) && 
               $file->getSize() <= ($maxSize * 1024);
    }

    /**
     * Get image URL for display
     */
    public function getImageUrl(string $path, string $disk = 'public'): string
    {
        if ($disk === 'public') {
            return asset('storage/' . $path);
        }
        
        // For other disks, return the path as is
        return $path;
    }
}
