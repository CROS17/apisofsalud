<?php
namespace App\Services;

use App\DTO\InvoiceDTO;
use App\Models\Invoice;
use App\DTO\InvoiceItemDTO;
use App\Models\InvoiceItem;
use Illuminate\Support\Facades\DB;

class InvoiceService
{
    public function createInvoice(InvoiceDTO $invoiceDTO): Invoice
    {

        return DB::transaction(function () use ($invoiceDTO) {

            $invoice = Invoice::create([
                'invoice_number' => $invoiceDTO->invoice_number,
                'issue_date' => $invoiceDTO->issue_date,
                'client' => $invoiceDTO->client,
                'subtotal' => $invoiceDTO->subtotal,
                'igv' => $invoiceDTO->igv,
                'total' => $invoiceDTO->total,
            ]);

            foreach ($invoiceDTO->items as $itemData) {
                $itemDTO = new InvoiceItemDTO($itemData);

                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'item_id' => $itemDTO->item_id,
                    'quantity' => $itemDTO->quantity,
                    'unit_price' => $itemDTO->unit_price,
                    'total' => $itemDTO->total,
                ]);
            }

            return $invoice;
        });
    }

    public function updateInvoice(Invoice $invoice, InvoiceDTO $invoiceDTO): Invoice
    {
        return DB::transaction(function () use ($invoice, $invoiceDTO) {

            $invoice->update([
                'invoice_number' => $invoiceDTO->invoice_number,
                'issue_date' => $invoiceDTO->issue_date,
                'client' => $invoiceDTO->client,
                'subtotal' => $invoiceDTO->subtotal,
                'igv' => $invoiceDTO->igv,
                'total' => $invoiceDTO->total,
            ]);

            InvoiceItem::where('invoice_id', $invoice->id)->delete();

            foreach ($invoiceDTO->items as $itemData) {
                $itemDTO = new InvoiceItemDTO($itemData);

                InvoiceItem::create([
                    'invoice_id' =>  $invoice->id,
                    'item_id' => $itemDTO->item_id,
                    'quantity' => $itemDTO->quantity,
                    'unit_price' => $itemDTO->unit_price,
                    'total' => $itemDTO->total,
                ]);
            }

            return $invoice;
        });
    }
}
