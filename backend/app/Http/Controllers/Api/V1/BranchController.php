<?php
namespace App\Http\Controllers\Api\V1;
use App\Domains\Restaurant\Models\Branch;
use App\Domains\Shared\Services\AuditService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
class BranchController extends Controller
{
    public function __construct(private AuditService $audit) {}
    public function index(Request $request): JsonResponse
    {
        $branches = Branch::withCount('tables')->orderBy('name')->paginate(20);
        return response()->json(['data' => $branches->items(), 'meta' => [
            'current_page' => $branches->currentPage(), 'per_page' => $branches->perPage(), 'total' => $branches->total(),
        ]]);
    }
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255', 'code' => 'required|string|max:50',
            'address' => 'nullable|string', 'phone' => 'nullable|string|max:50',
        ]);
        $data['company_id'] = $request->user()->company_id;
        if (Branch::where('company_id', $data['company_id'])->where('code', $data['code'])->exists()) {
            return response()->json(['message' => 'Validation failed.', 'errors' => ['code' => ['Code already used.']]], 422);
        }
        $branch = Branch::create($data);
        $this->audit->log('branches.create', $branch, ['name' => $branch->name]);
        return response()->json(['data' => $branch], 201);
    }
}
