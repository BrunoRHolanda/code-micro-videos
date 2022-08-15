<?php

namespace Tests\Stub\Http\Controllers;

use App\Http\Controllers\Api\BasicCrudController;
use Tests\Stub\Models\CategoryStub;

class CategoryControllerStub extends BasicCrudController
{

    protected function model()
    {
        return CategoryStub::class;
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
