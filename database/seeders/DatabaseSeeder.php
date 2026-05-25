<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(MasterProductCatalogSeeder::class);

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        Role::findOrCreate('super_admin', 'web');
        $seller = Role::findOrCreate('seller', 'web');

        // SuperAdmin con bypass total via Shield intercept_gate=before.
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@svd.test'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('Super/Admin?'),
                'email_verified_at' => now(),
            ],
        );
        $superAdmin->syncRoles(['super_admin']);

        // Vendedor de prueba para el panel /vendedor.
        $vendedor = User::firstOrCreate(
            ['email' => 'vendedor@svd.test'],
            [
                'name' => 'Vend Test',
                'password' => bcrypt('vendedor'),
                'email_verified_at' => now(),
            ],
        );
        $vendedor->syncRoles(['seller']);

        // Permisos mínimos del rol seller — sólo lo que necesita para
        // emitir remisiones desde el panel /vendedor. Los permisos los
        // genera `php artisan shield:generate`, aquí sólo los asignamos
        // si ya existen (evita romper si Shield aún no se generó).
        $sellerPermNames = [
            'ViewAny:Remission', 'View:Remission', 'Create:Remission',
            'ViewAny:Client', 'View:Client',
            'ViewAny:Product', 'View:Product',
            'View:ReportesVendedor', 'View:MisVentasOverview',
        ];
        $sellerPerms = Permission::whereIn('name', $sellerPermNames)->get();
        if ($sellerPerms->isNotEmpty()) {
            $seller->syncPermissions($sellerPerms);
        }

        if (app()->environment('local')) {
            $this->call(DemoDataSeeder::class);
        }
    }
}
