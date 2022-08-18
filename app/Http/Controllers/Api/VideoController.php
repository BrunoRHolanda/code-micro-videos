<?php

namespace App\Http\Controllers\Api;

use App\Models\Video;
use App\Rules\InVideoRating;
use App\Rules\RelatedWithCategory;
use App\Rules\RelatedWithGenre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\ArrayShape;
use Throwable;

class VideoController extends BasicCrudController
{
    /**
     * @throws Throwable
     */
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

    /**
     * @throws Throwable
     */
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

    protected function model(): string
    {
        return Video::class;
    }

    #[ArrayShape([
        'title' => "string",
        'description' => "string",
        'year_launched' => "string",
        'opened' => "string",
        'rating' => "array",
        'duration' => "string",
        'categories' => "string",
        'genres' => "string"
    ])]
    protected function rulesStore(): array
    {
        return [
            'title' => 'required|max:255',
            'description' => 'required|string',
            'year_launched' => 'required|date_format:Y',
            'opened' => 'boolean',
            'rating' => ['required', new InVideoRating()],
            'duration' => 'required|integer',
            'categories' => [
                'required',
                'array',
                'exists:categories,id',
                new RelatedWithGenre()
            ],
            'genres' => [
                'required ',
                'array',
                'exists:genres,id',
                new RelatedWithCategory()
            ]
        ];
    }

    #[ArrayShape([
        'title' => "string",
        'description' => "string",
        'year_launched' => "string",
        'opened' => "string",
        'rating' => "array",
        'duration' => "string",
        'categories' => "string",
        'genres' => "string"
    ])]
    protected function rulesUpdate(): array
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
