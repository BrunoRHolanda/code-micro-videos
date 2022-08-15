<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends BasicCrudController
{
    public function show(Category $category)
    {
        return $category;
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return response()->noContent();
    }

    protected function model()
    {
        return Category::class;
    }

    protected function rulesStore()
    {
        return [
            "name" => "required|string|max:255",
            "description" => "string|max:255",
            "is_active" => "boolean"
        ];
    }

    protected function rulesUpdate()
    {
        return [
            "name" => "string|max:255",
            "description" => "string|max:255",
            "is_active" => "boolean"
        ];
    }
}
