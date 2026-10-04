<?php

namespace App\Domains\Restaurant\Models;

use App\Domains\Shared\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DiningTable extends Model
{
    use HasFactory, SoftDeletes, BelongsToCompany;

    public const STATUS_AVAILABLE = 'available';
    public const STATUS_OCCUPIED = 'occupied';
    public const STATUS_RESERVED = 'reserved';

    protected $fillable = [
        'company_id', 'branch_id', 'name', 'code', 'capacity', 'status',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
