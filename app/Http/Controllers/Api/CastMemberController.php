<?php

namespace App\Http\Controllers\Api;

use App\Models\CastMember;
use JetBrains\PhpStorm\ArrayShape;

class CastMemberController extends BasicCrudController
{

    protected function model(): string
    {
        return CastMember::class;
    }

    #[ArrayShape(['name' => "string", 'type' => "string"])]
    protected function rulesStore(): array
    {
        return [
            'name' => 'required|string|max:255',
            'type' => 'required|numeric|between:1,2',
        ];
    }

    #[ArrayShape(['name' => "string", 'type' => "string"])]
    protected function rulesUpdate(): array
    {
        return [
            'name' => 'string|max:255',
            'type' => 'numeric|between:1,2',
        ];
    }
}
