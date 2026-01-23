<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Submission extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'closure_date',
        'final_closure_date',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'closure_date' => 'datetime',
        'final_closure_date' => 'datetime',
    ];

    /**
     * Get the ideas for the submission.
     */
    public function ideas()
    {
        return $this->hasMany(Idea::class);
    }

    /**
     * Check if submission is closed (closure_date has passed).
     */
    public function getIsClosedAttribute(): bool
    {
        return now()->greaterThan($this->closure_date);
    }

    /**
     * Check if submission is finally closed (final_closure_date has passed).
     */
    public function getIsFinalClosedAttribute(): bool
    {
        return now()->greaterThan($this->final_closure_date);
    }

    /**
     * Scope to get active submissions (not finally closed).
     */
    public function scopeActive($query)
    {
        return $query->where('final_closure_date', '>', now());
    }

    /**
     * Scope to get closed submissions (closure_date passed).
     */
    public function scopeClosed($query)
    {
        return $query->where('closure_date', '<=', now());
    }
}