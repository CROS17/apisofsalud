<?php
namespace App\DTO;

class ItemDTO
{
    public string $code;
    public string $description;
    public string $price;

    public function __construct(array $data)
    {
        $this->code = $data['code'];
        $this->description = $data['description'];
        $this->price = $data['price'];
    }
}
