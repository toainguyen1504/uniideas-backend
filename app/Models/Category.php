<?php

namespace App\Models;

use App\Enum\CategoryStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status',
    ];

    protected $casts = [
        'status' => CategoryStatus::class,
    ];

    // Scope cho các trạng thái
    public function scopeActive($query)
    {
        return $query->where('status', CategoryStatus::ACTIVE);
    }

    public function scopeInactive($query)
    {
        return $query->where('status', CategoryStatus::INACTIVE);
    }
}