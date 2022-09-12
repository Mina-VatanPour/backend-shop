<?php

namespace App\Repositories\Banner;

use App\Factories\BannerFactory;
use App\Factories\ProductFactory;
use App\Models\Enums\PagePosition;
use App\Repositories\MySqlRepository;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Collection;

class BannerRepository extends MySqlRepository implements BannerInterface
{
    public function __construct()
    {
        $this->table = "banners";
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
        return $products ? (new BannerFactory())->makeFromCollection($products) : null;
    }

    public function getAllByCategoryId($id): ?Collection
    {
        $banners = $this->newQuery()
            ->where('category_id', $id)->get();
        return $banners ? (new BannerFactory())->makeFromCollection($banners) : null;
    }

    public function getOneById($id)
    {
        //todo
    }

    public function getAllByHomePosition(): ?Collection
    {
        $products = $this->newQuery()
            ->where('position', PagePosition::HOME)->get();
        return $products ? (new ProductFactory())->makeFromCollection($products) : null;
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
