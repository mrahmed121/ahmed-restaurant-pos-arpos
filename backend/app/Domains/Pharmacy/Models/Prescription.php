<?php
namespace App\Domains\Pharmacy\Models;
use App\Domains\Shared\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;
class Prescription extends Model
{
    use BelongsToCompany;
    protected $fillable = ['company_id', 'appointment_id', 'patient_id', 'doctor_id', 'diagnosis', 'notes'];
    public function items() { return $this->hasMany(PrescriptionItem::class); }
}
