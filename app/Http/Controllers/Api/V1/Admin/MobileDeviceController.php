<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\MobileDeviceResource;
use App\Models\MobileDevice;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class MobileDeviceController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $devices = MobileDevice::query()
            ->forUsers()
            ->with('tokenable:id,name,email')
            ->when($request->integer('user_id'), fn ($q, $v) => $q->where('tokenable_id', $v))
            ->when($request->string('search')->toString(), fn ($q, $v) => $q->where('name', 'like', "%{$v}%"))
            ->latest('last_used_at')
            ->paginate(25);

        return MobileDeviceResource::collection($devices);
    }

    public function destroy(MobileDevice $mobileDevice): Response
    {
        $mobileDevice->delete();

        return response()->noContent();
    }

    public function revokeAll(Request $request): JsonResponse
    {
        $data = $request->validate([
            'confirm' => ['required', 'boolean', 'accepted'],
        ]);

        $deleted = MobileDevice::query()->forUsers()->delete();

        return response()->json([
            'message' => 'Todos los tokens fueron revocados.',
            'revoked_count' => $deleted,
        ]);
    }
}
