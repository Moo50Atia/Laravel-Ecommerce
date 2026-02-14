<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;

/**
 * Provides caching capabilities to any repository.
 * Uses a key-prefix system so that related cache entries can be invalidated together.
 */
trait CacheableRepository
{
    /**
     * Execute a query callback and cache the result.
     *
     * @param string   $key  Unique cache key (e.g. "product_stats")
     * @param int      $ttl  Cache TTL in seconds
     * @param \Closure $callback  Query to execute if cache miss
     * @return mixed
     */
    protected function cacheFor(string $key, int $ttl, \Closure $callback): mixed
    {
        $prefix = $this->getCachePrefix();
        $fullKey = "{$prefix}:{$key}";

        return Cache::remember($fullKey, $ttl, $callback);
    }

    /**
     * Invalidate all cache entries with a given key pattern.
     *
     * @param string|null $key  Specific key to forget, or null to clear all for this prefix
     */
    public function invalidateCache(?string $key = null): void
    {
        $prefix = $this->getCachePrefix();

        if ($key) {
            Cache::forget("{$prefix}:{$key}");
        }
    }

    /**
     * Get cache prefix based on model class name.
     * Override in repository for custom prefix.
     */
    protected function getCachePrefix(): string
    {
        return strtolower(class_basename($this->model));
    }
}
