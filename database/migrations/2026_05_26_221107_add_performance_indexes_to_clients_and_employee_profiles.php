<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table): void {
            // Usado por la API (?is_active=true) y filtros de tabla.
            $table->index('is_active');
        });

        Schema::table('employee_profiles', function (Blueprint $table): void {
            // Filtrable en el listado de empleados (SelectFilter).
            $table->index('employment_status');
        });
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table): void {
            $table->dropIndex(['is_active']);
        });

        Schema::table('employee_profiles', function (Blueprint $table): void {
            $table->dropIndex(['employment_status']);
        });
    }
};
