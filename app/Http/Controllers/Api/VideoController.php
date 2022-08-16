<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVideoRequest;
use App\Http\Requests\UpdateVideoRequest;
use App\Models\Video;

class VideoController extends BasicCrudController
{

    protected function model()
    {
        return Video::class;
    }

    protected function rulesStore()
    {
        // TODO: Implement rulesStore() method.
    }

    protected function rulesUpdate()
    {
        // TODO: Implement rulesUpdate() method.
    }
}
