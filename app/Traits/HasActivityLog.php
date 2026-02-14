<?php

namespace App\Traits;

use App\Models\ActivityLog;

/**
 * Provides automatic activity logging for any Eloquent model.
 * Logs created, updated, and deleted events with old/new property snapshots.
 *
 * Usage: Add `use HasActivityLog;` to your model.
 * Override `getLogType()` and `getTrackedAttributes()` for customization.
 */
trait HasActivityLog
{
    /**
     * Boot the trait: register model event listeners.
     */
    public static function bootHasActivityLog(): void
    {
        static::created(function ($model) {
            $model->logActivity('created', [], $model->getAttributes());
        });

        static::updated(function ($model) {
            $dirty = $model->getDirty();
            if (!empty($dirty)) {
                $original = array_intersect_key($model->getOriginal(), $dirty);
                $model->logActivity('updated', $original, $dirty);
            }
        });

        static::deleted(function ($model) {
            $model->logActivity('deleted', $model->getOriginal(), []);
        });
    }

    /**
     * Log an activity for this model.
     */
    public function logActivity(string $event, array $old, array $new): void
    {
        $logType = $this->getLogType();
        $eventName = $logType . '.' . $event;

        // Filter to only tracked attributes if configured
        $tracked = $this->getTrackedAttributes();
        if (!empty($tracked)) {
            $old = array_intersect_key($old, array_flip($tracked));
            $new = array_intersect_key($new, array_flip($tracked));
        }

        // Remove sensitive attributes
        $sensitive = ['password', 'remember_token'];
        $old = array_diff_key($old, array_flip($sensitive));
        $new = array_diff_key($new, array_flip($sensitive));

        ActivityLog::create([
            'log_type' => $logType,
            'event' => $eventName,
            'trackable_type' => get_class($this),
            'trackable_id' => $this->getKey(),
            'causer_type' => auth()->check() ? get_class(auth()->user()) : null,
            'causer_id' => auth()->id(),
            'properties' => [
                'old' => !empty($old) ? $old : null,
                'new' => !empty($new) ? $new : null,
            ],
            'ip_address' => request()?->ip(),
            'user_agent' => request()?->userAgent(),
        ]);
    }

    /**
     * Get the log type for this model (e.g. 'product', 'order').
     * Override in model for customization.
     */
    public function getLogType(): string
    {
        return strtolower(class_basename($this));
    }

    /**
     * Get the list of attributes to track.
     * Return empty array to track all attributes.
     * Override in model for customization.
     */
    public function getTrackedAttributes(): array
    {
        return [];
    }

    /**
     * Get all activity logs for this model.
     */
    public function activityLogs()
    {
        return $this->morphMany(ActivityLog::class, 'trackable');
    }
}
