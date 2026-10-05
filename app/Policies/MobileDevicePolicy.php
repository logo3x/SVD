<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\MobileDevice;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class MobileDevicePolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:MobileDevice');
    }

    public function view(AuthUser $authUser, MobileDevice $mobileDevice): bool
    {
        return $authUser->can('View:MobileDevice');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:MobileDevice');
    }

    public function update(AuthUser $authUser, MobileDevice $mobileDevice): bool
    {
        return $authUser->can('Update:MobileDevice');
    }

    public function delete(AuthUser $authUser, MobileDevice $mobileDevice): bool
    {
        return $authUser->can('Delete:MobileDevice');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:MobileDevice');
    }

    public function restore(AuthUser $authUser, MobileDevice $mobileDevice): bool
    {
        return $authUser->can('Restore:MobileDevice');
    }

    public function forceDelete(AuthUser $authUser, MobileDevice $mobileDevice): bool
    {
        return $authUser->can('ForceDelete:MobileDevice');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:MobileDevice');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:MobileDevice');
    }

    public function replicate(AuthUser $authUser, MobileDevice $mobileDevice): bool
    {
        return $authUser->can('Replicate:MobileDevice');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:MobileDevice');
    }
}
