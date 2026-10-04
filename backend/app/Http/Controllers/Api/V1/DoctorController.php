<?php
namespace App\Http\Controllers\Api\V1;
use App\Domains\Clinic\Models\Doctor;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
class DoctorController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $doctors = Doctor::with('department')->where('is_active', true)->orderBy('name')->get();
        return response()->json(['data' => $doctors]);
    }
}
