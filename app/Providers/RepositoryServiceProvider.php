<?php

namespace App\Providers;

use App\Repositories\Department\DepartmentRepository;
use App\Repositories\Department\DepartmentRepositoryInterface;
<<<<<<< HEAD
use App\Repositories\Category\CategoryRepository;
use App\Repositories\Category\CategoryRepositoryInterface;
=======
>>>>>>> d48c9ebcd168ba4501b59c33f7ad06ed5c190435
use App\Repositories\Role\RoleRepository;
use App\Repositories\Role\RoleRepositoryInterface;
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\ServiceProvider;


class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(UserRepositoryInterface::class, UserRepository::class);
        $this->app->singleton(RoleRepositoryInterface::class, RoleRepository::class);
        $this->app->singleton(DepartmentRepositoryInterface::class, DepartmentRepository::class);
<<<<<<< HEAD
        $this->app->singleton(CategoryRepositoryInterface::class,CategoryRepository::class);
=======
>>>>>>> d48c9ebcd168ba4501b59c33f7ad06ed5c190435
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
