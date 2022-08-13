<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGenreRequest;
use App\Http\Requests\UpdateGenreRequest;
use App\Models\Genre;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    public function index(Request $request)
    {
        if ($request->query('trashed')) {
            return Genre::onlyTrashed()->get();
        }

        return Genre::all();
    }

    public function store(StoreGenreRequest $request)
    {
        $genre = Genre::create($request->all());

        $genre->refresh();

        return $genre;
    }

    public function show(Genre $genre)
    {
        return $genre;
    }

    public function update(UpdateGenreRequest $request, Genre $genre)
    {
        $genre->update($request->all());

        return $genre;
    }

    public function destroy(Genre $genre)
    {
        $genre->delete();

        return response()->noContent();
    }
}
