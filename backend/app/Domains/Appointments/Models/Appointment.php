<?php
namespace App\Domains\Appointments\Models;
use App\Domains\Shared\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Appointment extends Model
{
    use SoftDeletes, BelongsToCompany;
    public const STATUS_SCHEDULED = 'scheduled';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';
    protected $fillable = ['company_id', 'appointment_number', 'patient_id', 'doctor_id', 'scheduled_at', 'status', 'reason', 'notes', 'fee', 'created_by'];
    protected function casts(): array { return ['scheduled_at' => 'datetime', 'fee' => 'decimal:2']; }
    public function patient() { return $this->belongsTo(\App\Domains\Patients\Models\Patient::class); }
    public function doctor() { return $this->belongsTo(\App\Domains\Clinic\Models\Doctor::class); }
}
