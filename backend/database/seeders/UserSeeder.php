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
        $super = User::firstOrCreate(['email' => 'super@acms.local'], ['name' => 'Super Admin', 'company_id' => null, 'password' => self::DEMO_PASSWORD, 'is_active' => true]);
        $super->roles()->syncWithoutDetaching([$superRole->id]);
        $company = Company::where('code', 'AHMED-CLINIC')->firstOrFail();
        foreach ([['Admin','admin','admin'],['Doctor','doctor','doctor'],['Receptionist','receptionist','receptionist'],['Pharmacist','pharmacist','pharmacist'],['Auditor','auditor','auditor']] as [$name, $local, $roleSlug]) {
            $user = User::firstOrCreate(['email' => "{$local}@ahmedclinic.local"], ['name' => $name, 'company_id' => $company->id, 'password' => self::DEMO_PASSWORD, 'is_active' => true]);
            $user->roles()->syncWithoutDetaching([Role::where('slug', $roleSlug)->firstOrFail()->id]);
        }
        $companyB = Company::where('code', 'SECOND-CARE')->firstOrFail();
        $userB = User::firstOrCreate(['email' => 'admin@secondcare.local'], ['name' => 'Second Admin', 'company_id' => $companyB->id, 'password' => self::DEMO_PASSWORD, 'is_active' => true]);
        $userB->roles()->syncWithoutDetaching([Role::where('slug', 'admin')->firstOrFail()->id]);
    }
}
