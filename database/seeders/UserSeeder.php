<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Acl\Acl;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (User::count() > 0) {
            return;
        }

        $admin = User::withoutEvents(function () {
            return User::create([
                'name' => 'Admin',
                'email' => 'admin@uniideas.vn',
                'password' => Hash::make('Abcd@123'),
            ]);
        });

        $qaManager = User::withoutEvents(function () {
            return User::create([
                'name' => 'QA Manager',
                'email' => 'qa_manager@uniideas.vn',
                'password' => Hash::make('Abcd@123'),
            ]);
        });

        $qaCoordinator = User::withoutEvents(function () {
            return User::create([
                'name' => 'QA Coordinator',
                'email' => 'qa_coordinator@uniideas.vn',
                'password' => Hash::make('Abcd@123'),
            ]);
        });

        $staff = User::withoutEvents(function () {
            return User::create([
                'name' => 'Staff',
                'email' => 'staff@uniideas.vn',
                'password' => Hash::make('Abcd@123'),
            ]);
        });

        $guard = config('auth.defaults.guard', 'api');

        $adminRole = Role::findByName(Acl::ROLE_ADMIN, $guard);
        $qaManagerRole = Role::findByName(Acl::ROLE_QA_MANAGER, $guard);
        $qaCoordinatorRole = Role::findByName(Acl::ROLE_QA_COORDINATOR, $guard);
        $staffRole = Role::findByName(Acl::ROLE_STAFF, $guard);

        //Sync Roles to seed accounts
        $admin->syncRoles($adminRole);
        $qaManager->syncRoles($qaManagerRole);
        $qaCoordinator->syncRoles($qaCoordinatorRole);
        $staff->syncRoles($staffRole);
    }
}
