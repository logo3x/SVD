<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\ClientResource;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ClientController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $search = $request->string('search')->toString();

        $clients = Client::query()
            ->where('is_active', true)
            ->when($search, fn ($query, string $term) => $query->where(function ($q) use ($term): void {
                $q->where('name', 'like', "%{$term}%")
                    ->orWhere('nit', 'like', "%{$term}%");
            }))
            ->orderBy('name')
            ->paginate(25);

        return ClientResource::collection($clients);
    }

    public function show(Client $client): ClientResource
    {
        abort_unless($client->is_active, 404);

        return ClientResource::make($client);
    }
}
