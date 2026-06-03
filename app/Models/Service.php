<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'category_id',
        'name_service',
        'description_service',
        'image_service',
        'stock_service',
        'price_service'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function stockServices()
    {
        return $this->hasMany(StockService::class);
    }
}
