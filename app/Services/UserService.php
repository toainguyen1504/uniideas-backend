<?php

namespace App\Services;

use App\Acl\Acl;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Repositories\User\UserRepositoryInterface;
use Nette\Utils\Json;
use Illuminate\Support\Facades\Log;

class UserService
{
    /**
     * Summary of __construct
     *
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(
        protected UserRepositoryInterface $userRepository,
    ) {
        //
    }

    public function create($data)
    {
        try {
            DB::beginTransaction();

            $data['name'] = $data['last_name'] . ' ' . $data['first_name'];

            if (empty($data['password'])) {
                unset($data['password']);
            } else {
                $data['password'] = Hash::make($data['password']);
            }

            $user = $this->userRepository->create($data);

            if (isset($data['department_id']) && $data['department_id']) {
                $user->department()->associate($data['department_id']);
                $user->save();
            }

            $rolesInput = $data['roles'] ?? [];
            if (!is_array($rolesInput)) {
                if (is_string($rolesInput)) {
                    $rolesInput = array_filter(array_map('trim', explode(',', $rolesInput)));
                } else {
                    $rolesInput = (array) $rolesInput;
                }
            }
            $user->syncRoles(array_map(fn($role) => (int) $role, $rolesInput));
            $user->load('roles');

            DB::commit();

            return $user;
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error creating user: ' . $e->getMessage());
            return null;
        }
    }

    public function update(User $user, array $data)
    {
        try {
            DB::beginTransaction();

            if (empty($data['password'])) {
                unset($data['password']);
            } else {
                $data['password'] = Hash::make($data['password']);
            }

            if (isset($data['last_name']) && isset($data['first_name'])) {
                $data['name'] = $data['last_name'] . ' ' . $data['first_name'];
            }

            $user->update($data);

            if (isset($data['department'])) {
                $user->department()->associate($data['department']);
                $user->save();
            }

            $rolesInput = $data['roles'] ?? [];
            if (!is_array($rolesInput)) {
                if (is_string($rolesInput)) {
                    $rolesInput = array_filter(array_map('trim', explode(',', $rolesInput)));
                } else {
                    $rolesInput = (array) $rolesInput;
                }
            }
            $user->syncRoles(array_map(fn($role) => (int) $role, $rolesInput));
            $user->load('roles');

            DB::commit();

            return $user;
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error updating user: ' . $e->getMessage());
            return null;
        }
    }

    public function updateProfileBySelf(User $user, array $data)
    {
        try {
            DB::beginTransaction();

            if (isset($data['last_name']) && isset($data['first_name'])) {
                $data['name'] = $data['last_name'] . ' ' . $data['first_name'];
            }

            $user->update($data);

            DB::commit();

            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating user profile: ' . $e->getMessage());
            return null;
        }
    }
}
