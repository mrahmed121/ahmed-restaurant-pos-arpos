<?php
namespace App\Domains\Patients\Models;
use App\Domains\Shared\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Patient extends Model
{
    use SoftDeletes, BelongsToCompany;
    protected $fillable = ['company_id', 'patient_code', 'name', 'phone', 'email', 'date_of_birth', 'gender', 'blood_group', 'address', 'medical_history'];
    protected function casts(): array { return ['date_of_birth' => 'date']; }
    public function appointments() { return $this->hasMany(\App\Domains\Appointments\Models\Appointment::class); }
}
