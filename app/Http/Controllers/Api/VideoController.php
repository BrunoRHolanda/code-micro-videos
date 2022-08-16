<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVideoRequest;
use App\Http\Requests\UpdateVideoRequest;
use App\Models\Video;
use App\Rules\InVideoRating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VideoController extends BasicCrudController
{
    public function store(Request $request)
    {
        $videoCommitted = DB::transaction(function () use ($request) {
            /**
             * @var Video $video
             */
            $video = parent::store($request);

            $video->categories()->sync($request->input('categories'));
            $video->genres()->sync($request->input('genres'));

            return $video;
        });

        $videoCommitted->refresh();

        return $videoCommitted;
    }

    public function update(Request $request, string $id)
    {
        $videoCommitted = DB::transaction(function () use($request, $id) {
            /**
             * @var Video $video
             */
            $video = parent::update($request, $id);

            if ($request->has('categories')) {
                $video->categories()->sync($request->input('categories'));
            }

            if ($request->has('genres')) {
                $video->genres()->sync($request->input('genres'));
            }

            return $video;
        });

        $videoCommitted->refresh();

        return $videoCommitted;
    }

    protected function model()
    {
        return Video::class;
    }

    protected function rulesStore()
    {
        return [
            'title' => 'required|max:255',
            'description' => 'required|string',
            'year_launched' => 'required|date_format:Y',
            'opened' => 'boolean',
            'rating' => ['required', new InVideoRating()],
            'duration' => 'required|integer',
            'categories' => 'required|array|exists:categories,id',
            'genres' => 'required|array|exists:genres,id'
        ];
    }

    protected function rulesUpdate()
    {
        return [
            'title' => 'ax:255',
            'description' => 'string',
            'year_launched' => 'date_format:Y',
            'opened' => 'boolean',
            'rating' => new InVideoRating(),
            'duration' => 'integer',
            'categories' => 'array|exists:categories,id',
            'genres' => 'array|exists:genres,id'
        ];
    }
}
