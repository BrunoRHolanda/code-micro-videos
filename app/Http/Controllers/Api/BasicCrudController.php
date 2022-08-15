<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

abstract class BasicCrudController extends Controller
{
    protected abstract function model();
    protected abstract function rulesStore();

    public function index()
    {
        return $this->model()::all();
    }

    public function store(Request $request)
    {
        $validated = $this->validate($request, $this->rulesStore());

        $entity = $this->model()::create($validated);

        $entity->refresh();

        return $entity;
    }
}
