<?php

namespace App\Factories;

use App\Models\Entities\Discount;
use stdClass;

class DiscountFactory extends Factory implements IFactory
{
    /**
     * @param stdClass $entity
     * @return Discount
     */
    public function make(\stdClass $entity)
    {
        $discount = new Discount();

        $discount->setId($entity->id ?? null);
        $discount->setPropertiesId($entity->properties_id ?? null);
        $discount->setCampaignId($entity->campaign_id ?? null);
        $discount->setCitiesId($entity->cities_id ?? null);
        $discount->setName($entity->name ?? null);
        $discount->setDiscountCode($entity->discount_code ?? null);
        $discount->setAmountType($entity->amount_type ?? null);
        $discount->setHasChildCode($entity->has_child_code ?? null);
        $discount->setAmount($entity->amount ?? null);
        $discount->setPurchaseCount($entity->purchase_count ?? null);
        $discount->setCount($entity->count ?? null);
        $discount->setType($entity->type ?? null);
        $discount->setGenerateCount($entity->generate_count ?? null);
        $discount->setUsableDuration($entity->usable_duration ?? null);
        $discount->setReserveMinPrice($entity->reserve_min_price ?? null);
        $discount->setReserveMaxPrice($entity->reserve_max_price ?? null);
        $discount->setReserveMinNight($entity->reserve_min_night ?? null);
        $discount->setReserveMaxNight($entity->reserve_max_night ?? null);
        $discount->setReserveStartAt($entity->reserve_start_at ?? null);
        $discount->setReserveEndAt($entity->reserve_end_at ?? null);
        $discount->setBackup($entity->backup ?? null);
        $discount->setStartAt($entity->start_at ?? null);
        $discount->setEndAt($entity->end_at ?? null);
        $discount->setDisabled($entity->disabled ?? null);
        $discount->setCreatedAt($entity->created_at ?? null);
        $discount->setUpdatedAt($entity->updated_at ?? null);
        $discount->setDeletedAt($entity->deleted_at ?? null);
        return $discount;
    }
}
