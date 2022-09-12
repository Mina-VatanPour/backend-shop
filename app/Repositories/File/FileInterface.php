<?php

namespace App\Repositories\File;

use Illuminate\Http\Client\Request;

interface FileInterface
{
    public function getAllByBannersId($ids);

    public function getAllByCategoriesId($ids);

    public function create(Request $request);

    public function update(Request $request);
}
