<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Todo extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'title',
        'description',
        'is_completed',
        'priority',
        'due_date',
        'assigned_to',
        'status',
        'labels',
        'checklist',
        'comments',
        'attachments',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'due_date'     => 'date',
        'labels'       => 'array',
        'checklist'    => 'array',
        'comments'     => 'array',
        'attachments'  => 'array',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function getChecklistProgressAttribute(): int
    {
        $items = $this->checklist ?? [];

        if (empty($items)) {
            return 0;
        }

        $done = collect($items)->where('done', true)->count();

        return (int) round(($done / count($items)) * 100);
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->due_date
            && !$this->is_completed
            && $this->due_date->isPast();
    }
}
