<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCastMemberRequest;
use App\Http\Requests\UpdateCastMemberRequest;
use App\Models\CastMember;

class CastMemberController extends BasicCrudController
{

    protected function model()
    {
        return CastMember::class;
    }

    protected function rulesStore()
    {
        return [
            'name' => 'required|string|max:255',
            'type' => 'required|numeric|between:1,2',
        ];
    }

    protected function rulesUpdate()
    {
        return [
            'name' => 'string|max:255',
            'type' => 'numeric|between:1,2',
        ];
    }
}
