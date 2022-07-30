<?php

namespace App\Repositories\Product;

use App\Factories\ProductFactory;
use App\Repositories\MySqlRepository;
use Illuminate\Http\Client\Request;
use App\Models\Entities\Product;
use Illuminate\Support\Collection;

class MySqlProductRepository extends MySqlRepository implements ProductInterface
{

    public function __construct()
    {
        $this->table = "products";
        $this->primaryKey = "id";

    }

    public function getAll($offset = 0, $count = 0, &$total = null, $orders = null, $filters = null): ?Collection
    {
        $query = $this->newQuery();

        if ($orders) {
            $query = $this->processOrder($query, $orders);
        }

        if ($filters) {
            $query = $this->processFilter($query, $filters);
        }
        $total = $query->count();

        if ($count) {
            $query->offset($offset);
            $query->limit($count);
        }
        $products = $query->get();
        return $products ? (new ProductFactory())->makeFromCollection($products) : null;
    }

    public function getOneById($id): ?Product
    {
        $product = $this->newQuery()
            ->where($this->primaryKey, $id)->first();
        return $product ? (new ProductFactory())->make($product) : null;
    }

    public function getAllByCategoryId($id)
    {
        // TODO: Implement getAllByCategoryId() method.
    }

    public function create(Request $request)
    {
        // TODO: Implement create() method.
    }

    public function update(Request $request)
    {
        // TODO: Implement update() method.
    }
}
