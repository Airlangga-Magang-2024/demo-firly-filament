<?php

namespace App\Models\Blog;

use App\Models\Comment;
use Spatie\Tags\HasTags;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;

class Post extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    use HasTags;

    protected $table = 'blog_posts';

    protected $fillable = [
        'title',
        'slug',
        'content',
        'blog_author_id',
        'published_at',
        'tags',
        'image',
    ];

    protected $casts = [
        'published_at' => 'date',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class, 'blog_author_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'blog_category_id');
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function getTagNamesAttribute()
    {
        return $this->tags->pluck('name')->toArray();
    }
}
