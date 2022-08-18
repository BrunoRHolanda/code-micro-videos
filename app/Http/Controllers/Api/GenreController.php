<?php

namespace App\Http\Controllers\Api;

use App\Models\Genre;
use DB;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\ArrayShape;
use Throwable;

class GenreController extends BasicCrudController
{
    /**
     * @throws Throwable
     */
    public function store(Request $request)
    {
         $genreCommitted = DB::transaction(function () use($request) {
             /**
              * @var Genre $genre
              */
             $genre = parent::store($request);

             $genre->categories()->sync($request->input('categories'));

             return $genre;
         });

        $genreCommitted->refresh();

        return $genreCommitted;
    }

    /**
     * @throws Throwable
     */
    public function update(Request $request, string $id)
    {
        $genreCommitted = DB::transaction(function () use($request, $id) {
            /**
             * @var Genre $genre
             */
            $genre = parent::update($request, $id);

            if ($request->has('categories')) {
                $genre->categories()->sync($request->input('categories'));
            }

            return $genre;
        });

        $genreCommitted->refresh();

        return $genreCommitted;
    }

    protected function model(): string
    {
        return Genre::class;
    }

    #[ArrayShape(['name' => "string", 'categories' => "string", 'is_active' => "string"])]
    protected function rulesStore(): array
    {
        return [
            'name' => 'required|string|max:255',
            'categories' => 'required|array|exists:categories,id',
            'is_active' => 'boolean',
        ];
    }

    #[ArrayShape(['name' => "string", 'categories' => "string", 'is_active' => "string"])]
    protected function rulesUpdate(): array
    {
        return [
            'name' => 'string|max:255',
            'categories' => 'array|exists:categories,id',
            'is_active' => 'boolean',
        ];
    }
}
