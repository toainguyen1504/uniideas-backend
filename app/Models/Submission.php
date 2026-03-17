<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enum\SubmissionStatus;
use Carbon\Carbon;

class Submission extends Model
{
  use HasFactory;

  protected $fillable = [
    'name',
    'closure_date',
    'final_closure_date',
  ];

  protected $casts = [
    'closure_date' => 'datetime',
    'final_closure_date' => 'datetime',
    // Không cần cast status vì nó là computed attribute
  ];

  protected $appends = [
    'status', // Giữ lại status attribute
    'is_closed',
    'is_final_closed',
  ];

  public function ideas()
  {
    return $this->hasMany(Idea::class);
  }

  /**
   * Get submission status using enum
   */
  public function getStatusAttribute(): SubmissionStatus
  {

    if (now()->greaterThan($this->final_closure_date)) {
      return SubmissionStatus::FINALLY_CLOSED;
    }

    if (now()->greaterThan($this->closure_date)) {
      return SubmissionStatus::CLOSED;
    }

    return SubmissionStatus::OPEN;
  }

  /**
   * Get status as string (for backward compatibility)
   */
  public function getStatusStringAttribute(): string
  {
    return $this->status->value;
  }

  /**
   * Check if submission is closed (computed from enum)
   */
  public function getIsClosedAttribute(): bool
  {
    return $this->status === SubmissionStatus::CLOSED
      || $this->status === SubmissionStatus::FINALLY_CLOSED;
  }

  /**
   * Check if submission is finally closed
   */
  public function getIsFinalClosedAttribute(): bool
  {
    return $this->status === SubmissionStatus::FINALLY_CLOSED;
  }

  /**
   * Check if submission can accept new ideas
   */
  public function canAcceptIdeas(): bool
  {
    return $this->status->canAcceptIdeas();
  }

  /**
   * Check if submission can be modified
   */
  public function canBeModified(): bool
  {
    return $this->status->canBeModified();
  }



  /**
   * Get remaining days until closure
   */
  public function getRemainingDaysAttribute(): ?int
  {
    if ($this->is_closed) {
      return null;
    }

    return now()->diffInDays($this->closure_date, false);
  }

  /**
   * Scope query by status
   */
  public function scopeByStatus($query, SubmissionStatus $status)
  {
    return match ($status) {
      SubmissionStatus::OPEN => $query->where('closure_date', '>', now()),
      SubmissionStatus::CLOSED => $query->where('closure_date', '<=', now())
        ->where('final_closure_date', '>', now()),
      SubmissionStatus::FINALLY_CLOSED => $query->where('final_closure_date', '<=', now()),
    };
  }

  /**
   * Scope query for active submissions (not finally closed)
   */
  public function scopeActive($query)
  {
    return $query->where('final_closure_date', '>', now());
  }

  /**
   * Scope query for open submissions
   */
  public function scopeOpen($query)
  {
    return $this->scopeByStatus($query, SubmissionStatus::OPEN);
  }

  /**
   * Scope query for closed submissions
   */
  public function scopeClosed($query)
  {
    return $this->scopeByStatus($query, SubmissionStatus::CLOSED);
  }

  /**
   * Scope query for finally closed submissions
   */
  public function scopeFinallyClosed($query)
  {
    return $this->scopeByStatus($query, SubmissionStatus::FINALLY_CLOSED);
  }
}
