<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Settings\MobileSettings;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MobileSettingsController extends Controller
{
    public function show(MobileSettings $settings): JsonResponse
    {
        return response()->json([
            'data' => $this->serialize($settings),
        ]);
    }

    public function update(Request $request, MobileSettings $settings): JsonResponse
    {
        $data = $request->validate([
            'min_app_version' => ['sometimes', 'string', 'max:20'],
            'force_update_version' => ['sometimes', 'string', 'max:20'],
            'maintenance_mode' => ['sometimes', 'boolean'],
            'maintenance_message' => ['sometimes', 'string', 'max:500'],
            'announcement_enabled' => ['sometimes', 'boolean'],
            'announcement_message' => ['sometimes', 'string', 'max:500'],
            'default_token_ttl_days' => ['sometimes', 'integer', 'min:0', 'max:3650'],
            'api_base_url' => ['sometimes', 'url', 'max:255'],
        ]);

        foreach ($data as $key => $value) {
            $settings->{$key} = $value;
        }
        $settings->save();

        return response()->json([
            'data' => $this->serialize($settings),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function serialize(MobileSettings $settings): array
    {
        return [
            'min_app_version' => $settings->min_app_version,
            'force_update_version' => $settings->force_update_version,
            'maintenance_mode' => $settings->maintenance_mode,
            'maintenance_message' => $settings->maintenance_message,
            'announcement_enabled' => $settings->announcement_enabled,
            'announcement_message' => $settings->announcement_message,
            'default_token_ttl_days' => $settings->default_token_ttl_days,
            'api_base_url' => $settings->api_base_url,
        ];
    }
}
