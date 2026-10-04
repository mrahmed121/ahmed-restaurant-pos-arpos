<?php
namespace App\Http\Controllers\Api\V1;
use App\Domains\Menu\Models\Category;
use App\Domains\Menu\Models\MenuItem;
use App\Domains\Shared\Services\AuditService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
class MenuController extends Controller
{
    public function __construct(private AuditService $audit) {}
    public function categories(Request $request): JsonResponse
    {
        $cats = Category::withCount('items')->orderBy('sort_order')->orderBy('name')->get();
        return response()->json(['data' => $cats]);
    }
    public function items(Request $request): JsonResponse
    {
        $query = MenuItem::with('category')->orderBy('name');
        if ($request->filled('category_id')) $query->where('category_id', $request->category_id);
        if ($request->filled('search')) $query->where('name', 'like', '%'.$request->search.'%');
        if ($request->filled('available')) $query->where('is_available', $request->boolean('available'));
        $items = $query->paginate($request->get('per_page', 20));
        return response()->json(['data' => $items->items(), 'meta' => [
            'current_page' => $items->currentPage(), 'per_page' => $items->perPage(), 'total' => $items->total(),
        ]]);
    }
    public function storeItem(Request $request): JsonResponse
    {
        $data = $request->validate([
            'category_id' => 'required|integer|exists:categories,id',
            'name' => 'required|string|max:255', 'description' => 'nullable|string',
            'price' => 'required|numeric|min:0', 'cost' => 'nullable|numeric|min:0',
        ]);
        $data['company_id'] = $request->user()->company_id;
        $item = MenuItem::create($data);
        $this->audit->log('menu.create', $item, ['name' => $item->name]);
        return response()->json(['data' => $item], 201);
    }
}
