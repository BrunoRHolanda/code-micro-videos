<?php

namespace App\Http\Controllers\Api;

use App\Models\Genre;

class GenreController extends BasicCrudController
{

    protected function model()
    {
        return Genre::class;
    }

    protected function rulesStore()
    {
        return [
            'name' => 'required|string|max:255',
            'is_active' => 'boolean',
        ];
    }

    protected function rulesUpdate()
    {
        return [
            'name' => 'string|max:255',
            'is_active' => 'boolean',
        ];
    }
}
