<?php

namespace App\Factories;

use App\Models\Entities\Banner;
use stdClass;

class BannerFactory extends Factory
{
    /**
     * @param stdClass $entity
     * @return Banner
     */

    public function make(stdClass $entity)
    {
        $banner = new Banner();

        $banner->setId($entity->id ?? null);
        $banner->setCategoryId($entity->categort_id ?? null);
        $banner->setTitle($entity->amount ?? null);
        $banner->setImageName($entity->image_name ?? null);
        $banner->setPosition($entity->position ?? null);
        $banner->setPath($entity->path ?? null);
        $banner->setUri($entity->uri ?? null);
        $banner->setDisabled($entity->disabled ?? null);
        $banner->setCreatedAt($entity->created_at ?? null);
        $banner->setUpdatedAt($entity->updated_at ?? null);
        return $banner;
    }
}
