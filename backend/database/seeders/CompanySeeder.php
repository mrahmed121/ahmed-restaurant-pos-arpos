<?php
namespace Database\Seeders;
use App\Domains\Shared\Models\Company;
use Illuminate\Database\Seeder;
class CompanySeeder extends Seeder
{
    public function run(): void
    {
        Company::firstOrCreate(['code' => 'AHMED-FOODS'], [
            'name' => 'Ahmed Foods', 'address' => 'DHA Phase 5, Karachi',
            'phone' => '021-35800000', 'email' => 'info@ahmedfoods.local', 'status' => 'active',
        ]);
        Company::firstOrCreate(['code' => 'SECOND-EATS'], [
            'name' => 'Second Eats', 'address' => 'Gulberg, Lahore',
            'phone' => '042-35770000', 'email' => 'info@secondeats.local', 'status' => 'active',
        ]);
    }
}
