<?php

namespace App\Repositories\Category;

use App\Factories\CategoryFactory;
use App\Repositories\MySqlRepository;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Collection;

class CategoryRepository extends MySqlRepository implements CategoryInterface
{
    public function __construct()
    {
        $this->table = "categories";
        $this->primaryKey = "id";

    }

    public function getAll(): ?Collection
    {
        $categories = $this->newQuery()->whereNotNull('parent_id')->get();
        return $categories ? (new CategoryFactory())->makeFromCollection($categories) : null;
    }

    public function getAllChosen(): ?Collection
    {
        $categories = $this->newQuery()
            ->where('chosen', true)->get();
        return $categories ? (new CategoryFactory())->makeFromCollection($categories) : null;
    }

    public function getOneById($id)
    {
        //todo
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
