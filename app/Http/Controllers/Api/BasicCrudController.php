<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

abstract class BasicCrudController extends Controller
{
    protected abstract function model();
    protected abstract function rulesStore();
    protected abstract function rulesUpdate();

    public function index()
    {
        return $this->model()::all();
    }

    public function show(string $id)
    {
        return $this->findOrFail($id);
    }

    /**
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $validated = $this->validate($request, $this->rulesStore());

        $entity = $this->model()::create($validated);

        $entity->refresh();

        return $entity;
    }

    /**
     * @throws ValidationException
     */
    public function update(Request $request, string $id)
    {
        $entity = $this->findOrFail($id);

        $validated = $this->validate($request, $this->rulesUpdate());

        $entity->update($validated);

        $entity->refresh();

        return $entity;
    }

    public function destroy(string $id): Response
    {
        $entity = $this->findOrFail($id);

        $entity->delete();

        return response()->noContent();
    }

    protected function findOrFail(string $id)
    {
        $key = (new ($this->model()))->getRouteKeyName();

        return $this->model()::where($key, '=', $id)->firstOrFail();
    }
}
