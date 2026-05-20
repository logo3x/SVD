<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete()->unique();
            $table->string('national_id', 32)->nullable()->index();
            $table->string('job_title', 128)->nullable();
            $table->string('phone', 32)->nullable();
            $table->string('whatsapp', 32)->nullable();
            $table->date('birth_date')->nullable();
            $table->string('city')->nullable();
            $table->string('address')->nullable();
            $table->string('marital_status', 32)->nullable();
            $table->unsignedTinyInteger('children')->nullable();
            $table->string('employment_link', 64)->nullable();
            $table->string('employment_status', 32)->default('active');
            $table->date('contract_start')->nullable();
            $table->date('retired_at')->nullable();
            $table->string('blood_type', 3)->nullable();
            $table->string('eps')->nullable();
            $table->string('afp')->nullable();
            $table->string('arl')->nullable();
            $table->string('bank_account')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_profiles');
    }
};
