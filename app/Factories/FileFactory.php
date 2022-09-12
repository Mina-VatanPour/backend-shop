<?php

namespace App\Factories;

use App\Models\Entities\File;
use stdClass;

class FileFactory extends Factory
{
    /**
     * @param stdClass $entity
     * @return File
     */

    public function make(stdClass $entity)
    {
        $file = new File();

        $file->setId($entity->id ?? null);
        $file->setProductId($entity->product_id ?? null);
        $file->setBannerId($entity->banner_id ?? null);
        $file->setCategoryId($entity->category_id ?? null);
        $file->setName($entity->name ?? null);
        $file->setPath($entity->path ?? null);
        $file->setPriority($entity->priority ?? null);
        $file->setCreatedAt($entity->created_at ?? null);
        $file->setUpdatedAt($entity->updated_at ?? null);
        $file->setDeletedAt($entity->deleted_at ?? null);
        return $file;
    }
}
