<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Services\ItemService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Mockery;

class ItemControllerTest extends TestCase
{
    use RefreshDatabase;

    private $itemService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->itemService = Mockery::mock(ItemService::class);
        $this->app->instance(ItemService::class, $this->itemService);
    }

    /** @test */
    public function it_can_list_items()
    {
        // Arrange
        Item::factory()->count(10)->create();

        // Act
        $response = $this->getJson('/api/v1/items');

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
    public function it_can_show_an_item()
    {
        // Arrange
        $item = Item::factory()->create();

        // Act
        $response = $this->getJson("/api/v1/items/{$item->id}");

        // Assert
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $item->id,
                    "code" => $item->code,
                    "description" => $item->description,
                    "price" => $item->price
                ]
            ]);
    }

    /** @test */
    public function it_can_store_a_new_item()
    {
        // Arrange
        $item = Item::factory()->make();
        $this->itemService->shouldReceive('createItem')
            ->once()
            ->andReturn($item);

        // Arrange
        $data = [
            "code" => $item->code,
            "description" => $item->description,
            "price" => $item->price
        ];

        // Act
        $response = $this->postJson('/api/v1/items', $data);

        // Assert
        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'data' => $item->id
            ]);
    }

    /** @test */
    public function it_can_update_an_item()
    {
        // Arrange
        $item = Item::factory()->create();
        $updatedItem = clone $item;
        $updatedItem->description = "ITEM001 EDIT";

        $this->itemService->shouldReceive('updateItem')
            ->once()
            ->andReturn($updatedItem);

        $data = [
            "code" => $updatedItem->code,
            "description" => $updatedItem->description,
            "price" => $updatedItem->price
        ];

        // Act
        $response = $this->patchJson("/api/v1/items/{$item->id}", $data);

        // Assert
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => $updatedItem->id
            ]);
    }

    /** @test */
    public function it_can_delete_an_item()
    {
        // Arrange
        $item = Item::factory()->create();

        // Act
        $response = $this->deleteJson("/api/v1/items/{$item->id}");

        // Assert
        $response->assertStatus(204);
    }
}
