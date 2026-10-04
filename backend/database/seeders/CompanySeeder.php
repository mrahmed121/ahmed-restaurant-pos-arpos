<?php
namespace Database\Seeders;
use App\Domains\Shared\Models\Company;
use Illuminate\Database\Seeder;
class CompanySeeder extends Seeder
{
    public function run(): void
    {
        Company::firstOrCreate(['code' => 'AHMED-CLINIC'], ['name' => 'Ahmed Clinic', 'address' => 'DHA Phase 5, Karachi', 'phone' => '021-35800000', 'status' => 'active']);
        Company::firstOrCreate(['code' => 'SECOND-CARE'], ['name' => 'Second Care', 'address' => 'Gulberg, Lahore', 'phone' => '042-35770000', 'status' => 'active']);
    }
}
