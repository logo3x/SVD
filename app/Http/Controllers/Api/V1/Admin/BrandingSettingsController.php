<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Settings\BrandingSettings;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BrandingSettingsController extends Controller
{
    public function show(BrandingSettings $settings): JsonResponse
    {
        return response()->json([
            'data' => $this->serialize($settings),
        ]);
    }

    public function update(Request $request, BrandingSettings $settings): JsonResponse
    {
        $data = $request->validate([
            'company_name' => ['sometimes', 'string', 'max:128'],
            'company_tagline' => ['sometimes', 'string', 'max:255'],
            'primary_phone' => ['sometimes', 'string', 'max:32'],
            'secondary_phone' => ['sometimes', 'string', 'max:32'],
            'city' => ['sometimes', 'string', 'max:128'],
            'address' => ['sometimes', 'string', 'max:255'],
            'email_inbox' => ['sometimes', 'email', 'max:128'],
            'email_cash' => ['sometimes', 'email', 'max:128'],
            'email_cash_for_billing' => ['sometimes', 'email', 'max:128'],
            'email_credit' => ['sometimes', 'email', 'max:128'],
            'logo_path' => ['sometimes', 'nullable', 'string', 'max:255'],
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
    private function serialize(BrandingSettings $settings): array
    {
        return [
            'company_name' => $settings->company_name,
            'company_tagline' => $settings->company_tagline,
            'primary_phone' => $settings->primary_phone,
            'secondary_phone' => $settings->secondary_phone,
            'city' => $settings->city,
            'address' => $settings->address,
            'email_inbox' => $settings->email_inbox,
            'email_cash' => $settings->email_cash,
            'email_cash_for_billing' => $settings->email_cash_for_billing,
            'email_credit' => $settings->email_credit,
            'logo_path' => $settings->logo_path,
            'logo_url' => $settings->logo_path && Storage::disk('public')->exists($settings->logo_path)
                ? Storage::disk('public')->url($settings->logo_path)
                : null,
        ];
    }
}
