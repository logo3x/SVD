<?php

declare(strict_types=1);

namespace App\Actions;

use App\Mail\RemisionCreada;
use App\Models\Remission;
use App\Services\RemissionEmailRouter;
use Illuminate\Support\Facades\Mail;
use Throwable;

/**
 * Envía el comprobante de una remisión por correo.
 *
 * Un fallo de correo (SMTP caído, plantilla, etc.) se registra en el log pero
 * nunca interrumpe el guardado de la remisión: con QUEUE_CONNECTION=sync el
 * envío ocurre dentro de la misma petición y un error devolvería 500 aunque
 * la remisión ya esté guardada (la app móvil reintentaría y la duplicaría).
 */
class SendRemissionEmailAction
{
    public function __construct(private RemissionEmailRouter $router) {}

    /**
     * @return int|null Número de destinatarios, o null si el envío falló.
     */
    public function execute(Remission $remission, ?string $extraEmail = null, bool $isCopy = false): ?int
    {
        $recipients = $this->router->recipientsFor($remission, $extraEmail);

        try {
            Mail::to($recipients)->queue(new RemisionCreada($remission, $isCopy));
        } catch (Throwable $exception) {
            report($exception);

            return null;
        }

        return count($recipients);
    }
}
