<?php
namespace App\Http\Controllers\Api\V1;
use App\Domains\Orders\Models\OrderItem;
use App\Domains\Shared\Services\AuditService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
class KitchenController extends Controller
{
    public function __construct(private AuditService $audit) {}
    public function tickets(Request $request): JsonResponse
    {
        $query = OrderItem::with(['order' => fn($q) => $q->select('id','order_number','type','status','table_id')])
            ->whereHas('order', fn($q) => $q->whereIn('status', ['pending','confirmed','preparing']))
            ->orderBy('created_at');
        if ($request->filled('kot_status')) $query->where('kot_status', $request->kot_status);
        $tickets = $query->paginate($request->get('per_page', 50));
        return response()->json(['data' => $tickets->items(), 'meta' => [
            'current_page' => $tickets->currentPage(), 'per_page' => $tickets->perPage(), 'total' => $tickets->total(),
        ]]);
    }
    public function updateTicket(Request $request, OrderItem $item): JsonResponse
    {
        $data = $request->validate(['kot_status' => 'required|in:pending,preparing,ready,served']);
        $item->update(['kot_status' => $data['kot_status']]);
        $this->audit->log('kitchen.ticket', $item, ['kot_status' => $data['kot_status']]);
        return response()->json(['data' => $item]);
    }
}
