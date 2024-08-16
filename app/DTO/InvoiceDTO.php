<?php
namespace App\DTO;

class InvoiceDTO
{
    public string $invoice_number;
    public string $issue_date;
    public string $client;
    public float  $subtotal;
    public float  $igv;
    public float  $total;
    public array  $items;

    public function __construct(array $data)
    {

        $this->invoice_number = isset($data['invoice_number']) ?? null;
        $this->issue_date = $data['issue_date'];
        $this->client = $data['client'];
        $this->subtotal = (float)$data['subtotal'];
        $this->igv = (float)$data['igv'];
        $this->total = (float)$data['total'];
        $this->items = $data['items'] ?? [];
    }
}
