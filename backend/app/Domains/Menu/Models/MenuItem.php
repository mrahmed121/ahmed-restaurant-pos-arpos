<?php

namespace App\Domains\Menu\Models;

use App\Domains\Shared\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MenuItem extends Model
{
    use HasFactory, SoftDeletes, BelongsToCompany;

    protected $fillable = [
        'company_id', 'category_id', 'name', 'description', 'price',
        'cost', 'sku', 'is_available', 'is_featured', 'sort_order',
        'preparation_time', 'image_path',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'cost' => 'decimal:2',
            'is_available' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function modifiers()
    {
        return $this->belongsToMany(Modifier::class, 'menu_item_modifier');
    }

    public function recipeItems()
    {
        return $this->hasMany(\App\Domains\Inventory\Models\RecipeItem::class);
    }
}
