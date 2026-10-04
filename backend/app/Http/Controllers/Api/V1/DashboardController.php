<?php
namespace App\Http\Controllers\Api\V1;
use App\Domains\Menu\Models\MenuItem;
use App\Domains\Orders\Models\Order;
use App\Domains\Restaurant\Models\Branch;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
class DashboardController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $cid = $request->user()->company_id;
        $todayRevenue = Order::where('company_id', $cid)->whereNotIn('status', ['cancelled'])
            ->whereDate('created_at', today())->sum('total');
        return response()->json(['data' => [
            'today_revenue' => round($todayRevenue, 2),
            'today_orders' => Order::where('company_id', $cid)->whereDate('created_at', today())->count(),
            'active_orders' => Order::where('company_id', $cid)->whereIn('status', ['pending','confirmed','preparing','ready'])->count(),
            'branches' => Branch::where('company_id', $cid)->count(),
            'menu_items' => MenuItem::where('company_id', $cid)->count(),
        ]]);
    }
}
