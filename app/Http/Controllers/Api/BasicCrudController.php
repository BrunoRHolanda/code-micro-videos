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
    protected abstract function rulesUpdate();

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

    public function update(Request $request, string $id)
    {
        $entity = $this->findOrFail($id);

        $validated = $this->validate($request, $this->rulesUpdate());

        $entity->update($validated);

        $entity->refresh();

        return $entity;
    }

    protected function findOrFail(string $id)
    {
        $key = (new ($this->model()))->getRouteKeyName();

        return $this->model()::where($key, '=', $id)->firstOrFail();
    }
}
