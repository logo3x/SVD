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
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('remissions');
    }
};
