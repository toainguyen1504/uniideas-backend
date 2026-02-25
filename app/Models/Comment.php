<?php

namespace App\Models;

use App\Enum\ActiveStatus;
use App\Enum\AnonymousEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\InteractsWithMedia;

class Comment extends Model
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'content',
        'user_id',
        'idea_id',
        'is_anonymous',
        'status',
    ];

    protected $casts = [
        'is_anonymous' => AnonymousEnum::class,
        'status' => ActiveStatus::class,
    ];

    /**
     * Get relation to user.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get relation to idea.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function idea()
    {
        return $this->belongsTo(Idea::class);
    }
}
