<?php

namespace App\Factories;

use App\Models\Entities\Product;
use stdClass;

class ProductFactory extends Factory
{
    /**
     * @param stdClass $entity
     * @return Product
     */

    public function make(stdClass $entity)
    {
        $product = new Product();

        $product->setId($entity->id ?? null);
        $product->setCategoryId($entity->categort_id ?? null);
        $product->setAmount($entity->amount ?? null);
        $product->setTitle($entity->title ?? null);
        $product->setDescription($entity->description ?? null);
        $product->setInventory($entity->inventory ?? null);
        $product->setDiscount($entity->discount ?? null);
        $product->setDisabled($entity->disabled ?? null);
        $product->setCreatedAt($entity->created_at ?? null);
        $product->setUpdatedAt($entity->updated_at ?? null);
        return $product;
    }
}
