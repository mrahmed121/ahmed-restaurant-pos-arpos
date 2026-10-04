<?php
namespace App\Http\Controllers\Api\V1;
use App\Domains\Pharmacy\Models\Medicine;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
class PharmacyController extends Controller
{
    public function medicines(Request $request): JsonResponse
    {
        $q = Medicine::orderBy('name');
        if ($request->filled('search')) $q->where('name', 'like', '%'.$request->search.'%');
        if ($request->boolean('low_stock')) $q->whereColumn('stock_quantity', '<=', 'reorder_level');
        $p = $q->paginate($request->get('per_page', 20));
        return response()->json(['data' => $p->items(), 'meta' => ['current_page' => $p->currentPage(), 'per_page' => $p->perPage(), 'total' => $p->total()]]);
    }
}
