<?php
namespace App\Http\Controllers\Api\V1;
use App\Domains\Menu\Models\MenuItem;
use App\Domains\Orders\Models\Order;
use App\Domains\Orders\Models\OrderItem;
use App\Domains\Orders\Models\Payment;
use App\Domains\Shared\Services\AuditService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class OrderController extends Controller
{
    public function __construct(private AuditService $audit) {}
    public function index(Request $request): JsonResponse
    {
        $query = Order::with(['items', 'table'])->orderByDesc('created_at');
        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('branch_id')) $query->where('branch_id', $request->branch_id);
        if ($request->filled('type')) $query->where('type', $request->type);
        $orders = $query->paginate($request->get('per_page', 20));
        return response()->json(['data' => $orders->items(), 'meta' => [
            'current_page' => $orders->currentPage(), 'per_page' => $orders->perPage(), 'total' => $orders->total(),
        ]]);
    }
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'branch_id' => 'required|integer|exists:branches,id',
            'type' => 'required|in:dine_in,takeaway,delivery',
            'table_id' => 'nullable|integer|exists:dining_tables,id',
            'customer_name' => 'nullable|string|max:255',
            'customer_phone' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.menu_item_id' => 'required|integer|exists:menu_items,id',
            'items.*.quantity' => 'required|integer|min:1|max:100',
            'items.*.notes' => 'nullable|string',
        ]);
        $actor = $request->user();
        return DB::transaction(function () use ($data, $actor) {
            $subtotal = 0;
            $orderItems = [];
            foreach ($data['items'] as $itemData) {
                $menuItem = MenuItem::findOrFail($itemData['menu_item_id']);
                if (!$menuItem->is_available) {
                    return response()->json(['message' => 'Validation failed.', 'errors' => ['items' => ["{$menuItem->name} is not available."]]], 422);
                }
                $lineTotal = $menuItem->price * $itemData['quantity'];
                $subtotal += $lineTotal;
                $orderItems[] = [
                    'menu_item_id' => $menuItem->id, 'name' => $menuItem->name,
                    'quantity' => $itemData['quantity'], 'unit_price' => $menuItem->price,
                    'notes' => $itemData['notes'] ?? null, 'kot_status' => 'pending',
                ];
            }
            $orderNumber = 'ORD-' . date('Ymd') . '-' . str_pad((string)(Order::whereDate('created_at', today())->count() + 1), 4, '0', STR_PAD_LEFT);
            $order = Order::create([
                'company_id' => $actor->company_id, 'branch_id' => $data['branch_id'],
                'order_number' => $orderNumber, 'type' => $data['type'], 'status' => Order::STATUS_PENDING,
                'table_id' => $data['table_id'] ?? null, 'customer_name' => $data['customer_name'] ?? null,
                'customer_phone' => $data['customer_phone'] ?? null, 'subtotal' => $subtotal,
                'total' => $subtotal, 'notes' => $data['notes'] ?? null, 'created_by' => $actor->id,
            ]);
            foreach ($orderItems as $oi) { $order->items()->create($oi); }
            $this->audit->log('orders.create', $order, ['order_number' => $orderNumber, 'total' => $subtotal]);
            $order->load(['items', 'table']);
            return response()->json(['data' => $order], 201);
        });
    }
    public function show(Request $request, Order $order): JsonResponse
    {
        $order->load(['items', 'payments', 'table']);
        return response()->json(['data' => $order]);
    }
    public function updateStatus(Request $request, Order $order): JsonResponse
    {
        $data = $request->validate(['status' => 'required|in:pending,confirmed,preparing,ready,served,completed,cancelled']);
        $order->update(['status' => $data['status']]);
        if ($data['status'] === 'completed') $order->update(['completed_at' => now()]);
        $this->audit->log('orders.status', $order, ['status' => $data['status']]);
        return response()->json(['data' => $order]);
    }
    public function addPayment(Request $request, Order $order): JsonResponse
    {
        $data = $request->validate([
            'amount' => 'required|numeric|min:0.01', 'method' => 'required|in:cash,card,mobile',
            'reference' => 'nullable|string|max:255',
        ]);
        $paid = $order->payments()->sum('amount');
        if ($paid + $data['amount'] > $order->total + 0.01) {
            return response()->json(['message' => 'Payment exceeds order total.'], 422);
        }
        $payment = $order->payments()->create([
            'company_id' => $request->user()->company_id, 'amount' => $data['amount'],
            'method' => $data['method'], 'reference' => $data['reference'] ?? null,
            'received_by' => $request->user()->id,
        ]);
        $this->audit->log('payments.create', $payment, ['order' => $order->order_number, 'amount' => $data['amount']]);
        return response()->json(['data' => $payment], 201);
    }
}
