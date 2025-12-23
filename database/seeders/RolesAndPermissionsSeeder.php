<?php
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::create(['name' => 'view products']);
        Permission::create(['name' => 'create products']);
        Permission::create(['name' => 'edit products']);
        Permission::create(['name' => 'delete products']);

        $admin = Role::create(['name' => 'admin']);
        $staff = Role::create(['name' => 'staff']);

        $admin->givePermissionTo(Permission::all());
        $staff->givePermissionTo(['view products']);
    }
}
