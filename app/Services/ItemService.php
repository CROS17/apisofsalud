<?php
namespace App\Services;

use App\DTO\ItemDTO;
use App\Models\Item;

class ItemService
{
    public function createItem(ItemDTO $itemDTO): Item
    {
        return Item::create([
            'code' => $itemDTO->code,
            'description' => $itemDTO->description,
            'price' => $itemDTO->price,
        ]);
    }

    public function updateItem(Item $item, ItemDTO $itemDTO): Item
    {
        $item->update([
            'code' => $itemDTO->code,
            'description' => $itemDTO->description,
            'price' => $itemDTO->price,
        ]);

        return $item;
    }
}
