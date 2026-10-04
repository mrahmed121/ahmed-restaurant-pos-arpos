<?php
namespace App\Http\Controllers\Api\V1;
use App\Domains\Orders\Models\Order;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class ReportController extends Controller
{
    public function sales(Request $request): JsonResponse
    {
        $from = $request->get('from', now()->subDays(30)->toDateString());
        $to = $request->get('to', now()->toDateString());
        $base = Order::where('company_id', $request->user()->company_id)
            ->whereNotIn('status', ['cancelled'])
            ->whereBetween(DB::raw('date(created_at)'), [$from, $to]);
        if ($request->filled('branch_id')) $base->where('branch_id', $request->branch_id);
        $byDay = (clone $base)->select(DB::raw('date(created_at) as day'), DB::raw('sum(total) as revenue'), DB::raw('count(*) as orders'))
            ->groupBy('day')->orderBy('day')->get();
        $byType = (clone $base)->select('type', DB::raw('sum(total) as revenue'), DB::raw('count(*) as orders'))
            ->groupBy('type')->get();
        $topItems = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.company_id', $request->user()->company_id)
            ->whereNotIn('orders.status', ['cancelled'])
            ->whereBetween(DB::raw('date(orders.created_at)'), [$from, $to])
            ->select('order_items.name', DB::raw('sum(order_items.quantity) as qty'), DB::raw('sum(order_items.quantity * order_items.unit_price) as revenue'))
            ->groupBy('order_items.name')->orderByDesc('revenue')->limit(10)->get();
        return response()->json(['data' => [
            'period' => ['from' => $from, 'to' => $to],
            'summary' => [
                'revenue' => (clone $base)->sum('total'),
                'orders' => (clone $base)->count(),
                'avg_order' => round((clone $base)->avg('total') ?? 0, 2),
            ],
            'by_day' => $byDay, 'by_type' => $byType, 'top_items' => $topItems,
        ]]);
    }
}
