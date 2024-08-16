<?php

namespace Database\Factories;

use App\Models\Invoice;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    public function definition()
    {
        return [
            'invoice_number' => $this->faker->unique()->word,
            'issue_date' => $this->faker->date(),
            'client' => $this->faker->company,
            'subtotal' => $this->faker->randomFloat(2, 0, 10),
            'igv' => $this->faker->randomFloat(2, 0, 10),
            'total' => $this->faker->randomFloat(2, 0, 10),
        ];
    }
}
