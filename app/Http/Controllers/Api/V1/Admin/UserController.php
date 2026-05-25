<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $role = $request->string('role')->toString() ?: null;

        $users = User::query()
            ->when($role, fn (Builder $q, string $r) => $q->whereHas('roles', fn ($qq) => $qq->where('name', $r)))
            ->withCount([
                'remissions as remissions_this_month_count' => fn (Builder $q) => $q->whereMonth('issued_at', now()->month)->whereYear('issued_at', now()->year),
                'remissions as remissions_total_count',
            ])
            ->orderBy('name')
            ->paginate(50);

        $payload = $users->getCollection()->map(function (User $u): array {
            $lastToken = $u->tokens()->latest('last_used_at')->first();

            return [
                'id' => $u->id,
                'name' => $u->name,
                'email' => $u->email,
                'roles' => $u->getRoleNames(),
                'role_label' => $u->getRoleLabel(),
                'is_admin' => $u->isAdmin(),
                'remissions_this_month' => (int) ($u->remissions_this_month_count ?? 0),
                'remissions_total' => (int) ($u->remissions_total_count ?? 0),
                'last_login_at' => $lastToken?->last_used_at?->toIso8601String(),
                'created_at' => $u->created_at?->toIso8601String(),
            ];
        });

        return response()->json([
            'data' => $payload,
            'meta' => [
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
            ],
        ]);
    }
}
