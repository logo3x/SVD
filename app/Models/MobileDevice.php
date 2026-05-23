<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Laravel\Sanctum\PersonalAccessToken;

/**
 * Alias semántico para los Personal Access Tokens vistos como "dispositivos
 * móviles" — un device_name por dispositivo en el panel admin.
 *
 * Apunta a la misma tabla personal_access_tokens que Sanctum.
 */
class MobileDevice extends PersonalAccessToken
{
    protected $table = 'personal_access_tokens';

    /**
     * Tokens que pertenecen a usuarios (App\Models\User).
     * Filtra tokens de otros tokenables si existieran.
     *
     * @return Builder<self>
     */
    public function scopeForUsers(Builder $query): Builder
    {
        return $query->where('tokenable_type', User::class);
    }

    public function user(): ?User
    {
        if ($this->tokenable_type !== User::class) {
            return null;
        }

        /** @var User|null $u */
        $u = User::find($this->tokenable_id);

        return $u;
    }
}
