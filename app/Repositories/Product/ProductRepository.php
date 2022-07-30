<?php

namespace App\Repositories\Product;

use Illuminate\Support\Collection;
use Illuminate\Http\Client\Request;
use App\Models\Entities\Product;

class ProductRepository implements productInterface
{
    /**@var MySqlProductRepository $mySqlProductRepository */
    private $mySqlProductRepository;


    function __construct()
    {
        $this->mySqlProductRepository = new MySqlProductRepository();
    }


    public function getAll($offset = 0, $count = 0, &$total = null, $orders = null, $filters = null): ?Collection
    {
        return $this->mySqlProductRepository->getAll($offset, $count, $total, $orders, $filters);
    }


    public function getOneById($id): ?Product
    {
        return $this->mySqlProductRepository->getOneById($id);
    }


    public function getAllByCategoryId($id)
    {
        return $this->mySqlProductRepository->findProduct($id);
    }


    public function delete($id)
    {
        return $this->mySqlProductRepository->deleteProduct($id);
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
