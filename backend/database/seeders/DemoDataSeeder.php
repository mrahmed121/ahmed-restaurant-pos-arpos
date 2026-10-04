<?php
namespace Database\Seeders;
use App\Domains\Menu\Models\Category;
use App\Domains\Menu\Models\MenuItem;
use App\Domains\Restaurant\Models\Branch;
use App\Domains\Restaurant\Models\DiningTable;
use App\Domains\Shared\Models\Company;
use Illuminate\Database\Seeder;
class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::where('code', 'AHMED-FOODS')->firstOrFail();

        $branch = Branch::firstOrCreate(
            ['company_id' => $company->id, 'code' => 'DHA-01'],
            ['name' => 'DHA Branch', 'address' => 'DHA Phase 5, Karachi', 'phone' => '021-35800001', 'is_active' => true]
        );

        foreach (['T1' => 2, 'T2' => 4, 'T3' => 4, 'T4' => 6, 'T5' => 8] as $code => $cap) {
            DiningTable::firstOrCreate(
                ['branch_id' => $branch->id, 'code' => $code],
                ['company_id' => $company->id, 'name' => "Table $code", 'capacity' => $cap, 'status' => 'available']
            );
        }

        $categories = [
            'Starters' => ['Chicken Wings' => 450, 'French Fries' => 250, 'Spring Rolls' => 350],
            'Burgers' => ['Classic Beef Burger' => 650, 'Chicken Zinger' => 550, 'Fish Fillet' => 700],
            'Pizza' => ['Chicken Tikka Pizza' => 1200, 'Cheese Lovers' => 1100, 'BBQ Chicken' => 1300],
            'Desi' => ['Chicken Biryani' => 350, 'Beef Karahi' => 850, 'Daal Fry' => 300],
            'Beverages' => ['Fresh Lime Soda' => 200, 'Mango Lassi' => 250, 'Cold Coffee' => 300],
            'Desserts' => ['Gulab Jamun' => 200, 'Kheer' => 250, 'Ice Cream' => 300],
        ];

        foreach ($categories as $catName => $items) {
            $cat = Category::firstOrCreate(
                ['company_id' => $company->id, 'name' => $catName],
                ['description' => "$catName menu", 'is_active' => true]
            );
            foreach ($items as $itemName => $price) {
                MenuItem::firstOrCreate(
                    ['company_id' => $company->id, 'category_id' => $cat->id, 'name' => $itemName],
                    ['price' => $price, 'cost' => $price * 0.4, 'is_available' => true, 'preparation_time' => 15]
                );
            }
        }
    }
}
