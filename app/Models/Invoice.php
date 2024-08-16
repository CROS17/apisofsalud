<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    use HasFactory;

    protected $table = 'invoices';

    protected $fillable = [
        'invoice_number',
        'issue_date',
        'client',
        'subtotal',
        'igv',
        'total'
    ];

    protected $guarded = [
        'invoice_number',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($invoice) {
            if (empty($invoice->invoice_number)) {
                $invoice->invoice_number = self::generateUniqueInvoiceNumber();
            }
        });

        static::updating(function ($invoice) {
            if ($invoice->isDirty('invoice_number')) {
                $invoice->attributes['invoice_number'] = $invoice->getOriginal('invoice_number');
            }
        });
    }

    public static function generateUniqueInvoiceNumber()
    {
        $number = 'INV-' . Str::upper(Str::random(8));
        while (self::where('invoice_number', $number)->exists()) {
            $number = 'INV-' . Str::upper(Str::random(8));
        }
        return $number;
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class, 'invoice_id');
    }

}
