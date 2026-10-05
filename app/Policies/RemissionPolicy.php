<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Remission;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class RemissionPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Remission');
    }

    public function view(AuthUser $authUser, Remission $remission): bool
    {
        return $authUser->can('View:Remission');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Remission');
    }

    public function update(AuthUser $authUser, Remission $remission): bool
    {
        return $authUser->can('Update:Remission');
    }

    public function delete(AuthUser $authUser, Remission $remission): bool
    {
        return $authUser->can('Delete:Remission');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Remission');
    }

    public function restore(AuthUser $authUser, Remission $remission): bool
    {
        return $authUser->can('Restore:Remission');
    }

    public function forceDelete(AuthUser $authUser, Remission $remission): bool
    {
        return $authUser->can('ForceDelete:Remission');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Remission');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Remission');
    }

    public function replicate(AuthUser $authUser, Remission $remission): bool
    {
        return $authUser->can('Replicate:Remission');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Remission');
    }
}
