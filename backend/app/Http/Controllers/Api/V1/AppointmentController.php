<?php
namespace App\Http\Controllers\Api\V1;
use App\Domains\Appointments\Models\Appointment;
use App\Domains\Shared\Services\AuditService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
class AppointmentController extends Controller
{
    public function __construct(private AuditService $audit) {}
    public function index(Request $request): JsonResponse
    {
        $q = Appointment::with(['patient', 'doctor'])->orderBy('scheduled_at');
        if ($request->filled('status')) $q->where('status', $request->status);
        if ($request->filled('doctor_id')) $q->where('doctor_id', $request->doctor_id);
        if ($request->filled('date')) $q->whereDate('scheduled_at', $request->date);
        $p = $q->paginate($request->get('per_page', 20));
        return response()->json(['data' => $p->items(), 'meta' => ['current_page' => $p->currentPage(), 'per_page' => $p->perPage(), 'total' => $p->total()]]);
    }
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate(['patient_id' => 'required|integer|exists:patients,id', 'doctor_id' => 'required|integer|exists:doctors,id', 'scheduled_at' => 'required|date|after:now', 'reason' => 'nullable|string', 'notes' => 'nullable|string']);
        // Conflict detection: same doctor, overlapping time (30-min slots)
        $conflict = Appointment::where('doctor_id', $data['doctor_id'])
            ->whereNotIn('status', ['cancelled'])
            ->whereBetween('scheduled_at', [date('Y-m-d H:i:s', strtotime($data['scheduled_at']) - 1800), date('Y-m-d H:i:s', strtotime($data['scheduled_at']) + 1800)])
            ->exists();
        if ($conflict) return response()->json(['message' => 'Doctor is not available at this time.'], 422);
        $data['company_id'] = $request->user()->company_id;
        $data['appointment_number'] = 'APT-' . date('Ymd') . '-' . str_pad((string)(Appointment::whereDate('created_at', today())->count() + 1), 4, '0', STR_PAD_LEFT);
        $data['status'] = 'scheduled';
        $data['created_by'] = $request->user()->id;
        $appt = Appointment::create($data);
        $this->audit->log('appointments.create', $appt, ['number' => $appt->appointment_number]);
        $appt->load(['patient', 'doctor']);
        return response()->json(['data' => $appt], 201);
    }
    public function updateStatus(Request $request, Appointment $appointment): JsonResponse
    {
        $data = $request->validate(['status' => 'required|in:scheduled,confirmed,completed,cancelled']);
        $appointment->update(['status' => $data['status']]);
        $this->audit->log('appointments.status', $appointment, ['status' => $data['status']]);
        return response()->json(['data' => $appointment]);
    }
}
