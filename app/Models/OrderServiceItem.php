<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderServiceItem extends Model
{
    /** @use HasFactory<\Database\Factories\OrderServiceItemFactory> */
    use HasFactory;

    protected $fillable = [
        'order_service_id',
        'item_type',
        'product_id',
        'service_id',
        'quantity',
        'unit_price',
        'discount',
        'total',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function orderService(): BelongsTo
    {
        return $this->belongsTo(OrderService::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
