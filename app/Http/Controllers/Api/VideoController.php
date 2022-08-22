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
        'genres' => "string",
        'video_file' => "string",
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
                'exists:categories,id,deleted_at,NULL',
                new RelatedWithGenre()
            ],
            'genres' => [
                'required ',
                'array',
                'exists:genres,id,deleted_at,NULL',
                new RelatedWithCategory()
            ],
            'video_file' => 'file|mimetypes:video/mp4|size:2048'
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
            'categories' => 'array|exists:categories,id,deleted_at,NULL',
            'genres' => 'array|exists:genres,id,deleted_at,NULL'
        ];
    }
}
