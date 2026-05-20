<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(MasterProductCatalogSeeder::class);

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        Role::findOrCreate('super_admin', 'web');
        Role::findOrCreate('seller', 'web');

        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@svd.test'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('Super/Admin?'),
                'email_verified_at' => now(),
            ],
        );

        $superAdmin->syncRoles(['super_admin']);
    }
}
