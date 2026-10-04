<?php
namespace Database\Seeders;
use App\Domains\Shared\Models\Permission;
use App\Domains\Shared\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    public const PERMISSIONS = [
        ['slug' => 'dashboard.view', 'name' => 'View dashboard'],
        ['slug' => 'branches.view', 'name' => 'View branches'],
        ['slug' => 'branches.manage', 'name' => 'Manage branches'],
        ['slug' => 'menu.view', 'name' => 'View menu'],
        ['slug' => 'menu.manage', 'name' => 'Manage menu'],
        ['slug' => 'orders.view', 'name' => 'View orders'],
        ['slug' => 'orders.create', 'name' => 'Create orders'],
        ['slug' => 'orders.manage', 'name' => 'Manage orders'],
        ['slug' => 'kitchen.view', 'name' => 'View KOT'],
        ['slug' => 'kitchen.manage', 'name' => 'Manage KOT'],
        ['slug' => 'payments.view', 'name' => 'View payments'],
        ['slug' => 'payments.create', 'name' => 'Process payments'],
        ['slug' => 'inventory.view', 'name' => 'View inventory'],
        ['slug' => 'inventory.manage', 'name' => 'Manage inventory'],
        ['slug' => 'staff.view', 'name' => 'View staff'],
        ['slug' => 'staff.manage', 'name' => 'Manage staff'],
        ['slug' => 'expenses.view', 'name' => 'View expenses'],
        ['slug' => 'expenses.manage', 'name' => 'Manage expenses'],
        ['slug' => 'reports.view', 'name' => 'View reports'],
        ['slug' => 'users.view', 'name' => 'View users'],
        ['slug' => 'users.manage', 'name' => 'Manage users'],
        ['slug' => 'settings.view', 'name' => 'View settings'],
        ['slug' => 'settings.manage', 'name' => 'Manage settings'],
        ['slug' => 'audit.view', 'name' => 'View audit logs'],
    ];

    public const ROLES = [
        'super-admin' => ['name' => 'Super Admin', 'permissions' => '*'],
        'owner' => ['name' => 'Restaurant Owner', 'permissions' => [
            'dashboard.view','branches.view','branches.manage','menu.view','menu.manage',
            'orders.view','orders.create','orders.manage','kitchen.view','kitchen.manage',
            'payments.view','payments.create','inventory.view','inventory.manage',
            'staff.view','staff.manage','expenses.view','expenses.manage',
            'reports.view','users.view','users.manage','settings.view','settings.manage','audit.view',
        ]],
        'branch-manager' => ['name' => 'Branch Manager', 'permissions' => [
            'dashboard.view','branches.view','menu.view','menu.manage',
            'orders.view','orders.create','orders.manage','kitchen.view',
            'payments.view','payments.create','inventory.view','inventory.manage',
            'staff.view','expenses.view','expenses.manage','reports.view','users.view',
        ]],
        'cashier' => ['name' => 'Cashier', 'permissions' => [
            'dashboard.view','menu.view','orders.view','orders.create',
            'payments.view','payments.create',
        ]],
        'waiter' => ['name' => 'Waiter', 'permissions' => [
            'dashboard.view','menu.view','orders.view','orders.create',
        ]],
        'kitchen-staff' => ['name' => 'Kitchen Staff', 'permissions' => [
            'dashboard.view','kitchen.view','kitchen.manage',
        ]],
        'accountant' => ['name' => 'Accountant', 'permissions' => [
            'dashboard.view','orders.view','payments.view','expenses.view','expenses.manage',
            'reports.view','audit.view',
        ]],
        'inventory-manager' => ['name' => 'Inventory Manager', 'permissions' => [
            'dashboard.view','menu.view','inventory.view','inventory.manage',
        ]],
        'auditor' => ['name' => 'Auditor', 'permissions' => [
            'dashboard.view','branches.view','menu.view','orders.view',
            'payments.view','inventory.view','staff.view','expenses.view',
            'reports.view','audit.view',
        ]],
    ];

    public function run(): void
    {
        DB::transaction(function () {
            foreach (self::PERMISSIONS as $p) {
                Permission::firstOrCreate(['slug' => $p['slug']], ['name' => $p['name']]);
            }
            $all = Permission::pluck('slug')->all();
            foreach (self::ROLES as $slug => $def) {
                $role = Role::firstOrCreate(['slug' => $slug], ['name' => $def['name']]);
                $slugs = $def['permissions'] === '*' ? $all : $def['permissions'];
                $role->permissions()->sync(Permission::whereIn('slug', $slugs)->pluck('id'));
            }
        });
    }
}
