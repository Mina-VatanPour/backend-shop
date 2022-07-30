<?php

namespace App\Models\Enums;


abstract class Enum
{
    /**
     * @return array
     * @throws \ReflectionException
     */
    public function getList()
    {
        $class = new \ReflectionClass($this);
        return $class->getConstants();
    }

    /**
     * @param integer|string $key
     * @return string|null
     * @throws \ReflectionException
     */
    public function getValue($key)
    {
        $list = $this->getList();
        $keys = array_keys($list);
        $key = is_numeric($key) ? (integer)$key : $key;
        $value = null;

        if (is_integer($key) && $key < count($keys)) {
            $value = $list[$keys[$key]];
        } else {
            $value = $list[strtoupper($key)];
        }

        return $value;
    }

    /**
     * @param mixed $value
     * @return integer
     * @throws \ReflectionException
     */
    public function indexOf($value)
    {
        $list = $this->getList();
        $values = array_values($list);
        $index = array_search($value, $values);

        return $index;
    }
}