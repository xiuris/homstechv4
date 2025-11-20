<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FiscalDocument extends Model
{
    /** @use HasFactory<\Database\Factories\FiscalDocumentFactory> */
    use HasFactory;

    protected $fillable = [
        'company_id',
        'customer_id',
        'sale_id',
        'order_service_id',
        'document_type',
        'uf',
        'environment',
        'total',
        'status',
        'protocol',
        'message',
        'xml_path',
        'pdf_path',
        'attempts',
        'scheduled_at',
        'last_emitted_at',
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'scheduled_at' => 'datetime',
        'last_emitted_at' => 'datetime',
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

    public function logs(): HasMany
    {
        return $this->hasMany(FiscalDocumentLog::class);
    }
}
