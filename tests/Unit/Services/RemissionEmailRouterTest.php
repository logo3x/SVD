<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Enums\PaymentType;
use App\Models\Client;
use App\Models\Remission;
use App\Services\RemissionEmailRouter;
use App\Settings\BrandingSettings;
use Tests\TestCase;

class RemissionEmailRouterTest extends TestCase
{
    private function settings(): BrandingSettings
    {
        // Hidratar Spatie Settings sin tocar BD usando reflection sobre las propiedades públicas.
        $s = (new \ReflectionClass(BrandingSettings::class))->newInstanceWithoutConstructor();
        $s->company_name = 'X';
        $s->company_tagline = 'Y';
        $s->primary_phone = '1';
        $s->secondary_phone = '2';
        $s->city = 'C';
        $s->address = 'A';
        $s->email_inbox = 'inbox@example.com';
        $s->email_cash = 'cash@example.com';
        $s->email_cash_for_billing = 'cashfb@example.com';
        $s->email_credit = 'credit@example.com';

        return $s;
    }

    private function remissionWith(PaymentType $type, ?string $clientEmail = null): Remission
    {
        $client = $clientEmail !== null ? new Client(['email' => $clientEmail]) : null;

        $remission = new Remission;
        $remission->payment_type = $type;
        if ($client) {
            $remission->setRelation('client', $client);
        }

        return $remission;
    }

    public function test_credit_routes_to_inbox_credit_and_client(): void
    {
        $router = new RemissionEmailRouter($this->settings());
        $recipients = $router->recipientsFor($this->remissionWith(PaymentType::Credit, 'cliente@x.com'));

        $this->assertSame(
            ['inbox@example.com', 'credit@example.com', 'cliente@x.com'],
            $recipients,
        );
    }

    public function test_cash_routes_to_inbox_cash_and_client(): void
    {
        $router = new RemissionEmailRouter($this->settings());
        $recipients = $router->recipientsFor($this->remissionWith(PaymentType::Cash, 'cliente@x.com'));

        $this->assertContains('cash@example.com', $recipients);
    }

    public function test_cash_for_billing_routes_to_specific_inbox(): void
    {
        $router = new RemissionEmailRouter($this->settings());
        $recipients = $router->recipientsFor($this->remissionWith(PaymentType::CashForBilling, 'c@x.com'));

        $this->assertContains('cashfb@example.com', $recipients);
    }

    public function test_gift_only_uses_main_inbox_and_client(): void
    {
        $router = new RemissionEmailRouter($this->settings());
        $recipients = $router->recipientsFor($this->remissionWith(PaymentType::Gift, 'c@x.com'));

        $this->assertSame(['inbox@example.com', 'c@x.com'], $recipients);
        $this->assertNotContains('credit@example.com', $recipients);
        $this->assertNotContains('cash@example.com', $recipients);
    }

    public function test_other_only_uses_main_inbox_and_client(): void
    {
        $router = new RemissionEmailRouter($this->settings());
        $recipients = $router->recipientsFor($this->remissionWith(PaymentType::Other, 'c@x.com'));

        $this->assertSame(['inbox@example.com', 'c@x.com'], $recipients);
    }

    public function test_extra_email_is_appended(): void
    {
        $router = new RemissionEmailRouter($this->settings());
        $recipients = $router->recipientsFor(
            $this->remissionWith(PaymentType::Credit, 'c@x.com'),
            extra: 'extra@x.com',
        );

        $this->assertContains('extra@x.com', $recipients);
    }

    public function test_duplicates_are_deduplicated(): void
    {
        $router = new RemissionEmailRouter($this->settings());
        $recipients = $router->recipientsFor(
            $this->remissionWith(PaymentType::Credit, 'inbox@example.com'),
            extra: 'inbox@example.com',
        );

        $this->assertSame(count(array_unique($recipients)), count($recipients));
    }

    public function test_works_without_client_email(): void
    {
        $router = new RemissionEmailRouter($this->settings());
        $recipients = $router->recipientsFor($this->remissionWith(PaymentType::Credit, null));

        $this->assertSame(['inbox@example.com', 'credit@example.com'], $recipients);
    }

    public function test_empty_strings_are_filtered_out(): void
    {
        $s = $this->settings();
        $s->email_credit = '';

        $router = new RemissionEmailRouter($s);
        $recipients = $router->recipientsFor($this->remissionWith(PaymentType::Credit, 'c@x.com'));

        $this->assertSame(['inbox@example.com', 'c@x.com'], $recipients);
    }
}
