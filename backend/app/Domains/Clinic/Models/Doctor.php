<?php
namespace App\Domains\Clinic\Models;
use App\Domains\Shared\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Doctor extends Model
{
    use SoftDeletes, BelongsToCompany;
    protected $fillable = ['company_id', 'department_id', 'user_id', 'name', 'specialization', 'phone', 'email', 'consultation_fee', 'is_active'];
    protected function casts(): array { return ['consultation_fee' => 'decimal:2', 'is_active' => 'boolean']; }
    public function department() { return $this->belongsTo(Department::class); }
}
