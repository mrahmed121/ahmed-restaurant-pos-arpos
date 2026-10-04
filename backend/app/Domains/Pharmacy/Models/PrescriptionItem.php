<?php
namespace App\Domains\Pharmacy\Models;
use Illuminate\Database\Eloquent\Model;
class PrescriptionItem extends Model
{
    protected $fillable = ['prescription_id', 'medicine_id', 'dosage', 'quantity', 'instructions'];
    public function medicine() { return $this->belongsTo(Medicine::class); }
}
