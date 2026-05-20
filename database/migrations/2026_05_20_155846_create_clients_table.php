<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name', 128);
            $table->string('nit', 64)->index();
            $table->string('manager_name', 128)->nullable();
            $table->string('description')->nullable();
            $table->string('address')->nullable();
            $table->string('city', 128)->nullable();
            $table->string('phone', 32)->nullable();
            $table->string('whatsapp', 32);
            $table->string('email', 128);
            $table->string('social_networks')->nullable();
            $table->string('delivery_point', 64);
            $table->string('payment_type', 64);
            $table->date('contract_start')->nullable();
            $table->date('contract_end')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
