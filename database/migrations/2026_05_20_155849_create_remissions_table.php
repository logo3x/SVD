<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('remissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamp('issued_at')->index();
            $table->string('route', 32);
            $table->string('payment_type', 64);
            $table->string('status', 32)->default('confirmed');
            $table->text('observations')->nullable();
            $table->decimal('gps_lat', 10, 8)->nullable();
            $table->decimal('gps_lng', 11, 8)->nullable();
            $table->unsignedBigInteger('total_amount')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index('payment_type');
            $table->index('status');

            // Índices compuestos para los queries más frecuentes:
            // - panel vendedor: WHERE user_id = ? ORDER BY issued_at DESC
            // - detalle cliente: WHERE client_id = ? ORDER BY issued_at DESC
            // - reportes:        WHERE issued_at BETWEEN ? AND status = ?
            $table->index(['user_id', 'issued_at']);
            $table->index(['client_id', 'issued_at']);
            $table->index(['issued_at', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('remissions');
    }
};
