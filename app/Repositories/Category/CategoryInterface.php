<?php

namespace App\Repositories\Category;

use Illuminate\Http\Client\Request;

interface CategoryInterface
{
    public function getOneById($id);

    public function getAllChosen();

    public function create(Request $request);

    public function update(Request $request);
}
