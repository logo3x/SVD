<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // El vínculo con un usuario del sistema pasa a ser OPCIONAL:
        // se permiten empleados de RRHH que no tienen acceso/login.
        Schema::table('employee_profiles', function (Blueprint $table): void {
            // Soltamos la FK para poder cambiar la columna a nullable.
            $table->dropForeign(['user_id']);
            $table->unsignedBigInteger('user_id')->nullable()->change();
            // Recreamos la FK; al borrar el usuario, el empleado queda
            // sin vínculo (no se borra) — su historial RRHH se conserva.
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();

            // Datos propios del empleado para cuando NO hay usuario vinculado.
            $table->string('full_name', 128)->nullable()->after('user_id');
            $table->string('email', 128)->nullable()->after('full_name');
        });
    }

    public function down(): void
    {
        Schema::table('employee_profiles', function (Blueprint $table): void {
            $table->dropColumn(['full_name', 'email']);
            $table->dropForeign(['user_id']);
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }
};
