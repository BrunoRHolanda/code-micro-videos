<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use JetBrains\PhpStorm\ArrayShape;

class CategoryController extends BasicCrudController
{
    protected function model(): string
    {
        return Category::class;
    }

    #[ArrayShape(["name" => "string", "description" => "string", "is_active" => "string"])]
    protected function rulesStore(): array
    {
        return [
            "name" => "required|string|max:255",
            "description" => "string|max:255",
            "is_active" => "boolean"
        ];
    }

    #[ArrayShape(["name" => "string", "description" => "string", "is_active" => "string"])]
    protected function rulesUpdate(): array
    {
        return [
            "name" => "string|max:255",
            "description" => "string|max:255",
            "is_active" => "boolean"
        ];
    }
}
