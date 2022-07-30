<?php

namespace App\Factories;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

abstract class Factory
{
    /**
     * @param Collection $entities
     * @return Collection $entityCollection
     */
    public function makeFromCollection(Collection $entities)
    {
        $entityCollection = collect();

        foreach ($entities as $entity) {
            $entityCollection->push($this->make($entity));
        }

        return $entityCollection;
    }

    /**
     * @param Collection $entitiesArray
     * @return Collection $entityCollection
     */
    public function makeFromArray($entitiesArray)
    {
        $entityCollection = collect();
        foreach ($entitiesArray as $entityArray) {
            $entityCollection->push($this->makeWithArray($entityArray));
        }
        return $entityCollection;
    }

    protected function makeEntityFromStdClass(\stdClass $class, $entity)
    {
        //of course $entity should be of type Entity!
        if (method_exists($entity, 'clearVariables')) {
            $entity->clearVariables();
        }

        $elements = get_object_vars($class);
        foreach ($elements as $elementName => $elementValue) {
            $function = camel_case('set_' . snake_case($elementName));
            if (method_exists($entity, $function)) {
                $entity->$function($elementValue);
            }
        }
        return $entity;
    }

    /**
     * @param \stdClass $entity
     * @return mixed
     */
    public abstract function make(\stdClass $entity);

    public function makeWithArray($entity)
    {
        return $this->make((object)$entity);
    }

    public function makeWithRequest(Request $request, $otherFields = [])
    {
        return $this->makeWithArray(array_merge($request->all(), $otherFields));
    }

    protected function decode($string)
    {
        try {
            return iconv('utf-8', null, $string);
        } catch (\Exception $exception) {
            return $string;
        }
    }
}
