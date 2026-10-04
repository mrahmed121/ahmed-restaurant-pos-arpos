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
        ['slug' => 'patients.view', 'name' => 'View patients'],
        ['slug' => 'patients.manage', 'name' => 'Manage patients'],
        ['slug' => 'appointments.view', 'name' => 'View appointments'],
        ['slug' => 'appointments.manage', 'name' => 'Manage appointments'],
        ['slug' => 'doctors.view', 'name' => 'View doctors'],
        ['slug' => 'doctors.manage', 'name' => 'Manage doctors'],
        ['slug' => 'pharmacy.view', 'name' => 'View pharmacy'],
        ['slug' => 'pharmacy.manage', 'name' => 'Manage pharmacy'],
        ['slug' => 'reports.view', 'name' => 'View reports'],
        ['slug' => 'users.view', 'name' => 'View users'],
        ['slug' => 'users.manage', 'name' => 'Manage users'],
        ['slug' => 'settings.view', 'name' => 'View settings'],
        ['slug' => 'settings.manage', 'name' => 'Manage settings'],
        ['slug' => 'audit.view', 'name' => 'View audit logs'],
    ];
    public const ROLES = [
        'super-admin' => ['name' => 'Super Admin', 'permissions' => '*'],
        'admin' => ['name' => 'Clinic Admin', 'permissions' => ['dashboard.view','patients.view','patients.manage','appointments.view','appointments.manage','doctors.view','doctors.manage','pharmacy.view','pharmacy.manage','reports.view','users.view','users.manage','settings.view','settings.manage','audit.view']],
        'doctor' => ['name' => 'Doctor', 'permissions' => ['dashboard.view','patients.view','appointments.view','appointments.manage','pharmacy.view']],
        'nurse' => ['name' => 'Nurse', 'permissions' => ['dashboard.view','patients.view','appointments.view']],
        'receptionist' => ['name' => 'Receptionist', 'permissions' => ['dashboard.view','patients.view','patients.manage','appointments.view','appointments.manage','doctors.view']],
        'pharmacist' => ['name' => 'Pharmacist', 'permissions' => ['dashboard.view','pharmacy.view','pharmacy.manage','patients.view']],
        'accountant' => ['name' => 'Accountant', 'permissions' => ['dashboard.view','appointments.view','reports.view','audit.view']],
        'auditor' => ['name' => 'Auditor', 'permissions' => ['dashboard.view','patients.view','appointments.view','doctors.view','pharmacy.view','reports.view','audit.view']],
    ];
    public function run(): void
    {
        DB::transaction(function () {
            foreach (self::PERMISSIONS as $p) Permission::firstOrCreate(['slug' => $p['slug']], ['name' => $p['name']]);
            $all = Permission::pluck('slug')->all();
            foreach (self::ROLES as $slug => $def) {
                $role = Role::firstOrCreate(['slug' => $slug], ['name' => $def['name']]);
                $slugs = $def['permissions'] === '*' ? $all : $def['permissions'];
                $role->permissions()->sync(Permission::whereIn('slug', $slugs)->pluck('id'));
            }
        });
    }
}
