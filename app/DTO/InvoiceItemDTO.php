<?php
namespace App\DTO;

class InvoiceItemDTO
{
    //public int $invoice_id;
    public int $item_id;
    public float $quantity;
    public float $unit_price;
    public float $total;

    public function __construct(array $data)
    {
      //  $this->invoice_id = $data['invoice_id'];
        $this->item_id = $data['item_id'];
        $this->quantity = (float)$data['quantity'];
        $this->unit_price = (float)$data['unit_price'];
        $this->total = (float)$data['total'];
    }
}
