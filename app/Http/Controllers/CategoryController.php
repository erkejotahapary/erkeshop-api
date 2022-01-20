<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Resources\Categories as CategoryResourceCollection;
use App\Http\Resources\Category as CategoryResource;


class CategoryController extends Controller
{
    public function index()
    {
        return new CategoryResourceCollection(Category::paginate(6));
    }

    public function random($count)
    {
        $criteria = Category::select('*')
            ->inRandomOrder()
            ->limit($count)
            ->get();

        return new CategoryResourceCollection($criteria);
    }

    public function slug($slug)
    {
        return new CategoryResource(Category::where('slug', $slug)->first());
    }
}
