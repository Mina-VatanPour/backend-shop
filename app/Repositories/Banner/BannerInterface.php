<?php

namespace App\Repositories\Banner;

use Illuminate\Http\Client\Request;

interface BannerInterface
{
    public function getAll($offset = 0, $count = 0, &$total = null, $orders = null, $filters = null);

    public function getOneById($id);

    public function getAllByCategoryId($id);

    public function create(Request $request);

    public function update(Request $request);
}
