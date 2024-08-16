<?php

namespace Tests\Feature;

use Mockery;
use Tests\TestCase;
use App\Models\Item;
use App\Models\Invoice;
use App\Services\InvoiceService;

class InvoiceControllerTest extends TestCase
{
    protected $invoiceService;

    protected function setUp(): void
    {
        parent::setUp();

        // Mocking InvoiceService
        $this->invoiceService = Mockery::mock(InvoiceService::class);
        $this->app->instance(InvoiceService::class, $this->invoiceService);
    }

     /** @test */
     public function it_can_list_invoices()
     {
         // Arrange
         Invoice::factory()->count(10)->create();

         // Act
         $response = $this->getJson('/api/v1/invoice');

         // Assert
         $response->assertStatus(200)
             ->assertJsonStructure([
                 'success',
                 'data',
                 'pagination' => [
                     'total',
                     'per_page',
                     'current_page',
                     'last_page',
                     'from',
                     'to',
                 ]
             ]);
     }



    /** @test */
    public function it_can_store_a_new_invoice()
    {
        // Arrange
        $invoice = Invoice::factory()->make();
        $invoiceItem = \App\Models\InvoiceItem::factory()->make();
        $this->invoiceService->shouldReceive('createInvoice')
            ->once()
            ->andReturn($invoice);

        $data = [
            "invoice_number" => $invoice->invoice_number,
            "issue_date" => $invoice->issue_date->toDateString(),
            "client" => $invoice->client,
            "subtotal" => $invoice->subtotal,
            "igv" => $invoice->igv,
            "total" => $invoice->total,
            "items" => [
                [
                    "item_id" => $invoiceItem->item_id,
                    "quantity" => $invoiceItem->quantity,
                    "unit_price" => $invoiceItem->unit_price,
                    "total" => $invoiceItem->total
                ]
            ]
        ];

        // Act
        $response = $this->postJson('/api/v1/invoice', $data);

        // Assert
        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'data' => $invoice->id
            ]);
    }


    /** @test */
    public function it_can_update_an_invoice()
    {
        // Arrange
        $invoice = Invoice::factory()->create();
        $data = [
            "invoice_number" => "INV001",
            "issue_date" => "2023-08-15",
            "client" => "Client001 Updated",
            "subtotal" => 200.0,
            "igv" => 36.0,
            "total" => 236.0,
            "items" => [
                [
                    "item_id" => 1, // Asegúrate de que este item exista en la base de datos
                    "quantity" => 4,
                    "unit_price" => 50.0,
                    "total" => 200.0,
                ]
            ]
        ];

        $this->invoiceService->shouldReceive('updateInvoice')
            ->once()
            ->andReturn($invoice);

        // Act
        $response = $this->patchJson("/api/v1/invoice/{$invoice->id}", $data);

        // Assert
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => $invoice->id
            ]);
    }


    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function it_can_delete_an_invoice()
    {
        // Arrange
        $invoice = Invoice::factory()->create();

        // Act
        $response = $this->deleteJson("/api/v1/invoice/{$invoice->id}");

        // Assert
        $response->assertStatus(204);
    }
}
