<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CourseMaterial extends Model
{
    protected $fillable = [
        'course_id',
        'course_section_id',
        'title',
        'type',
        'content',
        'file_path',
        'video_url',
        'sort_order',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(CourseSection::class, 'course_section_id');
    }

    public function completions(): HasMany
    {
        return $this->hasMany(MaterialCompletion::class);
    }
}
