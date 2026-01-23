<?php

namespace App\Traits;
use App\Models\Department;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait Departmentable
{
    /**
     * Get the model's department.
     */
    public function departments(): MorphToMany
    {
        return $this->morphToMany(Department::class, 'departmentable');
    }
}
