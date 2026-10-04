<?php
namespace Database\Seeders;
use App\Domains\Shared\Models\Company;
use App\Domains\Shared\Models\Role;
use App\Domains\Shared\Models\User;
use Illuminate\Database\Seeder;
class UserSeeder extends Seeder
{
    public const DEMO_PASSWORD = 'password123';
    public function run(): void
    {
        $superRole = Role::where('slug', 'super-admin')->firstOrFail();
        $super = User::firstOrCreate(['email' => 'super@arpos.local'],
            ['name' => 'Super Admin', 'company_id' => null, 'password' => self::DEMO_PASSWORD, 'is_active' => true]);
        $super->roles()->syncWithoutDetaching([$superRole->id]);

        $company = Company::where('code', 'AHMED-FOODS')->firstOrFail();
        $map = [
            ['Owner', 'owner', 'owner'], ['Branch Manager', 'manager', 'branch-manager'],
            ['Cashier', 'cashier', 'cashier'], ['Waiter', 'waiter', 'waiter'],
            ['Kitchen Staff', 'kitchen', 'kitchen-staff'], ['Accountant', 'accountant', 'accountant'],
            ['Inventory Manager', 'inventory', 'inventory-manager'], ['Auditor', 'auditor', 'auditor'],
        ];
        foreach ($map as [$name, $local, $roleSlug]) {
            $user = User::firstOrCreate(['email' => "{$local}@ahmedfoods.local"],
                ['name' => $name, 'company_id' => $company->id, 'password' => self::DEMO_PASSWORD, 'is_active' => true]);
            $role = Role::where('slug', $roleSlug)->firstOrFail();
            $user->roles()->syncWithoutDetaching([$role->id]);
        }

        $companyB = Company::where('code', 'SECOND-EATS')->firstOrFail();
        $userB = User::firstOrCreate(['email' => 'owner@secondeats.local'],
            ['name' => 'Second Owner', 'company_id' => $companyB->id, 'password' => self::DEMO_PASSWORD, 'is_active' => true]);
        $ownerRole = Role::where('slug', 'owner')->firstOrFail();
        $userB->roles()->syncWithoutDetaching([$ownerRole->id]);
    }
}
