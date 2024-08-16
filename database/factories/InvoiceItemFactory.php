<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InvoiceItem>
 */
class InvoiceItemFactory extends Factory
{
    protected $model = InvoiceItem::class;

    public function definition()
    {
        return [
            'invoice_id' => Invoice::factory(),
            'item_id' => $this->faker->numberBetween(1, 100),
            'quantity' => $this->faker->randomFloat(2, 1, 100),
            'unit_price' => $this->faker->randomFloat(2, 0.1, 1000),
            'total' => $this->faker->randomFloat(2, 0.1, 1000),
        ];
    }
}
