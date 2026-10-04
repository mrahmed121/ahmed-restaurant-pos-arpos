<?php
namespace App\Domains\Pharmacy\Models;
use App\Domains\Shared\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Medicine extends Model
{
    use SoftDeletes, BelongsToCompany;
    protected $fillable = ['company_id', 'name', 'generic_name', 'unit', 'stock_quantity', 'reorder_level', 'unit_price', 'requires_prescription'];
    protected function casts(): array { return ['stock_quantity' => 'decimal:2', 'unit_price' => 'decimal:2', 'requires_prescription' => 'boolean']; }
}
