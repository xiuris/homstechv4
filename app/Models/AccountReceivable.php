<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class AccountReceivable extends Model
{
    /** @use HasFactory<\Database\Factories\AccountReceivableFactory> */
    use HasFactory;

    protected $table = 'accounts_receivable';

    protected $fillable = [
        'company_id',
        'customer_id',
        'sale_id',
        'order_service_id',
        'amount',
        'installment_number',
        'installments_total',
        'due_date',
        'status',
        'notification_channel',
        'last_notified_at',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'due_date' => 'date',
        'last_notified_at' => 'datetime',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    public function orderService(): BelongsTo
    {
        return $this->belongsTo(OrderService::class);
    }

    public function payments(): MorphMany
    {
        return $this->morphMany(Payment::class, 'payable');
    }
}
