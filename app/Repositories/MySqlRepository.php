<?php

namespace App\Repositories;

use App\Models\Entities\Entity;
use App\Models\General\Polygon;
use App\Models\Griew\FilterOperator;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Str;

abstract class  MySqlRepository
{
    protected $primaryKey = 'id';
    /**
     * @var Builder $alternativeDbConnection
     */
    protected $table = '';
    protected $softDelete = false;


    /**
     * @return Builder
     */
    public function newQuery()
    {
        return app('db')->table($this->table);
    }

    protected function processOrder($query, $orders, $columnsMapper = [])
    {
        foreach ($orders as $order) {
            $name = $order->name;
            if (isset($columnsMapper[$order->name])) {
                $name = $columnsMapper[$order->name];
            }
            $query->orderBy($name, $order->type);
        }
        return $query;
    }

    /**
     * @param Builder $query
     * @param array $filters
     * @return Builder
     */
    protected function processFilter($query, $filters, $columnsMapper = [])
    {
        foreach ($filters as $filter) {
            $name = $filter->name;
            if (isset($columnsMapper[$filter->name])) {
                $name = $columnsMapper[$filter->name];
            }

            switch (strtolower(Str::snake($filter->operator))) {
                case FilterOperator::IS_NULL:
                    $query->whereNull($name);
                    break;
                case FilterOperator::IS_NOT_NULL:
                    $query->whereNotNull($name);
                    break;
                case FilterOperator::IS_EQUAL_TO:
                    if (is_string($filter->operand1) && str_contains($filter->operand1, '|')) {
                        // create in functionality with equal string
                        $arr = array_filter(explode('|', $filter->operand1));
                        $query->whereIn($name, $arr);
                    } else {
                        $query->where($name, '=', $filter->operand1);
                    }
                    break;
                case FilterOperator::IS_NOT_EQUAL_TO:
                    if (is_string($filter->operand1) && str_contains($filter->operand1, '|')) {
                        // create in functionality with equal string
                        $arr = array_filter(explode('|', $filter->operand1));
                        $query->whereNotIn($name, $arr);
                    } else {
                        $query->where($name, '<>', $filter->operand1);
                    }
                    break;
                case FilterOperator::START_WITH:
                    $query->where($name, 'LIKE', $filter->operand1 . '%');
                    break;
                case FilterOperator::DOES_NOT_CONTAINS:
                    $query->where($name, 'NOT LIKE', '%' . $filter->operand1 . '%');
                    break;
                case FilterOperator::CONTAINS:
                    $query->where($name, 'LIKE', '%' . $filter->operand1 . '%');
                    break;
                case FilterOperator::ENDS_WITH:
                    $query->where($name, 'LIKE', '%' . $filter->operand1);
                    break;
                case FilterOperator::IN:
                    $query->whereIn($name, $filter->operand1);
                    break;
                case FilterOperator::NOT_IN:
                    $query->whereNotIn($name, $filter->operand1);
                    break;
                case FilterOperator::BETWEEN:
                    $query->whereBetween($name, array($filter->operand1, $filter->operand2));
                    break;
                case FilterOperator::IS_AFTER_THAN_OR_EQUAL_TO:
                case FilterOperator::IS_GREATER_THAN_OR_EQUAL_TO:
                    $query->where($name, '>=', $filter->operand1);
                    break;
                case FilterOperator::IS_AFTER_THAN:
                case FilterOperator::IS_GREATER_THAN:
                    $query->where($name, '>', $filter->operand1);
                    break;
                case FilterOperator::IS_LESS_THAN_OR_EQUAL_TO:
                case FilterOperator::IS_BEFORE_THAN_OR_EQUAL_TO:
                    $query->where($name, '<=', $filter->operand1);
                    break;
                case FilterOperator::IS_LESS_THAN:
                case FilterOperator::IS_BEFORE_THAN:
                    $query->where($name, '<', $filter->operand1);
                    break;
                case FilterOperator::IS_INSIDE_POLYGON:
                    /** @var Polygon $polygon */
                    $polygon = $filter->operand1;
                    $query->whereRaw("Contains(GeomFromText('{$polygon->toRaw()}'),{$name})");
                    break;
            }
        }

        return $query;
    }
}
