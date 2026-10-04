<?php
namespace App\Http\Controllers\Api\V1;
use App\Domains\Appointments\Models\Appointment;
use App\Domains\Clinic\Models\Doctor;
use App\Domains\Patients\Models\Patient;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
class DashboardController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $cid = $request->user()->company_id;
        return response()->json(['data' => [
            'today_appointments' => Appointment::where('company_id', $cid)->whereDate('scheduled_at', today())->whereNotIn('status', ['cancelled'])->count(),
            'total_patients' => Patient::where('company_id', $cid)->count(),
            'total_doctors' => Doctor::where('company_id', $cid)->where('is_active', true)->count(),
            'pending_appointments' => Appointment::where('company_id', $cid)->whereIn('status', ['scheduled', 'confirmed'])->where('scheduled_at', '>=', now())->count(),
        ]]);
    }
}
