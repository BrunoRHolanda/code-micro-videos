<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;

class CategoryController extends BasicCrudController
{
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
