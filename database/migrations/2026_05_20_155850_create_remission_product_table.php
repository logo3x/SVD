<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('remission_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('remission_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->restrictOnDelete();
            $table->unsignedInteger('quantity');
            $table->unsignedInteger('unit_price_snapshot');
            $table->unsignedBigInteger('subtotal');
            $table->timestamps();

            $table->index(['remission_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('remission_product');
    }
};
