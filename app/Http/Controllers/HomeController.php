<?php

namespace App\Http\Controllers;

use App\Repositories\Banner\BannerRepository;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\File\FileRepository;
use \Illuminate\Http\JsonResponse;

class HomeController extends Controller
{
    public function index(): JsonResponse
    {
        $bannersRepository = new BannerRepository();
        $categoryRepository = new CategoryRepository();
        $fileRepository = new FileRepository();

        $banners = $bannersRepository->getAll();
        $bannersFile = $fileRepository->getAllByBannersId(array_column($banners->toArray(), 'id'))->keyBy('bannerId');
        $categories = $categoryRepository->getAll();
        $categoriesFile = $fileRepository->getAllByCategoriesId(array_column($categories->toArray(), 'id'))->keyBy('categoryId');



        return response()->json([
            'banners' => $banners,
            'bannersFile' => $bannersFile,
            'categories' => $categories,
            'categoriesFile' => $categoriesFile
        ]);
    }
}
