<?php

namespace App\Repositories\File;

use App\Factories\FileFactory;
use App\Repositories\MySqlRepository;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Collection;

class FileRepository extends MySqlRepository implements FileInterface
{
    public function __construct()
    {
        $this->table = "files";
        $this->primaryKey = "id";

    }

    /**
     * @param $ids
     * @return Collection|null
     */
    public function getAllByBannersId($ids): ?Collection
    {
        $images = $this->newQuery()
            ->whereIn('banner_id', $ids)
            ->whereNull('deleted_at')->get();
        return $images ? (new FileFactory())->makeFromCollection($images) : null;
    }

    /**
     * @param $ids
     * @return Collection|null
     */
    public function getAllByCategoriesId($ids): ?Collection
    {
        $images = $this->newQuery()
            ->whereIn('category_id', $ids)
            ->whereNull('deleted_at')->get();
        return $images ? (new FileFactory())->makeFromCollection($images) : null;
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
