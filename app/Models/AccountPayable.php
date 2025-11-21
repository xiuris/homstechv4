<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class AccountPayable extends Model
{
    /** @use HasFactory<\Database\Factories\AccountPayableFactory> */
    use HasFactory;

    protected $table = 'accounts_payable';

    protected $fillable = [
        'company_id',
        'reseller_id',
        'vendor_name',
        'category',
        'is_recurring',
        'recurrence_interval',
        'recurrence_count',
        'amount',
        'due_date',
        'status',
        'attachment_path',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'due_date' => 'date',
        'is_recurring' => 'bool',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function reseller(): BelongsTo
    {
        return $this->belongsTo(Reseller::class);
    }

    public function payments(): MorphMany
    {
        return $this->morphMany(Payment::class, 'payable');
    }
}
