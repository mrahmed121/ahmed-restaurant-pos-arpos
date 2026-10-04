<?php
namespace Database\Seeders;
use App\Domains\Clinic\Models\Department;
use App\Domains\Clinic\Models\Doctor;
use App\Domains\Patients\Models\Patient;
use App\Domains\Pharmacy\Models\Medicine;
use App\Domains\Shared\Models\Company;
use Illuminate\Database\Seeder;
class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::where('code', 'AHMED-CLINIC')->firstOrFail();
        foreach (['General Medicine', 'Pediatrics', 'Cardiology', 'Orthopedics'] as $dept) {
            Department::firstOrCreate(['company_id' => $company->id, 'name' => $dept], ['is_active' => true]);
        }
        $dept = Department::where('company_id', $company->id)->first();
        foreach ([['Dr. Ahmed Khan', 'Cardiology', 1500], ['Dr. Sara Ali', 'Pediatrics', 1200]] as [$name, $spec, $fee]) {
            Doctor::firstOrCreate(['company_id' => $company->id, 'name' => $name], ['department_id' => $dept->id, 'specialization' => $spec, 'consultation_fee' => $fee, 'is_active' => true]);
        }
        foreach ([['Ali Raza', '0300-1234567'], ['Fatima Khan', '0300-7654321']] as [$name, $phone]) {
            Patient::firstOrCreate(['company_id' => $company->id, 'name' => $name], ['patient_code' => 'PAT-' . strtoupper(substr(md5($name), 0, 6)), 'phone' => $phone]);
        }
        foreach ([['Paracetamol 500mg', 'Paracetamol', 'tablet', 1000, 50], ['Amoxicillin 250mg', 'Amoxicillin', 'capsule', 500, 25]] as [$name, $generic, $unit, $stock, $price]) {
            Medicine::firstOrCreate(['company_id' => $company->id, 'name' => $name], ['generic_name' => $generic, 'unit' => $unit, 'stock_quantity' => $stock, 'unit_price' => $price]);
        }
    }
}
