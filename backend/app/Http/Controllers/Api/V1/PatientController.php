<?php
namespace App\Http\Controllers\Api\V1;
use App\Domains\Patients\Models\Patient;
use App\Domains\Shared\Services\AuditService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
class PatientController extends Controller
{
    public function __construct(private AuditService $audit) {}
    public function index(Request $request): JsonResponse
    {
        $q = Patient::orderBy('name');
        if ($request->filled('search')) $q->where('name', 'like', '%'.$request->search.'%');
        $p = $q->paginate($request->get('per_page', 20));
        return response()->json(['data' => $p->items(), 'meta' => ['current_page' => $p->currentPage(), 'per_page' => $p->perPage(), 'total' => $p->total()]]);
    }
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate(['name' => 'required|string|max:255', 'phone' => 'nullable|string|max:50', 'email' => 'nullable|email', 'date_of_birth' => 'nullable|date', 'gender' => 'nullable|in:male,female,other', 'blood_group' => 'nullable|string|max:10', 'address' => 'nullable|string', 'medical_history' => 'nullable|string']);
        $data['company_id'] = $request->user()->company_id;
        $data['patient_code'] = 'PAT-' . date('Ymd') . '-' . str_pad((string)(Patient::where('company_id', $data['company_id'])->count() + 1), 4, '0', STR_PAD_LEFT);
        $patient = Patient::create($data);
        $this->audit->log('patients.create', $patient, ['name' => $patient->name]);
        return response()->json(['data' => $patient], 201);
    }
    public function show(Request $request, Patient $patient): JsonResponse
    {
        $patient->load(['appointments' => fn($q) => $q->orderByDesc('scheduled_at')->limit(10)]);
        return response()->json(['data' => $patient]);
    }
}
