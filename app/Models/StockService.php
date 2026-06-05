<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockService extends Model
{
    protected $fillable = [
        'service_id',
        'user_id',
        'quantity',
        'type',
        'stock_before',
        'stock_after',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
