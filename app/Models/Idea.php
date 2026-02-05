<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enum\IdeaStatus;
use App\Enum\AnonymousEnum;
;;

class Idea extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'file_path',
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
}
