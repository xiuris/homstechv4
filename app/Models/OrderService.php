<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class OrderService extends Model
{
    /** @use HasFactory<\Database\Factories\OrderServiceFactory> */
    use HasFactory;

    protected $fillable = [
        'company_id',
        'customer_id',
        'reseller_id',
        'assigned_user_id',
        'title',
        'description',
        'status',
        'priority',
        'total_value',
        'opened_at',
        'closed_at',
        'invoiced_at',
    ];

    protected $casts = [
        'total_value' => 'decimal:2',
        'opened_at' => 'datetime',
        'closed_at' => 'datetime',
        'invoiced_at' => 'datetime',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function reseller(): BelongsTo
    {
        return $this->belongsTo(Reseller::class);
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    public function accountsReceivable(): HasMany
    {
        return $this->hasMany(AccountReceivable::class);
    }

    public function warranties(): HasMany
    {
        return $this->hasMany(Warranty::class);
    }

    public function payments(): MorphMany
    {
        return $this->morphMany(Payment::class, 'payable');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderServiceItem::class);
    }

    public function sale(): HasOne
    {
        return $this->hasOne(Sale::class);
    }
}
