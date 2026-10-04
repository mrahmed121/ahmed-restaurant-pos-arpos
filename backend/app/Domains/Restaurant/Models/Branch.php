<?php

namespace App\Domains\Restaurant\Models;

use App\Domains\Shared\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
    use HasFactory, SoftDeletes, BelongsToCompany;

    protected $fillable = [
        'company_id', 'name', 'code', 'address', 'phone', 'email',
        'is_active', 'opens_at', 'closes_at',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function tables()
    {
        return $this->hasMany(DiningTable::class);
    }
}
