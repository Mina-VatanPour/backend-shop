<?php

namespace App\Factories;

use App\Models\Entities\Banner;
use App\Models\Entities\Category;
use stdClass;

class CategoryFactory extends Factory
{
    /**
     * @param stdClass $entity
     * @return Category
     */

    public function make(stdClass $entity)
    {
        $category = new Category();

        $category->setId($entity->id ?? null);
        $category->setParentId($entity->parent_id ?? null);
        $category->setTitle($entity->title ?? null);
        $category->setChosen($entity->chosen ?? null);
        $category->setCreatedAt($entity->created_at ?? null);
        $category->setUpdatedAt($entity->updated_at ?? null);
        $category->setDeletedAt($entity->updated_at ?? null);
        return $category;
    }
}
