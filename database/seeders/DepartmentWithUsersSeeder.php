<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Acl\Acl;

class DepartmentWithUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = ['IT', 'HR', 'Marketing'];

        $guard = config('auth.defaults.guard', 'api');

        $qaRole = Role::findByName(Acl::ROLE_QA_COORDINATOR, $guard);
        $staffRole = Role::findByName(Acl::ROLE_STAFF, $guard);

        foreach ($departments as $dept) {
            $department = Department::firstOrCreate([
                'name' => $dept,
            ], [
                'status' => 1,
            ]);

            // QA Coordinator
            $qaEmail = sprintf('qa_coordinator_%s@uniideas.vn', strtolower($dept));
            if (! User::where('email', $qaEmail)->exists()) {
                $qa = User::withoutEvents(function () use ($department, $qaEmail, $dept) {
                    return User::create([
                        'name' => sprintf('QA Coordinator %s', $dept),
                        'email' => $qaEmail,
                        'password' => Hash::make('Abcd@123'),
                        'department_id' => $department->id,
                    ]);
                });

                if (isset($qa) && $qaRole) {
                    $qa->syncRoles($qaRole);
                }
            }

            // Staff 1
            $staff1Email = sprintf('staff_%s_1@uniideas.vn', strtolower($dept));
            if (! User::where('email', $staff1Email)->exists()) {
                $staff1 = User::withoutEvents(function () use ($department, $staff1Email, $dept) {
                    return User::create([
                        'name' => sprintf('Staff %s 1', $dept),
                        'email' => $staff1Email,
                        'password' => Hash::make('Abcd@123'),
                        'department_id' => $department->id,
                    ]);
                });

                if (isset($staff1) && $staffRole) {
                    $staff1->syncRoles($staffRole);
                }
            }

            // Staff 2
            $staff2Email = sprintf('staff_%s_2@uniideas.vn', strtolower($dept));
            if (! User::where('email', $staff2Email)->exists()) {
                $staff2 = User::withoutEvents(function () use ($department, $staff2Email, $dept) {
                    return User::create([
                        'name' => sprintf('Staff %s 2', $dept),
                        'email' => $staff2Email,
                        'password' => Hash::make('Abcd@123'),
                        'department_id' => $department->id,
                    ]);
                });

                if (isset($staff2) && $staffRole) {
                    $staff2->syncRoles($staffRole);
                }
            }
        }
    }
}
