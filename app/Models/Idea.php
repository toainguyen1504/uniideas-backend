<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enum\IdeaStatus;
use App\Enum\AnonymousEnum;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Idea extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'status',
        'is_anonymous',
        'total_views',
        'total_comments',
        'user_id',
        'category_id',
        'submission_id',
    ];

    protected $casts = [
        'status'         => IdeaStatus::class, 
        'is_anonymous'   => AnonymousEnum::class,
        'total_views'    => 'integer',
        'total_comments' => 'integer',
    ];

    protected $with = ['media'];

    const FILE_PATH_COLLECTION = 'file_path';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function submission()
    {
        return $this->belongsTo(Submission::class);
    }

    /**
     * Get file path for document
     *
     * @param string $value
     * @return string|null
     */
    public function getFilePathAttribute($value): ?string
    {
        return $this->getFirstMediaUrl(self::FILE_PATH_COLLECTION) ?: null;
    }

    /**
     * Register media collections for the model.
     *
     * @return void
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(self::FILE_PATH_COLLECTION);
    }
}
