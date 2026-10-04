<?php
namespace App\Domains\Clinic\Models;
use App\Domains\Shared\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Department extends Model
{
    use SoftDeletes, BelongsToCompany;
    protected $fillable = ['company_id', 'name', 'description', 'is_active'];
    protected function casts(): array { return ['is_active' => 'boolean']; }
}
