<?php

declare(strict_types=1);

namespace App\Observers;

use App\Actions\AttachDefaultProductsAction;
use App\Models\Client;

class ClientObserver
{
    public function __construct(private AttachDefaultProductsAction $attachDefaults) {}

    public function created(Client $client): void
    {
        $this->attachDefaults->execute($client);
    }
}
